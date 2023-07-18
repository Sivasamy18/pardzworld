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
class GPSC_Product_ATC {

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
	 * ATC Button Text Rendered.
	 *
	 * @var Mixed
	 */
	public $gpsc_atc_button_text;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string $plugin_name       The name of the plugin.
	 * @param    string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		$this->gpsc_atc_button_text = wpgpsc_get_options( 'gpsc_atc_button_text' );

	}

	// Change add to cart text on single product page.
	public function woocommerce_add_to_cart_button_text_single() {

		return $this->gpsc_atc_button_text;
	}

	// Change add to cart text on product archives page.
	public function woocommerce_add_to_cart_button_text_archives() {

		return $this->gpsc_atc_button_text;
	}
}
