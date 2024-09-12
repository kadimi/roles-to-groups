<?php
/**
 * Sync user.
 *
 * @package RolesToGroups
 */

namespace RolesToGroups;

use RolesToGroups\Functions;

add_action(
	'profile_update',
	function ( $id ) {
		if ( Functions\bp_groups_is_active() ) {
			Functions\sync_user( $id );
		}
	}
);
