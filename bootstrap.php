<?php
/**
 * Plugin Name: Roles to Groups
 * Description: Automatically add users to BuddyPress/BuddyBoss groups based on their roles.
 * Text Domain: roles-to-groups
 * Version: 1.0.0
 * Plugin URI: https://wordpress.org/plugins/roles-to-groups
 * Author: Nabil Kadimi
 * Author URI: https://kadimi.com
 * License: GNU General Public License v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
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
