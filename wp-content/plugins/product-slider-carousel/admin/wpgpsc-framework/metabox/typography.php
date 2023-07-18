<?php if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

//
// Create a section
//
WPGPSC::createSection(
	$wpgpsc_page_opts,
	array(
		'title'  => 'Typography',
		'icon'   => 'fa fa-font',
		'fields' => array(

			array(
				'type'    => 'content',
				'content' => '<div class="wpgpsc--menu-detail">
									<strong>Typography</strong>
									<a href="https://pluginic.com/support/" target="_blank" class="">Need Help?</a>
									<br>
									<p>Arranging the content of the gallery to make it more legible, readable, and appealing when displayed. You can prevent to load google font individually. If you leave any style field empty, the particular style will be inherited to its parent element of your theme. To know more you can connect us with help button beside.</p>
									<p style="background: antiquewhite;padding: 10px;text-align: center;color: chocolate;">Typography options are available for only <b>Grid</b> display mode.</p>
								</div>',
			),

			array(
				'id'         => 'wpgpscyg_section_title_font_load',
				'type'       => 'switcher',
				'title'      => __( 'Load Section Title Font', 'gpsc-product-slider-carousel' ),
				'subtitle'   => __( 'On/Off google font for the section title.', 'gpsc-product-slider-carousel' ),
				'text_on'    => __( 'On', 'gpsc-product-slider-carousel' ),
				'text_off'   => __( 'Off', 'gpsc-product-slider-carousel' ),
				'text_width' => 70,
				'default'    => true,
			),
			array(
				'id'           => 'wpgpscyg_section_title_typo',
				'type'         => 'typography',
				'title'        => __( 'Section Title Font', 'gpsc-product-slider-carousel' ),
				'subtitle'     => __( 'Set section title font properties.', 'gpsc-product-slider-carousel' ),
				'preview'      => 'always',
				'preview_text' => __( 'Grand Slider Section Title', 'gpsc-product-slider-carousel' ),
			),
			array(
				'id'         => 'wpgpscyg_video_title_font_load',
				'type'       => 'switcher',
				'title'      => __( 'Load video Title Font', 'gpsc-product-slider-carousel' ),
				'subtitle'   => __( 'On/Off google font for the video title.', 'gpsc-product-slider-carousel' ),
				'text_on'    => __( 'On', 'gpsc-product-slider-carousel' ),
				'text_off'   => __( 'Off', 'gpsc-product-slider-carousel' ),
				'text_width' => 70,
				'default'    => true,
			),
			array(
				'id'           => 'wpgpscyg_video_title_typo',
				'type'         => 'typography',
				'title'        => __( 'video Title Font', 'gpsc-product-slider-carousel' ),
				'subtitle'     => __( 'Set video title font properties.', 'gpsc-product-slider-carousel' ),
				'preview'      => 'always',
				'preview_text' => __( 'Grand Slider video Title', 'gpsc-product-slider-carousel' ),
			),
			array(
				'id'         => 'wpgpscyg_desc_font_load',
				'type'       => 'switcher',
				'title'      => __( 'Load Description Font', 'gpsc-product-slider-carousel' ),
				'subtitle'   => __( 'On/Off google font for the video description.', 'gpsc-product-slider-carousel' ),
				'text_on'    => __( 'On', 'gpsc-product-slider-carousel' ),
				'text_off'   => __( 'Off', 'gpsc-product-slider-carousel' ),
				'text_width' => 70,
				'default'    => true,
			),
			array(
				'id'           => 'wpgpscyg_desc_typo',
				'type'         => 'typography',
				'title'        => __( 'Description Font', 'gpsc-product-slider-carousel' ),
				'subtitle'     => __( 'Set video description font properties.', 'gpsc-product-slider-carousel' ),
				'preview'      => 'always',
				'preview_text' => __( 'Grand Slider video Description', 'gpsc-product-slider-carousel' ),
			),
			array(
				'id'         => 'wpgpscyg_meta_font_load',
				'type'       => 'switcher',
				'title'      => __( 'Load Post Meta Font', 'gpsc-product-slider-carousel' ),
				'subtitle'   => __( 'On/Off google font for the post meta.', 'gpsc-product-slider-carousel' ),
				'text_on'    => __( 'On', 'gpsc-product-slider-carousel' ),
				'text_off'   => __( 'Off', 'gpsc-product-slider-carousel' ),
				'text_width' => 70,
				'default'    => true,
			),
			array(
				'id'           => 'wpgpscyg_meta_typo',
				'type'         => 'typography',
				'title'        => __( 'Post Meta Font', 'gpsc-product-slider-carousel' ),
				'subtitle'     => __( 'Set post meta font properties.', 'gpsc-product-slider-carousel' ),
				'preview'      => 'always',
				'preview_text' => __( 'Grand Slider Post Meta', 'gpsc-product-slider-carousel' ),
			),

		),
	)
);
