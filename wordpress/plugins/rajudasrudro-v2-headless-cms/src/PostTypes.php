<?php
namespace RDR\V2\HeadlessCMS;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

final class PostTypes {
    const PROJECT = 'rdr_project';
    const SERVICE = 'rdr_service';
    const REVIEW  = 'rdr_review';

    public function register() {
        register_post_type(
            self::PROJECT,
            array(
                'labels' => array(
                    'name'          => 'Work / Projects',
                    'singular_name' => 'Project',
                    'add_new_item'  => 'Add Project',
                    'edit_item'     => 'Edit Project',
                    'menu_name'     => 'Work / Projects',
                ),
                'public'              => false,
                'publicly_queryable'  => false,
                'show_ui'             => true,
                'show_in_menu'        => true,
                'show_in_rest'        => false,
                'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
                'menu_icon'           => 'dashicons-portfolio',
                'capability_type'     => 'post',
                'map_meta_cap'        => true,
                'has_archive'         => false,
                'rewrite'             => false,
            )
        );

        register_post_type(
            self::SERVICE,
            array(
                'labels' => array(
                    'name'          => 'Services',
                    'singular_name' => 'Service',
                    'add_new_item'  => 'Add Service',
                    'edit_item'     => 'Edit Service',
                    'menu_name'     => 'Services',
                ),
                'public'              => false,
                'publicly_queryable'  => false,
                'show_ui'             => true,
                'show_in_menu'        => true,
                'show_in_rest'        => false,
                'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
                'menu_icon'           => 'dashicons-admin-tools',
                'capability_type'     => 'post',
                'map_meta_cap'        => true,
                'has_archive'         => false,
                'rewrite'             => false,
            )
        );

        register_post_type(
            self::REVIEW,
            array(
                'labels' => array(
                    'name'          => 'Reviews',
                    'singular_name' => 'Review',
                    'add_new_item'  => 'Add Review',
                    'edit_item'     => 'Edit Review',
                    'menu_name'     => 'Reviews',
                ),
                'public'              => false,
                'publicly_queryable'  => false,
                'show_ui'             => true,
                'show_in_menu'        => true,
                'show_in_rest'        => false,
                'supports'            => array( 'title', 'revisions' ),
                'menu_icon'           => 'dashicons-star-filled',
                'capability_type'     => 'post',
                'map_meta_cap'        => true,
                'has_archive'         => false,
                'rewrite'             => false,
            )
        );
    }
}
