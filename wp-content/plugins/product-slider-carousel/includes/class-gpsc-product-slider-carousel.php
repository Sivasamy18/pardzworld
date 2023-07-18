<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.pluginic.com
 * @since      1.0.0
 *
 * @package    GPSC_Product_Slider_Carousel
 * @subpackage GPSC_Product_Slider_Carousel/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    GPSC_Product_Slider_Carousel
 * @subpackage GPSC_Product_Slider_Carousel/includes
 * @author     Pluginic
 */
class GPSC_Product_Slider_Carousel {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      GPSC_Product_Slider_Carousel_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

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
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'GPSC_PRODUCT_SLIDER_CAROUSEL_VERSION' ) ) {
			$this->version = GPSC_PRODUCT_SLIDER_CAROUSEL_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'gpsc-product-slider-carousel';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - GPSC_Product_Slider_Carousel_Loader. Orchestrates the hooks of the plugin.
	 * - GPSC_Product_Slider_Carousel_i18n. Defines internationalization functionality.
	 * - GPSC_Product_Slider_Carousel_Admin. Defines all hooks for the admin area.
	 * - GPSC_Product_Slider_Carousel_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * Autoloading system registered.
		 */
		spl_autoload_register( array( $this, 'gpsc_product_slider_carousel_autoloader' ) );

		$this->loader = new GPSC_Product_Slider_Carousel_Loader();

		/**
		 * A Custom function to get post options settings with sanitization and validation.
		 *
		 * @param string $option Options key.
		 * @param [type] $default Default option value.
		 * @param string $option_two Nested option key.
		 * @param [type] $default_two Nested option value.
		 * @return mixed
		 */
		function wpgpsc_get_options( $option = '', $default = null, $option_two = '', $default_two = null ) {

			$options = get_option( '_wpgpsc_option_settings' );
			if ( ! empty( $option_two ) ) {

				return ( isset( $options[ $option ][ $option_two ] ) ) ? $options[ $option ][ $option_two ] : $default_two;
			} else {

				return ( isset( $options[ $option ] ) ) ? $options[ $option ] : $default;
			}
		}

	}

	/**
	 * Automatically included all of the directories by the autoloader.
	 *
	 * @param string $class_name Search all of the class name from this file.
	 * @return string
	 */
	private function gpsc_product_slider_carousel_autoloader( $class_name ) {

		// Convert the class name to the file name.
		$class_file = 'class-' . str_replace( '_', '-', strtolower( $class_name ) ) . '.php';

		// Set up the list of directories to look in.
		$classes_dir   = array();
		$include_dir   = realpath( plugin_dir_path( __FILE__ ) );
		$classes_dir[] = $include_dir;

		// Add each of the possible directories to the list.
		foreach ( array( 'admin', 'public' ) as $option ) {

			$classes_dir[] = str_replace( 'includes', $option, $include_dir );
		}

		// Look in each directory and see if the class file exists.
		foreach ( $classes_dir as $class_dir ) {

			$inc = $class_dir . DIRECTORY_SEPARATOR . $class_file;

			// If it does require it.
			if ( file_exists( $inc ) ) {

				require_once $inc;
				return true;
			}
		}
		return false;
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the GPSC_Product_Slider_Carousel_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new GPSC_Product_Slider_Carousel_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new GPSC_Product_Slider_Carousel_Admin( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// Plugin admin custom post types.
		$plugin_admin_cpt = new GPSC_Product_Slider_Carousel_CPT( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'init', $plugin_admin_cpt, 'gpsc_custom_post_type' );
		$this->loader->add_filter( 'post_updated_messages', $plugin_admin_cpt, 'wpps_updated_messages', 10, 2 );
		$this->loader->add_action( 'admin_menu', $plugin_admin_cpt, 'gpsc_help_admin_submenu', 15 );
		$this->loader->add_action( 'admin_init', $plugin_admin_cpt, 'gpsc_safe_welcome_redirect' );
		$this->loader->add_filter( 'admin_footer_text', $plugin_admin_cpt, 'gpsc_review_text', 10, 2 );
		$this->loader->add_filter( 'plugin_action_links', $plugin_admin_cpt, 'gpsc_add_action_plugin', 10, 5 );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new GPSC_Product_Slider_Carousel_Public( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		// Plugin Shortcode.
		$plugin_shortcode = new GPSC_Product_Slider_Carousel_Shortcode( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'gpsc_action_tag_for_shortcode', $plugin_shortcode, 'gpsc_shortcode_execute' );
		add_shortcode( 'psc_product', array( $plugin_shortcode, 'gpsc_shortcode_execute' ) );

		$plugin_public_related = new GPSC_Product_Slider_Carousel_Related( $this->get_plugin_name(), $this->get_version() );
		if ( wpgpsc_get_options( 'wpgpscsc_related_slider_show' ) ) {

			$this->loader->add_action( 'init', $plugin_public_related, 'gpsc_remove_default_related_product_display', 10 );

			$this->loader->add_action( 'woocommerce_after_single_product_summary', $plugin_public_related, 'gpsc_add_custom_related_product_display', 20 );
		}

		/*
		 // A simple excluding products form related products.
		$this->loader->add_filter( 'woocommerce_related_products', $plugin_public_related, 'exclude_related_products', 10, 3 );
		function exclude_related_products( $related_posts, $product_id, $args ) {

			// var_dump( $product_id );

			// HERE set your product IDs to exclude.
			$exclude_ids = array( '19', '28' );

			return array_diff( $related_posts, $exclude_ids );
		} */

		$plugin_public_upsell = new GPSC_Product_Slider_Carousel_Upsell( $this->get_plugin_name(), $this->get_version() );
		if ( wpgpsc_get_options( 'wpgpscsc_upsell_slider_show' ) ) {

			$this->loader->add_action( 'init', $plugin_public_upsell, 'gpsc_remove_default_upsell_product_display', 10 );

			$wpgpscsc_related_products_position = 15;
			if ( wpgpsc_get_options( 'wpgpscsc_related_before_upsells' ) ) {

				$wpgpscsc_related_products_position = 25;
			}
			$this->loader->add_action( 'woocommerce_after_single_product_summary', $plugin_public_upsell, 'gpsc_add_custom_upsell_product_display', $wpgpscsc_related_products_position );
		}

		$plugin_public_gallery = new GPSC_Product_Slider_Carousel_Gallery( $this->get_plugin_name(), $this->get_version() );
		if ( wpgpsc_get_options( 'wpgpscsc_gallery_slider_show' ) ) {

			$this->loader->add_action( 'init', $plugin_public_gallery, 'gpsc_remove_default_product_gallery_display', 10 );
			$this->loader->add_action( 'woocommerce_product_thumbnails', $plugin_public_gallery, 'gpsc_add_custom_product_gallery_display', 10 );
		}

		$plugin_public_cross_sell = new GPSC_Product_Slider_Carousel_Cross_Sell( $this->get_plugin_name(), $this->get_version() );
		if ( wpgpsc_get_options( 'wpgpscsc_crossell_slider_show' ) ) {

			$this->loader->add_action( 'init', $plugin_public_cross_sell, 'gpsc_remove_default_crossell_product_display', 10 );
			$this->loader->add_action( 'woocommerce_cart_collaterals', $plugin_public_cross_sell, 'gpsc_add_custom_product_gallery_display', 10 );
		}

		/**
		 * Changing Add to Cart Button.
		 */
		if ( wpgpsc_get_options( 'gpsc_atc_btn_change' ) ) {

			$plugin_public_atc_btn = new GPSC_Product_ATC( $this->get_plugin_name(), $this->get_version() );
			$this->loader->add_filter( 'woocommerce_product_single_add_to_cart_text', $plugin_public_atc_btn, 'woocommerce_add_to_cart_button_text_single' );
			$this->loader->add_filter( 'woocommerce_product_add_to_cart_text', $plugin_public_atc_btn, 'woocommerce_add_to_cart_button_text_archives' );
		}

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    GPSC_Product_Slider_Carousel_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
