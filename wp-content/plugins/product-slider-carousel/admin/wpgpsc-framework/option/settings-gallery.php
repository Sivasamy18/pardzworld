<?php if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

//
// Create a section.
WPGPSC::createSection(
	$prefix,
	array(
		'title'  => __( 'Gallery', 'gpsc-product-slider-carousel' ),
		'icon'   => 'fa fa-th-large',
		'fields' => array(

			array(
				'id'       => 'wpgpscsc_gallery_slider_show',
				'type'     => 'switcher',
				'title'    => __( 'Product Gallery Slider', 'gpsc-product-slider-carousel' ),
				'subtitle' => __( 'Turn off if you want to keep the default view of product gallery.', 'gpsc-product-slider-carousel' ),
				'default'  => true,
			),
			array(
				'type'       => 'submessage',
				'style'      => 'warning',
				'content'    => 'Product gallery default view is showing now. All of the settings below are not working.',
				'dependency' => array( 'wpgpscsc_gallery_slider_show', '==', 'false' ),
			),
			array(
				'id'         => 'wpgpscsc_gallery_section_title_show',
				'type'       => 'switcher',
				'title'      => __( 'Show the section title', 'gpsc-product-slider-carousel' ),
				'subtitle'   => __( 'Show/Hide the section title.', 'gpsc-product-slider-carousel' ),
				'text_on'    => __( 'Show', 'gpsc-product-slider-carousel' ),
				'text_off'   => __( 'Hide', 'gpsc-product-slider-carousel' ),
				'text_width' => 75,
				'default'    => false,
			),
			array(
				'id'         => 'wpgpscsc_gallery_section_title_text',
				'type'       => 'text',
				'title'      => __( 'Section Title Text', 'gpsc-product-slider-carousel' ),
				'subtitle'   => __( 'Set your section title text.', 'gpsc-product-slider-carousel' ),
				'default'    => __( 'Related Products', 'gpsc-product-slider-carousel' ),
				'dependency' => array( 'wpgpscsc_gallery_section_title_show', '==', 'true' ),
			),

			// Slider Settings.
			array(
				'id'       => 'wpgpscsc_gallery_slider_speed',
				'type'     => 'spinner',
				'title'    => __( 'Slider Speed', 'gpsc-product-slider-carousel' ),
				'subtitle' => __( 'Duration of transition between slides (in ms). Default 300ms.', 'gpsc-product-slider-carousel' ),
				'unit'     => 'ms',
				'default'  => 300,
			),
			array(
				'id'       => 'wpgpscsc_gallery_slider_autoplay',
				'type'     => 'switcher',
				'title'    => __( 'Slider Autoplay', 'gpsc-product-slider-carousel' ),
				'subtitle' => __( 'On/Off slider autoplay.', 'gpsc-product-slider-carousel' ),
				'default'  => false,
			),
			array(
				'id'         => 'wpgpscsc_gallery_slider_autoplay_delay',
				'type'       => 'spinner',
				'title'      => __( 'Slider Autoplay Delay', 'gpsc-product-slider-carousel' ),
				'subtitle'   => __( 'Delay between transitions (in ms). Default 500ms.', 'gpsc-product-slider-carousel' ),
				'unit'       => 'ms',
				'default'    => 5000,
				'dependency' => array( 'wpgpscsc_gallery_slider_autoplay', '==', 'true' ),
			),
			array(
				'id'       => 'wpgpscsc_gallery_slider_loop',
				'type'     => 'switcher',
				'title'    => __( 'Slider Loop', 'gpsc-product-slider-carousel' ),
				'subtitle' => __( 'Set to true to enable continuous loop mode.', 'gpsc-product-slider-carousel' ),
				'default'  => false,
			),
			array(
				'id'       => 'wpgpscsc_gallery_slides_per_view',
				'type'     => 'spinner',
				'title'    => __( 'Slide Per View', 'gpsc-product-slider-carousel' ),
				'subtitle' => __( 'Number of slides per view (slides visible at the same time on slider\'s container).', 'gpsc-product-slider-carousel' ),
				'default'  => 4,
			),
			array(
				'id'       => 'wpgpscsc_gallery_slides_space_between',
				'type'     => 'spinner',
				'title'    => __( 'Slider Space Between', 'gpsc-product-slider-carousel' ),
				'subtitle' => __( 'Distance between slides in px.', 'gpsc-product-slider-carousel' ),
				'default'  => 15,
			),
			array(
				'id'         => 'wpgpscsc_gallery_slider_navigation',
				'type'       => 'switcher',
				'title'      => __( 'Show Slider Navigation', 'gpsc-product-slider-carousel' ),
				'subtitle'   => __( 'Show/Hide slider navigation.', 'gpsc-product-slider-carousel' ),
				'text_on'    => __( 'Show', 'gpsc-product-slider-carousel' ),
				'text_off'   => __( 'Hide', 'gpsc-product-slider-carousel' ),
				'text_width' => 75,
				'default'    => false,
			),
			array(
				'id'         => 'wpgpscsc_gallery_slider_nav_icon',
				'type'       => 'button_set',
				'title'      => __( 'Navigation Icon', 'gpsc-product-slider-carousel' ),
				'subtitle'   => __( 'Set a icon for slider navigation.', 'gpsc-product-slider-carousel' ),
				'options'    => array(
					'f105' => '<i class="fa fa-angle-right"></i>',
					'f101' => '<i class="fa fa-angle-double-right"></i>',
					'f18e' => '<i class="fa fa-arrow-circle-o-right"></i>',
					'f0a9' => '<i class="fa fa-arrow-circle-right"></i>',
					'f061' => '<i class="fa fa-arrow-right"></i>',
					'f0da' => '<i class="fa fa-caret-right"></i>',
					'f152' => '<i class="fa fa-caret-square-o-right"></i>',
					'f138' => '<i class="fa fa-chevron-circle-right"></i>',
					'f0a4' => '<i class="fa fa-hand-o-right"></i>',
					'f178' => '<i class="fa fa-long-arrow-right"></i>',
					'f152' => '<i class="fa fa-caret-square-o-right"></i>',
				),
				'default'    => 'f105',
				'dependency' => array( 'wpgpscsc_gallery_slider_navigation', '==', 'true' ),
			),
			array(
				'id'         => 'wpgpscsc_gallery_slider_nav_colors',
				'type'       => 'color_group',
				'title'      => __( 'Navigation Colors', 'gpsc-product-slider-carousel' ),
				'subtitle'   => __( 'Set colors for slider navigation.', 'gpsc-product-slider-carousel' ),
				'options'    => array(
					'icon'             => __( 'Icon', 'gpsc-product-slider-carousel' ),
					'icon-hover'       => __( 'Icon Hover', 'gpsc-product-slider-carousel' ),
					'background'       => __( 'Background', 'gpsc-product-slider-carousel' ),
					'background-hover' => __( 'Background Hover', 'gpsc-product-slider-carousel' ),
				),
				'default'    => array(
					'icon'             => '#333333',
					'icon-hover'       => '#ffffff',
					'background'       => '#d5d5d5',
					'background-hover' => '#333333',
				),
				'dependency' => array( 'wpgpscsc_gallery_slider_navigation', '==', 'true' ),
			),
			array(
				'id'         => 'wpgpscsc_gallery_slider_pagination',
				'type'       => 'switcher',
				'title'      => __( 'Show Slider Pagination', 'gpsc-product-slider-carousel' ),
				'subtitle'   => __( 'Show/Hide slider pagination.', 'gpsc-product-slider-carousel' ),
				'text_on'    => __( 'Show', 'gpsc-product-slider-carousel' ),
				'text_off'   => __( 'Hide', 'gpsc-product-slider-carousel' ),
				'text_width' => 75,
				'default'    => true,
			),
			array(
				'id'         => 'wpgpscsc_gallery_slider_pagination_type',
				'type'       => 'button_set',
				'title'      => __( 'Pagination Type', 'gpsc-product-slider-carousel' ),
				'subtitle'   => __( 'Set a pagination type.', 'gpsc-product-slider-carousel' ),
				'options'    => array(
					'bullets'     => __( 'Bullets', 'gpsc-product-slider-carousel' ),
					'fraction'    => __( 'Fraction', 'gpsc-product-slider-carousel' ),
					'progressbar' => __( 'Progressbar', 'gpsc-product-slider-carousel' ),
				),
				'default'    => 'bullets',
				'dependency' => array( 'wpgpscsc_gallery_slider_pagination', '==', 'true' ),
			),
			array(
				'id'         => 'wpgpscsc_gallery_slider_pagination_colors',
				'type'       => 'color_group',
				'title'      => __( 'Pagination Colors', 'gpsc-product-slider-carousel' ),
				'subtitle'   => __( 'Set colors for slider pagination.', 'gpsc-product-slider-carousel' ),
				'options'    => array(
					'active'     => __( 'Active', 'gpsc-product-slider-carousel' ),
					'background' => __( 'Background', 'gpsc-product-slider-carousel' ),
				),
				'default'    => array(
					'active'     => '#007aff',
					'background' => '#000000',
				),
				'dependency' => array( 'wpgpscsc_gallery_slider_pagination', '==', 'true' ),
			),

		),
	)
);
