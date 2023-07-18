<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://www.pluginic.com
 * @since      1.0.0
 *
 * @package    GPSC_Product_Slider_Carousel
 * @subpackage GPSC_Product_Slider_Carousel/public/partials
 */

/**
 * Doc →
 * https://docs.woocommerce.com/wc-apidocs/class-WC_Product.html
 * https://github.com/woocommerce/woocommerce/wiki/wc_get_products-and-WC_Product_Query
 */
$gpsc_args     = array(
	'include'  => ( 'hand-pick' === $wpgpscsc_product_by ) ? $wpgpscsc_specific_product_selected : '',
	'limit'    => $wpgpscsc_product_limit,
	'category' => ( 'category' === $wpgpscsc_product_by ) ? $wpgpscsc_product_cat_selection : '',
	'orderby'  => $wpgpscsc_product_orderby,
	'order'    => $wpgpscsc_product_order,
	'status'   => 'publish',
);
$gpsc_products = wc_get_products( $gpsc_args );
?>
<style>
.gpsc--grid-container {
	display: grid;
	grid-template-columns: repeat(<?php echo esc_attr( $wpgpscsc_product_columns ); ?>, 1fr);
	column-gap: <?php echo esc_attr( $wpgpscsc_product_space_between ); ?>px;
	row-gap: <?php echo esc_attr( $wpgpscsc_product_space_between ); ?>px;
}
.gpsc--grid-container .product.woocommerce.add_to_cart_inline {
	display: flex;
	flex-direction: column;
	justify-content: center;
	align-items: center;
	flex-wrap: wrap;
	gap: 10px;
	padding: 0 !important;
	margin: 0 0 10px !important;
}
.gpsc--grid-container .product.woocommerce.add_to_cart_inline > a.button {
	margin-left: 0;
	text-align: center;
}
.gpsc-single-product {
	display: flex;
	flex-direction: column;
	align-items: center;
	box-shadow: rgb(0 0 0 / 16%) 0px 1px 4px;
	padding: 20px;
}
h2.gpsc-single-product-name a {
	font-size: 24px;
	line-height: 30px;
	font-weight: bold;
	text-decoration: none !important;
}
h2.gpsc-single-product-name {
	margin-bottom: 10px;
	text-align: center;
}
bdi.gpsc-single-product-price {
	font-size: 26px;
	line-height: 34px;
	font-weight: bold;
	margin-bottom: 14px;
}
a.gpsc-details-btn {
	background: transparent;
	color: #0170b9;
	border: 2px solid #0170b9;
	display: inline-block;
	padding: 5px 15px;
	text-decoration: none !important;
}
a.gpsc-details-btn:hover {
	background: #0170b9;
	color: #fff;
}
</style>
<div id="gpsc--grid-wrap-<?php echo esc_attr( $post_id ); ?>" class="gpsc--product-grid">
	<?php if ( ! empty( get_the_title( $post_id ) ) && $wpgpscsc_section_title_show ) : ?>
	<h2 class="gpsc--section-title gpsc-title-<?php echo esc_attr( $post_id ); ?>"><?php echo esc_html( get_the_title( $post_id ) ); ?></h2>
	<?php endif; ?>

	<div class="gpsc--grid-container">
	<?php
	foreach ( $gpsc_products as $product ) {

		echo '<div class="gpsc-single-product">';
		if ( $wpgpscsc_show_thumb ) {

			echo '<div class="gpsc-single-product-img">
			<a href="' . esc_url( get_permalink( $product->get_id() ) ) . '">
			<img src="' . esc_url( wp_get_attachment_image_src( get_post_thumbnail_id( $product->get_id() ), $wpgpscsc_thumb_size )[0] ) . '" alt="' . esc_html( $product->get_title() ) . '"></a></div>';
		}
		if ( $wpgpscsc_show_name ) {

			echo '<h2 class="gpsc-single-product-name"><a href="' . esc_url( get_permalink( $product->get_id() ) ) . '">' . esc_html( $product->get_title() ) . '</a></h2>';
		}
		if ( $wpgpscsc_show_short_desc ) {

			echo wp_kses_post( $product->get_short_description() );
		}
		if ( $wpgpscsc_show_price ) {

			echo '<bdi class="gpsc-single-product-price">' . wp_kses_post( $product->get_price_html() ) . '</bdi>';
		}
		if ( $wpgpscsc_show_button ) {

			echo do_shortcode( '[add_to_cart id="' . $product->get_id() . '" show_price="FALSE"]' );
		}
		if ( $wpgpscsc_show_detail_btn ) {

			echo '<a  class="gpsc-details-btn" href="' . esc_url( get_permalink( $product->get_id() ) ) . '" target="_blank">' . esc_html( $wpgpscsc_show_detail_btn_txt ) . '</a>';
		}
		echo '</div>';

	}
	?>
	</div>
</div>
<?php
