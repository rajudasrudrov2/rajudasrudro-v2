<?php
namespace RDR\V2\HeadlessCMS;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

final class Plugin {
    /** @var self|null */
    private static $instance = null;

    /** @var PostTypes */
    private $post_types;
    /** @var Taxonomies */
    private $taxonomies;
    /** @var Meta */
    private $meta;
    /** @var Settings */
    private $settings;
    /** @var Media */
    private $media;
    /** @var Admin */
    private $admin;
    /** @var RestApi */
    private $rest_api;
    /** @var Headless */
    private $headless;
    /** @var \RDR\V2\HeadlessCMS\Portal\Portal */
    private $portal;

    public static function boot() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function activate() {
        self::load_files();
        $post_types = new PostTypes();
        $taxonomies = new Taxonomies();
        $meta = new Meta();

        $post_types->register();
        $taxonomies->register();
        $meta->register();
        \RDR\V2\HeadlessCMS\Portal\Portal::register_rewrites();
        self::seed_primary_services();
        flush_rewrite_rules( false );
    }

    private function __construct() {
        self::load_files();

        $this->post_types = new PostTypes();
        $this->taxonomies = new Taxonomies();
        $this->meta = new Meta();
        $this->settings = new Settings();
        $this->media = new Media();
        $this->admin = new Admin( $this->meta, $this->settings );
        $this->rest_api = new RestApi( $this->media, $this->settings );
        $this->headless = new Headless();
        $this->portal = new \RDR\V2\HeadlessCMS\Portal\Portal( $this->settings );

        add_action( 'init', array( $this->post_types, 'register' ), 5 );
        add_action( 'init', array( $this->taxonomies, 'register' ), 6 );
        add_action( 'init', array( $this->meta, 'register' ), 7 );

        $this->settings->hooks();
        $this->admin->hooks();
        $this->rest_api->hooks();
        $this->headless->hooks();
        $this->portal->hooks();
    }

    private static function load_files() {
        require_once RDR_V2_CMS_DIR . 'src/PostTypes.php';
        require_once RDR_V2_CMS_DIR . 'src/Taxonomies.php';
        require_once RDR_V2_CMS_DIR . 'src/Meta.php';
        require_once RDR_V2_CMS_DIR . 'src/Settings.php';
        require_once RDR_V2_CMS_DIR . 'src/Media.php';
        require_once RDR_V2_CMS_DIR . 'src/Admin.php';
        require_once RDR_V2_CMS_DIR . 'src/RestApi.php';
        require_once RDR_V2_CMS_DIR . 'src/Headless.php';
        require_once RDR_V2_CMS_DIR . 'src/Portal/Support.php';
        require_once RDR_V2_CMS_DIR . 'src/Portal/Assets.php';
        require_once RDR_V2_CMS_DIR . 'src/Portal/Shell.php';
        require_once RDR_V2_CMS_DIR . 'src/Portal/ContentModule.php';
        require_once RDR_V2_CMS_DIR . 'src/Portal/Dashboard.php';
        require_once RDR_V2_CMS_DIR . 'src/Portal/Projects.php';
        require_once RDR_V2_CMS_DIR . 'src/Portal/Services.php';
        require_once RDR_V2_CMS_DIR . 'src/Portal/Reviews.php';
        require_once RDR_V2_CMS_DIR . 'src/Portal/Articles.php';
        require_once RDR_V2_CMS_DIR . 'src/Portal/About.php';
        require_once RDR_V2_CMS_DIR . 'src/Portal/SiteSettings.php';
        require_once RDR_V2_CMS_DIR . 'src/Portal/Router.php';
        require_once RDR_V2_CMS_DIR . 'src/Portal/Portal.php';
    }

    public static function primary_services() {
        return array(
            'ai-ugc-video-ads'       => 'AI UGC Video Ads',
            'ai-video-production'    => 'AI Video Production',
            'ai-spokesperson-videos' => 'AI Spokesperson Videos',
            'web-design-development' => 'Web Design & Development',
        );
    }

    private static function seed_primary_services() {
        foreach ( self::primary_services() as $slug => $title ) {
            $existing = get_page_by_path( $slug, OBJECT, PostTypes::SERVICE );
            if ( $existing instanceof \WP_Post ) {
                continue;
            }

            $post_id = wp_insert_post(
                array(
                    'post_type'   => PostTypes::SERVICE,
                    'post_status' => 'draft',
                    'post_title'  => $title,
                    'post_name'   => $slug,
                ),
                true
            );

            if ( ! is_wp_error( $post_id ) && $post_id ) {
                update_post_meta( $post_id, Meta::PUBLICATION_STATE, 'public' );
            }
        }
    }
}
