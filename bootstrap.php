<?php
/**
 * Plugin Name: Roles to Groups
 * Description: Automatically add users to BuddyPress/BuddyBoss groups based on their roles.
 * Text Domain: wp-starter-plugin
 * Version: 1.0.0
 * Plugin URI: https://kadimi.com
 * Author: Nabil Kadimi
 * Author URI: https://kadimi.com
 *
 * @package WPStarterPlugin
 */

/**
 * Composer autoload.
 */
require __DIR__ . '/vendor/autoload.php';

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
