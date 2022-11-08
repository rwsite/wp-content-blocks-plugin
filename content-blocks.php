<?php
/**
 * Plugin Name: Content Blocks
 * Plugin URI:  http://rwsite.ru/
 * Description: Content Blocks plugin
 * Version:     1.0.0
 * Text Domain: rw-block
 * Domain Path: /languages/
 * Author:      Aleksey Tikhomirov
 * Author URI:  https://rwsite.ru/
 * License:     GPLv3 or later
*/


defined( 'ABSPATH' ) or die();

require_once 'includes/cb_main.php';
require_once 'includes/cb_widget.php';

$plugin = new cb_main(['file' => __FILE__]);
$plugin->add_actions();

register_activation_hook( __FILE__, cb_main::class . '::plugin_activate' );
register_deactivation_hook( __FILE__, cb_main::class . '::plugin_deactivate' );

