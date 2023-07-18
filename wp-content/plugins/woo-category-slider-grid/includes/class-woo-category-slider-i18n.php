<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://shapedplugin.com/
 * @since      1.1.0
 *
 * @package    Woo_Category_Slider
 * @subpackage Woo_Category_Slider/includes
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

/**
 * Woo_Category_Slider_i18n class
 */
class Woo_Category_Slider_i18n {
	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.1.0
	 */
	public function load_plugin_textdomain() {

		load_textdomain( 'woo-category-slider-grid', WP_LANG_DIR . '/woo-category-slider-grid/languages/woo-category-slider-grid-' . apply_filters( 'plugin_locale', get_locale(), 'woo-category-slider-grid' ) . '.mo' );
		load_plugin_textdomain( 'woo-category-slider-grid', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	}

}
