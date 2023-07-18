<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.pluginic.com
 * @since      1.0.0
 *
 * @package    GPSC_Product_Slider_Carousel
 * @subpackage GPSC_Product_Slider_Carousel/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    GPSC_Product_Slider_Carousel
 * @subpackage GPSC_Product_Slider_Carousel/public
 * @author     Pluginic
 */
class GPSC_Product_Slider_Carousel_Upsell {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	function gpsc_remove_default_upsell_product_display() {

		// Remove current upsell products.
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );

		// Specially for storefront theme.
		remove_action( 'woocommerce_after_single_product_summary', 'storefront_upsell_display', 15 );
	}

	function gpsc_add_custom_upsell_product_display() {

		// Get options.
		$wpgpscsc_upsell_slider_show           = wpgpsc_get_options( 'wpgpscsc_upsell_slider_show' );
		$wpgpscsc_upsell_section_title_show    = wpgpsc_get_options( 'wpgpscsc_upsell_section_title_show' );
		$wpgpscsc_upsell_section_title_text    = wpgpsc_get_options( 'wpgpscsc_upsell_section_title_text' );
		$wpgpscsc_upsell_slider_speed          = wpgpsc_get_options( 'wpgpscsc_upsell_slider_speed' );
		$wpgpscsc_upsell_slider_autoplay_delay = wpgpsc_get_options( 'wpgpscsc_upsell_slider_autoplay_delay' );
		$wpgpscsc_upsell_slider_autoplay       = '';
		if ( wpgpsc_get_options( 'wpgpscsc_upsell_slider_autoplay' ) ) {

			$wpgpscsc_upsell_slider_autoplay = (object) array(
				'autoplay' => array(
					'delay' => $wpgpscsc_upsell_slider_autoplay_delay,
				),
			);
		}
		$wpgpscsc_upsell_slider_loop              = wpgpsc_get_options( 'wpgpscsc_upsell_slider_loop' ) ? 'true' : 'false';
		$wpgpscsc_upsell_slides_per_view          = wpgpsc_get_options( 'wpgpscsc_upsell_slides_per_view' );
		$wpgpscsc_upsell_product_details_padding  = wpgpsc_get_options( 'wpgpscsc_upsell_product_details_padding' );
		$wpgpscsc_upsell_slides_space_between     = wpgpsc_get_options( 'wpgpscsc_upsell_slides_space_between' );
		$wpgpscsc_upsell_slider_navigation        = wpgpsc_get_options( 'wpgpscsc_upsell_slider_navigation' );
		$wpgpscsc_upsell_slider_nav_icon          = wpgpsc_get_options( 'wpgpscsc_upsell_slider_nav_icon' );
		$wpgpscsc_upsell_slider_nav_colors        = wpgpsc_get_options( 'wpgpscsc_upsell_slider_nav_colors' );
		$wpgpscsc_upsell_slider_pagination        = wpgpsc_get_options( 'wpgpscsc_upsell_slider_pagination' );
		$wpgpscsc_upsell_slider_pagination_type   = wpgpsc_get_options( 'wpgpscsc_upsell_slider_pagination_type' );
		$wpgpscsc_upsell_slider_pagination_colors = wpgpsc_get_options( 'wpgpscsc_upsell_slider_pagination_colors' );

		$gpsc_get_all_products   = wc_get_product( get_the_ID() );
		$gpsc_upsell_product_ids = $gpsc_get_all_products->get_upsell_ids();

		if ( ! empty( $gpsc_upsell_product_ids ) ) {

			?>
			<!-- Swiper -->
			<div id="gpsc--upsell" class="gpsc--product-slider-carousel"
				data-speed="<?php echo esc_attr( $wpgpscsc_upsell_slider_speed ); ?>"
				data-autoplay=<?php echo wp_json_encode( $wpgpscsc_upsell_slider_autoplay ); ?>
				data-loop="<?php echo esc_attr( $wpgpscsc_upsell_slider_loop ); ?>"
				data-slidesperview="<?php echo esc_attr( $wpgpscsc_upsell_slides_per_view ); ?>"
				data-spacebetween="<?php echo esc_attr( $wpgpscsc_upsell_slides_space_between ); ?>"
				data-paginationtype="<?php echo esc_attr( $wpgpscsc_upsell_slider_pagination_type ); ?>">

				<?php if ( $wpgpscsc_upsell_section_title_show ) : ?>
				<h2 class="gpsc--upsell-product-section-title gpsc--section-title"><?php echo esc_html( $wpgpscsc_upsell_section_title_text ); ?></h2>
				<?php endif; ?>

				<div class="swiper-container">
					<div class="swiper-wrapper">
					<?php
					foreach ( $gpsc_upsell_product_ids as $gpsc_upsell_product_id ) {

						$is_object = wc_get_product( $gpsc_upsell_product_id );
						if ( is_object( $is_object ) ) {

							echo '<div class="swiper-slide">
									<div class="gpsc--product-image">
										<a href="' . esc_url( get_permalink( $gpsc_upsell_product_id ) ) . '">
											<img src="' . esc_url( wp_get_attachment_image_src( get_post_thumbnail_id( $gpsc_upsell_product_id ), 'medium' )[0] ) . '" alt="' . esc_html( get_the_title( $gpsc_upsell_product_id ) ) . '">
											<h2>' . esc_html( get_the_title( $gpsc_upsell_product_id ) ) . '</h2>

										</a>
										<br>' . do_shortcode( '[add_to_cart id="' . $gpsc_upsell_product_id . '"]' ) .
									'</div>
								</div>';
						}
					}
					?>
					</div>

					<?php if ( $wpgpscsc_upsell_slider_navigation ) : ?>
					<!-- Add Arrows -->
					<div class="swiper-button-next"></div>
					<div class="swiper-button-prev"></div>
					<?php endif; ?>

					<?php if ( $wpgpscsc_upsell_slider_pagination ) : ?>
					<!-- Add Pagination -->
					<div class="swiper-pagination"></div>
					<?php endif; ?>
				</div>
			</div>
			<?php

		} // End if has upsell products.
	}

}
