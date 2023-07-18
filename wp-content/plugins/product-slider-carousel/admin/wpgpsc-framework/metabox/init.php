<?php if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

//
// Metabox of the PAGE.
// Set a unique slug-like ID.
//
$wpgpsc_page_opts = '_wpgpsc_page_options';

//
// Create a metabox.
//
WPGPSC::createMetabox(
	$wpgpsc_page_opts,
	array(
		'title'        => '<img src="' . GPSC_PRODUCT_SLIDER_CAROUSEL_DIR_URL_FILE . 'admin/img/product-slider-admin-title.jpg" alt="Product Slider For WooCommerce">',
		'post_type'    => 'gpsc_slider_carousel',
		'show_restore' => true,
		'theme'        => 'light',
		'class'        => 'wpgpsc--metabox-options',
	)
);

//
// Metabox of the Product.
// Set a unique slug-like ID.
//
$wpgpsc_product_opts = '_wpgpsc_product_options';

//
// Create a metabox.
//
WPGPSC::createMetabox(
	$wpgpsc_product_opts,
	array(
		'title'        => '<style>.wpgpsc--metabox-product-options h2 {background: #96588B;display: flex !important;justify-content: center !important;}</style><img src="' . GPSC_PRODUCT_SLIDER_CAROUSEL_DIR_URL_FILE . 'admin/img/product-slider-admin-title.jpg" alt="Product Slider For WooCommerce">',
		'post_type'    => 'product',
		'show_restore' => true,
		'class'        => 'wpgpsc--metabox-product-options',
	)
);
