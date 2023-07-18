<?php if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

//
// Create a section
//
WPGPSC::createSection(
	$wpgpsc_page_opts,
	array(
		'title'  => __( 'General', 'gpsc-product-slider-carousel' ),
		'icon'   => 'fa fa-puzzle-piece',
		'fields' => array(

			array(
				'type'    => 'content',
				'content' => '<div class="wpgpsc--menu-detail">
									<strong>General</strong>
									<a href="https://pluginic.com/support/" target="_blank" class="">Need Help?</a>
									<br>
									<p>General settings connecting the most important part of this plugin. You can show products from your specific selected. Most powerful methods applied to retrieving the products. All of the settings based on the latest WordPress standard. To know more click the help button beside.</p>
								</div>',
			),

			array(
				'id'       => 'wpgpscsc_module',
				'type'     => 'image_select',
				'title'    => 'Select a Module',
				'subtitle' => __( 'Select a layout that view of shortcode.', 'gpsc-product-slider-carousel' ),
				'options'  => array(
					'carousel' => GPSC_PRODUCT_SLIDER_CAROUSEL_DIR_URL_FILE . 'admin/img/carousel-icon.png',
					'grid'     => GPSC_PRODUCT_SLIDER_CAROUSEL_DIR_URL_FILE . 'admin/img/grid-icon.png',
				),
				'default'  => 'carousel',
			),
			array(
				'id'         => 'wpgpscsc_section_title_show',
				'type'       => 'switcher',
				'title'      => __( 'Show the section title', 'gpsc-product-slider-carousel' ),
				'subtitle'   => __( 'Show/Hide the section title.', 'gpsc-product-slider-carousel' ),
				'text_on'    => __( 'Show', 'gpsc-product-slider-carousel' ),
				'text_off'   => __( 'Hide', 'gpsc-product-slider-carousel' ),
				'text_width' => 80,
				'default'    => true,
			),
			array(
				'id'         => 'wpgpscsc_section_title_margin_bottom',
				'type'       => 'slider',
				'title'      => __( 'Section Title Margin Bottom', 'gpsc-product-slider-carousel' ),
				'subtitle'   => __( 'Set margin bottom form section title.', 'gpsc-product-slider-carousel' ),
				'unit'       => 'px',
				'default'    => 10,
				'dependency' => array( 'wpgpscsc_section_title_show', '==', 'true' ),
				'class'      => 'wpgpsc--width-50',
			),
			array(
				'id'          => 'wpgpscsc_product_by',
				'type'        => 'select',
				'title'       => 'Product By',
				'placeholder' => 'Select a option',
				'options'     => array(
					'default'   => 'Default',
					'category'  => 'Category',
					'hand-pick' => 'Hand Pick',
				),
				'default'     => 'default',
			),
			array(
				'id'          => 'wpgpscsc_product_cat_selection',
				'type'        => 'select',
				'title'       => 'Select a category',
				'placeholder' => 'Select a category',
				'chosen'      => true,
				'multiple'    => true,
				'options'     => 'wpgpsc_get_product_cat',
				'dependency'  => array( 'wpgpscsc_product_by', '==', 'category' ),
			),
			array(
				'id'          => 'wpgpscsc_specific_product_selected',
				'type'        => 'select',
				'title'       => __( 'Select Specific Products', 'gpsc-product-slider-carousel' ),
				'subtitle'    => __( 'Select some specific products.', 'gpsc-product-slider-carousel' ),
				'placeholder' => 'Select a product',
				'chosen'      => true,
				'ajax'        => true,
				'multiple'    => true,
				'options'     => 'posts',
				'query_args'  => array(
					'post_type' => 'product',
				),
				'dependency'  => array( 'wpgpscsc_product_by', '==', 'hand-pick' ),
			),
			array(
				'id'       => 'wpgpscsc_product_limit',
				'type'     => 'spinner',
				'title'    => __( 'Product Limit', 'gpsc-product-slider-carousel' ),
				'subtitle' => __( 'Maximum number of results to show products or -1 for unlimited.', 'gpsc-product-slider-carousel' ),
				'default'  => -1,
			),
			array(
				'id'      => 'wpgpscsc_product_orderby',
				'type'    => 'select',
				'title'   => 'Orderby',
				'options' => array(
					'none'     => 'None',
					'ID'       => 'ID',
					'name'     => 'Name',
					'type'     => 'Type',
					'rand'     => 'Rand',
					'date'     => 'Date',
					'modified' => 'Modified',
				),
				'default' => 'date',
			),
			array(
				'id'      => 'wpgpscsc_product_order',
				'type'    => 'button_set',
				'title'   => 'Order',
				'options' => array(
					'ASC'  => 'Ascending',
					'DESC' => 'Descending',
				),
				'default' => 'DESC',
			),

			// Slider Settings.
			array(
				'id'         => 'wpgpscsc_shortcode_slider_speed',
				'type'       => 'spinner',
				'title'      => __( 'Slider Speed', 'gpsc-product-slider-carousel' ),
				'subtitle'   => __( 'Duration of transition between slides (in ms). Default 300ms.', 'gpsc-product-slider-carousel' ),
				'unit'       => 'ms',
				'default'    => 300,
				'dependency' => array( 'wpgpscsc_module', '==', 'carousel' ),
			),
			array(
				'id'         => 'wpgpscsc_shortcode_slider_autoplay',
				'type'       => 'switcher',
				'title'      => __( 'Slider Autoplay', 'gpsc-product-slider-carousel' ),
				'subtitle'   => __( 'On/Off slider autoplay.', 'gpsc-product-slider-carousel' ),
				'default'    => false,
				'dependency' => array( 'wpgpscsc_module', '==', 'carousel' ),
			),
			array(
				'id'         => 'wpgpscsc_shortcode_slider_autoplay_delay',
				'type'       => 'spinner',
				'title'      => __( 'Slider Autoplay Delay', 'gpsc-product-slider-carousel' ),
				'subtitle'   => __( 'Delay between transitions (in ms). Default 500ms.', 'gpsc-product-slider-carousel' ),
				'unit'       => 'ms',
				'default'    => 5000,
				'dependency' => array( 'wpgpscsc_module|wpgpscsc_shortcode_slider_autoplay', '==|==', 'carousel|true' ),
			),
			array(
				'id'         => 'wpgpscsc_shortcode_slider_loop',
				'type'       => 'switcher',
				'title'      => __( 'Slider Loop', 'gpsc-product-slider-carousel' ),
				'subtitle'   => __( 'Set to true to enable continuous loop mode.', 'gpsc-product-slider-carousel' ),
				'default'    => false,
				'dependency' => array( 'wpgpscsc_module', '==', 'carousel' ),
			),
			array(
				'id'       => 'wpgpscsc_product_columns',
				'type'     => 'spinner',
				'title'    => __( 'Product Columns', 'gpsc-product-slider-carousel' ),
				'subtitle' => __( 'Number of column\'s of products.', 'gpsc-product-slider-carousel' ),
				'default'  => 3,
			),
			array(
				'id'       => 'wpgpscsc_shortcode_add_to_cart_button_colors',
				'type'     => 'color_group',
				'title'    => __( 'Add to Cart Button Colors', 'gpsc-product-slider-carousel' ),
				'subtitle' => __( 'Set add to cart button colors.', 'gpsc-product-slider-carousel' ),
				'options'  => array(
					'background'       => __( 'Background', 'gpsc-product-slider-carousel' ),
					'background-hover' => __( 'Background Hover', 'gpsc-product-slider-carousel' ),
				),
				'default'  => array(
					'background'       => '#eeeeee',
					'background-hover' => '#d5d5d5',
				),
			),
			array(
				'id'       => 'wpgpscsc_product_space_between',
				'type'     => 'spinner',
				'title'    => __( 'Product Space Between', 'gpsc-product-slider-carousel' ),
				'subtitle' => __( 'Distance between products in px.', 'gpsc-product-slider-carousel' ),
				'default'  => 30,
			),
			array(
				'id'         => 'wpgpscsc_shortcode_slider_navigation',
				'type'       => 'switcher',
				'title'      => __( 'Show Slider Navigation', 'gpsc-product-slider-carousel' ),
				'subtitle'   => __( 'Show/Hide slider navigation.', 'gpsc-product-slider-carousel' ),
				'text_on'    => __( 'Show', 'gpsc-product-slider-carousel' ),
				'text_off'   => __( 'Hide', 'gpsc-product-slider-carousel' ),
				'text_width' => 75,
				'default'    => true,
				'dependency' => array( 'wpgpscsc_module', '==', 'carousel' ),
			),
			array(
				'id'         => 'wpgpscsc_shortcode_slider_nav_icon',
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
				'dependency' => array( 'wpgpscsc_module|wpgpscsc_shortcode_slider_navigation', '==|==', 'carousel|true' ),
			),
			array(
				'id'         => 'wpgpscsc_shortcode_slider_nav_colors',
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
				'dependency' => array( 'wpgpscsc_module|wpgpscsc_shortcode_slider_navigation', '==|==', 'carousel|true' ),
			),
			array(
				'id'         => 'wpgpscsc_shortcode_slider_pagination',
				'type'       => 'switcher',
				'title'      => __( 'Show Slider Pagination', 'gpsc-product-slider-carousel' ),
				'subtitle'   => __( 'Show/Hide slider pagination.', 'gpsc-product-slider-carousel' ),
				'text_on'    => __( 'Show', 'gpsc-product-slider-carousel' ),
				'text_off'   => __( 'Hide', 'gpsc-product-slider-carousel' ),
				'text_width' => 75,
				'default'    => false,
				'dependency' => array( 'wpgpscsc_module', '==', 'carousel' ),
			),
			array(
				'id'         => 'wpgpscsc_shortcode_slider_pagination_type',
				'type'       => 'button_set',
				'title'      => __( 'Pagination Type', 'gpsc-product-slider-carousel' ),
				'subtitle'   => __( 'Set a pagination type.', 'gpsc-product-slider-carousel' ),
				'options'    => array(
					'bullets'     => __( 'Bullets', 'gpsc-product-slider-carousel' ),
					'fraction'    => __( 'Fraction', 'gpsc-product-slider-carousel' ),
					'progressbar' => __( 'Progressbar', 'gpsc-product-slider-carousel' ),
				),
				'default'    => 'bullets',
				'dependency' => array( 'wpgpscsc_module|wpgpscsc_shortcode_slider_pagination', '==|==', 'carousel|true' ),
			),
			array(
				'id'         => 'wpgpscsc_shortcode_slider_pagination_colors',
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
				'dependency' => array( 'wpgpscsc_module|wpgpscsc_shortcode_slider_pagination', '==|==', 'carousel|true' ),
			),

		),
	)
);

/**
 * Get Product Categories.
 *
 * @return Array Categories.
 */
function wpgpsc_get_product_cat() {

	$cat_arr    = array();
	$orderby    = 'name';
	$order      = 'asc';
	$hide_empty = false;
	$cat_args   = array(
		'orderby'    => $orderby,
		'order'      => $order,
		'hide_empty' => $hide_empty,
	);

	$product_categories = get_terms( 'product_cat', $cat_args );

	if ( ! empty( $product_categories ) ) {

		foreach ( $product_categories as $key => $category ) {

			$cat_arr[ strtolower( $category->name ) ] = $category->name;
		}
	}

	return $cat_arr;
}
