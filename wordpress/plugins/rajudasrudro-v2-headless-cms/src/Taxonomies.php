<?php
namespace RDR\V2\HeadlessCMS;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

final class Taxonomies {
    const PROJECT_CATEGORY = 'rdr_project_category';

    public function register() {
        register_taxonomy(
            self::PROJECT_CATEGORY,
            array( PostTypes::PROJECT ),
            array(
                'labels' => array(
                    'name'          => 'Project Categories',
                    'singular_name' => 'Project Category',
                    'menu_name'     => 'Project Categories',
                ),
                'public'            => false,
                'show_ui'           => true,
                'show_admin_column' => true,
                'show_in_rest'      => false,
                'hierarchical'      => false,
                'rewrite'           => false,
            )
        );
    }
}
