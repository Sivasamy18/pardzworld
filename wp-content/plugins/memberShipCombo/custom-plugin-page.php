<?php
// Ensure that this file is being accessed within the WordPress environment



if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( isset( $_GET['page'] ) ) {
	$plugin_page = wp_unslash( $_GET['page'] );
	$plugin_page = plugin_basename( $plugin_page );
}

// Create a function to render the custom page content
function custom_plugin_page_content() {

    global $wpdb;
    // Add your custom page content here
    echo '<div class="wrap">';
    echo '<h1 class="wp-heading-inline">Plugins</h1>
    <a href="'.site_url().'/wp-admin/admin.php?page=add-membership-page" class="page-title-action">Add New</a>
    <hr class="wp-header-end">';
    echo '<p>This is a custom page created by your plugin.</p>';
    include 'table/table.php';
    echo '</div>';
   ?>
   <script>
   console.log( <?php echo print_r($wpdb->get_row());?>);
   </script>
<?php
}

// Create a function to add the custom page to the WordPress admin menu
function add_custom_plugin_page() {
    add_menu_page(
        'Combo MemberShip Page',
        'Combo MemberShip',
        'manage_options',
        'member-ship-page',
        'custom_plugin_page_content',
        MEMBERSHIP_DIR .'/assets/Group.svg',
        25
    );
}
add_action( 'admin_menu', 'add_custom_plugin_page' );

// Create a function to render the custom page content
function add_memberShip_details() {
    // Add your custom page content here
    echo '<div class="wrap">';
    echo '<h1 class="wp-heading-inline">Add MemberShip</h1>
    <hr class="wp-header-end">';
    echo '<p>This is a custom page created by your plugin.</p>';
    include 'getInformation/getInformation.php';
    echo '</div>';
}


// Create a function to add the custom page to the WordPress admin menu
function add_custom_plugin_page_for_adding() {
    add_submenu_page(
        'member-ship-page',
        'Add MemberShip',
        'Add MemberShip',
        'manage_options',
        'add-membership-page',
        'add_memberShip_details',
    );
}
add_action( 'admin_menu', 'add_custom_plugin_page_for_adding' );