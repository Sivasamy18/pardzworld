<?php if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

//
// Create a section.
WPGPSC::createSection(
	$prefix,
	array(
		'title'  => __( 'Custom Fields', 'gpsc-product-slider-carousel' ),
		'icon'   => 'fa fa-plus-square-o',
		'fields' => array(

			array(
				'id'       => 'gpsc_custom_fields_activate',
				'type'     => 'switcher',
				'title'    => 'Custom Fields',
				'label'    => 'That shows at the bottom of product edit page?',
				'text_on'  => 'YES',
				'text_off' => 'NO',
				'default'  => false,
			),

		),
	)
);
