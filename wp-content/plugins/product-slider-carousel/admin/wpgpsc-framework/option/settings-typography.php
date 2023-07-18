<?php if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

//
// Create a section.
WPGPSC::createSection(
	$prefix,
	array(
		'title'  => __( 'Typography', 'gpsc-product-slider-carousel' ),
		'icon'   => 'fa fa-font',
		'fields' => array(

			array(
				'id'         => 'gpsc_section_title_font_load',
				'type'       => 'switcher',
				'title'      => __( 'Load Section Title Font', 'gpsc-product-slider-carousel' ),
				'subtitle'   => __( 'On/Off google font for the section title.', 'gpsc-product-slider-carousel' ),
				'text_on'    => __( 'On', 'gpsc-product-slider-carousel' ),
				'text_off'   => __( 'Off', 'gpsc-product-slider-carousel' ),
				'text_width' => 70,
				'default'    => true,
			),
			array(
				'id'           => 'gpsc_section_title_typo',
				'type'         => 'typography',
				'title'        => __( 'Section Title Font', 'gpsc-product-slider-carousel' ),
				'subtitle'     => __( 'Set section title font properties.', 'gpsc-product-slider-carousel' ),
				'preview'      => 'always',
				'preview_text' => __( 'Grand Slider Section Title', 'gpsc-product-slider-carousel' ),
			),
			array(
				'id'         => 'gpsc_product_title_font_load',
				'type'       => 'switcher',
				'title'      => __( 'Load Product Title Font', 'gpsc-product-slider-carousel' ),
				'subtitle'   => __( 'On/Off google font for the product title.', 'gpsc-product-slider-carousel' ),
				'text_on'    => __( 'On', 'gpsc-product-slider-carousel' ),
				'text_off'   => __( 'Off', 'gpsc-product-slider-carousel' ),
				'text_width' => 70,
				'default'    => true,
			),
			array(
				'id'           => 'gpsc_product_title_typo',
				'type'         => 'typography',
				'title'        => __( 'Product Title Font', 'gpsc-product-slider-carousel' ),
				'subtitle'     => __( 'Set product title font properties.', 'gpsc-product-slider-carousel' ),
				'preview'      => 'always',
				'preview_text' => __( 'Grand Slider Product Title', 'gpsc-product-slider-carousel' ),
			),
			array(
				'id'         => 'gpsc_button_title_font_load',
				'type'       => 'switcher',
				'title'      => __( 'Load Button Title Font', 'gpsc-product-slider-carousel' ),
				'subtitle'   => __( 'On/Off google font for the button title.', 'gpsc-product-slider-carousel' ),
				'text_on'    => __( 'On', 'gpsc-product-slider-carousel' ),
				'text_off'   => __( 'Off', 'gpsc-product-slider-carousel' ),
				'text_width' => 70,
				'default'    => true,
			),
			array(
				'id'           => 'gpsc_button_title_typo',
				'type'         => 'typography',
				'title'        => __( 'Button Title Font', 'gpsc-product-slider-carousel' ),
				'subtitle'     => __( 'Set button title font properties.', 'gpsc-product-slider-carousel' ),
				'preview'      => 'always',
				'preview_text' => __( 'Grand Slider Button Title', 'gpsc-product-slider-carousel' ),
			),

		),
	)
);
