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

wp_enqueue_style( $this->plugin_name . 'swiper' );
wp_enqueue_script( $this->plugin_name . 'swiper' );
wp_enqueue_style( $this->plugin_name . 'fontawesome' );
wp_enqueue_style( $this->plugin_name );
wp_enqueue_script( $this->plugin_name );

/**
 * Doc →
 * https://docs.woocommerce.com/wc-apidocs/class-WC_Product.html
 * https://github.com/woocommerce/woocommerce/wiki/wc_get_products-and-WC_Product_Query
 */
$args     = array(
	'include'  => $wpgpscsc_specific_product_selected,
	'limit'    => $wpgpscsc_product_limit,
	'category' => ( 'category' === $wpgpscsc_product_by ) ? $wpgpscsc_product_cat_selection : '',
	'orderby'  => $wpgpscsc_product_orderby,
	'order'    => $wpgpscsc_product_order,
	'status'   => 'publish',
);
$products = wc_get_products( $args );
?>
<!-- Swiper -->
<div id="gpsc--products-<?php echo esc_attr( $post_id ); ?>" class="gpsc--product-slider-carousel"
	data-speed="<?php echo esc_attr( $wpgpscsc_shortcode_slider_speed ); ?>"
	data-autoplay=<?php echo wp_json_encode( $wpgpscsc_shortcode_slider_autoplay ); ?>
	data-loop="<?php echo esc_attr( $wpgpscsc_shortcode_slider_loop ); ?>"
	data-slidesperview="<?php echo esc_attr( $wpgpscsc_product_columns ); ?>"
	data-spacebetween="<?php echo esc_attr( $wpgpscsc_product_space_between ); ?>"
	data-paginationtype="<?php echo esc_attr( $wpgpscsc_shortcode_slider_pagination_type ); ?>">

	<?php if ( ! empty( get_the_title( $post_id ) ) && $wpgpscsc_section_title_show ) : ?>
	<h2 class="gpsc--section-title-<?php echo esc_attr( $post_id ); ?> gpsc--section-title"><?php echo esc_html( get_the_title( $post_id ) ); ?></h2>
	<?php endif; ?>
	<div class="swiper-container">
		<div class="swiper-wrapper">
		<?php
		foreach ( $products as $product ) {

			echo '<div class="swiper-slide">
                    <div class="gpsc--product-image">
                        <a href="' . esc_url( get_permalink( $product->get_id() ) ) . '">';

			if ( $wpgpscsc_show_thumb ) {

				echo '<img src="' . esc_url( wp_get_attachment_image_src( get_post_thumbnail_id( $product->get_id() ), 'medium' )[0] ) . '" alt="' . esc_html( $product->get_title() ) . '">';
			}

			if ( $wpgpscsc_show_name ) {

				echo '<h2>' . esc_html( $product->get_title() ) . '</h2>';
			}

			echo '</a>
                    <br>' . do_shortcode( '[add_to_cart id="' . $product->get_id() . '"]' ) . '</div>
                </div>';
		}

		if ( ! $wpgpscsc_show_price ) {

			echo '<style>
                .swiper-container .woocommerce-Price-amount { display: none; }
            </style>';
		}

		if ( ! $wpgpscsc_show_button ) {

			echo '<style>
                .swiper-container .add_to_cart_button { display: none !important; }
            </style>';
		}
		?>
		</div>

		<?php if ( $wpgpscsc_shortcode_slider_navigation ) : ?>
		<!-- Add Arrows -->
		<div class="swiper-button-next"></div>
		<div class="swiper-button-prev"></div>
		<?php endif; ?>

		<?php if ( $wpgpscsc_shortcode_slider_pagination ) : ?>
		<!-- Add Pagination -->
		<div class="swiper-pagination"></div>
		<?php endif; ?>
	</div>
</div>
<?php
