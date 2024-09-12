<?php
/**
 * Tasks to run with cron.
 *
 * @package RolesToGroups
 */

/**
 * Schedule task to sync unsynced users.
 */
WPStarterPlugin::get_instance()->schedule_task(
	'roles-to-groups_sync-unsynced-users',
	'RolesToGroups\Functions\sync_unsynced_users',
	array( 'count' => 100 ),
	5 * MINUTE_IN_SECONDS
);
