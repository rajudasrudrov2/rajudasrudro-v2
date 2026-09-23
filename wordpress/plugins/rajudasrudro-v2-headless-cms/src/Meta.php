<?php
namespace RDR\V2\HeadlessCMS;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

final class Meta {
    const PUBLICATION_STATE = '_rdr_publication_state';

    public static function publication_states() {
        return array(
            'public'              => 'Public eligible',
            'development-preview' => 'Development preview',
            'migration'           => 'Migration / review',
            'internal'            => 'Internal only',
        );
    }

    public static function project_fields() {
        return array(
            '_rdr_short_summary'              => array( 'type' => 'string',  'sanitize' => 'sanitize_textarea_field' ),
            '_rdr_feature_media_id'           => array( 'type' => 'integer', 'sanitize' => 'absint' ),
            '_rdr_gallery_media_ids'          => array( 'type' => 'array',   'sanitize' => array( __CLASS__, 'sanitize_id_array' ) ),
            '_rdr_related_service_id'         => array( 'type' => 'integer', 'sanitize' => 'absint' ),
            '_rdr_featured'                   => array( 'type' => 'boolean', 'sanitize' => array( __CLASS__, 'sanitize_bool' ) ),
            '_rdr_case_study_enabled'         => array( 'type' => 'boolean', 'sanitize' => array( __CLASS__, 'sanitize_bool' ) ),
            '_rdr_case_status'                => array( 'type' => 'string',  'sanitize' => array( __CLASS__, 'sanitize_case_status' ) ),
            '_rdr_case_overview'              => array( 'type' => 'string',  'sanitize' => 'wp_kses_post' ),
            '_rdr_case_challenge'             => array( 'type' => 'string',  'sanitize' => 'wp_kses_post' ),
            '_rdr_case_approach'              => array( 'type' => 'string',  'sanitize' => 'wp_kses_post' ),
            '_rdr_case_deliverables'          => array( 'type' => 'array',   'sanitize' => array( __CLASS__, 'sanitize_text_list' ) ),
            '_rdr_case_creative'              => array( 'type' => 'string',  'sanitize' => 'wp_kses_post' ),
            '_rdr_case_outcomes'              => array( 'type' => 'string',  'sanitize' => 'wp_kses_post' ),
            '_rdr_related_review_id'          => array( 'type' => 'integer', 'sanitize' => 'absint' ),
            '_rdr_related_project_ids'        => array( 'type' => 'array',   'sanitize' => array( __CLASS__, 'sanitize_id_array' ) ),
            '_rdr_display_order'               => array( 'type' => 'integer', 'sanitize' => 'absint' ),
            '_rdr_seo_title'                   => array( 'type' => 'string',  'sanitize' => 'sanitize_text_field' ),
            '_rdr_meta_description'            => array( 'type' => 'string',  'sanitize' => 'sanitize_textarea_field' ),
            '_rdr_og_image_id'                 => array( 'type' => 'integer', 'sanitize' => 'absint' ),
            self::PUBLICATION_STATE            => array( 'type' => 'string',  'sanitize' => array( __CLASS__, 'sanitize_publication_state' ) ),
        );
    }

    public static function service_fields( $include_hero_fields = false ) {
        $fields = array(
            '_rdr_short_description'    => array( 'type' => 'string',  'sanitize' => 'sanitize_textarea_field' ),
            '_rdr_positioning'          => array( 'type' => 'string',  'sanitize' => 'sanitize_textarea_field' ),
            '_rdr_hero_copy'            => array( 'type' => 'string',  'sanitize' => 'wp_kses_post' ),
            '_rdr_hero_media_id'        => array( 'type' => 'integer', 'sanitize' => 'absint' ),
            '_rdr_capabilities'         => array( 'type' => 'array',   'sanitize' => array( __CLASS__, 'sanitize_text_list' ) ),
            '_rdr_deliverables'         => array( 'type' => 'array',   'sanitize' => array( __CLASS__, 'sanitize_text_list' ) ),
            '_rdr_use_cases'            => array( 'type' => 'array',   'sanitize' => array( __CLASS__, 'sanitize_text_list' ) ),
            '_rdr_formats'              => array( 'type' => 'array',   'sanitize' => array( __CLASS__, 'sanitize_text_list' ) ),
            '_rdr_process_steps'        => array( 'type' => 'array',   'sanitize' => array( __CLASS__, 'sanitize_structured_rows' ) ),
            '_rdr_faq'                  => array( 'type' => 'array',   'sanitize' => array( __CLASS__, 'sanitize_structured_rows' ) ),
            '_rdr_why_raju'             => array( 'type' => 'string',  'sanitize' => 'wp_kses_post' ),
            '_rdr_featured'             => array( 'type' => 'boolean', 'sanitize' => array( __CLASS__, 'sanitize_bool' ) ),
            '_rdr_display_order'        => array( 'type' => 'integer', 'sanitize' => 'absint' ),
            '_rdr_seo_title'            => array( 'type' => 'string',  'sanitize' => 'sanitize_text_field' ),
            '_rdr_meta_description'     => array( 'type' => 'string',  'sanitize' => 'sanitize_textarea_field' ),
            '_rdr_og_image_id'          => array( 'type' => 'integer', 'sanitize' => 'absint' ),
            self::PUBLICATION_STATE     => array( 'type' => 'string',  'sanitize' => array( __CLASS__, 'sanitize_publication_state' ) ),
        );

        if ( $include_hero_fields ) {
            $fields['_rdr_hero_title'] = array( 'type' => 'string', 'sanitize' => 'sanitize_text_field' );
            $fields['_rdr_hero_content'] = array( 'type' => 'string', 'sanitize' => 'wp_kses_post' );
        }

        return $fields;
    }

    public static function review_fields() {
        return array(
            '_rdr_reviewer_name'       => array( 'type' => 'string',  'sanitize' => 'sanitize_text_field' ),
            '_rdr_review_text'         => array( 'type' => 'string',  'sanitize' => 'sanitize_textarea_field' ),
            '_rdr_rating'              => array( 'type' => 'number',  'sanitize' => array( __CLASS__, 'sanitize_rating' ) ),
            '_rdr_source'              => array( 'type' => 'string',  'sanitize' => 'sanitize_text_field' ),
            '_rdr_source_url'          => array( 'type' => 'string',  'sanitize' => 'esc_url_raw' ),
            '_rdr_context'             => array( 'type' => 'string',  'sanitize' => 'sanitize_text_field' ),
            '_rdr_country'             => array( 'type' => 'string',  'sanitize' => 'sanitize_text_field' ),
            '_rdr_review_date'         => array( 'type' => 'string',  'sanitize' => array( __CLASS__, 'sanitize_date' ) ),
            '_rdr_featured'            => array( 'type' => 'boolean', 'sanitize' => array( __CLASS__, 'sanitize_bool' ) ),
            '_rdr_related_service_id'  => array( 'type' => 'integer', 'sanitize' => 'absint' ),
            '_rdr_related_project_id'  => array( 'type' => 'integer', 'sanitize' => 'absint' ),
            '_rdr_display_order'       => array( 'type' => 'integer', 'sanitize' => 'absint' ),
            self::PUBLICATION_STATE    => array( 'type' => 'string',  'sanitize' => array( __CLASS__, 'sanitize_publication_state' ) ),
        );
    }

    public static function article_fields() {
        return array(
            '_rdr_featured'            => array( 'type' => 'boolean', 'sanitize' => array( __CLASS__, 'sanitize_bool' ) ),
            '_rdr_seo_title'           => array( 'type' => 'string',  'sanitize' => 'sanitize_text_field' ),
            '_rdr_meta_description'    => array( 'type' => 'string',  'sanitize' => 'sanitize_textarea_field' ),
            '_rdr_og_image_id'         => array( 'type' => 'integer', 'sanitize' => 'absint' ),
            '_rdr_related_service_ids' => array( 'type' => 'array',   'sanitize' => array( __CLASS__, 'sanitize_id_array' ) ),
            '_rdr_related_project_ids' => array( 'type' => 'array',   'sanitize' => array( __CLASS__, 'sanitize_id_array' ) ),
            self::PUBLICATION_STATE    => array( 'type' => 'string',  'sanitize' => array( __CLASS__, 'sanitize_publication_state' ) ),
        );
    }

    public function register() {
        foreach ( self::project_fields() as $key => $schema ) {
            $this->register_field( PostTypes::PROJECT, $key, $schema );
        }
        foreach ( self::service_fields( true ) as $key => $schema ) {
            $this->register_field( PostTypes::SERVICE, $key, $schema );
        }
        foreach ( self::review_fields() as $key => $schema ) {
            $this->register_field( PostTypes::REVIEW, $key, $schema );
        }
        foreach ( self::article_fields() as $key => $schema ) {
            $this->register_field( 'post', $key, $schema );
        }
    }

    private function register_field( $post_type, $key, $schema ) {
        register_post_meta(
            $post_type,
            $key,
            array(
                'type'              => $schema['type'],
                'single'            => true,
                'default'           => $this->default_for_type( $schema['type'], $key ),
                'sanitize_callback' => $schema['sanitize'],
                'auth_callback'     => static function() use ( $post_type ) {
                    $object = get_post_type_object( $post_type );
                    return $object ? current_user_can( $object->cap->edit_posts ) : current_user_can( 'edit_posts' );
                },
                'show_in_rest'      => false,
            )
        );
    }

    private function default_for_type( $type, $key ) {
        if ( self::PUBLICATION_STATE === $key ) {
            return 'public';
        }
        if ( 'array' === $type ) {
            return array();
        }
        if ( 'boolean' === $type ) {
            return false;
        }
        if ( 'integer' === $type || 'number' === $type ) {
            return 0;
        }
        return '';
    }

    public static function sanitize_bool( $value ) {
        return ! empty( $value );
    }

    public static function sanitize_id_array( $value ) {
        if ( is_string( $value ) ) {
            $value = preg_split( '/[\s,]+/', $value, -1, PREG_SPLIT_NO_EMPTY );
        }
        if ( ! is_array( $value ) ) {
            return array();
        }
        return array_values( array_unique( array_filter( array_map( 'absint', $value ) ) ) );
    }

    public static function sanitize_text_list( $value ) {
        if ( is_string( $value ) ) {
            $value = preg_split( '/\r\n|\r|\n/', $value );
        }
        if ( ! is_array( $value ) ) {
            return array();
        }
        $clean = array();
        foreach ( $value as $item ) {
            $item = sanitize_text_field( $item );
            if ( '' !== $item ) {
                $clean[] = $item;
            }
        }
        return array_values( $clean );
    }

    public static function sanitize_structured_rows( $value ) {
        if ( is_string( $value ) ) {
            $decoded = json_decode( wp_unslash( $value ), true );
            $value = is_array( $decoded ) ? $decoded : array();
        }
        if ( ! is_array( $value ) ) {
            return array();
        }
        $clean = array();
        foreach ( $value as $row ) {
            if ( ! is_array( $row ) ) {
                continue;
            }
            $title = isset( $row['title'] ) ? sanitize_text_field( $row['title'] ) : '';
            $body  = isset( $row['body'] ) ? sanitize_textarea_field( $row['body'] ) : '';
            if ( '' === $title && '' === $body ) {
                continue;
            }
            $clean[] = array( 'title' => $title, 'body' => $body );
        }
        return $clean;
    }

    public static function sanitize_publication_state( $value ) {
        $value = sanitize_key( (string) $value );
        return array_key_exists( $value, self::publication_states() ) ? $value : 'internal';
    }

    public static function sanitize_case_status( $value ) {
        $value = sanitize_key( (string) $value );
        $allowed = array( 'none', 'available', 'development-preview', 'migration' );
        return in_array( $value, $allowed, true ) ? $value : 'none';
    }

    public static function sanitize_rating( $value ) {
        if ( '' === $value || null === $value ) {
            return 0;
        }
        $rating = round( (float) $value, 1 );
        return ( $rating >= 1 && $rating <= 5 ) ? $rating : 0;
    }

    public static function sanitize_date( $value ) {
        $value = sanitize_text_field( (string) $value );
        if ( '' === $value ) {
            return '';
        }
        $date = \DateTime::createFromFormat( 'Y-m-d', $value );
        return ( $date && $date->format( 'Y-m-d' ) === $value ) ? $value : '';
    }
}
