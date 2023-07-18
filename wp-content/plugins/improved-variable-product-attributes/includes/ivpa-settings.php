<?php

	class XforWC_Improved_Options_Settings {

		public static $plugin;

		public static function init() {

			self::$plugin = array(
				'name' => esc_html__( 'Improved Options for WooCommerce', 'improved-variable-product-attributes' ),
				'xforwc' => esc_html__( 'Product Options', 'improved-variable-product-attributes' ),
				'slug' => 'product-options',
				'label' => 'improved_options',
				'image' => ImprovedOptions()->plugin_url() . '/assets/images/improved-product-options-for-woocommerce-elements.png',
				'path' => 'improved-variable-product-attributes/improved-variable-product-attributes',
				'version' => XforWC_Improved_Options::$version,
			);

			if ( isset($_GET['page'], $_GET['tab']) && ($_GET['page'] == 'wc-settings' ) && $_GET['tab'] == 'improved_options' ) {
				add_filter( 'svx_plugins_settings', array( 'XforWC_Improved_Options_Settings', 'get_settings' ), 50 );
				add_action( 'admin_enqueue_scripts', __CLASS__ . '::ivpa_settings_scripts', 9 );
			}
			if ( isset($_GET['page']) && $_GET['page'] == 'xforwoocommerce' ) {
				add_action( 'admin_enqueue_scripts', __CLASS__ . '::ivpa_settings_scripts', 9 );
			}

			if ( function_exists( 'XforWC' ) ) {
				add_filter( 'xforwc_settings', array( 'XforWC_Improved_Options_Settings', 'xforwc' ), 9999999111 );
				add_filter( 'xforwc_svx_get_product_options', array( 'XforWC_Improved_Options_Settings', '_get_settings_xforwc' ) );
			}

			add_filter( 'svx_plugins', array( 'XforWC_Improved_Options_Settings', 'add_plugin' ), 0 );

			add_action( 'svx_ajax_saved_settings_improved_options', __CLASS__ . '::delete_cache' );
			
			add_action( 'save_post', __CLASS__ . '::delete_post_cache', 10, 3 );

		}

		public static function xforwc( $settings ) {
			$settings['plugins'][] = self::$plugin;

			return $settings;
		}

		public static function add_plugin( $plugins ) {
			$plugins[self::$plugin['label']] = array(
				'slug' => self::$plugin['label'],
				'name' => self::$plugin['xforwc']
			);

			return $plugins;
		}

		public static function _get_settings_xforwc() {
			$settings = self::get_settings( array() );
			return $settings[self::$plugin['label']];
		}

		public static function ivpa_settings_scripts( $settings_tabs ) {
			wp_enqueue_script( 'ivpa-admin', ImprovedOptions()->plugin_url() . '/assets/js/admin.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-sortable' ), XforWC_Improved_Options::$version, true );
		}

		public static function get_settings() {

			$attributes = get_object_taxonomies( 'product' );
			$ready_attributes = array();
			if ( !empty( $attributes ) ) {
				foreach( $attributes as $k ) {
					if ( substr($k, 0, 3) == 'pa_' ) {
						$ready_attributes[$k] =  wc_attribute_label( $k );
					}
				}
			}

			include_once( 'class-themes.php' );
			$install = XforWC_Product_Options_Themes::get_theme();

			$plugins[self::$plugin['label']] = array(
				'slug' => self::$plugin['label'],
				'name' => esc_html( function_exists( 'XforWC' ) ? self::$plugin['xforwc'] : self::$plugin['name'] ),
				'desc' => esc_html( function_exists( 'XforWC' ) ? self::$plugin['name'] . ' v' . self::$plugin['version'] : esc_html__( 'Settings page for', 'improved-variable-product-attributes' ) . ' ' . self::$plugin['name'] ),
				'link' => esc_url( 'https://xforwoocommerce.com/store/product-options/' ),
				'ref' => array(
					'name' => esc_html__( 'Visit XforWooCommerce.com', 'improved-variable-product-attributes' ),
					'url' => 'https://xforwoocommerce.com'
				),
				'doc' => array(
					'name' => esc_html__( 'Get help', 'improved-variable-product-attributes' ),
					'url' => 'https://help.xforwoocommerce.com'
				),
				'sections' => array(
					'dashboard' => array(
						'name' => esc_html__( 'Dashboard', 'improved-variable-product-attributes' ),
						'desc' => esc_html__( 'Dashboard Overview', 'improved-variable-product-attributes' ),
					),
					'options' => array(
						'name' => esc_html__( 'Product Options', 'improved-variable-product-attributes' ),
						'desc' => esc_html__( 'Product Options', 'improved-variable-product-attributes' ),
					),
					'general' => array(
						'name' => esc_html__( 'General', 'improved-variable-product-attributes' ),
						'desc' => esc_html__( 'General Options', 'improved-variable-product-attributes' ),
					),
					'product' => array(
						'name' => esc_html__( 'Product Page', 'improved-variable-product-attributes' ),
						'desc' => esc_html__( 'Product Page Options', 'improved-variable-product-attributes' ),
					),
					'shop' => array(
						'name' => esc_html__( 'Shop/Archives', 'improved-variable-product-attributes' ),
						'desc' => esc_html__( 'Shop/Archives Options', 'improved-variable-product-attributes' ),
					),
					'installation' => array(
						'name' => esc_html__( 'Installation', 'improved-variable-product-attributes' ),
						'desc' => esc_html__( 'Installation Options', 'improved-variable-product-attributes' ),
					),
				),
				'extras' => array(
					'product_attributes' => $ready_attributes
				),
				'settings' => array(

					'wcmn_dashboard' => array(
						'type' => 'html',
						'id' => 'wcmn_dashboard',
						'desc' => '
						<img src="' . ImprovedOptions()->plugin_url() . '/assets/images/improved-product-options-for-woocommerce-shop.png" class="svx-dashboard-image" />
						<h3><span class="dashicons dashicons-store"></span> XforWooCommerce</h3>
						<p>' . esc_html__( 'Visit XforWooCommerce.com store, demos and knowledge base.', 'improved-variable-product-attributes' ) . '</p>
						<p><a href="https://xforwoocommerce.com" class="xforwc-button-primary x-color" target="_blank">XforWooCommerce.com</a></p>

						<br /><hr />

						<h3><span class="dashicons dashicons-admin-tools"></span> ' . esc_html__( 'Help Center', 'improved-variable-product-attributes' ) . '</h3>
						<p>' . esc_html__( 'Need support? Visit the Help Center.', 'improved-variable-product-attributes' ) . '</p>
						<p><a href="https://help.xforwoocommerce.com" class="xforwc-button-primary red" target="_blank">XforWooCommerce.com HELP</a></p>
						
						<br /><hr />

						<h3><span class="dashicons dashicons-update"></span> ' . esc_html__( 'Automatic Updates', 'improved-variable-product-attributes' ) . '</h3>
						<p>' . esc_html__( 'Get automatic updates, by downloading and installing the Envato Market plugin.', 'improved-variable-product-attributes' ) . '</p>
						<p><a href="https://envato.com/market-plugin/" class="svx-button" target="_blank">Envato Market Plugin</a></p>
						
						<br />',
						'section' => 'dashboard',
					),

					'wcmn_utility' => array(
						'name' => esc_html__( 'Plugin Options', 'improved-variable-product-attributes' ),
						'type' => 'utility',
						'id' => 'wcmn_utility',
						'desc' => esc_html__( 'Quick export/import, backup and restore, or just reset your optons here', 'improved-variable-product-attributes' ),
						'section' => 'dashboard',
					),

					'wc_ivpa_attribute_customization' => array(
						'name' => esc_html__( 'Options Manager', 'improved-variable-product-attributes' ),
						'type' => 'list-select',
						'desc' => esc_html__( 'Use the manager to customize your attributes or add custom product options!', 'improved-variable-product-attributes' ),
						'id'   => 'wc_ivpa_attribute_customization',
						'default' => array(),
						'autoload' => false,
						'section' => 'options',
						//'title' => esc_html__( 'Option', 'improved-variable-product-attributes' ),
						'supports' => array( 'customizer' ),
						'options' => 'list',
						'translate' => true,
						'selects' => array(
							'ivpa_attr' => esc_html__( 'Attribute Swatch', 'improved-variable-product-attributes' ),
							'ivpa_custom' => esc_html__( 'Custom Option', 'improved-variable-product-attributes' )
						),
						'settings' => array(
							'ivpa_attr' => array(
								'taxonomy' => array(
									'name' => esc_html__( 'Select Attribute', 'improved-variable-product-attributes' ),
									'type' => 'select',
									'desc' => esc_html__( 'Select attribute to customize', 'improved-variable-product-attributes' ),
									'id'   => 'taxonomy',
									'options' => 'ajax:product_attributes:has_none',
									'default' => '',
									'class' => 'svx-update-list-title'
								),
								'name' => array(
									'name' => esc_html__( 'Name', 'improved-variable-product-attributes' ),
									'type' => 'text',
									'desc' => esc_html__( 'Use alternative title', 'improved-variable-product-attributes' ),
									'id'   => 'name',
									'default' => '',
								),
								'ivpa_desc' => array(
									'name' => esc_html__( 'Description', 'improved-variable-product-attributes' ),
									'type' => 'textarea',
									'desc' => esc_html__( 'Enter description', 'improved-variable-product-attributes' ),
									'id'   => 'ivpa_desc',
									'default' => ''
								),
								'ivpa_archive_include' => array(
									'name' => esc_html__( 'Shop Display Mode', 'improved-variable-product-attributes' ),
									'type' => 'checkbox',
									'desc' => esc_html__( 'Show on Shop Pages (Works with Shop Display Mode set to Show Available Options Only)', 'improved-variable-product-attributes' ),
									'id'   => 'ivpa_archive_include',
									'default' => 'yes'
								),
								'ivpa_svariation' => array(
									'name' => esc_html__( 'Attribute is Selectable', 'improved-variable-product-attributes' ),
									'type' => 'checkbox',
									'desc' => esc_html__( 'This option is in use only with simple products and when General &gt; Attribute Selection Support option is set to All Products', 'improved-variable-product-attributes' ),
									'id'   => 'ivpa_svariation',
									'default' => false
								),
								'ivpa_required' => array(
									'name' => esc_html__( 'Required', 'improved-variable-product-attributes' ),
									'type' => 'checkbox',
									'desc' => esc_html__( 'This option is required (Only works on simple products, variable product attributes are required by default)', 'improved-variable-product-attributes' ),
									'id'   => 'ivpa_required',
									'default' => 'no'
								),
							),
							'ivpa_custom' => array(
								'name' => array(
									'name' => esc_html__( 'Name', 'improved-variable-product-attributes' ),
									'type' => 'text',
									'desc' => esc_html__( 'Set option name', 'improved-variable-product-attributes' ),
									'id'   => 'name',
									'default' => ''
								),
								'ivpa_desc' => array(
									'name' => esc_html__( 'Description', 'improved-variable-product-attributes' ),
									'type' => 'textarea',
									'desc' => esc_html__( 'Enter description for current option', 'improved-variable-product-attributes' ),
									'id'   => 'ivpa_desc',
									'default' => ''
								),
								'ivpa_addprice' => array(
									'name' => esc_html__( 'Add Price', 'improved-variable-product-attributes' ),
									'type' => 'text',
									'desc' => esc_html__( 'Add-on price if option is used by customer', 'improved-variable-product-attributes' ),
									'id'   => 'ivpa_addprice',
									'default' => ''
								),
								'ivpa_condition' => array(
									'name' => esc_html__( 'Condition', 'improved-variable-product-attributes' ),
									'type' => 'text',
									'desc' => esc_html__( 'Enter condition | Sample: &rarr; ', 'improved-variable-product-attributes' ) . '<code>pa_color:red</code>',
									'id'   => 'ivpa_condition',
									'default' => ''
								),
								'ivpa_limit_type' => array(
									'name' => esc_html__( 'Limit to Product Type', 'improved-variable-product-attributes' ),
									'type' => 'text',
									'desc' => esc_html__( 'Enter product types separated by | Sample: &rarr; ', 'improved-variable-product-attributes' ) . '<code>simple|variable</code>',
									'id'   => 'ivpa_limit_type',
									'default' => ''
								),
								'ivpa_limit_category' => array(
									'name' => esc_html__( 'Limit to Product Category', 'improved-variable-product-attributes' ),
									'type' => 'text',
									'desc' => esc_html__( 'Enter product category IDs separated by | Sample: &rarr; ', 'improved-variable-product-attributes' ) . '<code>7|55</code>',
									'id'   => 'ivpa_limit_category',
									'default' => ''
								),
								'ivpa_limit_product' => array(
									'name' => esc_html__( 'Limit to Products', 'improved-variable-product-attributes' ),
									'type' => 'text',
									'desc' => esc_html__( 'Enter product IDs separated by | Sample: &rarr; ', 'improved-variable-product-attributes' ) . '<code>155|222|333</code>',
									'id'   => 'ivpa_limit_product',
									'default' => ''
								),
								'ivpa_multiselect' => array(
									'name' => esc_html__( 'Multiselect', 'improved-variable-product-attributes' ),
									'type' => 'checkbox',
									'desc' => esc_html__( 'Use multi select on this option', 'improved-variable-product-attributes' ),
									'id'   => 'ivpa_multiselect',
									'default' => 'yes'
								),
								'ivpa_archive_include' => array(
									'name' => esc_html__( 'Shop Display Mode', 'improved-variable-product-attributes' ),
									'type' => 'checkbox',
									'desc' => esc_html__( 'Show on Shop Pages (Works with Shop Display Mode set to Show Available Options Only)', 'improved-variable-product-attributes' ),
									'id'   => 'ivpa_archive_include',
									'default' => 'yes'
								),
								'ivpa_required' => array(
									'name' => esc_html__( 'Required', 'improved-variable-product-attributes' ),
									'type' => 'checkbox',
									'desc' => esc_html__( 'This option is required', 'improved-variable-product-attributes' ),
									'id'   => 'ivpa_required',
									'default' => 'no'
								),
							)
						)
					),

					'wc_settings_ivpa_single_enable' => array(
						'name' => esc_html__( 'Use Plugin In Product Pages', 'improved-variable-product-attributes' ),
						'type' => 'checkbox',
						'desc' => esc_html__( 'Check this option to use the plugin selectors in Single Product Pages.', 'improved-variable-product-attributes' ),
						'id'   => 'wc_settings_ivpa_single_enable',
						'default' => 'yes',
						'autoload' => false,
						'section' => 'product'
					),
					'wc_settings_ivpa_archive_enable' => array(
						'name' => esc_html__( 'Use Plugin In Shop', 'improved-variable-product-attributes' ),
						'type' => 'checkbox',
						'desc' => esc_html__( 'Check this option to use the plugin styled selectors in Shop Pages.', 'improved-variable-product-attributes' ),
						'id'   => 'wc_settings_ivpa_archive_enable',
						'default' => 'no',
						'autoload' => false,
						'section' => 'shop'
					),

					'wc_settings_ivpa_single_selectbox' => array(
						'name' => esc_html__( 'Hide WooCommerce Select Boxes', 'improved-variable-product-attributes' ),
						'type' => 'checkbox',
						'desc' => esc_html__( 'Check this option to hide default WooCommerce select boxes in Product Pages.', 'improved-variable-product-attributes' ),
						'id'   => 'wc_settings_ivpa_single_selectbox',
						'default' => 'yes',
						'autoload' => false,
						'section' => 'product'
					),
					'wc_settings_ivpa_single_addtocart' => array(
						'name' => esc_html__( 'Hide Add To Cart Before Selection', 'improved-variable-product-attributes' ),
						'type' => 'checkbox',
						'desc' => esc_html__( 'Check this option to hide the Add To Cart button in Product Pages before the selection is made.', 'improved-variable-product-attributes' ),
						'id'   => 'wc_settings_ivpa_single_addtocart',
						'default' => 'yes',
						'autoload' => false,
						'section' => 'product'
					),
					'wc_settings_ivpa_single_desc' => array(
						'name' => esc_html__( 'Select Descriptions Position', 'improved-variable-product-attributes' ),
						'type' => 'select',
						'desc' => esc_html__( 'Select where to show descriptions.', 'improved-variable-product-attributes' ),
						'id'   => 'wc_settings_ivpa_single_desc',
						'options' => array(
							'ivpa_aftertitle' => esc_html__( 'After Title', 'improved-variable-product-attributes' ),
							'ivpa_afterattribute' => esc_html__( 'After Attributes', 'improved-variable-product-attributes' )
						),
						'default' => 'ivpa_afterattribute',
						'autoload' => false,
						'section' => 'product'
					),
					'wc_settings_ivpa_single_ajax' => array(
						'name' => esc_html__( 'AJAX Add To Cart', 'improved-variable-product-attributes' ),
						'type' => 'checkbox',
						'desc' => esc_html__( 'Check this option to enable AJAX add to cart in Product Pages.', 'improved-variable-product-attributes' ),
						'id'   => 'wc_settings_ivpa_single_ajax',
						'default' => 'no',
						'autoload' => false,
						'section' => 'product'
					),
					'wc_settings_ivpa_single_image' => array(
						'name' => esc_html__( 'Use Advanced Image Switcher', 'improved-variable-product-attributes' ),
						'type' => 'checkbox',
						'desc' => esc_html__( 'Check this option to enable advanced image switcher in Single Product Pages. This option enables image switch when a single attribute is selected.', 'improved-variable-product-attributes' ),
						'id'   => 'wc_settings_ivpa_single_image',
						'default' => 'no',
						'autoload' => false,
						'section' => 'product'
					),

					'wc_settings_ivpa_single_prices' => array(
						'name' => esc_html__( 'Price Total', 'improved-variable-product-attributes' ),
						'type' => 'select',
						'desc' => esc_html__( 'Select price to use for product options cost total.', 'improved-variable-product-attributes' ),
						'id'   => 'wc_settings_ivpa_single_prices',
						'options' => array(
							'disable' => esc_html__( 'Disable', 'improved-variable-product-attributes' ),
							'summary' => esc_html__( 'Use prices from product summary element', 'improved-variable-product-attributes' ),
							'form' => esc_html__( 'Use only variable price inside product summary element (Only Variable Products have this)', 'improved-variable-product-attributes' ),
							'plugin' => esc_html__( 'Add product price to top of product option', 'improved-variable-product-attributes' ),
							'plugin-bottom' => esc_html__( 'Add product price to bottom of product options', 'improved-variable-product-attributes' ),
						),
						'default' => 'summary',
						'autoload' => false,
						'section' => 'product'
					),

					'wc_settings_ivpa_archive_quantity' => array(
						'name' => esc_html__( 'Show Quantities', 'improved-variable-product-attributes' ),
						'type' => 'checkbox',
						'desc' => esc_html__( 'Check this option to enable product quantity in Shop.', 'improved-variable-product-attributes' ),
						'id'   => 'wc_settings_ivpa_archive_quantity',
						'default' => 'no',
						'autoload' => false,
						'section' => 'shop'
					),
					'wc_settings_ivpa_archive_mode' => array(
						'name' => esc_html__( 'Shop Display Mode', 'improved-variable-product-attributes' ),
						'type' => 'select',
						'desc' => esc_html__( 'Select how to show the options in Shop Pages.', 'improved-variable-product-attributes' ),
						'id'   => 'wc_settings_ivpa_archive_mode',
						'options' => array(
							'ivpa_showonly' => esc_html__( 'Only Show Available Options', 'improved-variable-product-attributes' ),
							'ivpa_selection' => esc_html__( 'Enable Selection and Add to Cart', 'improved-variable-product-attributes' )
						),
						'default' => 'ivpa_selection',
						'autoload' => false,
						'section' => 'shop'
					),
					'wc_settings_ivpa_archive_align' => array(
						'name' => esc_html__( 'Options Alignment', 'improved-variable-product-attributes' ),
						'type' => 'select',
						'desc' => esc_html__( 'Select options alignment in Shop Pages.', 'improved-variable-product-attributes' ),
						'id'   => 'wc_settings_ivpa_archive_align',
						'options' => array(
							'ivpa_align_left' => esc_html__( 'Left', 'improved-variable-product-attributes' ),
							'ivpa_align_right' => esc_html__( 'Right', 'improved-variable-product-attributes' ),
							'ivpa_align_center' => esc_html__( 'Center', 'improved-variable-product-attributes' )
						),
						'default' => 'ivpa_align_left',
						'autoload' => false,
						'section' => 'shop'
					),

					'wc_settings_ivpa_archive_prices' => array(
						'name' => esc_html__( 'Price Total', 'improved-variable-product-attributes' ),
						'type' => 'select',
						'desc' => esc_html__( 'Select price to use for product options cost total.', 'improved-variable-product-attributes' ),
						'id'   => 'wc_settings_ivpa_archive_prices',
						'options' => array(
							'disable' => esc_html__( 'Disable', 'improved-variable-product-attributes' ),
							'product' => esc_html__( 'Use product price from shop element', 'improved-variable-product-attributes' ),
							'plugin' => esc_html__( 'Add product price to top of product options', 'improved-variable-product-attributes' ),
							'plugin-bottom' => esc_html__( 'Add product price to bottom of product options', 'improved-variable-product-attributes' ),
						),
						'default' => 'product',
						'autoload' => false,
						'section' => 'shop'
					),

					'wc_settings_ivpa_automatic' => array(
						'name' => esc_html__( 'Automatic Installation', 'improved-variable-product-attributes' ),
						'type' => 'checkbox',
						'desc' => esc_html__( 'Check this option to use automatic installation.', 'improved-variable-product-attributes' ) . '<strong>' . ( isset( $install['recognized'] ) ? esc_html__( 'Theme supported! Installation is set for', 'improved-variable-product-attributes' ) . ' ' . $install['name'] . '.' : esc_html__( 'Theme not found in database. Using default settings.', 'improved-variable-product-attributes' ) ) . '</strong>',
						'id'   => 'wc_settings_ivpa_automatic',
						'default' => 'yes',
						'autoload' => false,
						'section' => 'installation',
						'class' => 'svx-refresh-active-tab'
					),

					'wc_settings_ivpa_single_action' => array(
						'name' => esc_html__( 'Product Page Hook', 'improved-variable-product-attributes' ),
						'type' => 'text',
						'desc' => esc_html__( 'Product Page installation hook. Enter action name in following format action_name:priority.', 'improved-variable-product-attributes' ) . ' ' . esc_html__( 'Default:', 'improved-variable-product-attributes' ) . ' ' . ( isset( $install['product_hook'] ) ? esc_html( $install['product_hook'] ) : 'woocommerce_before_add_to_cart_button' ),
						'id'   => 'wc_settings_ivpa_single_action',
						'default' => '',
						'autoload' => true,
						'section' => 'installation',
						'condition' => 'wc_settings_ivpa_automatic:no',
					),
					'wc_settings_ivpa_archive_action' => array(
						'name' => esc_html__( 'Shop Hook', 'improved-variable-product-attributes' ),
						'type' => 'text',
						'desc' => esc_html__( 'Shop installation hook. Enter action name in following format action_name:priority.', 'improved-variable-product-attributes' ) . ' ' . esc_html__( 'Default:', 'improved-variable-product-attributes' ) . ' ' . ( isset( $install['shop_hook'] ) ? esc_html( $install['shop_hook'] ) : 'woocommerce_after_shop_loop_item:999' ),
						'id'   => 'wc_settings_ivpa_archive_action',
						'default' => '',
						'autoload' => true,
						'section' => 'installation',
						'condition' => 'wc_settings_ivpa_automatic:no',
					),

					'wc_settings_ivpa_archive_selector' => array(
						'name' => esc_html__( 'Product', 'improved-variable-product-attributes' ),
						'type' => 'text',
						'desc' => esc_html__( 'Enter product in Shop jQuery selector. Currently set to:', 'improved-variable-product-attributes' ) . ' ' . ( isset( $install['product'] ) ? esc_html( $install['product'] ) : '.type-product' ),
						'id'   => 'wc_settings_ivpa_archive_selector',
						'default' => '',
						'autoload' => false,
						'section' => 'installation',
						'condition' => 'wc_settings_ivpa_automatic:no',
					),

					'wc_settings_ivpa_single_selector' => array(
						'name' => esc_html__( ' Product Images', 'improved-variable-product-attributes' ),
						'type' => 'text',
						'desc' => esc_html__( 'Enter product page images jQuery selector. Currently set to:', 'improved-variable-product-attributes' ) . ' ' . ( isset( $install['product_images'] ) ? esc_html( $install['product_images'] ) : '.images' ),
						'id'   => 'wc_settings_ivpa_single_selector',
						'default' => '',
						'autoload' => false,
						'section' => 'installation',
						'condition' => 'wc_settings_ivpa_automatic:no',
					),

					'wc_settings_ivpa_single_summary' => array(
						'name' => esc_html__( ' Product Summary', 'improved-variable-product-attributes' ),
						'type' => 'text',
						'desc' => esc_html__( 'Enter product summary with prices jQuery selector. Currently set to:', 'improved-variable-product-attributes' ) . ' ' . ( isset( $install['product_summary'] ) ? esc_html( $install['product_summary'] ) : '.summary' ),
						'id'   => 'wc_settings_ivpa_single_summary',
						'default' => '',
						'autoload' => false,
						'section' => 'installation',
						'condition' => 'wc_settings_ivpa_automatic:no',
					),

					'wc_settings_ivpa_addcart_selector' => array(
						'name' => esc_html__( 'Add To Cart', 'improved-variable-product-attributes' ),
						'type' => 'text',
						'desc' => esc_html__( 'Enter add to cart in Shop jQuery selector. Currently set to:', 'improved-variable-product-attributes' ) . ' ' . ( isset( $install['add_to_cart'] ) ? esc_html( $install['add_to_cart'] ) : '.add_to_cart_button' ),
						'id'   => 'wc_settings_ivpa_addcart_selector',
						'default' => '',
						'autoload' => false,
						'section' => 'installation',
						'condition' => 'wc_settings_ivpa_automatic:no',
					),
					'wc_settings_ivpa_price_selector' => array(
						'name' => esc_html__( 'Price', 'improved-variable-product-attributes' ),
						'type' => 'text',
						'desc' => esc_html__( 'Enter price jQuery selector. Currently set to:', 'improved-variable-product-attributes' ) . ' ' . ( isset( $install['price'] ) ? esc_html( $install['price'] ) : '.price' ),
						'id'   => 'wc_settings_ivpa_price_selector',
						'default' => '',
						'autoload' => false,
						'section' => 'installation',
						'condition' => 'wc_settings_ivpa_automatic:no',
					),

					'wc_settings_ivpa_simple_support' => array(
						'name' => esc_html__( 'Options Support', 'improved-variable-product-attributes' ),
						'type' => 'select',
						'desc' => esc_html__( 'Select product types that will support product options.', 'improved-variable-product-attributes' ),
						'id'   => 'wc_settings_ivpa_simple_support',
						'options' => array(
							'none' => esc_html__( 'Variable Products', 'improved-variable-product-attributes' ),
							'full' => esc_html__( 'All Products (Simple Products)', 'improved-variable-product-attributes' )
						),
						'default' => 'none',
						'autoload' => false,
						'section' => 'general'
					),

					'wc_settings_ivpa_outofstock_mode' => array(
						'name' => esc_html__( 'Out Of Stock Display', 'improved-variable-product-attributes' ),
						'type' => 'select',
						'desc' => esc_html__( 'Select how the to display the Out of Stock options for variable products.', 'improved-variable-product-attributes' ),
						'id'   => 'wc_settings_ivpa_outofstock_mode',
						'options' => array(
							'default' => esc_html__( 'Shown but not clickable', 'improved-variable-product-attributes' ),
							'clickable' => esc_html__( 'Shown and clickable', 'improved-variable-product-attributes' ),
							'hidden' => esc_html__( 'Hidden from pages', 'improved-variable-product-attributes' )
						),
						'default' => 'default',
						'autoload' => false,
						'section' => 'general'
					),

					'wc_settings_ivpa_image_attributes' => array(
						'name' => esc_html__( 'Image Switching Attributes', 'improved-variable-product-attributes' ),
						'type' => 'multiselect',
						'desc' => esc_html__( 'Select attributes that will switch the product image. Available only if Advanced Image Switcher option is used.', 'improved-variable-product-attributes' ),
						'id'   => 'wc_settings_ivpa_image_attributes',
						'options' => 'ajax:product_attributes',
						'default' => '',
						'autoload' => false,
						'section' => 'general'
					),

					'wc_settings_ivpa_step_selection' => array(
						'name' => esc_html__( 'Step Selection', 'improved-variable-product-attributes' ),
						'type' => 'checkbox',
						'desc' => esc_html__( 'Check this option to use stepped selection.', 'improved-variable-product-attributes' ),
						'id'   => 'wc_settings_ivpa_step_selection',
						'default' => 'no',
						'autoload' => false,
						'section' => 'general'
					),
					'wc_settings_ivpa_disable_unclick' => array(
						'name' => esc_html__( 'Disable Option Deselection', 'improved-variable-product-attributes' ),
						'type' => 'checkbox',
						'desc' => esc_html__( 'Check this option to disallow option deselection/unchecking.', 'improved-variable-product-attributes' ),
						'id'   => 'wc_settings_ivpa_disable_unclick',
						'default' => 'no',
						'autoload' => false,
						'section' => 'general'
					),
					'wc_settings_ivpa_backorder_support' => array(
						'name' => esc_html__( 'Backorder Notifications', 'improved-variable-product-attributes' ),
						'type' => 'checkbox',
						'desc' => esc_html__( 'Check this option to enable and show backorder notifications.', 'improved-variable-product-attributes' ),
						'id'   => 'wc_settings_ivpa_backorder_support',
						'default' => 'no',
						'autoload' => false,
						'section' => 'general'
					),
					'wc_settings_ivpa_force_scripts' => array(
						'name' => esc_html__( 'Plugin Scripts', 'improved-variable-product-attributes' ),
						'type' => 'checkbox',
						'desc' => esc_html__( 'Check this option to load plugin scripts in all pages. This option fixes issues in Quick Views.', 'improved-variable-product-attributes' ),
						'id'   => 'wc_settings_ivpa_force_scripts',
						'default' => 'no',
						'autoload' => false,
						'section' => 'installation'
					),
					'wc_settings_ivpa_use_caching' => array(
						'name' => esc_html__( 'Use Caching', 'improved-variable-product-attributes' ),
						'type' => 'checkbox',
						'desc' => esc_html__( 'Check this option to use product caching for better performance.', 'improved-variable-product-attributes' ),
						'id'   => 'wc_settings_ivpa_use_caching',
						'default' => 'no',
						'autoload' => false,
						'section' => 'installation'
					),

				)
			);

			return SevenVX()->_do_options( $plugins, self::$plugin['label'] );
		}

		public static function delete_cache( $id = '' ) {
			global $wpdb;
			if ( empty( $id ) ) {
				$wpdb->query( "DELETE meta FROM {$wpdb->postmeta} meta WHERE meta.meta_key LIKE '_ivpa_cached_%';" );
			}
			else if ( is_numeric( $id ) ) {
				$wpdb->query( "DELETE meta FROM {$wpdb->postmeta} meta WHERE meta.post_id = {$id} AND meta.meta_key LIKE '_ivpa_cached_%';" );
			}
		}

		public static function delete_post_cache( $id, $post, $update ) {
			if ( SevenVXGet()->get_option( 'wc_settings_ivpa_use_caching', 'improved_options', 'no' ) == 'yes' ) {
				if ( $post->post_type != 'product' ) {
					return;
				}
				self::delete_cache( $id );
			}
		}

	}

	XforWC_Improved_Options_Settings::init();
