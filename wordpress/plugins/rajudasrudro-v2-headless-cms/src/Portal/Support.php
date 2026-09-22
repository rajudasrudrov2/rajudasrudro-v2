<?php
namespace RDR\V2\HeadlessCMS\Portal;

use RDR\V2\HeadlessCMS\Meta;
use RDR\V2\HeadlessCMS\PostTypes;
use RDR\V2\HeadlessCMS\Taxonomies;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

final class Support {
    public static function url( $path = '' ) {
        $path = trim( (string) $path, '/' );
        return home_url( '/admin-portal' . ( '' !== $path ? '/' . $path : '' ) . '/' );
    }

    public static function can_access() {
        return current_user_can( 'edit_posts' );
    }

    public static function normalize_route( $route ) {
        $route = trim( sanitize_text_field( (string) $route ), '/' );
        return '' === $route ? 'dashboard' : $route;
    }

    public static function portal_state_options() {
        return array(
            'draft'               => 'Draft',
            'published'           => 'Published',
            'internal'            => 'Internal / Not Public',
            'development-preview' => 'Development Preview',
            'migration'           => 'Migration',
        );
    }

    public static function current_portal_state( $post ) {
        if ( ! $post instanceof \WP_Post ) {
            return 'draft';
        }
        $rdr = get_post_meta( $post->ID, Meta::PUBLICATION_STATE, true );
        if ( 'publish' !== $post->post_status ) {
            return 'draft';
        }
        if ( 'public' === $rdr || '' === $rdr ) {
            return 'published';
        }
        return array_key_exists( $rdr, self::portal_state_options() ) ? $rdr : 'internal';
    }

    public static function state_to_storage( $state ) {
        $state = sanitize_key( (string) $state );
        if ( 'published' === $state ) {
            return array( 'post_status' => 'publish', 'rdr_state' => 'public' );
        }
        if ( 'draft' === $state ) {
            return array( 'post_status' => 'draft', 'rdr_state' => 'public' );
        }
        if ( in_array( $state, array( 'internal', 'development-preview', 'migration' ), true ) ) {
            return array( 'post_status' => 'publish', 'rdr_state' => $state );
        }
        return array( 'post_status' => 'draft', 'rdr_state' => 'internal' );
    }

    public static function apply_portal_state( $post_id, $state ) {
        $mapped = self::state_to_storage( $state );
        update_post_meta( $post_id, Meta::PUBLICATION_STATE, $mapped['rdr_state'] );
        return $mapped['post_status'];
    }

    public static function field_value( $post, $key, $submitted = null ) {
        if ( is_array( $submitted ) && array_key_exists( $key, $submitted ) ) {
            return $submitted[ $key ];
        }
        if ( $post instanceof \WP_Post ) {
            return get_post_meta( $post->ID, $key, true );
        }
        return '';
    }

    public static function save_meta_fields( $post_id, $schema, $payload ) {
        $payload = is_array( $payload ) ? $payload : array();
        foreach ( $schema as $key => $definition ) {
            $raw = array_key_exists( $key, $payload ) ? $payload[ $key ] : null;
            if ( 'boolean' === $definition['type'] ) {
                $raw = ! empty( $raw ) ? 1 : 0;
            }
            $value = call_user_func( $definition['sanitize'], $raw );
            update_post_meta( $post_id, $key, $value );
        }
    }

    public static function validate_relation( $id, $post_type, $optional = true ) {
        $id = absint( $id );
        if ( ! $id ) {
            return $optional ? 0 : false;
        }
        $post = get_post( $id );
        return ( $post instanceof \WP_Post && $post_type === $post->post_type ) ? $id : false;
    }

    public static function validate_relation_ids( $ids, $post_type ) {
        $ids = Meta::sanitize_id_array( $ids );
        $valid = array();
        foreach ( $ids as $id ) {
            if ( false !== self::validate_relation( $id, $post_type, true ) ) {
                $post = get_post( $id );
                if ( $post instanceof \WP_Post && $post_type === $post->post_type ) {
                    $valid[] = $id;
                }
            }
        }
        return array_values( array_unique( $valid ) );
    }

    public static function post_options( $post_type, $selected = 0, $include_empty = true ) {
        $posts = get_posts(
            array(
                'post_type'      => $post_type,
                'post_status'    => array( 'publish', 'draft', 'pending', 'private' ),
                'posts_per_page' => 200,
                'orderby'        => 'title',
                'order'          => 'ASC',
            )
        );
        $html = $include_empty ? '<option value="">— None —</option>' : '';
        foreach ( $posts as $post ) {
            $html .= sprintf(
                '<option value="%1$d"%2$s>%3$s</option>',
                absint( $post->ID ),
                selected( absint( $selected ), absint( $post->ID ), false ),
                esc_html( $post->post_title ? $post->post_title : '(Untitled #' . $post->ID . ')' )
            );
        }
        return $html;
    }

    public static function post_multiselect( $name, $post_type, $selected_ids ) {
        $selected_ids = Meta::sanitize_id_array( $selected_ids );
        $posts = get_posts(
            array(
                'post_type'      => $post_type,
                'post_status'    => array( 'publish', 'draft', 'pending', 'private' ),
                'posts_per_page' => 200,
                'orderby'        => 'title',
                'order'          => 'ASC',
            )
        );
        $html = '<select class="rdr-multiselect" multiple size="6" name="' . esc_attr( $name ) . '[]">';
        foreach ( $posts as $post ) {
            $html .= sprintf(
                '<option value="%1$d"%2$s>%3$s</option>',
                absint( $post->ID ),
                in_array( absint( $post->ID ), $selected_ids, true ) ? ' selected' : '',
                esc_html( $post->post_title ? $post->post_title : '(Untitled #' . $post->ID . ')' )
            );
        }
        $html .= '</select>';
        return $html;
    }

    public static function category_options( $selected_ids = array() ) {
        $selected_ids = array_map( 'absint', (array) $selected_ids );
        $terms = get_terms(
            array(
                'taxonomy'   => Taxonomies::PROJECT_CATEGORY,
                'hide_empty' => false,
            )
        );
        $html = '';
        if ( ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                $html .= sprintf(
                    '<label class="rdr-check"><input type="checkbox" name="project_categories[]" value="%1$d"%2$s> <span>%3$s</span></label>',
                    absint( $term->term_id ),
                    in_array( absint( $term->term_id ), $selected_ids, true ) ? ' checked' : '',
                    esc_html( $term->name )
                );
            }
        }
        return $html ? $html : '<p class="rdr-help">No Project Categories exist yet. Use the fallback WordPress taxonomy screen to create category terms when needed.</p>';
    }

    public static function update_categories( $post_id, $ids ) {
        $ids = array_values( array_filter( array_map( 'absint', (array) $ids ) ) );
        return wp_set_object_terms( $post_id, $ids, Taxonomies::PROJECT_CATEGORY, false );
    }

    public static function media_field( $name, $label, $attachment_id = 0, $multiple = false, $description = '' ) {
        $ids = $multiple ? Meta::sanitize_id_array( $attachment_id ) : array_filter( array( absint( $attachment_id ) ) );
        $serialized = $multiple ? implode( ',', $ids ) : ( $ids ? (string) $ids[0] : '' );
        $preview = '';
        foreach ( $ids as $id ) {
            $src = wp_get_attachment_image_url( $id, 'thumbnail' );
            if ( $src ) {
                $preview .= '<img src="' . esc_url( $src ) . '" alt="">';
            }
        }
        return '<div class="rdr-field rdr-media-field" data-rdr-media-field data-multiple="' . ( $multiple ? '1' : '0' ) . '">' .
            '<label>' . esc_html( $label ) . '</label>' .
            '<input type="hidden" name="' . esc_attr( $name ) . '" value="' . esc_attr( $serialized ) . '" data-rdr-media-input>' .
            '<div class="rdr-media-preview" data-rdr-media-preview>' . $preview . '</div>' .
            '<div class="rdr-inline-actions"><button type="button" class="rdr-button rdr-button-secondary" data-rdr-media-pick>' . ( $ids ? 'Replace selection' : 'Select media' ) . '</button>' .
            '<button type="button" class="rdr-button rdr-button-quiet" data-rdr-media-clear>Remove</button></div>' .
            ( $description ? '<p class="rdr-help">' . esc_html( $description ) . '</p>' : '' ) .
            '</div>';
    }

    public static function flash_message() {
        $notice = isset( $_GET['rdr_notice'] ) ? sanitize_key( wp_unslash( $_GET['rdr_notice'] ) ) : '';
        if ( 'saved' === $notice ) {
            return array( 'type' => 'success', 'message' => 'Saved successfully.' );
        }
        if ( 'created' === $notice ) {
            return array( 'type' => 'success', 'message' => 'Created successfully.' );
        }
        return null;
    }

    public static function format_date( $mysql ) {
        $timestamp = $mysql ? strtotime( $mysql ) : false;
        return $timestamp ? date_i18n( get_option( 'date_format' ), $timestamp ) : '—';
    }
}
