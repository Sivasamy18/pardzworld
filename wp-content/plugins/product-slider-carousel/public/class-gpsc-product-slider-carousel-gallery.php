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
class GPSC_Product_Slider_Carousel_Gallery {

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

	function gpsc_remove_default_product_gallery_display() {

		// Remove images from under featured image.
		remove_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );
	}

	function gpsc_add_custom_product_gallery_display() {

		// Get options.
		$wpgpscsc_gallery_slider_show           = wpgpsc_get_options( 'wpgpscsc_gallery_slider_show' );
		$wpgpscsc_gallery_section_title_show    = wpgpsc_get_options( 'wpgpscsc_gallery_section_title_show' );
		$wpgpscsc_gallery_section_title_text    = wpgpsc_get_options( 'wpgpscsc_gallery_section_title_text' );
		$wpgpscsc_gallery_slider_speed          = wpgpsc_get_options( 'wpgpscsc_gallery_slider_speed' );
		$wpgpscsc_gallery_slider_autoplay_delay = wpgpsc_get_options( 'wpgpscsc_gallery_slider_autoplay_delay' );
		$wpgpscsc_gallery_slider_autoplay       = '';
		if ( wpgpsc_get_options( 'wpgpscsc_gallery_slider_autoplay' ) ) {

			$wpgpscsc_gallery_slider_autoplay = (object) array(
				'autoplay' => array(
					'delay' => $wpgpscsc_gallery_slider_autoplay_delay,
				),
			);
		}
		$wpgpscsc_gallery_slider_loop              = wpgpsc_get_options( 'wpgpscsc_gallery_slider_loop' ) ? 'true' : 'false';
		$wpgpscsc_gallery_slides_per_view          = wpgpsc_get_options( 'wpgpscsc_gallery_slides_per_view' );
		$wpgpscsc_gallery_slides_space_between     = wpgpsc_get_options( 'wpgpscsc_gallery_slides_space_between' );
		$wpgpscsc_gallery_slider_navigation        = wpgpsc_get_options( 'wpgpscsc_gallery_slider_navigation' );
		$wpgpscsc_gallery_slider_nav_icon          = wpgpsc_get_options( 'wpgpscsc_gallery_slider_nav_icon' );
		$wpgpscsc_gallery_slider_nav_colors        = wpgpsc_get_options( 'wpgpscsc_gallery_slider_nav_colors' );
		$wpgpscsc_gallery_slider_pagination        = wpgpsc_get_options( 'wpgpscsc_gallery_slider_pagination' );
		$wpgpscsc_gallery_slider_pagination_type   = wpgpsc_get_options( 'wpgpscsc_gallery_slider_pagination_type' );
		$wpgpscsc_gallery_slider_pagination_colors = wpgpsc_get_options( 'wpgpscsc_gallery_slider_pagination_colors' );

		$_product = wc_get_product( get_the_ID() );

		// Up Sell IDs.
		// $_product->upsell_ids;

		// Cross Sell IDs.
		// $_product->cross_sell_ids;

		// Cross Gallery Image IDs.
		$gpsc_gallery_image_ids = $_product->get_gallery_image_ids();

		if ( ! empty( $gpsc_gallery_image_ids ) ) {

			?>
			<!-- Style -->
			<style>
			.gpsc--product-slider-carousel .gpsc--section-title {
				font-size: 32px;
				line-height: 50px;
			}
			/* Slider Pagination */
			#gpsc--gallery .swiper-pagination {
				position: relative;
			}
			#gpsc--gallery .swiper-pagination-bullet {
				margin: -7px 3px;
			}
			/* Centering the venobox iframe */
			.vbox-container img.vbox-figlio {
				margin: 0 auto;
			}
			<?php if ( empty( $wpgpscsc_gallery_slider_navigation ) ) : ?>
			#gpsc--gallery .swiper-container {
				padding-top: <?php echo esc_attr( $wpgpscsc_gallery_slides_space_between ); ?>px !important;
			}
			<?php endif; ?>
			</style>
			<!-- Script -->
			<script>
			(function( $ ) {
				'use strict';

				$( document ).ready(function() {

					$('.venobox').venobox();
				});

			})( jQuery );
			</script>
			<!-- Swiper -->
			<div id="gpsc--gallery" class="gpsc--product-slider-carousel"
				data-speed="<?php echo esc_attr( $wpgpscsc_gallery_slider_speed ); ?>"
				data-autoplay=<?php echo wp_json_encode( $wpgpscsc_gallery_slider_autoplay ); ?>
				data-loop="<?php echo esc_attr( $wpgpscsc_gallery_slider_loop ); ?>"
				data-slidesperview="<?php echo esc_attr( $wpgpscsc_gallery_slides_per_view ); ?>"
				data-spacebetween="<?php echo esc_attr( $wpgpscsc_gallery_slides_space_between ); ?>"
				data-paginationtype="<?php echo esc_attr( $wpgpscsc_gallery_slider_pagination_type ); ?>">

				<?php if ( $wpgpscsc_gallery_section_title_show ) : ?>
				<h2 class="gpsc--gallery-image-section-title gpsc--section-title"><?php echo esc_html( $wpgpscsc_gallery_section_title_text ); ?></h2>
				<?php endif; ?>
				<div class="swiper-container">
					<div class="swiper-wrapper">

						<?php
						foreach ( $gpsc_gallery_image_ids as $gpsc_gallery_image_id ) {

							echo '<div class="swiper-slide">
							<a class="venobox" data-gall="gallery01" href="' . esc_url( wp_get_attachment_image_src( $gpsc_gallery_image_id, 'full' )[0] ) . '">
								<img src="' . esc_url( wp_get_attachment_image_src( $gpsc_gallery_image_id )[0] ) . '" alt="' . esc_html( get_the_title( get_the_ID() ) ) . '">
							</a>
							</div>';
						}
						?>

					</div>

				</div>

				<?php if ( $wpgpscsc_gallery_slider_navigation ) : ?>
				<!-- Add Arrows -->
				<div class="swiper-button-next"></div>
				<div class="swiper-button-prev"></div>
				<?php endif; ?>

				<?php if ( $wpgpscsc_gallery_slider_pagination ) : ?>
				<!-- Add Pagination -->
				<div class="swiper-pagination"></div>
				<?php endif; ?>
			</div>
			<?php

		}

	}

}
