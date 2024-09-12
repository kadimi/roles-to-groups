<?php
/**
 * Helper functions.
 *
 * @package RolesToGroups
 */

namespace RolesToGroups\Functions;

use WP_User_Query;

/**
 * Get an associative array of all groups.
 *
 * @return array An associative array of all groups where the key is the
 *               group ID and the value is the group name.
 */
function get_groups() {
	global $wpdb;

	$groups = $wpdb->get_results( "SELECT id, name, status FROM {$wpdb->prefix}bp_groups", OBJECT_K );

	foreach ( $groups as $group_id => $group ) {
		$group_name = $group->name;
		if ( 'hidden' === $group->status ) {
			$group_name .= ' (hidden)';
		}
		$groups[ $group_id ] = $group_name;
	}

	return $groups;
}

/**
 * Get an associative array of all roles.
 *
 * @return array An associative array of all roles where the key is the
 *               role and the value is the role name.
 */
function get_roles() {
	global $wpdb;
	$roles = $wpdb->get_var( "SELECT option_value FROM {$wpdb->options} WHERE option_name = 'wp_user_roles'" );
	$roles = maybe_unserialize( $roles );

	$role_names = array();
	if ( is_array( $roles ) ) {
		foreach ( $roles as $role_key => $role_info ) {
			$role_names[ $role_key ] = $role_info['name'];
		}
	}
	return $role_names;
}

/**
 * Checks if the BuddyPress Groups component is active.
 *
 * @return bool True if BuddyPress Groups is active, false otherwise.
 */
function bp_groups_is_active() {
	return function_exists( 'bp_is_active' ) && bp_is_active( 'groups' );
}

/**
 * Get an array of roles for a given user ID.
 *
 * @param int $id The user ID.
 * @return array An array of roles.
 */
function get_user_roles( $id ) {
	return get_userdata( $id )->roles;
}
/**
 * Get an array of groups for a given user ID.
 *
 * @param int $id The user ID.
 * @return array An array of group IDs.
 */
function get_user_groups( $id ) {
	return groups_get_user_groups( $id )['groups'];
}

/**
 * Sync user to groups based on roles.
 *
 * @param int $id The user ID.
 */
function sync_user( $id ) {
	$user_roles = get_user_roles( $id );

	$good_groups = array();
	foreach ( $user_roles as $role ) {
		$good_groups = array_merge(
			$good_groups,
			carbon_get_theme_option( 'roles-to-groups_' . $role ) ?? array()
		);
	}
	$good_groups = array_unique( $good_groups );
	$bad_groups  = array_diff( array_keys( get_groups() ), $good_groups );

	/**
	 * Remove user from irrelevant groups.
	 */
	foreach ( $bad_groups as $group ) {
		groups_remove_member( $id, $group );
	}

	/**
	 * Add user to expected groups.
	 */
	foreach ( $good_groups as $group ) {
		groups_join_group( $group, $id );
	}

	/**
	 * Set user meta for syncing time.
	 */
	update_user_meta( $id, 'roles_to_groups_synced_at', time() );
}

/**
 * Sync users to groups based on roles.
 *
 * @param WP_User[] $users WP_User objects to sync.
 *
 * @return true
 */
function sync_users( $users ) {
	foreach ( $users as $user ) {
		sync_user( $user->ID );
	}
	return true;
}

/**
 * Unsync all users.
 *
 * Unsyncing is done by deleting the roles_to_groups_synced_at user meta for all users.
 *
 * @return void
 */
function unsync_all_users() {
	global $wpdb;
	$wpdb->query(
		$wpdb->prepare(
			"DELETE FROM {$wpdb->usermeta} WHERE meta_key = %s",
			'roles_to_groups_synced_at'
		)
	);
}

/**
 * Sync unsynced users to groups based on roles.
 *
 * @param array $args {
 *     An array of arguments.
 *
 *     @type int $count The number of unsynced users to sync. Default is 0 for all.
 * }
 *
 * @return true
 */
function sync_unsynced_users( $args = array( 'count' => 0 ) ) {
	return sync_users( get_unsynced_users( $args['count'] ) );
}

/**
 * Get an array of WP_User objects for unsynced users.
 *
 * @param  int $count The number of unsynced users to get. Default is 0 for all.
 * @return WP_User[]
 */
function get_unsynced_users( $count = 0 ) {
	return ( new WP_User_Query(
		array(
			'number'     => max( 0, intval( $count ) ),
			'meta_query' => array(
				array(
					'key'     => 'roles_to_groups_synced_at',
					'compare' => 'NOT EXISTS',
				),
			),
		)
	) )->get_results();
}
