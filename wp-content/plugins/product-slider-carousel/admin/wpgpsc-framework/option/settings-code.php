<?php if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

//
// Create a section.
WPGPSC::createSection(
	$prefix,
	array(
		'title'  => __( 'Custom Code', 'gpsc-product-slider-carousel' ),
		'icon'   => 'fa fa-code',
		'fields' => array(

			array(
				'id'       => 'gpsc_css_code_editor',
				'type'     => 'code_editor',
				'title'    => 'CSS Editor',
				'settings' => array(
					'theme' => 'mbo',
					'mode'  => 'css',
				),
				'default'  => '.gpsc--element{ color: #ffbc00; }',
			),
			array(
				'id'       => 'gpsc_js_code_editor',
				'type'     => 'code_editor',
				'title'    => 'Javascript Editor',
				'settings' => array(
					'theme' => 'monokai',
					'mode'  => 'javascript',
				),
				'default'  => 'console.log("Hello world");',
			),

		),
	)
);
