<?php if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

//
// Set a unique slug-like ID.
//
$prefix = '_wpgpsc_option_settings';

//
// Create customize options.
//
WPGPSC::createOptions(
	$prefix,
	array(
		'framework_title'   => __( 'Product Slider Settings', 'gpsc-product-slider-carousel' ),
		'menu_title'        => __( 'Settings', 'gpsc-product-slider-carousel' ),
		'menu_parent'       => 'edit.php?post_type=gpsc_slider_carousel',
		'menu_slug'         => 'wpgpscsc_settings',
		'menu_type'         => 'submenu',
		'sticky_header'     => false,
		'show_bar_menu'     => false,
		'show_search'       => false,
		'show_network_menu' => false,
		'theme'             => 'light',
		'footer_credit'     => __( 'SHOW YOUR LOVE 💕 LEAVE A REVIEW HERE → <a href="https://wordpress.org/plugins/product-slider-carousel/reviews/?filter=5#new-post" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a>', 'gpsc-product-slider-carousel' ),
		'class'             => 'wpgpscsc--option-settings',
	)
);
