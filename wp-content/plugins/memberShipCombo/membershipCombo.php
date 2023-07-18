<?php
/**
 * Plugin Name:  Memberships Combo
 * Plugin URI:
 * Description: The customs member management and membership subscriptions plugin for WordPress.
 * Version: 0.2
 * Author: Softsuave
 * Author URI: 
 * Text Domain: Memberships-Combo
 * Domain Path:
 */
/**
 * Copyright 2011-2023	Stranger Studios
 * (email : info@paidmembershipspro.com)
 * GPLv2 Full license details in license.txt
 */


//  define( 'MEMBERSHIP_DIR', __FILE__ );
 define( 'MEMBERSHIP_DIR', plugin_dir_url( __FILE__ ));

 function custom_hello_world_shortcode($atts) {
    return 'Hello World!';
}

// Register the shortcode
add_shortcode('hello-world', 'custom_hello_world_shortcode');

include 'custom-plugin-page.php';