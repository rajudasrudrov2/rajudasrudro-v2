<?php
namespace RDR\V2\HeadlessCMS\Portal;

use RDR\V2\HeadlessCMS\DeploymentWebhook;
use RDR\V2\HeadlessCMS\Settings as CoreSettings;

if ( ! defined( 'ABSPATH' ) ) { exit; }

final class SiteSettings extends ContentModule {
    /** @var CoreSettings */
    private $settings;
    /** @var DeploymentWebhook */
    private $deployment;

    public function __construct( CoreSettings $settings, DeploymentWebhook $deployment ) {
        $this->settings = $settings;
        $this->deployment = $deployment;
    }

    public function render() {
        if ( ! current_user_can( 'manage_options' ) ) {
            Shell::simple_error( 403, 'Access denied', 'Site Settings require the manage_options capability.' );
        }

        $errors = array();
        $submitted = array();
        if ( 'POST' === $_SERVER['REQUEST_METHOD'] ) {
            $action = isset( $_POST['rdr_portal_action'] ) ? sanitize_key( wp_unslash( $_POST['rdr_portal_action'] ) ) : 'save_site_settings';
            $result = 'trigger_staging_rebuild' === $action ? $this->trigger_staging_rebuild() : $this->save();
            if ( ! empty( $result['redirect'] ) ) {
                wp_safe_redirect( $result['redirect'] );
                exit;
            }
            $errors = isset( $result['errors'] ) ? $result['errors'] : array();
            $submitted = isset( $result['submitted'] ) ? $result['submitted'] : array();
        }

        $value = $submitted && isset( $submitted['site'] ) && is_array( $submitted['site'] )
            ? $submitted['site']
            : $this->settings->site();
        $status = $this->deployment->get_safe_status();
        $deploy_notice = isset( $_GET['rdr_deploy_notice'] ) ? sanitize_key( wp_unslash( $_GET['rdr_deploy_notice'] ) ) : '';

        ob_start(); ?>
        <?php echo $this->errors_html( $errors ); ?>
        <?php if ( 'success' === $deploy_notice ) : ?>
            <div class="rdr-flash rdr-flash-success" role="status">Staging rebuild request sent successfully.</div>
        <?php elseif ( 'failed' === $deploy_notice ) : ?>
            <div class="rdr-flash rdr-flash-error" role="status">Content is unchanged. Staging rebuild request failed. Check configuration/status.</div>
        <?php endif; ?>

        <form method="post" class="rdr-form rdr-editor-form" data-rdr-unsaved>
            <?php wp_nonce_field( 'rdr_portal_site_settings_save', 'rdr_portal_nonce' ); ?>
            <input type="hidden" name="rdr_portal_action" value="save_site_settings">
            <section class="rdr-card">
                <h2>Safe Public Site Settings</h2>
                <div class="rdr-form-grid">
                    <?php echo $this->field( 'site[public_email]', 'Public Contact Email', $value['public_email'] ?? '', 'email' ); ?>
                    <?php echo $this->field( 'site[fiverr_url]', 'Fiverr URL', $value['fiverr_url'] ?? '', 'url' ); ?>
                    <?php echo $this->field( 'site[linkedin_url]', 'LinkedIn URL', $value['linkedin_url'] ?? '', 'url' ); ?>
                </div>
                <?php echo $this->field( 'site[response_expectation]', 'Response Expectation', $value['response_expectation'] ?? '' ); ?>
                <?php echo Support::media_field( 'site[default_social_image_id]', 'Default Social Image', $value['default_social_image_id'] ?? 0 ); ?>
                <div class="rdr-callout"><strong>Secret isolation</strong><p>Contact delivery webhooks, staging deployment hooks, API keys, SMTP passwords and environment secrets are intentionally not exposed in this Portal.</p></div>
            </section>
            <div class="rdr-sticky-actions"><button class="rdr-button rdr-button-primary" type="submit" data-rdr-save>Save Site Settings</button><span class="rdr-save-state" data-rdr-save-state aria-live="polite"></span></div>
        </form>

        <section class="rdr-card" aria-labelledby="rdr-staging-rebuild-title">
            <div class="rdr-card-head"><div><h2 id="rdr-staging-rebuild-title">Staging Rebuild Status</h2><p>Deployment status only. The Vercel Deploy Hook URL is never displayed or stored here.</p></div></div>
            <div class="rdr-code-list">
                <div><strong>Hook configured</strong><code><?php echo esc_html( 'configured' === $status['configuration_status'] ? 'Yes' : 'No' ); ?></code></div>
                <div><strong>Configuration</strong><code><?php echo esc_html( $this->configuration_label( $status['configuration_status'] ) ); ?></code></div>
                <div><strong>Last attempt</strong><code><?php echo esc_html( $this->status_time( $status['last_attempt_at'] ) ); ?></code></div>
                <div><strong>Last success</strong><code><?php echo esc_html( $this->status_time( $status['last_success_at'] ) ); ?></code></div>
                <div><strong>Last result</strong><code><?php echo esc_html( $status['last_result'] ? $status['last_result'] : '—' ); ?></code></div>
                <div><strong>Last HTTP code</strong><code><?php echo esc_html( $status['last_http_code'] ? (string) $status['last_http_code'] : '—' ); ?></code></div>
                <div><strong>Last reason</strong><code><?php echo esc_html( $status['last_reason'] ? $status['last_reason'] : '—' ); ?></code></div>
                <div><strong>Last error</strong><code><?php echo esc_html( $status['last_error'] ? $status['last_error'] : '—' ); ?></code></div>
            </div>
            <form method="post" class="rdr-form">
                <?php wp_nonce_field( 'rdr_portal_staging_rebuild', 'rdr_portal_staging_rebuild_nonce' ); ?>
                <input type="hidden" name="rdr_portal_action" value="trigger_staging_rebuild">
                <button class="rdr-button rdr-button-secondary" type="submit">Trigger Staging Rebuild</button>
            </form>
        </section>
        <?php
        $content = ob_get_clean();
        Shell::render( 'Site Settings', 'settings', $content, array( 'subtitle' => 'Manage only approved public site content.' ) );
    }

    private function save() {
        $submitted = wp_unslash( $_POST );
        $errors = array();
        if ( ! isset( $_POST['rdr_portal_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['rdr_portal_nonce'] ) ), 'rdr_portal_site_settings_save' ) ) {
            $errors[] = 'Security check failed. Reload and try again.';
        }
        if ( ! current_user_can( 'manage_options' ) ) {
            $errors[] = 'You do not have permission to save Site Settings.';
        }
        $input = isset( $submitted['site'] ) && is_array( $submitted['site'] ) ? $submitted['site'] : array();
        if ( ! empty( $input['public_email'] ) && ! is_email( $input['public_email'] ) ) {
            $errors[] = 'Public Contact Email is invalid.';
        }
        foreach ( array( 'fiverr_url', 'linkedin_url' ) as $key ) {
            if ( ! empty( $input[ $key ] ) && ! wp_http_validate_url( $input[ $key ] ) ) {
                $errors[] = 'One of the profile URLs is invalid.';
            }
        }
        if ( $errors ) {
            return array( 'errors' => $errors, 'submitted' => $submitted );
        }
        $clean = $this->settings->sanitize_site_settings( $input );
        update_option( CoreSettings::SITE_OPTION, $clean, false );
        return array( 'redirect' => Support::url( 'settings' ) . '?rdr_notice=saved' );
    }

    private function trigger_staging_rebuild() {
        if ( 'POST' !== $_SERVER['REQUEST_METHOD'] ) {
            return array( 'errors' => array( 'Staging rebuild requires POST.' ), 'submitted' => array() );
        }
        if ( ! current_user_can( 'manage_options' ) ) {
            return array( 'errors' => array( 'You do not have permission to trigger a staging rebuild.' ), 'submitted' => array() );
        }
        if ( ! isset( $_POST['rdr_portal_staging_rebuild_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['rdr_portal_staging_rebuild_nonce'] ) ), 'rdr_portal_staging_rebuild' ) ) {
            return array( 'errors' => array( 'Security check failed. Reload and try again.' ), 'submitted' => array() );
        }

        $result = $this->deployment->trigger( 'manual' );
        return array(
            'redirect' => add_query_arg(
                'rdr_deploy_notice',
                ! empty( $result['ok'] ) ? 'success' : 'failed',
                Support::url( 'settings' )
            ),
        );
    }

    private function configuration_label( $status ) {
        if ( 'configured' === $status ) {
            return 'Configured';
        }
        if ( 'invalid' === $status ) {
            return 'Invalid configuration';
        }
        return 'Not configured';
    }

    private function status_time( $value ) {
        if ( ! $value ) {
            return '—';
        }
        $format = get_option( 'date_format' ) . ' ' . get_option( 'time_format' );
        return get_date_from_gmt( $value, $format );
    }
}
