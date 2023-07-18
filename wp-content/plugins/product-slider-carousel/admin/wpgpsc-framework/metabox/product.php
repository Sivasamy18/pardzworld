<?php if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

$gpsc_custom_fields_activate = isset( get_option( '_wpgpsc_option_settings' )['gpsc_custom_fields_activate'] ) ? get_option( '_wpgpsc_option_settings' )['gpsc_custom_fields_activate'] : '';

if ( $gpsc_custom_fields_activate ) {

	WPGPSC::createSection(
		$wpgpsc_product_opts,
		array(
			'fields' => array(
				array(
					'id'           => 'wpgpscsc_custom_field_repeater',
					'type'         => 'repeater',
					'button_title' => '+ Add New Field',
					'fields'       => array(
	
						array(
							'id'    => 'wpgpscsc_custom_field_name',
							'type'  => 'text',
							'title' => 'Custom Field Name',
						),
	
						array(
							'id'    => 'wpgpscsc_custom_field_value',
							'type'  => 'text',
							'title' => 'Custom Field Value',
						),
	
					),
					'default'      => array(
						array(
							'wpgpscsc_custom_field_name'  => '',
							'wpgpscsc_custom_field_value' => '',
						),
					),
				),
			),
		)
	);
}
