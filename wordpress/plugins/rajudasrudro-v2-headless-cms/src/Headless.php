<?php
namespace RDR\V2\HeadlessCMS;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

final class Headless {
    public function hooks() {
        add_action( 'template_redirect', array( $this, 'render_root_holding_page' ), 1 );
        add_filter( 'wp_robots', array( $this, 'noindex_public_frontend' ), 999 );
        add_filter( 'wp_sitemaps_enabled', '__return_false' );
    }

    public function render_root_holding_page() {
        if ( is_admin() || $this->is_rest_request() || is_preview() || is_customize_preview() || ! is_front_page() ) {
            return;
        }

        $request_uri  = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '/';
        $request_path = wp_parse_url( $request_uri, PHP_URL_PATH );
        $home_path    = wp_parse_url( home_url( '/' ), PHP_URL_PATH );
        $request_path = '/' . ltrim( (string) $request_path, '/' );
        $home_path    = '/' . ltrim( (string) $home_path, '/' );

        if ( untrailingslashit( $request_path ) !== untrailingslashit( $home_path ) ) {
            return;
        }

        status_header( 200 );
        nocache_headers();
        header( 'Content-Type: text/html; charset=' . get_bloginfo( 'charset' ) );
        ?><!doctype html>
<html lang="en">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>Raju Das Rudro — Content Management System</title>
<style>
*{box-sizing:border-box}html{color-scheme:light}body{margin:0;min-width:0;background:#f8fafc;color:#172033;font-family:system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;line-height:1.6}.rdr-cms-home{min-height:100vh;display:grid;place-items:center;padding:2rem 1rem}.rdr-cms-card{width:min(100%,38rem);padding:clamp(2rem,6vw,3.25rem);border:1px solid #e2e8f0;border-radius:1rem;background:#fff;text-align:center;box-shadow:0 18px 50px rgba(15,23,42,.06)}.rdr-cms-brand{margin:0 0 .65rem;font-size:.92rem;font-weight:750;letter-spacing:.06em;text-transform:uppercase;color:#526174}.rdr-cms-card h1{margin:0;font-size:clamp(1.8rem,5vw,2.65rem);line-height:1.12;letter-spacing:-.035em}.rdr-cms-copy{max-width:30rem;margin:1rem auto 0;color:#64748b;font-size:1rem}.rdr-cms-cta{display:inline-flex;align-items:center;justify-content:center;min-height:3rem;margin-top:1.5rem;padding:.72rem 1.1rem;border-radius:.65rem;background:#172033;color:#fff;text-decoration:none;font-size:.95rem;font-weight:700}.rdr-cms-cta:hover{background:#24324a}.rdr-cms-cta:focus-visible{outline:3px solid #60a5fa;outline-offset:3px}.rdr-cms-footer{margin:1.6rem 0 0;color:#94a3b8;font-size:.82rem}@media(max-width:30rem){.rdr-cms-home{padding:1rem}.rdr-cms-card{padding:1.6rem 1.1rem;border-radius:.8rem}.rdr-cms-card h1{font-size:1.8rem}.rdr-cms-cta{width:100%}}
</style>
</head>
<body>
<main class="rdr-cms-home">
<section class="rdr-cms-card" aria-labelledby="rdr-cms-title">
<p class="rdr-cms-brand">Raju Das Rudro</p>
<h1 id="rdr-cms-title">Content Management System</h1>
<p class="rdr-cms-copy">This subdomain is used to manage content for rajudasrudro.com.</p>
<a class="rdr-cms-cta" href="https://rajudasrudro.com/">Visit the Public Website →</a>
<p class="rdr-cms-footer">© Raju Das Rudro</p>
</section>
</main>
</body>
</html><?php
        exit;
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
