<?php
/**
 * The file that defines the dynamic styles of the plugin.
 *
 * @link       https://www.pluginic.com
 * @since      1.0.0
 *
 * @package    GPSC_Product_Slider_Carousel
 * @subpackage GPSC_Product_Slider_Carousel/public
 */

/**
 * Typography.
 *
 * @param array $gpsc_typos Typography elements.
 * @return string
 */
function get_typos( $gpsc_typos ) {

	$items = array();

	if ( ! empty( $gpsc_typos ) ) {

		foreach ( $gpsc_typos as $key => $value ) {

			if ( ! empty( $value ) && 'inherit' !== $value && 'unit' !== $key ) {

				$items[] = $key . ': ' . $value;
			};
		};
	}
	return implode( '; ', $items );
}

$gpsc_section_title_typo = get_typos( wpgpsc_get_options( 'gpsc_section_title_typo' ) );
$gpsc_product_title_typo = get_typos( wpgpsc_get_options( 'gpsc_product_title_typo' ) );
$gpsc_button_title_typo  = get_typos( wpgpsc_get_options( 'gpsc_button_title_typo' ) );

// Related Product Details.
$wpgpscsc_related_add_to_cart_btn_bg_color       = wpgpsc_get_options( 'wpgpscsc_related_add_to_cart_button_colors', null, 'background' );
$wpgpscsc_related_add_to_cart_btn_bg_color_hover = wpgpsc_get_options( 'wpgpscsc_related_add_to_cart_button_colors', null, 'background-hover' );
$wpgpscsc_related_product_details_padding_top    = wpgpsc_get_options( 'wpgpscsc_related_product_details_padding', null, 'top' );
$wpgpscsc_related_product_details_padding_right  = wpgpsc_get_options( 'wpgpscsc_related_product_details_padding', null, 'right' );
$wpgpscsc_related_product_details_padding_bottom = wpgpsc_get_options( 'wpgpscsc_related_product_details_padding', null, 'bottom' );
$wpgpscsc_related_product_details_padding_left   = wpgpsc_get_options( 'wpgpscsc_related_product_details_padding', null, 'left' );
// Upsell Product Details Padding.
$wpgpscsc_upsell_product_details_padding_top    = wpgpsc_get_options( 'wpgpscsc_upsell_product_details_padding', null, 'top' );
$wpgpscsc_upsell_product_details_padding_right  = wpgpsc_get_options( 'wpgpscsc_upsell_product_details_padding', null, 'right' );
$wpgpscsc_upsell_product_details_padding_bottom = wpgpsc_get_options( 'wpgpscsc_upsell_product_details_padding', null, 'bottom' );
$wpgpscsc_upsell_product_details_padding_left   = wpgpsc_get_options( 'wpgpscsc_upsell_product_details_padding', null, 'left' );
// Cross-sell Product Details Padding.
$wpgpscsc_crossell_product_details_padding_top    = wpgpsc_get_options( 'wpgpscsc_crossell_product_details_padding', null, 'top' );
$wpgpscsc_crossell_product_details_padding_right  = wpgpsc_get_options( 'wpgpscsc_crossell_product_details_padding', null, 'right' );
$wpgpscsc_crossell_product_details_padding_bottom = wpgpsc_get_options( 'wpgpscsc_crossell_product_details_padding', null, 'bottom' );
$wpgpscsc_crossell_product_details_padding_left   = wpgpsc_get_options( 'wpgpscsc_crossell_product_details_padding', null, 'left' );

/**
 * CSS Rules.
 */
$gpsc_css_rule = array();

$gpsc_css_rule += array(

	'.gpsc--product-slider-carousel .gpsc--section-title' =>
		"{$gpsc_section_title_typo};",
);

$gpsc_css_rule += array(

	'.gpsc--product-slider-carousel .gpsc--product-image h2' =>
		"{$gpsc_product_title_typo};",
);

$gpsc_css_rule += array(

	'.gpsc--product-slider-carousel .add_to_cart_button' =>
		"{$gpsc_button_title_typo};",

	'.gpsc--product-slider-carousel .add_to_cart_button:hover' =>
		"background: {$wpgpscsc_related_add_to_cart_btn_bg_color_hover};",

);

$gpsc_dynamic_styles = '';
foreach ( $gpsc_css_rule as $key => $value ) {

	$gpsc_dynamic_styles .= $key . '{' . $value . '}';
}

wp_add_inline_style( $this->plugin_name, $gpsc_dynamic_styles );

echo '<style>';
