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
class GPSC_Product_Slider_Carousel_Related {

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

	public function gpsc_remove_default_related_product_display() {

		// Remove current related products.
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

	}

	/**
	 * Resources →
	 * https://woocommerce.wp-a2z.org/oik_api/wc_get_related_products/
	 *
	 * Methods summary
	 * https://docs.woocommerce.com/wc-apidocs/class-WC_Product.html
	 *
	 * Possibles →
	 * related_products_by_same_title
	 * exclude_related_products
	 *
	 * CREATE A CUSTOM WOOCOMMERCE PRODUCT LOOP (THE RIGHT WAY)
	 * https://cfxdesign.com/create-a-custom-woocommerce-product-loop-the-right-way/
	 *
	 * wc_get_products and WC_Product_Query
	 * https://github.com/woocommerce/woocommerce/wiki/wc_get_products-and-WC_Product_Query
	 *
	 * Awesome Features →
	 * Remove Related if Using Upsells ***
	 *
	 * Move Upsells after Related products section in WooCommerce
	 * https://stackoverflow.com/questions/50743139/move-upsells-after-related-products-section-in-woocommerce
	 *
	 * Woocommerce show cross sells on singe product page
	 * https://wordpress.stackexchange.com/questions/270974/woocommerce-show-cross-sells-on-singe-product-page
	 *
	 * Show Cross Sell products on single page.
	 */
	public function gpsc_add_custom_related_product_display() {

		// Get options.
		$wpgpscsc_related_slider_show           = wpgpsc_get_options( 'wpgpscsc_related_slider_show' );
		$wpgpscsc_has_upsell_products           = wpgpsc_get_options( 'wpgpscsc_has_upsell_products' );
		$wpgpscsc_related_before_upsells        = wpgpsc_get_options( 'wpgpscsc_related_before_upsells' );
		$wpgpscsc_related_section_title_show    = wpgpsc_get_options( 'wpgpscsc_related_section_title_show' );
		$wpgpscsc_related_section_title_text    = wpgpsc_get_options( 'wpgpscsc_related_section_title_text' );
		$wpgpscsc_related_products_from         = wpgpsc_get_options( 'wpgpscsc_related_products_from' );
		$wpgpscsc_related_slider_speed          = wpgpsc_get_options( 'wpgpscsc_related_slider_speed' );
		$wpgpscsc_related_slider_autoplay_delay = wpgpsc_get_options( 'wpgpscsc_related_slider_autoplay_delay' );
		$wpgpscsc_related_slider_autoplay       = '';
		if ( wpgpsc_get_options( 'wpgpscsc_related_slider_autoplay' ) ) {

			$wpgpscsc_related_slider_autoplay = (object) array(
				'autoplay' => array(
					'delay' => $wpgpscsc_related_slider_autoplay_delay,
				),
			);
		}
		$wpgpscsc_related_slider_loop              = wpgpsc_get_options( 'wpgpscsc_related_slider_loop' ) ? 'true' : 'false';
		$wpgpscsc_related_slides_per_view          = wpgpsc_get_options( 'wpgpscsc_related_slides_per_view' );
		$wpgpscsc_related_slides_space_between     = wpgpsc_get_options( 'wpgpscsc_related_slides_space_between' );
		$wpgpscsc_related_slider_navigation        = wpgpsc_get_options( 'wpgpscsc_related_slider_navigation' );
		$wpgpscsc_related_slider_nav_icon          = wpgpsc_get_options( 'wpgpscsc_related_slider_nav_icon' );
		$wpgpscsc_related_slider_nav_colors        = wpgpsc_get_options( 'wpgpscsc_related_slider_nav_colors' );
		$wpgpscsc_related_slider_pagination        = wpgpsc_get_options( 'wpgpscsc_related_slider_pagination' );
		$wpgpscsc_related_slider_pagination_type   = wpgpsc_get_options( 'wpgpscsc_related_slider_pagination_type' );
		$wpgpscsc_related_slider_pagination_colors = wpgpsc_get_options( 'wpgpscsc_related_slider_pagination_colors' );

		// Get current products terms and full details.
		$terms      = get_the_terms( get_the_ID(), 'product_cat' );
		$first_term = $terms[0]->name;

		$term_ids = array();
		if ( ! empty( $terms ) ) {

			$term_ids = wp_list_pluck( $terms, 'slug' );
		}

		// Get Products.
		$args     = array(
			// 'limit'    => 3,
			'category' => $term_ids,
			'status'   => 'publish',
		);
		$products = wc_get_products( $args );
		?>
		<!-- Swiper -->
		<div id="gpsc--related" class="gpsc--product-slider-carousel"
			data-speed="<?php echo esc_attr( $wpgpscsc_related_slider_speed ); ?>"
			data-autoplay=<?php echo wp_json_encode( $wpgpscsc_related_slider_autoplay ); ?>
			data-loop="<?php echo esc_attr( $wpgpscsc_related_slider_loop ); ?>"
			data-slidesperview="<?php echo esc_attr( $wpgpscsc_related_slides_per_view ); ?>"
			data-spacebetween="<?php echo esc_attr( $wpgpscsc_related_slides_space_between ); ?>"
			data-paginationtype="<?php echo esc_attr( $wpgpscsc_related_slider_pagination_type ); ?>">

			<?php if ( $wpgpscsc_related_section_title_show ) : ?>
			<h2 class="gpsc--related-product-section-title gpsc--section-title"><?php echo esc_html( $wpgpscsc_related_section_title_text ); ?></h2>
			<?php endif; ?>
			<div class="swiper-container">
				<div class="swiper-wrapper">
				<?php
				foreach ( $products as $product ) {

					echo '<div class="swiper-slide">
								<div class="gpsc--product-image">
									<a href="' . esc_url( get_permalink( $product->get_id() ) ) . '">
										<img src="' . esc_url( wp_get_attachment_image_src( get_post_thumbnail_id( $product->get_id() ), 'medium' )[0] ) . '" alt="' . esc_html( $product->get_title() ) . '">
										<h2>' . esc_html( $product->get_title() ) . '</h2>

									</a>
									<br>' . do_shortcode( '[add_to_cart id="' . $product->get_id() . '"]' ) .
								'</div>
							</div>';
				}
				?>
				</div>

				<?php if ( $wpgpscsc_related_slider_navigation ) : ?>
				<!-- Add Arrows -->
				<div class="swiper-button-next"></div>
				<div class="swiper-button-prev"></div>
				<?php endif; ?>

				<?php if ( $wpgpscsc_related_slider_pagination ) : ?>
				<!-- Add Pagination -->
				<div class="swiper-pagination"></div>
				<?php endif; ?>
			</div>
		</div>
		<?php

		// $array = wc_get_related_products( $product_id, $limit, $exclude_ids );
	}

}
