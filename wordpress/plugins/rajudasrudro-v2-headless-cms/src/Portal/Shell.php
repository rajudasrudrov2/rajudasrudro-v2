<?php
namespace RDR\V2\HeadlessCMS\Portal;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

final class Shell {
    public static function render( $title, $active, $content, $options = array() ) {
        $user = wp_get_current_user();
        $flash = Support::flash_message();
        $subtitle = isset( $options['subtitle'] ) ? $options['subtitle'] : '';
        $breadcrumbs = isset( $options['breadcrumbs'] ) && is_array( $options['breadcrumbs'] ) ? $options['breadcrumbs'] : array();
        $view_site = home_url( '/' );
        $logout = wp_logout_url( Support::url( 'login' ) );
        status_header( isset( $options['status'] ) ? absint( $options['status'] ) : 200 );
        nocache_headers();
        ?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex,nofollow,noarchive">
<title><?php echo esc_html( $title ); ?> — RDR V2 Admin Portal</title>
<?php wp_print_styles(); wp_print_head_scripts(); ?>
</head>
<body class="rdr-portal-body">
<a class="rdr-skip" href="#rdr-main">Skip to content</a>
<div class="rdr-portal" data-rdr-portal>
    <aside class="rdr-sidebar" id="rdr-sidebar" aria-label="Admin Portal navigation">
        <a class="rdr-brand" href="<?php echo esc_url( Support::url() ); ?>">
            <span class="rdr-brand-mark" aria-hidden="true">R</span>
            <span><strong>RajuDasRudro V2</strong><small>Headless CMS</small></span>
        </a>
        <nav class="rdr-nav">
            <?php self::nav_link( 'dashboard', 'Dashboard', Support::url(), 'dashicons-dashboard', $active ); ?>
            <p class="rdr-nav-heading">Content</p>
            <?php self::nav_link( 'work', 'Work / Projects', Support::url( 'work' ), 'dashicons-portfolio', $active ); ?>
            <?php self::nav_link( 'services', 'Services', Support::url( 'services' ), 'dashicons-admin-tools', $active ); ?>
            <?php self::nav_link( 'reviews', 'Reviews', Support::url( 'reviews' ), 'dashicons-star-filled', $active ); ?>
            <?php self::nav_link( 'insights', 'Insights', Support::url( 'insights' ), 'dashicons-welcome-write-blog', $active ); ?>
            <p class="rdr-nav-heading">Site</p>
            <?php self::nav_link( 'about', 'About', Support::url( 'about' ), 'dashicons-id-alt', $active ); ?>
            <?php if ( current_user_can( 'manage_options' ) ) : ?>
                <?php self::nav_link( 'settings', 'Site Settings', Support::url( 'settings' ), 'dashicons-admin-settings', $active ); ?>
            <?php endif; ?>
        </nav>
        <div class="rdr-sidebar-footer">
            <a href="<?php echo esc_url( $logout ); ?>"><span class="dashicons dashicons-exit" aria-hidden="true"></span> Logout</a>
        </div>
    </aside>

    <div class="rdr-app">
        <header class="rdr-topbar">
            <button type="button" class="rdr-icon-button rdr-menu-toggle" data-rdr-menu-toggle aria-controls="rdr-sidebar" aria-expanded="false"><span class="dashicons dashicons-menu" aria-hidden="true"></span><span class="screen-reader-text">Toggle navigation</span></button>
            <div class="rdr-topbar-spacer"></div>
            <a class="rdr-top-link" href="<?php echo esc_url( $view_site ); ?>" target="_blank" rel="noopener">View site</a>
            <div class="rdr-user"><span class="dashicons dashicons-admin-users" aria-hidden="true"></span><span><?php echo esc_html( $user->display_name ); ?></span></div>
        </header>

        <main id="rdr-main" class="rdr-main">
            <?php if ( $breadcrumbs ) : ?>
                <nav class="rdr-breadcrumbs" aria-label="Breadcrumb">
                    <?php foreach ( $breadcrumbs as $index => $crumb ) : ?>
                        <?php if ( $index > 0 ) : ?><span aria-hidden="true">/</span><?php endif; ?>
                        <?php if ( ! empty( $crumb['url'] ) ) : ?><a href="<?php echo esc_url( $crumb['url'] ); ?>"><?php echo esc_html( $crumb['label'] ); ?></a><?php else : ?><span><?php echo esc_html( $crumb['label'] ); ?></span><?php endif; ?>
                    <?php endforeach; ?>
                </nav>
            <?php endif; ?>
            <div class="rdr-page-head">
                <div><h1><?php echo esc_html( $title ); ?></h1><?php if ( $subtitle ) : ?><p><?php echo esc_html( $subtitle ); ?></p><?php endif; ?></div>
                <?php if ( ! empty( $options['actions'] ) ) : ?><div class="rdr-page-actions"><?php echo $options['actions']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div><?php endif; ?>
            </div>
            <?php if ( $flash ) : ?><div class="rdr-flash rdr-flash-<?php echo esc_attr( $flash['type'] ); ?>" role="status"><?php echo esc_html( $flash['message'] ); ?></div><?php endif; ?>
            <?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </main>
    </div>
</div>
<?php self::print_footer_dependencies(); ?>
</body>
</html><?php
        exit;
    }

    private static function print_footer_dependencies() {
        /*
         * wp_enqueue_media() registers WordPress' native media templates on
         * wp_footer. The custom Portal shell intentionally does not execute the
         * public theme footer lifecycle, so print those templates explicitly
         * when media was requested for this Portal response.
         */
        if ( did_action( 'wp_enqueue_media' ) && function_exists( 'wp_print_media_templates' ) && 0 === did_action( 'print_media_templates' ) ) {
            wp_print_media_templates();

            // Prevent duplicate media-template output if wp_footer is invoked later.
            remove_action( 'wp_footer', 'wp_print_media_templates' );
        }

        wp_print_footer_scripts();
    }

    private static function nav_link( $key, $label, $url, $icon, $active ) {
        $is_active = $key === $active;
        printf(
            '<a class="rdr-nav-link%1$s" href="%2$s"%3$s><span class="dashicons %4$s" aria-hidden="true"></span><span>%5$s</span></a>',
            $is_active ? ' is-active' : '',
            esc_url( $url ),
            $is_active ? ' aria-current="page"' : '',
            esc_attr( $icon ),
            esc_html( $label )
        );
    }

    public static function login( $errors = array(), $redirect = '' ) {
        status_header( 200 );
        nocache_headers();
        ?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex,nofollow,noarchive">
<title>Admin Portal Login — RDR V2</title>
<?php wp_print_styles(); wp_print_head_scripts(); ?>
</head>
<body class="rdr-portal-body rdr-login-body">
<main class="rdr-login-wrap">
    <section class="rdr-login-card" aria-labelledby="rdr-login-title">
        <div class="rdr-brand rdr-brand-login"><span class="rdr-brand-mark" aria-hidden="true">R</span><span><strong>RajuDasRudro V2</strong><small>Admin Portal</small></span></div>
        <h1 id="rdr-login-title">Sign in</h1>
        <p>Use your WordPress account to manage website content.</p>
        <?php if ( $errors ) : ?><div class="rdr-flash rdr-flash-error" role="alert"><ul><?php foreach ( $errors as $error ) : ?><li><?php echo esc_html( $error ); ?></li><?php endforeach; ?></ul></div><?php endif; ?>
        <form method="post" class="rdr-form" novalidate>
            <?php wp_nonce_field( 'rdr_portal_login', 'rdr_portal_login_nonce' ); ?>
            <input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect ); ?>">
            <div class="rdr-field"><label for="rdr-user-login">Username or email</label><input id="rdr-user-login" name="log" type="text" autocomplete="username" required></div>
            <div class="rdr-field"><label for="rdr-user-pass">Password</label><input id="rdr-user-pass" name="pwd" type="password" autocomplete="current-password" required></div>
            <label class="rdr-check"><input type="checkbox" name="rememberme" value="forever"> <span>Remember me</span></label>
            <button class="rdr-button rdr-button-primary rdr-button-block" type="submit">Sign in</button>
        </form>
        <p class="rdr-login-fallback">Emergency/technical login remains available through <a href="<?php echo esc_url( wp_login_url() ); ?>">WordPress login</a>.</p>
    </section>
</main>
<?php wp_print_footer_scripts(); ?>
</body>
</html><?php
        exit;
    }

    public static function simple_error( $status, $title, $message ) {
        $content = '<div class="rdr-card rdr-empty"><span class="dashicons dashicons-warning" aria-hidden="true"></span><h2>' . esc_html( $title ) . '</h2><p>' . esc_html( $message ) . '</p><a class="rdr-button rdr-button-secondary" href="' . esc_url( Support::url() ) . '">Back to Dashboard</a></div>';
        self::render( $title, '', $content, array( 'status' => $status ) );
    }
}
