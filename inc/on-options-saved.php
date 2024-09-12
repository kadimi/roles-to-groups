<?php
/**
 * Resync all users.
 *
 * @package RolesToGroups
 */

use RolesToGroups\Functions;

add_action(
	'carbon_fields_theme_options_container_saved',
	function ( $_, $container ) {
		if (
			'carbon_fields_container_roles-to-groups-settings' === $container->id
		) {
			Functions\unsync_all_users();
			delete_transient(
				wpstarterplugin()->plugin_slug .
					'_' .
					'roles-to-groups_sync-unsynced-users'
			);
		}
	},
	10,
	2
);
