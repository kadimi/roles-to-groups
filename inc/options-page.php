<?php
/**
 * Plugin options setup.
 *
 * @package RolesToGroups
 */

namespace RolesToGroups\Options;

use Carbon_Fields\Carbon_Fields;
use Carbon_Fields\Container;
use Carbon_Fields\Field;
use RolesToGroups\Functions;

add_action(
	'after_setup_theme',
	function () {
		Carbon_Fields::boot();
	}
);

add_action(
	'carbon_fields_register_fields',
	function () {
		/**
		 * Prepare settings container.
		 */
		$container = Container::make(
			'theme_options',
			'roles-to-groups-settings',
			__( 'Roles to Groups' )
		)
		->set_page_file( 'roles-to-groups' )
		->set_page_parent( 'options-general.php' );

		/**
		 *  Add settings.
		 */
		foreach ( Functions\get_roles() as $role => $role_name ) {
			$container->add_fields(
				array(
					Field::make(
						'multiselect',
						'roles-to-groups_' . $role,
						$role_name
					)->set_options( 'RolesToGroups\Functions\get_groups' ),
				)
			);
		}
	}
);
