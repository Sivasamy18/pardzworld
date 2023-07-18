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
class GPSC_Product_Slider_Carousel_Cross_Sell {

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

	public function gpsc_remove_default_crossell_product_display() {

		// Remove cross sell products.
		remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
	}

	public function gpsc_add_custom_product_gallery_display() {

		// Get options.
		$wpgpscsc_crossell_slider_show           = wpgpsc_get_options( 'wpgpscsc_crossell_slider_show' );
		$wpgpscsc_crossell_on_single_page        = wpgpsc_get_options( 'wpgpscsc_crossell_on_single_page' );
		$wpgpscsc_crossell_section_title_show    = wpgpsc_get_options( 'wpgpscsc_crossell_section_title_show' );
		$wpgpscsc_crossell_section_title_text    = wpgpsc_get_options( 'wpgpscsc_crossell_section_title_text' );
		$wpgpscsc_crossell_slider_speed          = wpgpsc_get_options( 'wpgpscsc_crossell_slider_speed' );
		$wpgpscsc_crossell_slider_autoplay_delay = wpgpsc_get_options( 'wpgpscsc_crossell_slider_autoplay_delay' );
		$wpgpscsc_crossell_slider_autoplay       = '';
		if ( wpgpsc_get_options( 'wpgpscsc_crossell_slider_autoplay' ) ) {

			$wpgpscsc_crossell_slider_autoplay = (object) array(
				'autoplay' => array(
					'delay' => $wpgpscsc_crossell_slider_autoplay_delay,
				),
			);
		}
		$wpgpscsc_crossell_slider_loop              = wpgpsc_get_options( 'wpgpscsc_crossell_slider_loop' ) ? 'true' : 'false';
		$wpgpscsc_crossell_slides_per_view          = wpgpsc_get_options( 'wpgpscsc_crossell_slides_per_view' );
		$wpgpscsc_crossell_product_details_padding  = wpgpsc_get_options( 'wpgpscsc_crossell_product_details_padding' );
		$wpgpscsc_crossell_slides_space_between     = wpgpsc_get_options( 'wpgpscsc_crossell_slides_space_between' );
		$wpgpscsc_crossell_slider_navigation        = wpgpsc_get_options( 'wpgpscsc_crossell_slider_navigation' );
		$wpgpscsc_crossell_slider_nav_icon          = wpgpsc_get_options( 'wpgpscsc_crossell_slider_nav_icon' );
		$wpgpscsc_crossell_slider_nav_colors        = wpgpsc_get_options( 'wpgpscsc_crossell_slider_nav_colors' );
		$wpgpscsc_crossell_slider_pagination        = wpgpsc_get_options( 'wpgpscsc_crossell_slider_pagination' );
		$wpgpscsc_crossell_slider_pagination_type   = wpgpsc_get_options( 'wpgpscsc_crossell_slider_pagination_type' );
		$wpgpscsc_crossell_slider_pagination_colors = wpgpsc_get_options( 'wpgpscsc_crossell_slider_pagination_colors' );

		// Enqueue styles and scripts.
		// wp_enqueue_style( $this->plugin_name . 'swiper' );
		// wp_enqueue_style( $this->plugin_name );
		// wp_enqueue_script( $this->plugin_name . 'swiper' );
		// wp_enqueue_script( $this->plugin_name );
		// wp_enqueue_style( $this->plugin_name . 'fontawesome' );

		$cart = WC()->cart->get_cart();
		if ( ! empty( $cart ) ) {

			$gpsc_cross_sell_ids_merged = array();
			foreach ( $cart as $cart_item_key => $cart_item ) {

				$_product                   = wc_get_product( $cart_item['product_id'] );
				$gpsc_cross_sell_ids        = $_product->get_cross_sell_ids();
				$gpsc_cross_sell_ids_merged = array_merge( $gpsc_cross_sell_ids_merged, $_product->get_cross_sell_ids() );
			}
			$gpsc_cross_sell_ids_no_duplicate = array_unique( $gpsc_cross_sell_ids_merged );

			if ( ! empty( $gpsc_cross_sell_ids_no_duplicate ) ) {

				?>
				<!-- Swiper -->
				<div id="gpsc--cross-sell" class="gpsc--product-slider-carousel"
					data-speed="<?php echo esc_attr( $wpgpscsc_crossell_slider_speed ); ?>"
					data-autoplay=<?php echo wp_json_encode( $wpgpscsc_crossell_slider_autoplay ); ?>
					data-loop="<?php echo esc_attr( $wpgpscsc_crossell_slider_loop ); ?>"
					data-slidesperview="<?php echo esc_attr( $wpgpscsc_crossell_slides_per_view ); ?>"
					data-spacebetween="<?php echo esc_attr( $wpgpscsc_crossell_slides_space_between ); ?>"
					data-paginationtype="<?php echo esc_attr( $wpgpscsc_crossell_slider_pagination_type ); ?>">

					<?php if ( $wpgpscsc_crossell_section_title_show ) : ?>
					<h2 class="gpsc--crossell-product-section-title gpsc--section-title"><?php echo esc_html( $wpgpscsc_crossell_section_title_text ); ?></h2>
					<?php endif; ?>
					<div class="swiper-container">
						<div class="swiper-wrapper">
						<?php
						foreach ( $gpsc_cross_sell_ids_no_duplicate as $product_id ) {

							$is_object = wc_get_product( $product_id );
							if ( is_object( $is_object ) ) {

								echo '<div class="swiper-slide">
											<div class="gpsc--product-image">
												<a href="' . esc_url( get_permalink( $product_id ) ) . '">
													<img src="' . esc_url( wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'medium' )[0] ) . '" alt="' . esc_html( get_the_title( $product_id ) ) . '">
													<h2>' . esc_html( get_the_title( $product_id ) ) . '</h2>

												</a>
												<br>' . do_shortcode( '[add_to_cart id="' . $product_id . '"]' ) .
											'</div>
										</div>';
							}
						}
						?>
						</div>

						<?php if ( $wpgpscsc_crossell_slider_navigation ) : ?>
						<!-- Add Arrows -->
						<div class="swiper-button-next"></div>
						<div class="swiper-button-prev"></div>
						<?php endif; ?>

						<?php if ( $wpgpscsc_crossell_slider_pagination ) : ?>
						<!-- Add Pagination -->
						<div class="swiper-pagination"></div>
						<?php endif; ?>
					</div>
				</div>
				<?php

			}
		}
	}

}
