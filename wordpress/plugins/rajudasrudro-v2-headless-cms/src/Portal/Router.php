<?php
namespace RDR\V2\HeadlessCMS\Portal;

use RDR\V2\HeadlessCMS\Settings as CoreSettings;
use RDR\V2\HeadlessCMS\DeploymentWebhook;

if ( ! defined( 'ABSPATH' ) ) { exit; }

final class Router {
    private $settings;
    private $deployment;
    public function __construct( CoreSettings $settings, DeploymentWebhook $deployment ) { $this->settings = $settings; $this->deployment = $deployment; }

    public static function register_rewrites() {
        add_rewrite_rule( '^admin-portal/?$', 'index.php?rdr_portal_route=dashboard', 'top' );
        add_rewrite_rule( '^admin-portal/(.+?)/?$', 'index.php?rdr_portal_route=$matches[1]', 'top' );
    }

    public function hooks() {
        add_action( 'init', array( __CLASS__, 'register_rewrites' ), 20 );
        add_filter( 'query_vars', array( $this, 'query_vars' ) );
        add_action( 'template_redirect', array( $this, 'dispatch' ), 0 );
    }

    public function query_vars( $vars ) { $vars[] = 'rdr_portal_route'; return $vars; }

    public function dispatch() {
        $route = get_query_var( 'rdr_portal_route' );
        if ( '' === $route && false === strpos( trim( (string) $_SERVER['REQUEST_URI'], '/' ), 'admin-portal' ) ) { return; }
        $route = Support::normalize_route( $route );
        if ( 'login' === $route ) { Assets::prepare( false, false ); $this->login(); }

        if ( ! is_user_logged_in() ) {
            $redirect = Support::url( $route === 'dashboard' ? '' : $route );
            wp_safe_redirect( add_query_arg( 'redirect_to', rawurlencode( $redirect ), Support::url( 'login' ) ) );
            exit;
        }
        if ( ! Support::can_access() ) {
            Assets::prepare( false, false );
            Shell::simple_error( 403, 'Access denied', 'Your WordPress account does not have permission to manage this CMS.' );
        }

        $needs_editor = (bool) preg_match( '#^insights/(add|edit/\d+)$#', $route );
        Assets::prepare( $needs_editor, true );

        if ( 'dashboard' === $route ) { ( new Dashboard() )->render(); }
        if ( 'work' === $route ) { ( new Projects() )->list_page(); }
        if ( 'work/add' === $route ) { ( new Projects() )->add_page(); }
        if ( preg_match( '#^work/edit/(\d+)$#', $route, $m ) ) { ( new Projects() )->edit_page( absint( $m[1] ) ); }
        if ( 'services' === $route ) { ( new Services() )->list_page(); }
        if ( preg_match( '#^services/edit/(\d+)$#', $route, $m ) ) { ( new Services() )->edit_page( absint( $m[1] ) ); }
        if ( 'reviews' === $route ) { ( new Reviews() )->list_page(); }
        if ( 'reviews/add' === $route ) { ( new Reviews() )->add_page(); }
        if ( preg_match( '#^reviews/edit/(\d+)$#', $route, $m ) ) { ( new Reviews() )->edit_page( absint( $m[1] ) ); }
        if ( 'insights' === $route ) { ( new Articles() )->list_page(); }
        if ( 'insights/add' === $route ) { ( new Articles() )->add_page(); }
        if ( preg_match( '#^insights/edit/(\d+)$#', $route, $m ) ) { ( new Articles() )->edit_page( absint( $m[1] ) ); }
        if ( 'about' === $route ) { ( new About( $this->settings ) )->render(); }
        if ( 'settings' === $route ) { ( new SiteSettings( $this->settings, $this->deployment ) )->render(); }

        Shell::simple_error( 404, 'Portal page not found', 'The requested Admin Portal route does not exist.' );
    }

    private function login() {
        if ( is_user_logged_in() && Support::can_access() ) { wp_safe_redirect( Support::url() ); exit; }
        $errors = array();
        $redirect = isset( $_GET['redirect_to'] ) ? rawurldecode( sanitize_text_field( wp_unslash( $_GET['redirect_to'] ) ) ) : '';
        if ( 'POST' === $_SERVER['REQUEST_METHOD'] ) {
            $redirect = isset( $_POST['redirect_to'] ) ? sanitize_text_field( wp_unslash( $_POST['redirect_to'] ) ) : '';
            if ( ! isset( $_POST['rdr_portal_login_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['rdr_portal_login_nonce'] ) ), 'rdr_portal_login' ) ) {
                $errors[] = 'Security check failed. Reload the page and try again.';
            } else {
                $creds = array(
                    'user_login'    => isset( $_POST['log'] ) ? sanitize_text_field( wp_unslash( $_POST['log'] ) ) : '',
                    'user_password' => isset( $_POST['pwd'] ) ? (string) wp_unslash( $_POST['pwd'] ) : '',
                    'remember'      => ! empty( $_POST['rememberme'] ),
                );
                $user = wp_signon( $creds, is_ssl() );
                if ( is_wp_error( $user ) ) {
                    $errors[] = 'Invalid login details.';
                } elseif ( ! user_can( $user, 'edit_posts' ) ) {
                    wp_logout();
                    $errors[] = 'This WordPress account does not have CMS management permission.';
                } else {
                    $safe = wp_validate_redirect( $redirect, Support::url() );
                    $parsed = wp_parse_url( $safe );
                    $home = wp_parse_url( home_url( '/' ) );
                    if ( ! empty( $parsed['host'] ) && isset( $home['host'] ) && strtolower( $parsed['host'] ) !== strtolower( $home['host'] ) ) { $safe = Support::url(); }
                    if ( empty( $parsed['path'] ) || 0 !== strpos( $parsed['path'], wp_parse_url( Support::url(), PHP_URL_PATH ) ) ) { $safe = Support::url(); }
                    wp_safe_redirect( $safe ); exit;
                }
            }
        }
        Shell::login( $errors, $redirect );
    }
}
