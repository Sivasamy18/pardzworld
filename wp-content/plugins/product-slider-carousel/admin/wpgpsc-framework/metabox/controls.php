<?php if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

//
// Create a section
//
WPGPSC::createSection(
	$wpgpsc_page_opts,
	array(
		'title'  => __( 'Controls', 'gpsc-product-slider-carousel' ),
		'icon'   => 'fa fa-cog',
		'fields' => array(

			array(
				'type'    => 'content',
				'content' => '<div class="wpgpsc--menu-detail">
									<strong>Controls</strong>
									<a href="https://pluginic.com/support/" target="_blank" class="">Need Help?</a>
									<br>
									<p>Our best controlling system helps you to create elegant and professionally looking gallery. Some methods are help with the query to displaying. Like orders, published date and video duration. Specified a total video to show maximum number of videos. All options are specific and exact what you need in your website.</p>
								</div>',
			),

			array(
				'id'         => 'wpgpscsc_show_thumb',
				'type'       => 'switcher',
				'title'      => __( 'Show/Hide Thumbnails', 'gpsc-product-slider-carousel' ),
				'subtitle'   => __( 'Show/Hide thumbnails of products.', 'gpsc-product-slider-carousel' ),
				'text_on'    => __( 'Show', 'gpsc-product-slider-carousel' ),
				'text_off'   => __( 'Hide', 'gpsc-product-slider-carousel' ),
				'text_width' => 75,
				'default'    => true,
			),
			array(
				'id'          => 'wpgpscsc_thumb_size',
				'type'        => 'select',
				'title'       => 'Select',
				'placeholder' => 'Select an option',
				'options'     => array(
					'thumbnail'    => 'Thumbnail',
					'medium'       => 'Medium',
					'medium_large' => 'Medium_large',
					'large'        => 'Large',
					'full'         => 'Full',
				),
				'default'     => 'medium',
				'dependency'  => array( 'wpgpscsc_show_thumb', '==', 'true' ),
			),
			array(
				'id'         => 'wpgpscsc_show_name',
				'type'       => 'switcher',
				'title'      => __( 'Show/Hide Names', 'gpsc-product-slider-carousel' ),
				'subtitle'   => __( 'Show/Hide names of products.', 'gpsc-product-slider-carousel' ),
				'text_on'    => __( 'Show', 'gpsc-product-slider-carousel' ),
				'text_off'   => __( 'Hide', 'gpsc-product-slider-carousel' ),
				'text_width' => 75,
				'default'    => true,
			),
			array(
				'id'         => 'wpgpscsc_show_price',
				'type'       => 'switcher',
				'title'      => __( 'Show/Hide Price', 'gpsc-product-slider-carousel' ),
				'subtitle'   => __( 'Show/Hide price of products.', 'gpsc-product-slider-carousel' ),
				'text_on'    => __( 'Show', 'gpsc-product-slider-carousel' ),
				'text_off'   => __( 'Hide', 'gpsc-product-slider-carousel' ),
				'text_width' => 75,
				'default'    => true,
			),
			array(
				'id'         => 'wpgpscsc_show_short_desc',
				'type'       => 'switcher',
				'title'      => __( 'Show/Hide Short Description', 'gpsc-product-slider-carousel' ),
				'subtitle'   => __( 'Show/Hide short description of products.', 'gpsc-product-slider-carousel' ),
				'text_on'    => __( 'Show', 'gpsc-product-slider-carousel' ),
				'text_off'   => __( 'Hide', 'gpsc-product-slider-carousel' ),
				'text_width' => 75,
				'default'    => true,
			),
			array(
				'id'         => 'wpgpscsc_show_button',
				'type'       => 'switcher',
				'title'      => __( 'Show/Hide Add to Cart', 'gpsc-product-slider-carousel' ),
				'subtitle'   => __( 'Show/Hide add to cart button of products.<br><a href="' . get_admin_url() . 'edit.php?post_type=gpsc_slider_carousel&page=wpgpscsc_settings#tab=add-to-cart" target="_blank">Change Add to Cart button text and more..</a>', 'gpsc-product-slider-carousel' ),
				'text_on'    => __( 'Show', 'gpsc-product-slider-carousel' ),
				'text_off'   => __( 'Hide', 'gpsc-product-slider-carousel' ),
				'text_width' => 75,
				'default'    => true,
			),
			array(
				'id'         => 'wpgpscsc_show_detail_btn',
				'type'       => 'switcher',
				'title'      => __( 'Show/Hide Details Button', 'gpsc-product-slider-carousel' ),
				'subtitle'   => __( 'Show/Hide details button of products.', 'gpsc-product-slider-carousel' ),
				'text_on'    => __( 'Show', 'gpsc-product-slider-carousel' ),
				'text_off'   => __( 'Hide', 'gpsc-product-slider-carousel' ),
				'text_width' => 75,
				'default'    => false,
			),
			array(
				'id'         => 'wpgpscsc_show_detail_btn_txt',
				'type'       => 'text',
				'title'      => 'Detail Button Text',
				'default'    => 'See Details',
				'dependency' => array( 'wpgpscsc_show_detail_btn', '==', 'true' ),
			),

		),
	)
);
