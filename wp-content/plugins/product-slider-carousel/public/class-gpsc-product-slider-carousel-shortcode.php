<?php

/**
 * The shortcode functionality of the plugin.
 *
 * @link       https://www.pluginic.com
 * @since      1.0.0
 *
 * @package    GPSC_Product_Slider_Carousel
 * @subpackage GPSC_Product_Slider_Carousel/public
 */

/**
 * The shortcode functionality of the plugin.
 *
 * @package    GPSC_Product_Slider_Carousel
 * @subpackage GPSC_Product_Slider_Carousel/public
 * @author     Pluginic
 */
class GPSC_Product_Slider_Carousel_Shortcode {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		/**
		 * A Custom function to get post meta with sanitization and validation.
		 *
		 * @param [type] $post_id Current post ID.
		 * @param string $option Meta key.
		 * @param [type] $default Default meta value.
		 * @param string $option_two Nested meta key.
		 * @param [type] $default_two Nested meta value.
		 * @return mixed
		 */
		function wpgpsc_get_post_meta( $post_id, $option = '', $default = null, $option_two = '', $default_two = null ) {

			$options = get_post_meta( $post_id, '_wpgpsc_page_options', true );
			if ( ! empty( $option_two ) ) {

				return ( isset( $options[ $option ][ $option_two ] ) ) ? $options[ $option ][ $option_two ] : $default_two;
			} else {

				return ( isset( $options[ $option ] ) ) ? $options[ $option ] : $default;
			}
		}
	}

	/**
	 * A shortcode for this plugin.
	 *
	 * @since   1.0.0
	 * @param   string $atts attribute of this shortcode.
	 */
	public function gpsc_shortcode_execute( $atts ) {

		$post_id = intval( $atts['id'] );

		// General Settings.
		$wpgpscsc_module                      = wpgpsc_get_post_meta( $post_id, 'wpgpscsc_module' );
		$wpgpscsc_section_title_show          = wpgpsc_get_post_meta( $post_id, 'wpgpscsc_section_title_show' );
		$wpgpscsc_section_title_margin_bottom = wpgpsc_get_post_meta( $post_id, 'wpgpscsc_section_title_margin_bottom' );
		$wpgpscsc_product_by                  = wpgpsc_get_post_meta( $post_id, 'wpgpscsc_product_by' );
		$wpgpscsc_product_cat_selection       = wpgpsc_get_post_meta( $post_id, 'wpgpscsc_product_cat_selection' );
		$wpgpscsc_specific_product_selected   = wpgpsc_get_post_meta( $post_id, 'wpgpscsc_specific_product_selected' );
		$wpgpscsc_product_limit               = wpgpsc_get_post_meta( $post_id, 'wpgpscsc_product_limit' );
		$wpgpscsc_product_orderby             = wpgpsc_get_post_meta( $post_id, 'wpgpscsc_product_orderby' );
		$wpgpscsc_product_order               = wpgpsc_get_post_meta( $post_id, 'wpgpscsc_product_order' );

		// Controls.
		$wpgpscsc_show_thumb          = wpgpsc_get_post_meta( $post_id, 'wpgpscsc_show_thumb' );
		$wpgpscsc_thumb_size          = wpgpsc_get_post_meta( $post_id, 'wpgpscsc_thumb_size' );
		$wpgpscsc_show_name           = wpgpsc_get_post_meta( $post_id, 'wpgpscsc_show_name' );
		$wpgpscsc_show_price          = wpgpsc_get_post_meta( $post_id, 'wpgpscsc_show_price' );
		$wpgpscsc_show_short_desc     = wpgpsc_get_post_meta( $post_id, 'wpgpscsc_show_short_desc' );
		$wpgpscsc_show_button         = wpgpsc_get_post_meta( $post_id, 'wpgpscsc_show_button' );
		$wpgpscsc_show_detail_btn     = wpgpsc_get_post_meta( $post_id, 'wpgpscsc_show_detail_btn' );
		$wpgpscsc_show_detail_btn_txt = wpgpsc_get_post_meta( $post_id, 'wpgpscsc_show_detail_btn_txt' );

		// Get options.
		$wpgpscsc_shortcode_slider_show           = wpgpsc_get_post_meta( $post_id, 'wpgpscsc_shortcode_slider_show' );
		$wpgpscsc_has_upsell_products             = wpgpsc_get_post_meta( $post_id, 'wpgpscsc_has_upsell_products' );
		$wpgpscsc_shortcode_before_upsells        = wpgpsc_get_post_meta( $post_id, 'wpgpscsc_shortcode_before_upsells' );
		$wpgpscsc_shortcode_section_title_show    = wpgpsc_get_post_meta( $post_id, 'wpgpscsc_shortcode_section_title_show' );
		$wpgpscsc_shortcode_section_title_text    = wpgpsc_get_post_meta( $post_id, 'wpgpscsc_shortcode_section_title_text' );
		$wpgpscsc_shortcode_products_from         = wpgpsc_get_post_meta( $post_id, 'wpgpscsc_shortcode_products_from' );
		$wpgpscsc_shortcode_slider_speed          = wpgpsc_get_post_meta( $post_id, 'wpgpscsc_shortcode_slider_speed' );
		$wpgpscsc_shortcode_slider_autoplay_delay = wpgpsc_get_post_meta( $post_id, 'wpgpscsc_shortcode_slider_autoplay_delay' );
		$wpgpscsc_shortcode_slider_autoplay       = '';
		if ( wpgpsc_get_post_meta( $post_id, 'wpgpscsc_shortcode_slider_autoplay' ) ) {

			$wpgpscsc_shortcode_slider_autoplay = (object) array(
				'autoplay' => array(
					'delay' => $wpgpscsc_shortcode_slider_autoplay_delay,
				),
			);
		}
		$wpgpscsc_shortcode_slider_loop              = wpgpsc_get_post_meta( $post_id, 'wpgpscsc_shortcode_slider_loop' ) ? 'true' : 'false';
		$wpgpscsc_product_columns                    = wpgpsc_get_post_meta( $post_id, 'wpgpscsc_product_columns' );
		$wpgpscsc_product_space_between              = wpgpsc_get_post_meta( $post_id, 'wpgpscsc_product_space_between' );
		$wpgpscsc_shortcode_slider_navigation        = wpgpsc_get_post_meta( $post_id, 'wpgpscsc_shortcode_slider_navigation' );
		$wpgpscsc_shortcode_slider_nav_icon          = wpgpsc_get_post_meta( $post_id, 'wpgpscsc_shortcode_slider_nav_icon' );
		$wpgpscsc_shortcode_slider_nav_colors        = wpgpsc_get_post_meta( $post_id, 'wpgpscsc_shortcode_slider_nav_colors' );
		$wpgpscsc_shortcode_slider_pagination        = wpgpsc_get_post_meta( $post_id, 'wpgpscsc_shortcode_slider_pagination' );
		$wpgpscsc_shortcode_slider_pagination_type   = wpgpsc_get_post_meta( $post_id, 'wpgpscsc_shortcode_slider_pagination_type' );
		$wpgpscsc_shortcode_slider_pagination_colors = wpgpsc_get_post_meta( $post_id, 'wpgpscsc_shortcode_slider_pagination_colors' );

		/**
		 * Load Google font.
		 *
		 * @package shortcode
		 */
		if ( wpgpsc_get_options( 'gpsc_dequeue_google_font' ) ) {
			$wpgpsc_unique_id     = uniqid();
			$wpgpsc_enqueue_fonts = array();
			$wpgpsc_typography    = array();

			// Typography.
			$wpgpsc_typography[] = $gpsc_section_title_typo;
			$wpgpsc_typography[] = $gpsc_video_title_typo;
			$wpgpsc_typography[] = $gpsc_desc_typo;
			$wpgpsc_typography[] = $gpsc_meta_typo;

			if ( ! empty( $wpgpsc_typography ) ) {

				foreach ( $wpgpsc_typography as $wpgpsc_font ) {

					if ( isset( $wpgpsc_font['type'] ) && 'google' === $wpgpsc_font['type'] ) {

						$wpgpsc_variant         = ( isset( $wpgpsc_font['font-weight'] ) ) ? ':' . $wpgpsc_font['font-weight'] : '';
						$wpgpsc_subset          = isset( $wpgpsc_font['subset'] ) ? ':' . $wpgpsc_font['subset'] : '';
						$wpgpsc_enqueue_fonts[] = $wpgpsc_font['font-family'] . $wpgpsc_variant . $wpgpsc_subset;
					}
				}
			}

			if ( ! empty( $wpgpsc_enqueue_fonts ) ) {

				wp_enqueue_style( 'wpgpsc--google-fonts' . $wpgpsc_unique_id, esc_url( add_query_arg( 'family', rawurlencode( implode( '|', $wpgpsc_enqueue_fonts ) ), '//fonts.googleapis.com/css' ) ), array(), $this->version );
			}
		} // Google font enqueue dequeue.

		ob_start();

		switch ( $wpgpscsc_module ) {

			case 'carousel':
				require GPSC_PRODUCT_SLIDER_CAROUSEL_DIR_PATH_FILE . 'public/modules/gpsc-product-carousel.php';
				break;

			case 'grid':
				require GPSC_PRODUCT_SLIDER_CAROUSEL_DIR_PATH_FILE . 'public/modules/gpsc-product-grid.php';
				break;

			default:
				require GPSC_PRODUCT_SLIDER_CAROUSEL_DIR_PATH_FILE . 'public/modules/gpsc-product-carousel.php';
				break;
		}

		return ob_get_clean();
	}

}
