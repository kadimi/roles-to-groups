<?php
/**
 * Plugin Name: WP Starter
 * Description: WP Starter Description
 * Text Domain: wp-starter-plugin
 * Version: 1.0.0
 * Plugin URI: https://www.github.com/kadimi/wp-starter-plugin
 * GitHub Plugin URI: https://github.com/kadimi/wp-starter-plugin
 * Author: Nabil Kadimi
 * Author URI: https://kadimi.com
 *
 * @package WPStarterPlugin
 */

/**
 * Composer autoload.
 */
require __DIR__ . 'vendor/autoload.php';

/**
 * The plugin class.
 */
require 'class-wpstarterplugin.php';

/**
 * Fire plugin.
 */
if ( defined( 'ABSPATH' ) ) {
	wpstarterplugin();
}

/**
 * Helper function.
 */
function wpstarterplugin() {
	return WPStarterPlugin::get_instance();
}
