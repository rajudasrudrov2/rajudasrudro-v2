<?php
namespace RDR\V2\HeadlessCMS\Portal;

use RDR\V2\HeadlessCMS\Settings;

if ( ! defined( 'ABSPATH' ) ) { exit; }

final class Portal {
    private $router;
    public function __construct( Settings $settings ) { $this->router = new Router( $settings ); }
    public function hooks() { $this->router->hooks(); }
    public static function register_rewrites() { Router::register_rewrites(); }
}
