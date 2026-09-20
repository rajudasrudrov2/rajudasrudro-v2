<?php
namespace RDR\V2\HeadlessCMS\Portal;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

final class Assets {
    public static function prepare( $needs_editor = false, $needs_media = true ) {
        wp_enqueue_style( 'dashicons' );
        wp_enqueue_style(
            'rdr-v2-admin-portal',
            plugins_url( 'assets/admin-portal.css', RDR_V2_CMS_FILE ),
            array(),
            RDR_V2_CMS_VERSION
        );
        wp_enqueue_script(
            'rdr-v2-admin-portal',
            plugins_url( 'assets/admin-portal.js', RDR_V2_CMS_FILE ),
            array(),
            RDR_V2_CMS_VERSION,
            true
        );
        if ( $needs_media ) {
            wp_enqueue_media();
        }
        if ( $needs_editor && function_exists( 'wp_enqueue_editor' ) ) {
            wp_enqueue_editor();
        }
    }
}
