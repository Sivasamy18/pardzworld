<?php

/**
 * The file that defines the Custom Post Type of the plugin.
 *
 * @link       https://www.pluginic.com
 * @since      1.0.0
 *
 * @package    GPSC_Product_Slider_Carousel
 * @subpackage GPSC_Product_Slider_Carousel/includes
 */

/**
 * The file that defines the Custom Post Type of the plugin.
 *
 * @since      1.0.0
 * @package    GPSC_Product_Slider_Carousel
 * @subpackage GPSC_Product_Slider_Carousel/includes
 * @author     Pluginic
 */
class GPSC_Product_Slider_Carousel_CPT {

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
	 * Settings page ID for post-to-card settings.
	 */
	const PAGE_ID = 'gpsc_slider_carousel';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Custom Post Type of the Plugin.
	 *
	 * @since    1.0.0
	 */
	public function gpsc_custom_post_type() {

		$labels = apply_filters(
			self::PAGE_ID . '_post_type_labels',
			array(
				'name'               => esc_html_x( 'Manage Sliders', 'gpsc-product-slider-carousel' ),
				'singular_name'      => esc_html_x( 'Sliders', 'gpsc-product-slider-carousel' ),
				'add_new'            => esc_html__( 'Add New', 'gpsc-product-slider-carousel' ),
				'add_new_item'       => esc_html__( 'Add New Gallery', 'gpsc-product-slider-carousel' ),
				'edit_item'          => esc_html__( 'Edit Sliders', 'gpsc-product-slider-carousel' ),
				'new_item'           => esc_html__( 'New Sliders', 'gpsc-product-slider-carousel' ),
				'view_item'          => esc_html__( 'View  Sliders', 'gpsc-product-slider-carousel' ),
				'search_items'       => esc_html__( 'Search Sliders', 'gpsc-product-slider-carousel' ),
				'not_found'          => esc_html__( 'No Gallery found.', 'gpsc-product-slider-carousel' ),
				'not_found_in_trash' => esc_html__( 'No Gallery found in trash.', 'gpsc-product-slider-carousel' ),
				'parent_item_colon'  => esc_html__( 'Parent Item:', 'gpsc-product-slider-carousel' ),
				'menu_name'          => esc_html__( 'Product Slider', 'gpsc-product-slider-carousel' ),
				'all_items'          => esc_html__( 'Manage Sliders', 'gpsc-product-slider-carousel' ),
			)
		);

		$args = apply_filters(
			self::PAGE_ID . '_post_type_args',
			array(
				'labels'              => $labels,
				'public'              => false,
				'hierarchical'        => false,
				'exclude_from_search' => true,
				'show_ui'             => true,
				'show_in_admin_bar'   => false,
				'menu_position'       => apply_filters( self::PAGE_ID . '_menu_position', 56 ),
				'menu_icon'           => 'dashicons-cart',
				'rewrite'             => false,
				'query_var'           => false,
				'imported'            => true,
				'supports'            => array( 'title' ),
			)
		);
		register_post_type( self::PAGE_ID, $args );

	}

	/**
	 * Change Sliders updated messages.
	 *
	 * @param string $messages The Update messages.
	 * @return statement
	 */
	public function wpps_updated_messages( $messages ) {
		global $post, $post_ID;
		$messages[ self::PAGE_ID ] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => sprintf( __( 'Sliders updated.', 'gpsc-product-slider-carousel' ) ),
			2  => '',
			3  => '',
			4  => __( 'updated.', 'gpsc-product-slider-carousel' ),
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Sliders restored to revision from %s', 'gpsc-product-slider-carousel' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => sprintf( __( 'Sliders published.', 'gpsc-product-slider-carousel' ) ),
			7  => __( 'Sliders saved.', 'gpsc-product-slider-carousel' ),
			8  => sprintf( __( 'Sliders submitted.', 'gpsc-product-slider-carousel' ) ),
			9  => sprintf( __( 'Sliders scheduled for: <strong>%1$s</strong>.', 'gpsc-product-slider-carousel' ), date_i18n( __( 'M j, Y @ G:i', 'gpsc-product-slider-carousel' ), strtotime( $post->post_date ) ) ),
			10 => sprintf( __( 'Sliders draft updated.', 'gpsc-product-slider-carousel' ) ),
		);
		return $messages;
	}

	/**
	 * Admin help page
	 *
	 * @since    2.0.0
	 */
	public function gpsc_help_admin_submenu() {
		add_submenu_page(
			'edit.php?post_type=' . self::PAGE_ID,
			__( 'Help', 'post-to-card' ),
			__( 'Help', 'post-to-card' ),
			'manage_options',
			'gpsc_help_page',
			array( $this, 'ptc_help_callback' )
		);
	}

	/**
	 * Safe Welcome Page Redirect.
	 *
	 * Safe welcome page redirect which happens only
	 * once and if the site is not a network or MU.
	 *
	 * @since 1.0.0
	 */
	public function gpsc_safe_welcome_redirect() {

		// Bail if no activation redirect transient is present. (if ! true).
		if ( ! get_transient( '_gpsc_safe_redirect' ) ) {
			return;
		}

		// Delete the redirect transient.
		delete_transient( '_gpsc_safe_redirect' );

		// Bail if activating from network or bulk sites.
		if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
			return;
		}

		// Redirects to a specific Page.
		wp_safe_redirect(
			add_query_arg(
				array(
					'post_type' => self::PAGE_ID,
					'page'      => 'gpsc_help_page',
				),
				admin_url( 'edit.php' )
			)
		);

	}

	/**
	 * Admin help callback function
	 *
	 * @since    1.0.0
	 */
	public function ptc_help_callback() {

		include_once GPSC_PRODUCT_SLIDER_CAROUSEL_DIR_PATH_FILE . '/admin/partials/gpsc-product-slider-carousel-admin-display.php';
	}

	/**
	 * Bottom review notice.
	 *
	 * @param string $text The review notice.
	 * @return string
	 */
	public function gpsc_review_text( $text ) {

		$screen = get_current_screen();
		if ( self::PAGE_ID === get_post_type() || ( self::PAGE_ID . '_page_gpsc_help_page' === $screen->id ) ) {

			$url  = 'https://wordpress.org/plugins/product-slider-carousel/reviews/?filter=5#new-post';
			$text = sprintf( __( 'SHOW YOUR LOVE 💕 LEAVE A REVIEW HERE → <a href="%s" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a>', 'gpsc-product-slider-carousel' ), $url );
		}

		return $text;
	}

	/**
	 * Add action links.
	 *
	 * @param Array $actions Get all action links.
	 * @param Sting $plugin_file Get all plugin file paths.
	 * @return Array
	 */
	public function gpsc_add_action_plugin( $actions, $plugin_file ) {

		static $plugin;

		if ( ! isset( $plugin ) ) {

			$plugin = GPSC_PRODUCT_SLIDER_CAROUSEL_BASE_FILE;
		}

		if ( $plugin == $plugin_file ) {

			$site_link = array( 'support' => '<a href="https://pluginic.com/support/?ref=100" target="_blank">Support</a>' );
			$settings  = array( 'settings' => '<a href="' . esc_url( get_admin_url() . 'post-new.php?post_type=gpsc_slider_carousel' ) . '">' . __( 'Get Started', 'General' ) . '</a>' );

			// Add link before Deactivate.
			$actions = array_merge( $site_link, $actions );
			$actions = array_merge( $settings, $actions );

			// Add link after Deactivate.
			$actions[] = '<a href="#">' . __( '<svg style="width: 14px;height: 14px;margin-bottom: -2px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 36"><path fill="#4caf50" d="M35 19c0-2.062-.367-4.039-1.04-5.868-.46 5.389-3.333 8.157-6.335 6.868-2.812-1.208-.917-5.917-.777-8.164.236-3.809-.012-8.169-6.931-11.794 2.875 5.5.333 8.917-2.333 9.125-2.958.231-5.667-2.542-4.667-7.042-3.238 2.386-3.332 6.402-2.333 9 1.042 2.708-.042 4.958-2.583 5.208-2.84.28-4.418-3.041-2.963-8.333C2.52 10.965 1 14.805 1 19c0 9.389 7.611 17 17 17s17-7.611 17-17z"/><path fill="#cddc39" d="M28.394 23.999c.148 3.084-2.561 4.293-4.019 3.709-2.106-.843-1.541-2.291-2.083-5.291s-2.625-5.083-5.708-6c2.25 6.333-1.247 8.667-3.08 9.084-1.872.426-3.753-.001-3.968-4.007C7.352 23.668 6 26.676 6 30c0 .368.023.73.055 1.09C9.125 34.124 13.342 36 18 36s8.875-1.876 11.945-4.91c.032-.36.055-.722.055-1.09 0-2.187-.584-4.236-1.606-6.001z"/></svg><span style="font-weight: bold;color: #4caf50;"> Go Pro</span>', 'General' ) . '</a>';
		}

		return $actions;
	}

}
