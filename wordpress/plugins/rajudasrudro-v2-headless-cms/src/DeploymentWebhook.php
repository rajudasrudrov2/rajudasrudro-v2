<?php
namespace RDR\V2\HeadlessCMS;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Staging-only Vercel Deploy Hook integration.
 *
 * The configured hook URL is treated as a secret. This service never stores,
 * logs, renders, or exposes the raw value. Public-impacting post saves are
 * queued and evaluated at shutdown so Portal metadata/taxonomy writes have
 * completed before the remote deployment request is sent.
 */
final class DeploymentWebhook {
    const CONFIG_KEY    = 'RDR_V2_VERCEL_STAGING_DEPLOY_HOOK_URL';
    const STATUS_OPTION = 'rdr_v2_staging_deploy_status';

    /** @var bool */
    private $hooks_registered = false;
    /** @var bool */
    private $triggered = false;
    /** @var array<int,array<string,mixed>> */
    private $pending_posts = array();
    /** @var string */
    private $pending_force_reason = '';
    /** @var array<int,bool> */
    private $before_public = array();

    public function hooks() {
        if ( $this->hooks_registered ) {
            return;
        }
        $this->hooks_registered = true;

        add_action( 'pre_post_update', array( $this, 'capture_before_post' ), 10, 2 );
        add_action( 'save_post', array( $this, 'queue_saved_post' ), 100, 3 );
        add_action( 'wp_trash_post', array( $this, 'queue_trashed_post' ), 10, 1 );
        add_action( 'before_delete_post', array( $this, 'queue_deleted_post' ), 10, 2 );

        add_action( 'updated_option', array( $this, 'queue_updated_option' ), 10, 3 );

        // Upload alone intentionally does not rebuild. Existing attachment edits/deletes do.
        add_action( 'edit_attachment', array( $this, 'queue_attachment_update' ), 10, 1 );
        add_action( 'delete_attachment', array( $this, 'queue_attachment_delete' ), 10, 1 );

        add_action( 'created_term', array( $this, 'queue_taxonomy_change' ), 10, 3 );
        add_action( 'edited_term', array( $this, 'queue_taxonomy_change' ), 10, 3 );
        add_action( 'delete_term', array( $this, 'queue_taxonomy_change' ), 10, 3 );

        add_action( 'shutdown', array( $this, 'flush_pending' ), 999 );
    }

    /**
     * Public REST eligibility is exactly publish + _rdr_publication_state=public.
     */
    public static function is_public_state( $post_status, $publication_state ) {
        return 'publish' === (string) $post_status && 'public' === (string) $publication_state;
    }

    public static function should_trigger_rebuild( $before_public, $after_public ) {
        return (bool) $before_public || (bool) $after_public;
    }

    public function is_configured() {
        return 'configured' === $this->configuration_status();
    }

    public function is_valid() {
        return $this->is_configured();
    }

    public function configuration_status() {
        $config = $this->read_config();
        if ( '' === $config['value'] ) {
            return 'not_configured';
        }
        return $this->validate_hook_url( $config['value'] ) ? 'configured' : 'invalid';
    }

    public function configuration_source() {
        $config = $this->read_config();
        if ( '' === $config['value'] ) {
            return 'none';
        }
        return $config['source'];
    }

    /**
     * Safe operational metadata only. Raw hook configuration is never returned.
     */
    public function get_safe_status() {
        $stored = get_option( self::STATUS_OPTION, array() );
        $stored = is_array( $stored ) ? $stored : array();

        return array(
            'configuration_status' => $this->configuration_status(),
            'configuration_source' => $this->configuration_source(),
            'last_attempt_at'       => $this->safe_status_text( isset( $stored['last_attempt_at'] ) ? $stored['last_attempt_at'] : '', 32 ),
            'last_success_at'       => $this->safe_status_text( isset( $stored['last_success_at'] ) ? $stored['last_success_at'] : '', 32 ),
            'last_result'           => $this->bounded_result( isset( $stored['last_result'] ) ? $stored['last_result'] : '' ),
            'last_http_code'        => isset( $stored['last_http_code'] ) ? absint( $stored['last_http_code'] ) : 0,
            'last_reason'           => $this->bounded_reason( isset( $stored['last_reason'] ) ? $stored['last_reason'] : '' ),
            'last_error'            => $this->safe_status_text( isset( $stored['last_error'] ) ? $stored['last_error'] : '', 180 ),
        );
    }

    /**
     * Sends at most one Deploy Hook request in this PHP request.
     *
     * @return array<string,mixed> Safe result only.
     */
    public function trigger( $reason ) {
        $reason = $this->bounded_reason( $reason );
        if ( '' === $reason ) {
            $reason = 'manual';
        }

        if ( $this->triggered ) {
            $status = $this->get_safe_status();
            return array(
                'ok'           => 'success' === $status['last_result'],
                'result'       => $status['last_result'],
                'http_code'    => $status['last_http_code'],
                'deduplicated' => true,
            );
        }
        $this->triggered = true;

        $config = $this->read_config();
        if ( '' === $config['value'] ) {
            $this->record_status( 'configuration_missing', $reason, 0, 'Deploy Hook is not configured.', false );
            return array( 'ok' => false, 'result' => 'configuration_missing', 'http_code' => 0, 'deduplicated' => false );
        }
        if ( ! $this->validate_hook_url( $config['value'] ) ) {
            $this->record_status( 'configuration_invalid', $reason, 0, 'Deploy Hook configuration is invalid.', false );
            return array( 'ok' => false, 'result' => 'configuration_invalid', 'http_code' => 0, 'deduplicated' => false );
        }

        try {
            $response = wp_remote_post(
                $config['value'],
                array(
                    'timeout'     => 5,
                    'redirection' => 0,
                    'sslverify'   => true,
                    'headers'     => array( 'Accept' => 'application/json' ),
                )
            );
        } catch ( \Throwable $error ) {
            $this->record_status( 'failed', $reason, 0, 'Deployment request failed.', false );
            return array( 'ok' => false, 'result' => 'failed', 'http_code' => 0, 'deduplicated' => false );
        }

        if ( is_wp_error( $response ) ) {
            $message = (string) $response->get_error_message();
            $safe_error = false !== stripos( $message, 'timed out' ) || false !== stripos( $message, 'timeout' )
                ? 'Request timed out.'
                : 'Deployment request failed.';
            $this->record_status( 'failed', $reason, 0, $safe_error, false );
            return array( 'ok' => false, 'result' => 'failed', 'http_code' => 0, 'deduplicated' => false );
        }

        $http_code = absint( wp_remote_retrieve_response_code( $response ) );
        if ( $http_code >= 200 && $http_code < 300 ) {
            $this->record_status( 'success', $reason, $http_code, '', true );
            return array( 'ok' => true, 'result' => 'success', 'http_code' => $http_code, 'deduplicated' => false );
        }

        $this->record_status( 'failed', $reason, $http_code, 'Deploy Hook returned HTTP ' . $http_code . '.', false );
        return array( 'ok' => false, 'result' => 'failed', 'http_code' => $http_code, 'deduplicated' => false );
    }

    public function capture_before_post( $post_id, $data = array() ) {
        $post_id = absint( $post_id );
        if ( ! $post_id || array_key_exists( $post_id, $this->before_public ) ) {
            return;
        }
        $post = get_post( $post_id );
        if ( ! $this->is_relevant_post( $post ) ) {
            return;
        }
        $this->before_public[ $post_id ] = $this->is_public_post( $post );
    }

    public function queue_saved_post( $post_id, $post, $update ) {
        $post_id = absint( $post_id );
        if ( ! $post_id || ! $this->is_relevant_post( $post ) ) {
            return;
        }
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }
        if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
            return;
        }

        $before_known = array_key_exists( $post_id, $this->before_public );
        $before_public = $before_known ? (bool) $this->before_public[ $post_id ] : false;

        $this->pending_posts[ $post_id ] = array(
            'before_public' => $before_public,
            'before_known'  => $before_known || ! $update,
            'reason'        => $this->post_reason( $post->post_type, 'save' ),
        );
    }

    public function queue_trashed_post( $post_id ) {
        $post = get_post( absint( $post_id ) );
        if ( $this->is_relevant_post( $post ) && $this->is_public_post( $post ) ) {
            $this->queue_force_reason( $this->post_reason( $post->post_type, 'delete' ) );
        }
    }

    public function queue_deleted_post( $post_id, $post = null ) {
        if ( ! $post instanceof \WP_Post ) {
            $post = get_post( absint( $post_id ) );
        }
        if ( $this->is_relevant_post( $post ) && $this->is_public_post( $post ) ) {
            $this->queue_force_reason( $this->post_reason( $post->post_type, 'delete' ) );
        }
    }

    public function queue_updated_option( $option, $old_value, $value ) {
        if ( $old_value === $value ) {
            return;
        }
        if ( Settings::SITE_OPTION === $option ) {
            $this->queue_force_reason( 'site_settings' );
        } elseif ( Settings::ABOUT_OPTION === $option ) {
            $this->queue_force_reason( 'about' );
        }
    }

    public function queue_attachment_update( $attachment_id ) {
        if ( absint( $attachment_id ) ) {
            $this->queue_force_reason( 'attachment_update' );
        }
    }

    public function queue_attachment_delete( $attachment_id ) {
        if ( absint( $attachment_id ) ) {
            $this->queue_force_reason( 'attachment_delete' );
        }
    }

    public function queue_taxonomy_change( $term_id, $tt_id = 0, $taxonomy = '' ) {
        if ( in_array( (string) $taxonomy, array( Taxonomies::PROJECT_CATEGORY, 'category' ), true ) ) {
            $this->queue_force_reason( 'taxonomy_change' );
        }
    }

    /**
     * Deferred evaluation guarantees Portal save_meta_fields(), relations,
     * category writes and featured-image changes finish before the POST.
     */
    public function flush_pending() {
        if ( $this->triggered ) {
            return;
        }

        if ( '' !== $this->pending_force_reason ) {
            $this->trigger( $this->pending_force_reason );
            return;
        }

        foreach ( $this->pending_posts as $post_id => $event ) {
            $post = get_post( $post_id );
            $after_public = $this->is_relevant_post( $post ) ? $this->is_public_post( $post ) : false;

            // If an update bypassed pre_post_update unexpectedly, fail toward
            // correctness rather than risk stale public staging output.
            if ( empty( $event['before_known'] ) ) {
                $this->trigger( $event['reason'] );
                return;
            }

            if ( self::should_trigger_rebuild( ! empty( $event['before_public'] ), $after_public ) ) {
                $this->trigger( $event['reason'] );
                return;
            }
        }
    }

    private function queue_force_reason( $reason ) {
        if ( '' === $this->pending_force_reason ) {
            $this->pending_force_reason = $this->bounded_reason( $reason );
        }
    }

    private function is_relevant_post( $post ) {
        return $post instanceof \WP_Post && in_array(
            $post->post_type,
            array( PostTypes::PROJECT, PostTypes::SERVICE, PostTypes::REVIEW, 'post' ),
            true
        );
    }

    private function is_public_post( $post ) {
        if ( ! $post instanceof \WP_Post ) {
            return false;
        }
        return self::is_public_state(
            $post->post_status,
            get_post_meta( $post->ID, Meta::PUBLICATION_STATE, true )
        );
    }

    private function post_reason( $post_type, $action ) {
        $prefix = 'post' === $post_type ? 'article' : str_replace( 'rdr_', '', (string) $post_type );
        $reason = $prefix . '_' . ( 'delete' === $action ? 'delete' : 'save' );
        return $this->bounded_reason( $reason );
    }

    /**
     * Constant wins over environment variable. Raw value never leaves service.
     *
     * @return array{value:string,source:string}
     */
    private function read_config() {
        if ( defined( self::CONFIG_KEY ) ) {
            $value = constant( self::CONFIG_KEY );
            return array( 'value' => is_string( $value ) ? trim( $value ) : '', 'source' => 'wp-config constant' );
        }

        $value = getenv( self::CONFIG_KEY );
        return array(
            'value'  => is_string( $value ) ? trim( $value ) : '',
            'source' => is_string( $value ) && '' !== trim( $value ) ? 'server environment' : 'none',
        );
    }

    private function validate_hook_url( $url ) {
        if ( ! is_string( $url ) || '' === trim( $url ) ) {
            return false;
        }

        $parts = wp_parse_url( trim( $url ) );
        if ( ! is_array( $parts ) ) {
            return false;
        }
        if ( 'https' !== ( isset( $parts['scheme'] ) ? strtolower( $parts['scheme'] ) : '' ) ) {
            return false;
        }
        if ( 'api.vercel.com' !== ( isset( $parts['host'] ) ? strtolower( $parts['host'] ) : '' ) ) {
            return false;
        }
        if ( isset( $parts['user'] ) || isset( $parts['pass'] ) || isset( $parts['query'] ) || isset( $parts['fragment'] ) ) {
            return false;
        }
        if ( isset( $parts['port'] ) && 443 !== absint( $parts['port'] ) ) {
            return false;
        }

        $path = isset( $parts['path'] ) ? (string) $parts['path'] : '';
        return 1 === preg_match( '#^/v1/integrations/deploy/[A-Za-z0-9_-]+/[A-Za-z0-9_-]+/?$#D', $path );
    }

    private function record_status( $result, $reason, $http_code, $error, $success ) {
        $existing = get_option( self::STATUS_OPTION, array() );
        $existing = is_array( $existing ) ? $existing : array();
        $now = current_time( 'mysql', true );

        $status = array(
            'last_attempt_at' => $this->safe_status_text( $now, 32 ),
            'last_success_at' => $success
                ? $this->safe_status_text( $now, 32 )
                : $this->safe_status_text( isset( $existing['last_success_at'] ) ? $existing['last_success_at'] : '', 32 ),
            'last_result'     => $this->bounded_result( $result ),
            'last_http_code'  => absint( $http_code ),
            'last_reason'     => $this->bounded_reason( $reason ),
            'last_error'      => $this->safe_status_text( $error, 180 ),
        );

        // Operational status only. Failure to persist status must never fail a content save.
        update_option( self::STATUS_OPTION, $status, false );
    }

    private function bounded_result( $result ) {
        $result = sanitize_key( (string) $result );
        return in_array( $result, array( 'success', 'failed', 'configuration_missing', 'configuration_invalid' ), true ) ? $result : '';
    }

    private function bounded_reason( $reason ) {
        $reason = sanitize_key( (string) $reason );
        $allowed = array(
            'manual',
            'site_settings',
            'about',
            'project_save',
            'project_delete',
            'service_save',
            'service_delete',
            'review_save',
            'review_delete',
            'article_save',
            'article_delete',
            'attachment_update',
            'attachment_delete',
            'taxonomy_change',
        );
        return in_array( $reason, $allowed, true ) ? $reason : '';
    }

    private function safe_status_text( $value, $max_length ) {
        $value = sanitize_text_field( (string) $value );
        if ( function_exists( 'mb_substr' ) ) {
            return mb_substr( $value, 0, absint( $max_length ) );
        }
        return substr( $value, 0, absint( $max_length ) );
    }
}
