<?php
/**
 * Plugin Name: Content Blocks
 * Plugin URI:  http://rwsite.ru
 * Description: Repeatable Content Blocks plugin
 * Version:     1.0.0
 * Text Domain: block
 * Domain Path: /languages/
 * Author:      Aleksey Tikhomirov
 * Author URI:  https://rwsite.ru
 * License:     GPLv3 or later
*/

namespace content_block;

defined( 'ABSPATH' ) || die();

require_once 'includes/ContentBlock.php';
require_once 'includes/ContentBlockWidget.php';

$plugin = ContentBlock::get_Instance(__FILE__);
$plugin->add_actions();

register_activation_hook( __FILE__, [$plugin, 'plugin_activate'] );
register_deactivation_hook( __FILE__, [$plugin, 'plugin_deactivate'] );

