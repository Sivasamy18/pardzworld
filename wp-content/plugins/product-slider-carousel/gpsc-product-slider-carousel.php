<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://pluginic.com
 * @since             1.0.0
 * @package           GPSC_Product_Slider_Carousel
 *
 * @wordpress-plugin
 * Plugin Name:       Product Slider Carousel
 * Plugin URI:        https://pluginic.com/plugins/product-slider-carousel/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           2.1.0
 * Author:            PLUGINIC
 * Author URI:        https://www.pluginic.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       gpsc-product-slider-carousel
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define global constants.
 */
$wpgpscyg_plugin_data = get_file_data(
	__FILE__,
	array(
		'version' => 'Version',
	)
);
define( 'GPSC_PRODUCT_SLIDER_CAROUSEL_VERSION', $wpgpscyg_plugin_data['version'] );
define( 'GPSC_PRODUCT_SLIDER_CAROUSEL_BASE_FILE', plugin_basename( __FILE__ ) );
define( 'GPSC_PRODUCT_SLIDER_CAROUSEL_DIR_PATH_FILE', plugin_dir_path( __FILE__ ) );
define( 'GPSC_PRODUCT_SLIDER_CAROUSEL_DIR_URL_FILE', plugin_dir_url( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-gpsc-product-slider-carousel-activator.php
 */
function activate_gpsc_product_slider_carousel() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-gpsc-product-slider-carousel-activator.php';
	GPSC_Product_Slider_Carousel_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-gpsc-product-slider-carousel-deactivator.php
 */
function deactivate_gpsc_product_slider_carousel() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-gpsc-product-slider-carousel-deactivator.php';
	GPSC_Product_Slider_Carousel_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_gpsc_product_slider_carousel' );
register_deactivation_hook( __FILE__, 'deactivate_gpsc_product_slider_carousel' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-gpsc-product-slider-carousel.php';

/**
 * WPGPSC Framework.
 */
require_once plugin_dir_path( __FILE__ ) . 'admin/wpgpsc-framework/classes/setup.class.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/wpgpsc-framework/metabox/init.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/wpgpsc-framework/metabox/general.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/wpgpsc-framework/metabox/controls.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/wpgpsc-framework/metabox/typography.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/wpgpsc-framework/metabox/shortcode.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/wpgpsc-framework/metabox/product.php';
// Settings.
require_once plugin_dir_path( __FILE__ ) . 'admin/wpgpsc-framework/option/settings-init.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/wpgpsc-framework/option/settings-add-to-cart.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/wpgpsc-framework/option/settings-related.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/wpgpsc-framework/option/settings-upsell.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/wpgpsc-framework/option/settings-cross.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/wpgpsc-framework/option/settings-gallery.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/wpgpsc-framework/option/settings-typography.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/wpgpsc-framework/option/settings-code.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/wpgpsc-framework/option/settings-custom-fields.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_gpsc_product_slider_carousel() {

	$plugin = new GPSC_Product_Slider_Carousel();
	$plugin->run();

}
run_gpsc_product_slider_carousel();
