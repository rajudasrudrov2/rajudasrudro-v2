<?php
namespace RDR\V2\HeadlessCMS;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

final class Headless {
    public function hooks() {
        add_filter( 'wp_robots', array( $this, 'noindex_public_frontend' ), 999 );
        add_filter( 'wp_sitemaps_enabled', '__return_false' );
    }

    public function noindex_public_frontend( $robots ) {
        if ( is_admin() || $this->is_rest_request() ) {
            return $robots;
        }
        $robots['noindex'] = true;
        $robots['nofollow'] = true;
        $robots['noarchive'] = true;
        unset( $robots['index'], $robots['follow'] );
        return $robots;
    }

    private function is_rest_request() {
        return defined( 'REST_REQUEST' ) && REST_REQUEST;
    }
}
