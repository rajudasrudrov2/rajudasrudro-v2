<?php
/**
 * Plugin Name: RajuDasRudro V2 — Headless CMS Core
 * Description: Structured content models and versioned REST DTOs for the Raju Das Rudro V2 headless WordPress CMS.
 * Version: 1.2.1
 * Requires at least: 6.2
 * Requires PHP: 7.4
 * Author: Raju Das Rudro
 * Text Domain: rdr-v2-headless-cms
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'RDR_V2_CMS_VERSION', '1.2.1' );
define( 'RDR_V2_CMS_FILE', __FILE__ );
define( 'RDR_V2_CMS_DIR', plugin_dir_path( __FILE__ ) );
define( 'RDR_V2_CMS_REST_NAMESPACE', 'rdr/v1' );

require_once RDR_V2_CMS_DIR . 'src/Plugin.php';

register_activation_hook( __FILE__, array( 'RDR\\V2\\HeadlessCMS\\Plugin', 'activate' ) );

RDR\V2\HeadlessCMS\Plugin::boot();
