<?php if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

//
// Metabox of the PAGE and POST both.
// Set a unique slug-like ID
//
$prefix_meta_opts = '_prefix_meta_options';

//
// Create a metabox
//
WPGPSC::createMetabox(
	$prefix_meta_opts,
	array(
		'title'     => 'Shortcode',
		'post_type' => 'gpsc_slider_carousel',
		'context'   => 'side',
	)
);

//
// Create a section
//
if ( isset( $_GET['post'] ) ) {

	WPGPSC::createSection(
		$prefix_meta_opts,
		array(
			'fields' => array(

				array(
					'type'  => 'shortcode',
					'class' => 'wpgpsc--shortcode-field',
				),
			),
		)
	);
} else {

	WPGPSC::createSection(
		$prefix_meta_opts,
		array(
			'fields' => array(

				array(
					'type'    => 'content',
					'content' => 'Shortcode will appear here after publish the slider.',
				),

			),
		)
	);
}
