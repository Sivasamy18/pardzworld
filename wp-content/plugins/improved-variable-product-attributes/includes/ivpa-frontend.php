<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class XforWC_Improved_Options_Frontend {

	public static $dir;
	public static $path;
	public static $url_path;
	public static $settings;
	public static $version;

	public static function init() {
		$class = __CLASS__;
		new $class;
	}

	function __construct() {

		if ( !class_exists( 'Woocommerce' ) ) {
			return;
		}

		self::$version = XforWC_Improved_Options::$version;

		self::$dir = ImprovedOptions()->plugin_path();
		self::$path = ImprovedOptions()->plugin_path();
		self::$url_path = ImprovedOptions()->plugin_url();

		self::$settings['single_action'] = '';
		self::$settings['archive_action'] = '';

		self::$settings['wc_settings_ivpa_single_enable'] = SevenVXGet()->get_option( 'wc_settings_ivpa_single_enable', 'improved_options', 'yes' );
		self::$settings['wc_settings_ivpa_single_selectbox'] = SevenVXGet()->get_option( 'wc_settings_ivpa_single_selectbox', 'improved_options', 'yes' );
		self::$settings['wc_settings_ivpa_archive_enable'] = SevenVXGet()->get_option( 'wc_settings_ivpa_archive_enable', 'improved_options', 'no' );
		self::$settings['wc_settings_ivpa_archive_quantity'] = SevenVXGet()->get_option( 'wc_settings_ivpa_archive_quantity', 'improved_options', 'no' );
		self::$settings['wc_settings_ivpa_archive_mode'] = SevenVXGet()->get_option( 'wc_settings_ivpa_archive_mode', 'improved_options', 'ivpa_selection' );
		self::$settings['wc_settings_ivpa_single_ajax'] = SevenVXGet()->get_option( 'wc_settings_ivpa_single_ajax', 'improved_options', 'no' );
		self::$settings['wc_settings_ivpa_archive_image_size'] = SevenVXGet()->get_option( 'wc_settings_ivpa_archive_image_size', 'improved_options', 'full' );
		self::$settings['wc_settings_ivpa_outofstock_mode'] = SevenVXGet()->get_option( 'wc_settings_ivpa_outofstock_mode', 'improved_options', 'default' );
		self::$settings['wc_settings_ivpa_force_scripts'] = SevenVXGet()->get_option( 'wc_settings_ivpa_force_scripts', 'improved_options', 'no' );
		self::$settings['wc_settings_ivpa_use_caching'] = SevenVXGet()->get_option( 'wc_settings_ivpa_use_caching', 'improved_options', 'no' );
		self::$settings['wc_settings_ivpa_single_image_size'] = SevenVXGet()->get_option( 'wc_settings_ivpa_single_image_size', 'improved_options', 'full' );
		self::$settings['wc_settings_ivpa_disable_unclick'] = SevenVXGet()->get_option( 'wc_settings_ivpa_disable_unclick', 'improved_options', 'no' );
		self::$settings['wc_settings_ivpa_single_image'] = SevenVXGet()->get_option( 'wc_settings_ivpa_single_image', 'improved_options', 'yes' );
		self::$settings['wc_settings_ivpa_image_attributes'] = SevenVXGet()->get_option( 'wc_settings_ivpa_image_attributes', 'improved_options', array() );
		self::$settings['wc_settings_ivpa_step_selection'] = SevenVXGet()->get_option( 'wc_settings_ivpa_step_selection', 'improved_options', 'no' );
		self::$settings['wc_settings_ivpa_single_addtocart'] = SevenVXGet()->get_option( 'wc_settings_ivpa_single_addtocart', 'improved_options', 'yes' );
		self::$settings['wc_settings_ivpa_single_desc'] = SevenVXGet()->get_option( 'wc_settings_ivpa_single_desc', 'improved_options', 'ivpa_afterattribute' );
		self::$settings['wc_settings_ivpa_backorder_support'] = SevenVXGet()->get_option( 'wc_settings_ivpa_backorder_support', 'improved_options', 'no' );
		self::$settings['wc_settings_ivpa_archive_align'] = SevenVXGet()->get_option( 'wc_settings_ivpa_archive_align', 'improved_options', 'ivpa_align_left' );
		self::$settings['wc_settings_ivpa_simple_support'] = SevenVXGet()->get_option( 'wc_settings_ivpa_simple_support', 'improved_options', 'none' );

		self::$settings['archive-css'] = array();
		self::$settings['archive-css-echo'] = '';

		$this->install();

		add_action( 'wp_enqueue_scripts', array( $this, 'ivpa_scripts') );
		add_action( 'wp_footer', array( $this, 'footer_actions' ) );

		if ( self::$settings['wc_settings_ivpa_archive_enable'] == 'yes' || self::$settings['wc_settings_ivpa_single_ajax'] == 'yes' ) {
			add_action( 'woocommerce_add_to_cart' , array( $this, 'ivpa_repair_cart') );
			add_action( 'wp_ajax_nopriv_ivpa_add_to_cart_callback', array( $this, 'ivpa_add_to_cart_callback') );
			add_action( 'wp_ajax_ivpa_add_to_cart_callback', array( $this, 'ivpa_add_to_cart_callback') );
		}

		add_filter( 'woocommerce_add_cart_item_data', array( $this, 'add_customs_item_data' ), 10, 3 );
		add_filter( 'woocommerce_add_cart_item', array( $this, 'add_customs_item' ), 10, 3 );
		add_filter( 'woocommerce_get_cart_item_from_session', array( $this, 'add_customs_from_session' ), 10, 3 );
		add_filter( 'woocommerce_get_item_data', array( $this, 'add_customs_get_item_data' ), 10, 2 );

		add_action( 'woocommerce_new_order_item', array( $this, 'add_custom_meta' ), 10, 3 );

		add_filter( 'xforwc__add_meta_information_used', array( $this, 'ivpa_info' ) );

	}

	function install() {
		$automatic = SevenVXGet()->get_option( 'wc_settings_ivpa_automatic', 'improved_options', 'yes' );

		include_once( 'class-themes.php' );
		$install = XforWC_Product_Options_Themes::get_theme();

		if ( $install === false ) {
			return false;
		}
		
		$install = array(
			'wc_settings_ivpa_single_selector' => $install['product_images'],
			'wc_settings_ivpa_archive_selector' => $install['product'],
			'wc_settings_ivpa_addcart_selector' => $install['add_to_cart'],
			'wc_settings_ivpa_price_selector' => $install['price'],
			'wc_settings_ivpa_single_action' => $install['product_hook'],
			'wc_settings_ivpa_archive_action' => $install['shop_hook'],
			'wc_settings_ivpa_single_summary' => $install['product_summary'],
		);

		if ( $automatic == 'yes' ) {
			self::$settings = array_merge( self::$settings, $install );
		}
		else {
			foreach( $install as $k => $v ) {
				$opt = in_array( $k, array( 'wc_settings_ivpa_single_action', 'wc_settings_ivpa_archive_action', 'wc_settings_ivpa_single_summary' ) ) ? SevenVXGet()->get_option_autoload( $k, '' ) : SevenVXGet()->get_option( $k, 'improved_options', '' );
				self::$settings[$k] = $opt == '' ? $v : $opt;
			}
		}

		if ( self::$settings['wc_settings_ivpa_single_enable'] == 'yes' ) {
			if ( strpos( self::$settings['wc_settings_ivpa_single_action'], ':' ) > 0 ) {
				$explode = explode( ':', self::$settings['wc_settings_ivpa_single_action'] );

				$curr_action = array(
					'action' => $explode[0],
					'priority' => intval( $explode[1] ) > -1 ? intval( $explode[1] ) : 10
				);
			}
			else {
				$curr_action = array(
					'action' => self::$settings['wc_settings_ivpa_single_action'],
					'priority' => 10
				);
			}

			self::$settings['single_action'] = $curr_action['action'];

			add_action( $curr_action['action'], array( $this, 'ivpa_attributes' ), $curr_action['priority'] );
			add_action( 'ivpa_get_single_options', array( $this, 'ivpa_attributes' ) );
		}

		if ( self::$settings['wc_settings_ivpa_archive_enable'] == 'yes' ) {
			if ( strpos( self::$settings['wc_settings_ivpa_archive_action'], ':' ) > 0 ) {
				$explode = explode( ':', self::$settings['wc_settings_ivpa_archive_action'] );
				$curr_action = array(
					'action' => $explode[0],
					'priority' => intval( $explode[1] ) > -1 ? intval( $explode[1] ) : 10
				);
			}
			else {
				$curr_action = array(
					'action' => self::$settings['wc_settings_ivpa_archive_action'],
					'priority' => 10
				);
			}

			self::$settings['archive_action'] = $curr_action['action'];

			add_action( $curr_action['action'], array( $this, 'ivpa_attributes' ), $curr_action['priority'] );
			add_action( 'ivpa_get_loop_options', array( $this, 'ivpa_attributes' ) );
		}

	}

	function ivpa_info( $val ) {
		return array_merge( $val, array( 'Improved Product Options for WooCommerce' ) );
	}

	public static function ivpa_get_path() {
		return self::$path;
	}

	public static function get_settings() {
		if ( !isset( self::$settings['custom'] ) ) {
			$language = self::ivpa_wpml_language();

			if ( $language === false ) {
				$settings = SevenVXGet()->get_option( 'wc_ivpa_attribute_customization', 'improved_options', '' );
			}
			else {
				$settings = SevenVXGet()->get_option( 'wc_ivpa_attribute_customization_' . $language, 'improved_options', '' );
			}

			if ( $settings == '' ) {
				$settings = array( 'ivpa_attr' => array() );
			}

			self::$settings['custom'] = $settings;
		}
		
		return self::$settings['custom'];
	}

	function add_custom_meta( $item_id, $item, $order_id ) {
		if ( is_object( $item ) ) {
			if ( !property_exists( $item, 'legacy_values' ) ) {
				return;
			}

			$values = $item->legacy_values;
		}
		else {
			$values = $item;
		}

		if ( isset( $values['ivpac'] ) && is_array( $values['ivpac'] ) ) {
			$curr_customizations = self::get_settings();

			foreach ( $values['ivpac'] as $k => $v ) {
				if ( is_array($v)) {
					$c='';
					foreach ( $v as $k1=>$v1 ) {
						$c .= (empty($c)?'':'<br/> ').$k1.': '.$v1;
					}
					$v = $c;
				}
				if ( isset( $curr_customizations['ivpa_attr'][$k] ) ) {
					if ( $curr_customizations['ivpa_attr'][$k] == 'ivpa_custom' ) {
						wc_add_order_item_meta( $item_id, $curr_customizations['ivpa_title'][$k], $v );
					}
					else {
						wc_add_order_item_meta( $item_id, wc_attribute_label( $curr_customizations['ivpa_attr'][$k] ), $v );
					}
				}
				else {
					wc_add_order_item_meta( $item_id, wc_attribute_label( $k ), $v );
				}
			}
		}
	}

	function add_customs_get_item_data( $item_data, $cart_item ) {
		if ( isset( $cart_item['ivpac'] ) && is_array( $cart_item['ivpac'] ) ) {
			$curr_customizations = self::get_settings();

			foreach( $cart_item['ivpac'] as $k => $v ) {
				if ( is_array($v)) {
					$c='';
					foreach ( $v as $k1=>$v1 ) {
						$c .= (empty($c)?'':'<br/> ').$k1.': '.$v1;
					}
					$v = $c;
				}

				if ( isset( $curr_customizations['ivpa_attr'][$k] ) ) {
					if ( $curr_customizations['ivpa_attr'][$k] == 'ivpa_custom' ) {
						$item_data[] = array( 'key' => $curr_customizations['ivpa_title'][$k], 'value' => $v );
					}
					else {
						$item_data[] = array( 'key' => wc_attribute_label( $curr_customizations['ivpa_attr'][$k] ), 'value' => $v );
					}
				}
				else {
					$item_data[] = array( 'key' => wc_attribute_label( $k ), 'value' => $v );
				}
			}
		}

		return $item_data;
	}

	function add_customs_from_session( $session_data, $values, $key ) {
		if ( isset( $values['ivpac'] ) ) {
			$session_data['ivpac'] = $values['ivpac'];
			$session_data = self::add_customs_item( $session_data, $key );
		}

		return $session_data;
	}

	function add_customs_item_data( $product_data, $product_id ) {
		if ( isset( $_REQUEST['ivpac'] ) ) {
			$vars = array();
			wp_parse_str( $_REQUEST['ivpac'], $vars );

			if ( is_array( $vars ) ) {
				foreach ( $vars as $k => $v ) {
					if ( substr( $k, 0, 6 ) == 'ivpac_' && !empty( $v ) ) {
						if ( is_array( $v ) ) {
							$v = array_filter( $v );
						}
						if ( !empty( $v ) ) {
							$product_data['ivpac'][substr( $k, 6 )] = $v;
						}
					}
				}
			}

			if ( isset( $_REQUEST['variation'] ) && is_array( $_REQUEST['variation'] ) ) {
				$_product = wc_get_product( $product_id );

				if( !$_product->is_type( 'variable' ) ) {
					foreach( $_REQUEST['variation'] as $k => $v ) {
						if ( substr( $k, 0, 10 ) == 'attribute_' && !empty( $v ) ) {
							if ( is_array( $v ) ) {
								$v = array_filter( $v );
							}
							if ( !empty( $v ) ) {
								$product_data['ivpac'][wc_attribute_label( substr( $k, 10 ) )] = $v;
							}
						}
					}
				}
			}
		}
		else {
			foreach ( $_REQUEST as $k => $v ) {
				if ( substr( $k, 0, 6 ) == 'ivpac_' && !empty( $v ) ) {
					if ( is_array( $v ) ) {
						$v = array_filter( $v );
					}
					if ( !empty( $v ) ) {
						$product_data['ivpac'][substr( $k, 6 )] = $v;
					}
				}
				else if ( substr( $k, 0, 10 ) == 'attribute_' && !empty( $v ) ) {
					if ( !isset( $_product ) ) {
						$_product = wc_get_product( $product_id );
					}
					if ( !$_product->is_type( 'variable' ) ) {
						if ( substr( $k, 0, 10 ) == 'attribute_' && !empty( $v ) ) {
							if ( is_array( $v ) ) {
								$v = array_filter( $v );
							}
							if ( !empty( $v ) ) {
								$product_data['ivpac'][wc_attribute_label( substr( $k, 10 ) )] = $v;
							}
						}
					}
				}
			}
		}

		return $product_data;
	}

	function add_customs_item( $product_data, $cart_item_key ) {
		if ( isset( $product_data['ivpac'] ) && is_array( $product_data['ivpac'] ) ) {
			$curr_customizations = self::get_settings();

			foreach( $product_data['ivpac'] as $k => $v ) {
				$v = is_array( $v ) ? $v : array( $v => $v );
				if ( isset( $curr_customizations['ivpa_attr'][$k] ) && $curr_customizations['ivpa_attr'][$k] == 'ivpa_custom' ) {

					if ( isset( $curr_customizations['ivpa_addprice'][$k] ) && $curr_customizations['ivpa_addprice'][$k] !== '' ) {
						self::add_price( $product_data, $curr_customizations['ivpa_addprice'][$k] );
					}

					foreach( $v as $k1 => $v1 ) {
						$customPrices = is_array( $k1 ) ? $k1 : (strpos( $k1, ', ' ) > 0 ? explode( ', ', $k1 ) : array( $k1 => $k1 ) );

						foreach( $customPrices as $k2 => $v2 ) {
							if ( isset( $curr_customizations['ivpa_price'][$k][$v2] ) && $curr_customizations['ivpa_price'][$k][$v2] !== '' ) {
								self::add_price( $product_data, $curr_customizations['ivpa_price'][$k][$v2] );
							}
						}
					}
				}
			}

		}

		return $product_data;
	}

	public static function add_price( $product_data, $price ) {
		if ( method_exists( $product_data['data'], 'set_price' ) ) {
			$product_data['data']->set_price( $product_data['data']->get_price() + $price );
		}
		else {
			$product_data['data']->adjust_price( $price );
		}
	}

	function ivpa_scripts() {
		wp_enqueue_style( 'ivpa-style', self::$url_path . '/assets/css/styles' . ( is_rtl() ? '-rtl' : '' ) . '.css', false, self::$version );

		wp_register_script( 'ivpa-scripts', self::$url_path .'/assets/js/scripts.js', array( 'jquery', 'hoverIntent', 'wp-util' ), self::$version, true );
		wp_enqueue_script( 'ivpa-scripts' );
	}

	function footer_actions() {

		global $ivpa_global;

		if ( !isset( $ivpa_global['init'] ) && self::$settings['wc_settings_ivpa_force_scripts'] == 'no' ) {

			wp_dequeue_script( 'ivpa-scripts' );

		}
		else {

			global $_wp_additional_image_sizes;

			$sizes = array();

			foreach( $_wp_additional_image_sizes as $_size_key => $_size ) {
				if ( in_array( $_size_key, array( 'shop_catalog', 'shop_single', 'shop_thumbnail' ) ) && isset( $_wp_additional_image_sizes[ $_size_key ] ) ) {
					$sizes[ $_size_key ] = array(
						'getimg' => '-' . $_wp_additional_image_sizes[ $_size_key ]['width'] . 'x' . $_wp_additional_image_sizes[ $_size_key ]['height'],
						'width' => $_wp_additional_image_sizes[ $_size_key ]['width'],
						'height' => $_wp_additional_image_sizes[ $_size_key ]['height'],
						'crop' =>  $_wp_additional_image_sizes[ $_size_key ]['crop']
					);
				}
			}

			$curr_args = array(
				'ajax' => esc_url( admin_url( 'admin-ajax.php' ) ),
				'outofstock' => self::$settings['wc_settings_ivpa_outofstock_mode'],
				'disableunclick' => self::$settings['wc_settings_ivpa_disable_unclick'],
				'imageswitch' => self::$settings['wc_settings_ivpa_single_image'],
				'imageattributes' => self::$settings['wc_settings_ivpa_image_attributes'],
				'stepped' => self::$settings['wc_settings_ivpa_step_selection'],
				'backorders' => self::$settings['wc_settings_ivpa_backorder_support'],
				'singleajax' => self::$settings['wc_settings_ivpa_single_ajax'],
				'archiveajax' => get_option( 'woocommerce_enable_ajax_add_to_cart', 'yes' ),
				'settings' => array(
					'single_selector' => self::$settings['wc_settings_ivpa_single_selector'],
					'summary_selector' => self::$settings['wc_settings_ivpa_single_summary'],
					'archive_selector' => self::$settings['wc_settings_ivpa_archive_selector'],
					'addcart_selector' => self::$settings['wc_settings_ivpa_addcart_selector'],
					'price_selector' => self::$settings['wc_settings_ivpa_price_selector'],
					'archive_prices' => SevenVXGet()->get_option( 'wc_settings_ivpa_archive_prices', 'improved_options', 'product' ),
					'single_prices' => SevenVXGet()->get_option( 'wc_settings_ivpa_single_prices', 'improved_options', 'summary' ),
				),
				'localization' => array(
					'select' => esc_html__( 'Select', 'improved-variable-product-attributes' ),
					'simple' => ( isset ( $ivpa_global['simple'] ) ? $ivpa_global['simple'] : esc_html__( 'Add to cart', 'improved-variable-product-attributes' ) ),
					'variable' => ( isset ( $ivpa_global['variable'] ) ? $ivpa_global['variable'] : esc_html__( 'Select options', 'improved-variable-product-attributes' ) )
				),
				'add_to_cart' => apply_filters( 'wc_add_to_cart_params', array(
					'ajax'                    => admin_url( 'admin-ajax.php' ),
					'ajax_url'                => WC()->ajax_url(),
					'ajax_loader_url'         => apply_filters( 'woocommerce_ajax_loader_url', '' ),
					'i18n_view_cart'          => esc_html__( 'View Cart', 'improved-variable-product-attributes' ),
					'cart_url'                => get_permalink( wc_get_page_id( 'cart' ) ),
					'is_cart'                 => is_cart(),
					'cart_redirect_after_add' => get_option( 'woocommerce_cart_redirect_after_add' )
				) ),
				'price' => array(
					'thousand_separator' => wc_get_price_thousand_separator(),
					'decimal_separator' => wc_get_price_decimal_separator(),
					'decimals' => wc_get_price_decimals(),
				),
				'imagesizes' => $sizes
			);

			wp_localize_script( 'ivpa-scripts', 'ivpa', $curr_args );

			self::ivpa_single_styles();
			self::ivpa_archive_styles_482();

			//self::ivpa_archive_styles();

		}

	}

	public static function ivpa_get_attributes() {
		$attributes = get_object_taxonomies( 'product' );
		$ready_attributes = array();

		if ( !empty( $attributes ) ) {
			foreach( $attributes as $k ) {
				if ( substr($k, 0, 3) == 'pa_' ) {
					$ready_attributes[] = $k;
				}
			}
		}

		return $ready_attributes;
	}

	function ivpa_single_styles() {
		$return = '';

		if ( self::$settings['wc_settings_ivpa_outofstock_mode'] == 'clickable' ) {
			$return .= '.ivpa_term.ivpa_outofstock {cursor:pointer!important;}';
		}
		else if ( self::$settings['wc_settings_ivpa_outofstock_mode'] == 'hidden' ) {
			$return .= '.ivpa_term.ivpa_outofstock {display:none!important;}';
		}
		if ( self::$settings['wc_settings_ivpa_single_enable'] == 'yes' && self::$settings['wc_settings_ivpa_single_selectbox'] == 'no' ) {
			$return .= 'body div .variations_form .variations {display:table!important;}';
		}
		if ( self::$settings['wc_settings_ivpa_single_addtocart'] == 'yes' ) {
			$return .= '.woocommerce-variation-add-to-cart-disabled .quantity, .woocommerce-variation-add-to-cart-disabled .single_add_to_cart_button, .ivpa-hide {visibility:hidden;position:fixed!important;top:0!important;left:0!important;width:0!important;height:0!important;overflow:hidden!important;z-index:-1!important;}';
		}

		if ( !empty( $return ) ) {
			echo '<style type="text/css">' . $return . '</style>';
		}
	}

	function ivpa_archive_styles_482() {
		global $ivpa_global;

		if ( !isset( $ivpa_global['init'] ) ) {
			return;
		}

		if ( !empty( self::$settings['archive-css-echo'] ) ) {
		?>
			<style type="text/css">
				<?php echo wp_kses_post( self::$settings['archive-css-echo'] ); ?>
			</style>
		<?php
		}

	}

	function utf8_urldecode($str) {
		$str = preg_replace("/%u([0-9a-f]{3,4})/i","&#x\\1;",urldecode($str));
		return html_entity_decode($str,0,'UTF-8');
	}

	public static function init_globals() {
		global $ivpa_global;

		$ivpa_global['init'] = true;

		$ivpa_product = new WC_Product_Simple( get_the_ID() );
		$ivpa_global['simple'] = esc_html( $ivpa_product->add_to_cart_text() );

		$ivpa_product = new WC_Product_Variable( get_the_ID() );
		$ivpa_global['variable'] = esc_html( $ivpa_product->add_to_cart_text() );
	}

	public static function reset_loop() {
		global $ivpa_global;

		if ( !isset( $ivpa_global['init'] ) ){
			self::init_globals();
		}

		self::$settings['is_loop'] = false;
	}

	public static function is_loop() {
		if ( empty( self::$settings['is_loop'] ) ) {
			switch( current_filter() ) {
				case self::$settings['archive_action'] :
				case 'ivpa_get_loop_options' :
					self::$settings['is_loop'] = 'loop';
				break;

				default :
					self::$settings['is_loop'] = 'single';
				break;
			}
		}

		return self::$settings['is_loop'];
	}

	public static function check_visibility( $n, $opt ) {
		$type = isset( $opt['ivpa_limit_type'][$n] ) && !empty( $opt['ivpa_limit_type'][$n] ) ? $opt['ivpa_limit_type'][$n] : '';
		$category = isset( $opt['ivpa_limit_category'][$n] ) && !empty( $opt['ivpa_limit_category'][$n] ) ? $opt['ivpa_limit_category'][$n] : '';
		$products = isset( $opt['ivpa_limit_product'][$n] ) && !empty( $opt['ivpa_limit_product'][$n] ) ? $opt['ivpa_limit_product'][$n] : '';

		if ( empty( $type ) && empty( $category ) && empty( $products ) ) {
			return true;
		}

		global $product;

		$check = strpos( $products, '|' ) ? explode( '|', $products ) : array( $products );
		if ( in_array( get_the_ID(), $check ) ) {
			return true;
		}

		$check = strpos( $type, '|' ) ? explode( '|', $type ) : array( $type );
		foreach( $check as $k => $v ) {
			if ( $product->is_type( $v ) ) {
				return true;
			}
		}

		$check = strpos( $category, '|' ) ? explode( '|', $category ) : array( $category );
		$productCat = $product->get_category_ids();
		if ( isset( $productCat[0] ) ) {
			if ( in_array( $productCat[0], $check ) ) {
				return true;
			}

		}

		return false;
	}

	function fix_custom_terms( $terms, $order ) {
		$return = array();

		foreach( $terms as $k => $v ) {
			if ( array_search($v->slug,array_keys($order)) ) {
				$return[array_search($v->slug,array_keys($order))] = $v;
			}
			else {
				$return[] = $v;
			}
		}

		ksort( $return, SORT_REGULAR );

		return $return;
	}

	static function _check_cache() {
		if ( !empty( $_REQUEST ) ) {
			return false;
		}

		$cachedHtml = get_post_meta( get_the_ID(), '_ivpa_cached_' . self::is_loop() . '_' . get_locale(), true );

		if ( !empty( $cachedHtml ) ) {
			$available_variations = get_post_meta( get_the_ID(), '_ivpa_cached_data', true );
			return str_replace( '%%%JSON_REPLACE_IVPA%%%', esc_attr( json_encode( $available_variations ) ), $cached_html );
		}

		return false;
	}

	static function _stop() {
		$settings = self::get_settings();

		if ( !isset( $settings['ivpa_attr'] ) || !is_array( $settings['ivpa_attr'] ) ) {
			return true;
		}

		global $product;

		if ( $product->is_type( apply_filters( 'ivpa_disallow_products_types', array( 'external', 'grouped' ) ) ) ) {
			return true;
		}
		if ( self::$settings['wc_settings_ivpa_simple_support'] == 'none' && !$product->is_type( 'variable' ) ) {
			return true;
		}

		return false;
	}

	function _get_available_variations() {
		global $product;

		$available_variations = array();
		
		if ( $product->is_type( 'variable' ) ) {
			foreach ( $product->get_children() as $child_id ) {
				$variation = wc_get_product( $child_id );

				if ( empty( $variation ) || ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) && ! $variation->is_in_stock() ) ) {
					continue;
				}

				if ( apply_filters( 'woocommerce_hide_invisible_variations', false, get_the_ID(), $variation ) && ! $variation->variation_is_visible() ) {
					continue;
				}

				if ( has_post_thumbnail( $variation->get_id() ) ) {
					$attachment_id   = get_post_thumbnail_id( $variation->get_id() );
					$attachment      = wp_get_attachment_image_src( $attachment_id, 'full' );
					$variation_image = str_replace( array( 'http:', 'https:' ), '', $attachment[0] );
				}
				else {
					$variation_image = '';
				}

				$availability_html = '';
				$backorder_required = $variation->backorders_require_notification();
				if ( self::is_loop() == 'loop' && $backorder_required === true ) {
					$availability      = $variation->get_availability();
					$availability_html = empty( $availability['availability'] ) ? '' : '<p class="stock ' . esc_attr( $availability['class'] ) . '">' . wp_kses_post( $availability['availability'] ) . '</p>';
					$availability_html = apply_filters( 'woocommerce_stock_html', $availability_html, $availability['availability'], $variation );
				}

				$available_variations[] = array(
					'variation_id'          => $variation->get_id(),
					'attributes'            => $variation->get_variation_attributes(),
					'price_html'            => apply_filters( 'woocommerce_show_variation_price', $variation->get_price() === "" || $product->get_variation_price( 'min' ) !== $product->get_variation_price( 'max' ), $product, $variation ) ? '<span class="price">' . $variation->get_price_html() . '</span>' : '',
					'is_in_stock'           => $variation->is_in_stock(),
					'ivpa_image'            => $variation_image,
					'stock'				    => $backorder_required ? $variation->get_stock_quantity() : '',
					'backorders_allowed'    => $backorder_required,
					'availability_html'     => $availability_html
				);
			}
		}

		return $available_variations;
	}

	function _get_variation_attributes() {
		global $product;

		$attributes = array();

		if ( $product->is_type( 'variable' ) ) {
			$attributes = $product->get_variation_attributes();
		}

		return $attributes;
	}

	function _get_selected_attributes() {
		global $product;

		$selected = array();

		if ( $product->is_type( 'variable' ) ) {
			if ( XforWC_Improved_Options::version_check() ) {
				$selected = $product->get_default_attributes();
			}
			else {
				$selected = $product->get_variation_default_attributes();
			}
		}

		return $selected;
	}

	function get_wrapper_thumb_data() {
		global $product;

		if ( $product->is_type( 'variable' ) && has_post_thumbnail() ) {
			$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );

			if ( isset( $thumbnail[0] ) ) {
				echo ' data-image="' . esc_url( str_replace( array( 'http:', 'https:' ), '', $thumbnail[0] ) ) . '"';
			}
		}
	}

	function get_wrapper_classes() {
		$classes = array();

		$classes[] = 'ivpa-register';
		$classes[] = self::$settings['wc_settings_ivpa_step_selection'] == 'yes' ? ' ivpa-stepped' : '';
		$classes[] = self::$settings['wc_settings_ivpa_disable_unclick'] == 'yes' ? ' ivpa-disableunclick' : '';

		if ( self::is_loop() == 'loop' ) {
			$classes[] = 'ivpa-content';
			$classes[] = self::$settings['wc_settings_ivpa_archive_align'];
		}
		
		echo esc_attr( implode( ' ', $classes ) );
	}

	function _get_hidden_price_template() {
	?>
		<div class="ivpa-hidden-price">
		<?php
			if ( self::is_loop() == 'loop' ) {
				wc_get_template( 'loop/price.php' );
			}
			if ( self::is_loop() == 'single' ) {
				wc_get_template( 'single-product/price.php' );
			}
		?>
		</div>
	<?php
	}

	function _get_selected_attribute( $v, $selected_attributes ) {
		global $prdctfltr_global;
				
		if ( self::is_loop() == 'single' && isset( $_REQUEST[ 'attribute_' . $v ] ) ) {
			$selected_attr = $_REQUEST[ 'attribute_' . $v ];
		}
		else if ( self::is_loop() == 'loop' && ( isset( $_REQUEST[ $v ] ) || isset( $prdctfltr_global['active_filters'][$v][0] ) || isset( $prdctfltr_global['active_permalinks'][$v][0] ) ) ) {
			$selected_attr = isset( $_REQUEST[ $v ] ) ? $_REQUEST[ $v ] : ( isset( $prdctfltr_global['active_filters'][$v][0] ) ? $prdctfltr_global['active_filters'][$v][0] : $prdctfltr_global['active_permalinks'][$v][0] );
		}
		else if ( isset( $selected_attributes[ $v ] ) ) {
			$selected_attr = $selected_attributes[ $v ];
		}
		else {
			$selected_attr = '';
		}

		return $selected_attr;
	}

	function _get_customized_options( $curr_customizations, $available_attributes ) {
		global $product;
		
		$avlb_atts = array();
		$cstm_atts = array();

		foreach ( $available_attributes as $k => $v ) {
			$avlb_atts[] = $k;
		}

		foreach( $curr_customizations['ivpa_attr'] as $k => $v ) {
			if ( $v == 'ivpa_custom' ) {
				$check = self::check_visibility( $k, $curr_customizations );
				if ( $check === true ) {
					$cstm_atts[] = array(
						'key' => $k,
						'custom' => isset( $curr_customizations['ivpa_title'][$k] ) ? sanitize_title( $curr_customizations['ivpa_title'][$k] ) : 'ivpa_custom'
					);
				}
			}
			else {
				if ( in_array( $v, $avlb_atts ) ) {
					if ( array_key_exists( $v, $available_attributes ) && $available_attributes[$v]['variation'] ) {
						$cstm_atts[] = array(
							'key' => $k,
							'custom' => $v
						);
					}
					else if ( self::$settings['wc_settings_ivpa_simple_support'] !== 'none' && $product->get_type()=="simple" && array_key_exists( $v, $available_attributes ) && isset( $curr_customizations['ivpa_svariation'][$k] ) && $curr_customizations['ivpa_svariation'][$k] == 'yes' ) {
						$cstm_atts[] = array(
							'key' => $k,
							'custom' => $v
						);
					}
					unset( $avlb_atts[array_search($v, $avlb_atts)] );
				}
			}
		}

		foreach( $avlb_atts as $k => $v ) {
			if ( array_key_exists( $v, $available_attributes ) && $available_attributes[$v]['variation'] ) {
				$cstm_atts[] = array(
					'key' => false,
					'custom' => $v
				);
			}
			else if ( self::$settings['wc_settings_ivpa_simple_support'] !== 'none' && $product->get_type()=="simple" && array_key_exists( $v, $available_attributes ) && isset( $curr_customizations['ivpa_svariation'][$k] ) && $curr_customizations['ivpa_svariation'][$k] == 'yes' ) {
				$cstm_atts[] = array(
					'key' => false,
					'custom' => $v
				);
			}
		}

		return $cstm_atts;
	}

	function _make_terms( &$curr, $v, $available_attributes ) {
		$curr['terms'] = array();
		$v = self::utf8_urldecode( $v );
			
		if ( taxonomy_exists( $v ) ) {
			$curr['terms'] = wc_get_product_terms( get_the_ID(), $v, array( 'fields' => 'all') );
		}
		else {
			if ( isset( $available_attributes[$v] ) ) {
				$curr['title'] = $available_attributes[$v]['name'];

				$custom_vals = array_map( 'trim', explode( WC_DELIMITER, $available_attributes[$v]['value'] ) );

				foreach ( $custom_vals as $cv ) {
					$curr['terms'][$cv] = new stdClass();
					$curr['terms'][$cv]->name = ucfirst( $cv );
					$curr['terms'][$cv]->slug = $cv;
				}
			}
			else if ( $curr['name'] !== '' && is_array( $curr['name'] ) ) {
				foreach ( $curr['name'] as $cv ) {
					$slug = sanitize_title( $cv );
					$curr['terms'][$cv] = new stdClass();
					$curr['terms'][$cv]->name = $cv;
					$curr['terms'][$cv]->slug = $slug;
				}
			}
		}

		if ( $curr['order'] == 'true' ) {
			$curr['terms'] = $this->fix_custom_terms( $curr['terms'], $curr['custom'] );
		}
	}

	function _get_style( $curr, $v, $willCustom ) {
		$style = '';
		
		if ( $curr['style'] == 'ivpa_text' && !in_array( $v, self::$settings['archive-css'] ) ) {

			self::$settings['archive-css'][] = $v;

			$str = ( $curr['attr'] == 'ivpa_custom' || $willCustom === true ? 'ivpa_custom_option' : 'ivpa_attribute' ); // OK

			switch ( $curr['custom']['style'] ) {

				case 'ivpa_border' :
					ob_start();
			?>
					<?php echo esc_attr( self::__get_css_class() ); ?> .<?php echo esc_attr( $str ); ?>[data-attribute="<?php echo esc_attr( $v ); ?>"].ivpa_text.ivpa_border .ivpa_term.ivpa_active {
						border-color:<?php echo ImprovedOptions()->esc_color( $curr['custom']['normal'] ); ?>;
						color:<?php echo ImprovedOptions()->esc_color( $curr['custom']['normal'] ); ?>;
					}
					<?php echo esc_attr( self::__get_css_class() ); ?> .<?php echo esc_attr( $str ); ?>[data-attribute="<?php echo esc_attr( $v ); ?>"].ivpa_text.ivpa_border .ivpa_term.ivpa_active.ivpa_clicked,
					<?php echo esc_attr( self::__get_css_class() ); ?> .<?php echo esc_attr( $str ); ?>[data-attribute="<?php echo esc_attr( $v ); ?>"].ivpa_text.ivpa_border .ivpa_term.ivpa_active.ivpa_clicked.ivpa_outofstock {
						border-color:<?php echo ImprovedOptions()->esc_color( $curr['custom']['active'] ); ?>;
						color:<?php echo ImprovedOptions()->esc_color( $curr['custom']['active'] ); ?>;
					}
					<?php echo esc_attr( self::__get_css_class() ); ?> .<?php echo esc_attr( $str ); ?>[data-attribute="<?php echo esc_attr( $v ); ?>"].ivpa_text.ivpa_border .ivpa_term.ivpa_active.ivpa_disabled {
						border-color:<?php echo ImprovedOptions()->esc_color( $curr['custom']['disabled'] ); ?>;
						color:<?php echo ImprovedOptions()->esc_color( $curr['custom']['disabled'] ); ?>;
					}
					<?php echo esc_attr( self::__get_css_class() ); ?> .<?php echo esc_attr( $str ); ?>[data-attribute="<?php echo esc_attr( $v ); ?>"].ivpa_text.ivpa_border .ivpa_term.ivpa_active.ivpa_outofstock {
						border-color:<?php echo ImprovedOptions()->esc_color( $curr['custom']['outofstock'] ); ?>;
						color:<?php echo ImprovedOptions()->esc_color( $curr['custom']['outofstock'] ); ?>;
					}
					<?php echo esc_attr( self::__get_css_class() ); ?> .<?php echo esc_attr( $str ); ?>.ivpa_text.ivpa_border .ivpa_term.ivpa_active.ivpa_outofstock:after {
						background-image:url('data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20xml%3Aspace%3D%22preserve%22%20width%3D%2232px%22%20height%3D%2232px%22%20style%3D%22shape-rendering%3AgeometricPrecision%3B%20text-rendering%3AgeometricPrecision%3B%20image-rendering%3AoptimizeQuality%3B%20fill-rule%3Aevenodd%3B%20clip-rule%3Aevenodd%22%20viewBox%3D%220%200%201181%201181%22%20preserveAspectRatio%3D%22none%22%20xmlns%3Axlink%3D%22http%3A//www.w3.org/1999/xlink%22%3E%0A%3Cdefs%3E%0A%09%3Cstyle%20type%3D%22text/css%22%3E%0A%09%3C%21%5BCDATA%5B%0A%09%09.fil0%20%7Bfill%3A%23<?php echo substr( ImprovedOptions()->esc_color( $curr['custom']['outofstock'] ), 1 ); ?>%3Bfill-rule%3Anonzero%3Bpaint-order%3Astroke%3Bstroke%3A%23<?php echo substr( ImprovedOptions()->esc_color( $curr['custom']['outofstock'] ), 1 ); ?>%3Bstroke-width%3A5%3Bstroke-linecap%3Abutt%3Bstroke-linejoin%3Amiter%3B%7D%0A%09%5D%5D%3E%0A%09%3C/style%3E%0A%3C/defs%3E%0A%3Cg%3E%0A%09%3Cpolygon%20class%3D%22fil0%22%20points%3D%221175.11%2C1181%20590.5%2C596.391%205.89083%2C1181%20-0%2C1181%20-0%2C1175.11%20584.609%2C590.5%20-0%2C5.89083%20-0%2C0%205.89083%2C0%20590.5%2C584.609%201175.11%2C0%201181%2C0%201181%2C5.89083%20596.391%2C590.5%201181%2C1175.11%201181%2C1181%20%22/%3E%0A%3C/g%3E%0A%3C/svg%3E');
					}
			<?php
					$style = ob_get_clean();
				break;


				case 'ivpa_background' :
				case 'ivpa_round' :
					ob_start();
			?>
					<?php echo esc_attr( self::__get_css_class() ); ?> .<?php echo esc_attr( $str ); ?>[data-attribute="<?php echo esc_attr( $v ); ?>"].ivpa_text .ivpa_term.ivpa_active {
						background-color:<?php echo ImprovedOptions()->esc_color( $curr['custom']['normal'] ); ?>;
					}
					<?php echo esc_attr( self::__get_css_class() ); ?> .<?php echo esc_attr( $str ); ?>[data-attribute="<?php echo esc_attr( $v ); ?>"].ivpa_text .ivpa_term.ivpa_active.ivpa_clicked,
					<?php echo esc_attr( self::__get_css_class() ); ?> .<?php echo esc_attr( $str ); ?>[data-attribute="<?php echo esc_attr( $v ); ?>"].ivpa_text .ivpa_term.ivpa_active.ivpa_clicked.ivpa_outofstock {
						background-color:<?php echo ImprovedOptions()->esc_color( $curr['custom']['active'] ); ?>;
					}
					<?php echo esc_attr( self::__get_css_class() ); ?> .<?php echo esc_attr( $str ); ?>[data-attribute="<?php echo esc_attr( $v ); ?>"].ivpa_text .ivpa_term.ivpa_active.ivpa_disabled {
						background-color:<?php echo ImprovedOptions()->esc_color( $curr['custom']['disabled'] ); ?>;
					}
					<?php echo esc_attr( self::__get_css_class() ); ?> .<?php echo esc_attr( $str ); ?>[data-attribute="<?php echo esc_attr( $v ); ?>"].ivpa_text .ivpa_term.ivpa_active.ivpa_outofstock {
						background-color:<?php echo ImprovedOptions()->esc_color( $curr['custom']['outofstock'] ); ?>;
					}
			<?php
					$style = ob_get_clean();
				break;

			}
		}

		return $style;
	}

	function get_term_wrap_classes( $curr, $willCustom ) {
		global $product;

		$classes = array();

		$classes[] = 'ivpa-opt';
		$classes[] = $curr['attr'] == 'ivpa_custom' || $willCustom === true ? 'ivpa_custom_option' : 'ivpa_attribute';
		$classes[] = $curr['style'];
		$classes[] = $curr['style'] == 'ivpa_text' ? ( isset( $curr['custom']['style'] ) ? $curr['custom']['style'] : 'border' ) : '';
		$classes[] = self::is_loop() == 'loop' ? self::$settings['wc_settings_ivpa_archive_mode'] : '';
		$classes[] = isset( $curr['multiselect'] ) && $curr['multiselect'] == 'yes' && $curr['attr'] == 'ivpa_custom' && in_array( $curr['style'], array( 'ivpa_text', 'ivpa_color', 'ivpa_image', 'ivpa_html', 'ivpa_selectbox', 'ivpac_checkbox' ) )  ? 'ivpa_multiselect' : '';
		$classes[] = !empty($curr['swatchDesign']) && $curr['swatchDesign'] == 'round' && in_array( $curr['style'], array( 'ivpa_color', 'ivpa_image' ) )  ? 'ivpa_round_swatches' : '';

		if ( ( self::is_loop() == 'loop' && self::$settings['wc_settings_ivpa_archive_mode'] == 'ivpa_showonly' ) === false ) {
			if ( ( $curr['attr'] == 'ivpa_custom' || $willCustom === true ) === true && in_array( $curr['style'], array( 'ivpa_text', 'ivpa_color', 'ivpa_image', 'ivpa_html', 'ivpa_selectbox' ) ) ) {
				$classes[] = 'ivpa-do';
			}
			else if ( $curr['attr'] !== 'ivpa_custom' ) {
				if ( self::is_loop() == 'single' && !$product->is_type( 'variable' ) ) {
					$classes[] = 'ivpa-do';
				}
			}
		}

		echo esc_attr( implode( ' ', $classes ) );
	}

	function _get_selectbox_title( $curr, $curr_term_sanitized ) {
		if ( self::is_loop() == 'single' ) {
		?>
			<strong class="ivpa_title ivpa_selectbox_title">
				<?php
					if ( $curr['title'] == '' ) {
						echo wc_attribute_label( $curr_term_sanitized );
					}
					else {
						echo wp_kses_post( stripslashes( $curr['title'] ) );
					}
				?>
			</strong>
		<?php
			self::__get_info_option( $curr );

			if ( self::is_loop() == 'single' && self::$settings['wc_settings_ivpa_single_desc'] == 'ivpa_aftertitle' ) {
				if ( $curr['desc'] !== '' ) {
					self::__get_desc( $curr );
				}
				self::__add_price_html_with_wrapper( $curr, false );
			}
		}
	}

	function _select_box_start( $curr, $curr_term_sanitized ) {
		if ( $curr['style'] == 'ivpa_selectbox' ) {
			$this->_get_selectbox_title( $curr, $curr_term_sanitized );
		?>
			<div class="ivpa_select_wrapper">
				<div class="ivpa_select_wrapper_inner">
		<?php
		}
	}

	function _select_box_end( $curr ) {
		if ( $curr['style'] == 'ivpa_selectbox' ) {
		?>
				</div>
			</div>
		<?php
		}
	}

	function _get_title( $curr, $selected_attr, $curr_term_sanitized ) {
		if ( self::is_loop() == 'single' || $curr['style'] == 'ivpa_selectbox' ) {
		?>
			<strong class="ivpa_title">
				<?php
					if ( $curr['style'] == 'ivpa_selectbox' ) {
						if ( $selected_attr == '' ) {
							if ( self::is_loop() == 'single' ) {
								esc_html_e( 'Select', 'improved-variable-product-attributes' );
							}
							else {
								if ( $curr['title'] == '' ) {
									echo wc_attribute_label( $curr_term_sanitized );
								}
								else {
									echo wp_kses_post( stripslashes( $curr['title'] ) );
								}
							}
						}
					}
					else {
						if ( $curr['title'] == '' ) {
							echo wc_attribute_label( $curr_term_sanitized );
						}
						else {
							echo wp_kses_post( stripslashes( $curr['title'] ) );
						}
					}

				?>
			</strong>
<?php
			if ( $curr['style'] !== 'ivpa_selectbox' ) {
				self::__get_info_option( $curr );
			}

			if ( self::is_loop() == 'single' && self::$settings['wc_settings_ivpa_single_desc'] == 'ivpa_aftertitle' && $curr['style'] !== 'ivpa_selectbox' ) {
				if ( $curr['desc'] !== '' ) {
					self::__get_desc( $curr );
				}
				self::__add_price_html_with_wrapper( $curr, false );
			}
		}
	}

	function _get_tooltip( $curr, $b ) {
		if ( self::is_loop() == 'single' && isset( $curr['ivpa_tooltip'] ) && isset( $curr['ivpa_tooltip'][$b->slug] ) && $curr['ivpa_tooltip'][$b->slug] !== '' ) {
		?>
			<span class="ivpa_tooltip">
				<span><?php echo wp_kses_post( stripslashes( $curr['ivpa_tooltip'][$b->slug] ) ); ?></span>
			</span>
		<?php
		}
	}

	function _term_continue( $curr_attributes, $curr_term_sanitized, $b ) {
		if ( !empty( $curr_attributes[$curr_term_sanitized] ) && !in_array( $b->slug, $curr_attributes[$curr_term_sanitized] ) ) {
			return true;
		}

		return false;
	}


	function _go_ip_select( $curr, $curr_attributes, $curr_term_sanitized, $selected_attr, $k ) {
		$field = sanitize_title( $curr['title'] );
	?>
		<span class="ivpa_term ivpa_custom<?php // self::__get_selected_term_class( $b->slug, $selected_attr ); ?>" data-term="<?php echo esc_attr( $field ); ?>">
		<?php
			if ( ( self::is_loop() == 'loop' && self::$settings['wc_settings_ivpa_archive_mode'] == 'ivpa_showonly' ) === false ) {
			?>
				<select class="ivpac-change" name="<?php echo esc_attr( 'ivpac_' . $k ); ?>">
					<option value=""><?php esc_html_e( 'Select', 'improved-variable-product-attributes' ); ?></option>
				<?php
					foreach ( $curr['terms'] as $l => $b ) {
					?>
						<option value="<?php echo esc_attr( !empty( $b->slug ) ? $b->slug : $b->name ); ?>" <?php self::add_price_html( $curr['attr'], ( isset( $curr['price'][$b->slug] ) ? $curr['price'][$b->slug] : '' ), true ); ?><?php self::__get_selected_form_term( $b->slug, $selected_attr, 'select' ); ?>><?php echo esc_html( ( isset( $curr['name'][$b->slug] ) && $curr['name'][$b->slug] !== '' ? $curr['name'][$b->slug] : $b->name ) ); ?> <?php strip_tags( self::add_price_html( $curr['attr'], ( isset( $curr['price'][$b->slug] ) ? $curr['price'][$b->slug] : '' ), false ) ); ?></option>
					<?php
					}
				?>
			</select> 
			<?php
			}
		?>
		</span>
	<?php
	}

	function _go_ip_system( $curr, $curr_attributes, $curr_term_sanitized, $selected_attr, $k ) {
		foreach ( $curr['terms'] as $l => $b ) {
			$field = sanitize_title( $curr['title'] );

		?>
			<span class="ivpa_term ivpa_custom<?php self::__get_selected_term_class( $b->slug, $selected_attr ); ?>" data-term="<?php echo esc_attr( !empty( $b->slug ) ? $b->slug : $b->name ); ?>">
			<?php
				if ( ( self::is_loop() == 'loop' && self::$settings['wc_settings_ivpa_archive_mode'] == 'ivpa_showonly' ) === false ) {
					switch( $curr['style'] ) {
						case 'ivpac_input' :
						?>
							<input class="ivpac-change" type="text" name="<?php echo esc_attr( 'ivpac_' . $k . '[' . $b->slug . ']' ); ?>" placeholder="<?php echo isset( $curr['name'][$b->slug] ) && $curr['name'][$b->slug] !== '' ? esc_attr( $curr['name'][$b->slug] ) : esc_attr( $b->name ); ?>"<?php self::__get_selected_form_term( $b->slug, $selected_attr, 'input' ); ?> /> 
						<?php
						break;

						case 'ivpac_checkbox' :
						?>
							<input class="ivpac-change" type="checkbox" name="<?php echo esc_attr( 'ivpac_' . $k . '[' . $b->slug . ']' ); ?>" value="<?php echo esc_attr( !empty( $b->slug ) ? $b->slug : $b->name ); ?>"<?php self::__get_selected_form_term( $b->slug, $selected_attr, 'checkbox' ); ?> /> 
						<?php
						break;

						case 'ivpac_textarea' :
						?>
							<textarea class="ivpac-change" name="<?php echo esc_attr( 'ivpac_' . $k . '[' . $b->slug . ']' ); ?>" placeholder="<?php echo isset( $curr['name'][$b->slug] ) && $curr['name'][$b->slug] !== '' ? esc_attr( $curr['name'][$b->slug] ) : esc_attr( $b->name ); ?>"><?php self::__get_selected_form_term( $b->slug, $selected_attr, 'textarea' ); ?></textarea> 
						<?php
						break;

						default :
						break;
					}
				}
				if ( self::is_loop() == 'single' || $curr['style'] == 'ivpac_checkbox' ) {
			?>
				<span class="ivpa_name">
					<?php echo isset( $curr['name'][$b->slug] ) && $curr['name'][$b->slug] !== '' ? wp_kses_post( stripcslashes( $curr['name'][$b->slug] ) )  : esc_html( $b->name ); ?>
				</span>
			<?php
				}
			?>
				<?php self::add_price_html( $curr['attr'], ( isset( $curr['price'][$b->slug] ) ? $curr['price'][$b->slug] : '' ), false ); ?>
			</span>
		<?php
		}
	}

	function _go_ip_selectbox( $curr, $curr_attributes, $curr_term_sanitized, $selected_attr ) {
		foreach ( $curr['terms'] as $l => $b ) {
			if ( $this->_term_continue( $curr_attributes, $curr_term_sanitized, $b ) ) {
				continue;
			}
		?>
			<span class="ivpa_term ivpa_active<?php self::__get_selected_term_class( $b->slug, $selected_attr ); ?>" data-term="<?php echo esc_attr( !empty( $b->slug ) ? $b->slug : $b->name ); ?>">
				<?php echo esc_html( ( isset( $curr['name'][$b->slug] ) && $curr['name'][$b->slug] !== '' ? $curr['name'][$b->slug] : $b->name ) ); ?> <?php self::add_price_html( $curr['attr'], ( isset( $curr['price'][$b->slug] ) ? $curr['price'][$b->slug] : '' ), false ); ?>
			</span>
		<?php

		}
	}

	function _go_ip_html( $curr, $curr_attributes, $curr_term_sanitized, $selected_attr ) {
		foreach ( $curr['terms'] as $l => $b ) {
			if ( $this->_term_continue( $curr_attributes, $curr_term_sanitized, $b ) ) {
				continue;
			}
		?>
			<span class="ivpa_term ivpa_active<?php self::__get_selected_term_class( $b->slug, $selected_attr ); ?>" data-term="<?php echo esc_attr( !empty( $b->slug ) ? $b->slug : $b->name ); ?>">
				<?php echo isset( $curr['custom'], $curr['custom'][$b->slug] ) ? wp_kses_post( stripslashes( $curr['custom'][$b->slug] ) ) : ''; ?>
				<?php
					self::add_price_html( $curr['attr'], ( isset( $curr['price'][$b->slug] ) ? $curr['price'][$b->slug] : '' ), false );

					$this->_get_tooltip( $curr, $b );
				?>
			</span>
		<?php

		}
	}

	function _go_ip_image( $curr, $curr_attributes, $curr_term_sanitized, $selected_attr ) {
		foreach ( $curr['terms'] as $l => $b ) {
			if ( $this->_term_continue( $curr_attributes, $curr_term_sanitized, $b ) ) {
				continue;
			}
		?>
			<span class="ivpa_term ivpa_active<?php self::__get_selected_term_class( $b->slug, $selected_attr ); ?>" data-term="<?php echo esc_attr( !empty( $b->slug ) ? $b->slug : $b->name ); ?>"<?php echo ( self::is_loop() == 'single' && isset( $curr['size'] ) ? ' style="width:' . esc_attr( $curr['size'] ) . 'px;"' : '' ); ?>>
			<?php
				if ( !empty( $curr['custom'] ) && !empty( $curr['custom'][$b->slug] ) ) {
			?>
				<img src="<?php echo isset( $curr['custom'], $curr['custom'][$b->slug] ) ? esc_url( $curr['custom'][$b->slug] ) : ''; ?>" alt="<?php echo esc_attr( $b->name ); ?>" />
			<?php
				} else {
			?>
				<img src="<?php echo esc_url( ImprovedOptions()->plugin_url() . '/assets/images/no-image.gif' ) ?>" alt="<?php echo esc_attr( $b->name ); ?>" />
			<?php	
				}

				self::add_price_html( $curr['attr'], ( isset( $curr['price'][$b->slug] ) ? $curr['price'][$b->slug] : '' ), false );

				$this->_get_tooltip( $curr, $b );
			?>
			</span>
		<?php
		}

	}

	function _go_ip_color( $curr, $curr_attributes, $curr_term_sanitized, $selected_attr ) {
		foreach ( $curr['terms'] as $l => $b ) {
			if ( $this->_term_continue( $curr_attributes, $curr_term_sanitized, $b ) ) {
				continue;
			}
		?>
			<span class="ivpa_term ivpa_active<?php self::__get_selected_term_class( $b->slug, $selected_attr ); ?>" data-term="<?php echo esc_attr( !empty( $b->slug ) ? $b->slug : $b->name ); ?>"<?php echo ( self::is_loop() == 'single' && isset( $curr['size'] ) ? ' style="width:' . esc_attr( $curr['size'] ) . 'px;height:' . esc_attr( $curr['size'] ) . 'px;"' : '' ); ?>>
				<span style="background-color:<?php echo isset( $curr['custom'], $curr['custom'][$b->slug] ) ? ImprovedOptions()->esc_color( $curr['custom'][$b->slug] ) : ''; ?>"></span>
			<?php
				self::add_price_html( $curr['attr'], ( isset( $curr['price'][$b->slug] ) ? $curr['price'][$b->slug] : '' ), false );

				$this->_get_tooltip( $curr, $b );
			?>
			</span>
		<?php
		}
	}

	function _go_ip_text( $curr, $curr_attributes, $curr_term_sanitized, $selected_attr ) {
		foreach ( $curr['terms'] as $l => $b ) {
			if ( $this->_term_continue( $curr_attributes, $curr_term_sanitized, $b ) ) {
				continue;
			}
		?>
			<span class="ivpa_term ivpa_active<?php self::__get_selected_term_class( $b->slug, $selected_attr ); ?>" data-term="<?php echo esc_attr( !empty( $b->slug ) ? $b->slug : $b->name ); ?>" >
				<?php
					echo isset( $curr['name'][$b->slug] ) && $curr['name'][$b->slug] !== '' ? wp_kses_post( stripslashes( $curr['name'][$b->slug] ) ) : esc_html( stripslashes( $b->name ) );

					self::add_price_html( $curr['attr'], ( isset( $curr['price'][$b->slug] ) ? $curr['price'][$b->slug] : '' ), false );

					$this->_get_tooltip( $curr, $b );
				?>
			</span>
		<?php
		}
	}

	function _get_terms( $curr, $curr_attributes, $curr_term_sanitized, $selected_attr, $k ) {
	?>
		<div class="ivpa-terms">
	<?php
		if ( empty( $curr['terms'] ) ) {
			if ( current_user_can( 'administrator' ) ) {
				esc_html_e( 'No terms. Use cogs/paint bucket tool to set.', 'improved-variable-product-attributes' );
			}
		}
		else {
			switch ( $curr['style'] ) {
				case 'ivpa_text' :
					$this->_go_ip_text( $curr, $curr_attributes, $curr_term_sanitized, $selected_attr );
				break;
	
				case 'ivpa_color' :
					$this->_go_ip_color( $curr, $curr_attributes, $curr_term_sanitized, $selected_attr );
				break;
	
				case 'ivpa_image' :
					$this->_go_ip_image( $curr, $curr_attributes, $curr_term_sanitized, $selected_attr );
				break;
	
				case 'ivpa_html' :
					$this->_go_ip_html( $curr, $curr_attributes, $curr_term_sanitized, $selected_attr );
				break;
	
				case 'ivpa_selectbox' :
					$this->_go_ip_selectbox( $curr, $curr_attributes, $curr_term_sanitized, $selected_attr );
				break;
	
				case 'ivpac_input' :
				case 'ivpac_checkbox' :
				case 'ivpac_textarea' :
					$this->_go_ip_system( $curr, $curr_attributes, $curr_term_sanitized, $selected_attr, $k );
				break;
	
				case 'ivpac_system' :
					$this->_go_ip_select( $curr, $curr_attributes, $curr_term_sanitized, $selected_attr, $k );
				break;
	
				default :
				break;
			}
		}
	?>
		</div>
	<?php
	}

	function _add_archive_qty() {
		if ( self::is_loop() == 'loop' && self::$settings['wc_settings_ivpa_archive_quantity'] == 'yes' ) {
		?>
			<div class="ivpa_quantity">
				<small><?php esc_html_e( 'Qty:', 'improved-variable-product-attributes' ); ?></small>
				<input type="number" class="ivpa_qty" value="1" min="1" />
			</div>
		<?php
		}
	}

	function ivpa_attributes() {
		if ( self::_stop() ) {
			return '';
		}

		self::reset_loop();

		if ( self::$settings['wc_settings_ivpa_use_caching'] == 'yes' ) {
			$cache = self::_check_cache();
			if ( $cache ) {
				echo $cache;
				return true;
			}
		}

		global $product;

		$available_attributes = $product->get_attributes();
		$curr_customizations = self::get_settings();

		$customOptions = $this->_get_customized_options( $curr_customizations, $available_attributes );

		if ( empty( $customOptions ) ) {
			return '';
		}
		
		$available_variations = $this->_get_available_variations();
		$curr_attributes = $this->_get_variation_attributes();
		$selected_attributes = $this->_get_selected_attributes();

		ob_start();

	?>
		<div class="<?php $this->get_wrapper_classes(); ?>" data-id="<?php the_ID(); ?>" data-variations="<?php echo '%%%JSON_REPLACE_IVPA%%%'; ?>"<?php echo ( self::ivpa_wpml_language() !== false ? ' data-lang="' . esc_attr( ICL_LANGUAGE_CODE ) . '"' : '' ); ?> data-type="<?php echo esc_attr( $product->get_type() ); ?>"<?php if ( self::is_loop() == 'single' ) { echo ' id="ivpa-content"'; } else {?> data-url="<?php the_permalink(); ?>" <?php }; ?><?php $this->get_wrapper_thumb_data(); ?>>
	<?php
		$this->_get_hidden_price_template();

		self::__get_prices_wrap( $product, '' );

		$style = '';
		$willCustom = false;

		foreach ( $customOptions as $k => $v ) {
			if ( is_array( $v ) ) {
				$willCustom = $v['custom'] == 'ivpa_custom' ? true : false;
				$k = $v['key'];
				$v = $v['custom'];
			}
			else {
				return '';
			}

			$curr = $this->__get_customization( $k, $curr_customizations );

			if ( $curr === false ) {
				continue;
			}

			$curr_term_sanitized = self::utf8_urldecode( $v );
			$v = sanitize_title( $v ); // OK

			$selected_attr = $this->_get_selected_attribute( $v, $selected_attributes );

			$this->_make_terms( $curr, $v, $available_attributes );

			$style .= $this->_get_style( $curr, $v, $willCustom );

		?>
			<div class="<?php $this->get_term_wrap_classes( $curr, $willCustom ); ?>" data-attribute="<?php echo esc_attr( $v ); ?>"<?php echo isset( $curr['required'] ) && $curr['required'] == 'yes' || $product->get_type() == 'variable' && $curr['attr'] !== 'ivpa_custom' ? ' data-required="yes"': ''; ?><?php echo isset( $curr['condition'] ) && !empty( $curr['condition'] ) ? ' data-condition="' . esc_attr( $curr['condition'] ) . '"' : ''; ?>>
			<?php
				$this->_select_box_start( $curr, $curr_term_sanitized );

				$this->_get_title( $curr, $selected_attr, $curr_term_sanitized );

				if ( ( $curr['attr'] == 'ivpa_custom' || $willCustom === true ) === true && in_array( $curr['style'], array( 'ivpa_text', 'ivpa_color', 'ivpa_image', 'ivpa_html', 'ivpa_selectbox' ) ) ) {
?>
					<input type="hidden" name="<?php echo esc_attr( 'ivpac_' . $k ); ?>"<?php echo !empty( $selected_attr ) ? ' value="' . $selected_attr . '"' : '' ; ?> />
				<?php
				}
				else {
					if ( self::is_loop() == 'single' && !$product->is_type( 'variable' ) ) {
					?>
						<input type="hidden" name="<?php echo esc_attr( 'attribute_' . $v ); ?>"<?php echo !empty( $selected_attr ) ? ' value="' . $selected_attr . '"' : '' ; ?> />
					<?php
					}
				}

				// Terms
				$this->_get_terms( $curr, $curr_attributes, $curr_term_sanitized, $selected_attr, $k );

				/*if ( self::$settings['wc_settings_ivpa_single_desc'] == 'ivpa_afterattribute' && $curr['style'] !== 'ivpa_selectbox' ) {
					if ( $curr['desc'] !== '' && self::is_loop() == 'single' ) {
						self::__get_desc( $curr );
					}
					self::__add_price_html_with_wrapper( $curr, false );
				}*/

				$this->_select_box_end( $curr );

				if ( self::is_loop() == 'loop' ) {
					self::__get_info_option( $curr );
				}

				if ( self::is_loop() == 'single' && self::$settings['wc_settings_ivpa_single_desc'] == 'ivpa_afterattribute' ) {
					if ( $curr['desc'] !== '' ) {
						self::__get_desc( $curr );
					}
					self::__add_price_html_with_wrapper( $curr, false );
				}
			?>
			</div>
		<?php
			$willCustom = false;
		}

		if ( self::is_loop() == 'single' ) {
		?>
			<a class="ivpa_reset_variations" href="#reset"><?php esc_html_e( 'Clear selection', 'improved-variable-product-attributes' ); ?></a>
		<?php
		}

		$this->_add_archive_qty();

		self::__get_prices_wrap( $product, '-bottom' );

		if ( !empty( $style ) ) {
			if ( self::is_loop() == 'single' ) {
				echo '<style type="text/css">' . $style . '</style>';
			}
			else {
				self::$settings['archive-css-echo'] .= $style;
			}
		}

	?>
		</div>
	<?php

		$html = trim( ob_get_clean() );

		echo str_replace( '%%%JSON_REPLACE_IVPA%%%', esc_attr( json_encode( $available_variations ) ), $html );

		if ( empty( $_REQUEST ) && self::$settings['wc_settings_ivpa_use_caching'] == 'yes' ) {
			update_post_meta( get_the_ID(), '_ivpa_cached_' . self::is_loop() . '_' . get_locale(), $html );
			update_post_meta( get_the_ID(), '_ivpa_cached_data', $available_variations );
		}

	}

	public static function __get_selected_form_term( $term_slug, $selected_attr, $field ) {
		$selected = self::__get_selection_from_string( $selected_attr ); 

		if ( in_array( $term_slug, $selected ) ) {
			$add = '';

			switch( $field ) {
				case 'input' :
					$add = ' value="' . esc_attr( $term_slug ) . '"';
				break;

				case 'checkbox' :
					$add = ' checked';
				break;
				
				case 'textarea' :
					$add = $term_slug;
				break;
				
				case 'select' :
					$add = ' selected';
				break;

				default :
				break;
			}

			echo $add;
		}
	}

	public static function __get_selection_from_string( $selected_attr ) {
		if ( strpos( $selected_attr, ','  ) !== false ) {
			return explode( ',', $selected_attr );
		}

		return array( $selected_attr );
	}

	public static function __get_selected_term_class( $term_slug, $selected_attr ) {
		if ( ( self::is_loop() == 'loop' && self::$settings['wc_settings_ivpa_archive_mode'] == 'ivpa_showonly' ) === false ) {
			$selected = self::__get_selection_from_string( $selected_attr );

			if ( $term_slug !== '' && in_array( $term_slug, $selected ) ) {
				echo esc_attr( ' ivpa_clicked' );
			}
		}
	}
	
	public static function __get_tooltip_price_string( $price ) {
		if ( $price > 0 ) {
			esc_html_e( 'Including it adds to price', 'improved-variable-product-attributes' );							
		}
		else {
			esc_html_e( 'Including it reduces from price', 'improved-variable-product-attributes' );
		}
	}

	public static function __add_price_html_with_wrapper( $curr, $select, $is_tooltip = false ) {
		if ( isset( $curr['addprice'] ) && !empty( $curr['addprice'] ) && $curr['addprice'] !== 'false' ) {
		?>
			<div class="ivpa_price">
			<?php
				if ( $is_tooltip ) {
					self::__get_tooltip_price_string( $curr['addprice'] );
				}

				self::add_price_html( $curr['attr'], $curr['addprice'], $select );
			?>
			</div>
		<?php
		}
	}

	public static function __get_prices_wrap( $product, $place ) {
		if ( self::is_loop() == 'single' && SevenVXGet()->get_option( 'wc_settings_ivpa_single_prices', 'improved_options', 'product' ) == 'plugin' . $place ) {
		?>
			<div class="ivpa-prices-add">
				<p class="price"><?php echo wp_kses_post( $product->get_price_html() ); ?></p>
			</div>
		<?php
		}

		if ( self::is_loop() == 'loop' && SevenVXGet()->get_option( 'wc_settings_ivpa_archive_prices', 'improved_options', 'product' ) == 'plugin' . $place ) {
		?>
			<div class="ivpa-prices-add">
				<span class="price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
			</div>
		<?php
		}
	}

	public static function __get_css_class() {
		return  self::is_loop() == 'single' ? '#ivpa-content' : '.ivpa-content';
	}

	public static function __get_info_option( $curr ) {
		global $product;
?>
		<div class="ivpa-info-box">
			<span class="ivpa-info-box-icon"></span>
			<div class="ivpa-info-box-tooltip">
				<span class="ivpa_tooltip">
					<span>
<?php
						if ( isset( $curr['required'] ) && $curr['required'] == 'yes' || $product->get_type() == 'variable' && $curr['attr'] !== 'ivpa_custom' ) {
							echo apply_filters( 'ivpa_required_field_content', ' <div class="ivpa-info-box-required">' . esc_html__( 'This option is required', 'improved-variable-product-attributes' ) . '</div>' ) ;
						}
						else {
							echo apply_filters( 'ivpa_not_required_field_content', ' <div class="ivpa-info-box-not-required">' . esc_html__( 'This option is not required', 'improved-variable-product-attributes' ) . '</div>' ) ;
						}

						self::__add_price_html_with_wrapper( $curr, false, 'loop', true );
?>
					</span>
				</span>
			</div>
		</div>
<?php
	}

	public static function __get_desc( $curr ) {
?>
		<div class="ivpa_desc">
			<?php echo wp_kses_post( stripslashes( $curr['desc'] ) ); ?>
		</div>
<?php
	}

	function __get_customization( $k, $customizations ) {
		if ( $k !== false ) {
			$array = array(
				'name' => ( isset( $customizations['ivpa_name'][$k] ) ? $customizations['ivpa_name'][$k] : '' ),
				'attr' => ( isset( $customizations['ivpa_attr'][$k] ) ? $customizations['ivpa_attr'][$k] : '' ),
				'style' => ( isset( $customizations['ivpa_style'][$k] ) ? $customizations['ivpa_style'][$k] : 'ivpa_text' ),
				'title' => ( isset( $customizations['ivpa_title'][$k] ) ? $customizations['ivpa_title'][$k] : '' ),
				'desc' => ( isset( $customizations['ivpa_desc'][$k] ) ? stripslashes( $customizations['ivpa_desc'][$k] ) : '' ),
				'custom' => ( isset( $customizations['ivpa_custom'][$k] ) ? $customizations['ivpa_custom'][$k] : array( 'style' => 'ivpa_border', 'normal' => '#bbbbbb', 'active' => '#1e73be', 'disabled' => '#dddddd', 'outofstock' => '#e45050' ) ),
				'ivpa_tooltip' => ( isset( $customizations['ivpa_tooltip'][$k] ) ? $customizations['ivpa_tooltip'][$k] : array() ),
				'size' => ( isset( $customizations['ivpa_size'][$k] ) ? $customizations['ivpa_size'][$k] : null ),
				'swatchDesign' => ( isset( $customizations['ivpa_swatchDesign'][$k] ) ? $customizations['ivpa_swatchDesign'][$k] : null ),
				'addprice' => ( isset( $customizations['ivpa_addprice'][$k] ) ? $customizations['ivpa_addprice'][$k] : null ),
				'order' => ( isset( $customizations['ivpa_custom_order'][$k] ) ? $customizations['ivpa_custom_order'][$k] : null ),
				'multiselect' => ( isset( $customizations['ivpa_multiselect'][$k] ) ? $customizations['ivpa_multiselect'][$k] : null ),
				'required' => ( isset( $customizations['ivpa_required'][$k] ) ? $customizations['ivpa_required'][$k] : null ),
				'condition' => ( isset( $customizations['ivpa_condition'][$k] ) ? ( $customizations['ivpa_condition'][$k] == 'false' ? null : $customizations['ivpa_condition'][$k] ) : null ),
				'add_empty' => ( isset( $customizations['ivpa_add_empty'][$k] ) ? ( $customizations['ivpa_add_empty'][$k] == 'false' ? null : $customizations['ivpa_add_empty'][$k] ) : null ),
				'price' => ( isset( $customizations['ivpa_price'][$k] ) ? $customizations['ivpa_price'][$k] : null ),
				'archive_include' => ( isset( $customizations['ivpa_archive_include'][$k] ) ? $customizations['ivpa_archive_include'][$k] : null ),
				'selectable' => ( isset( $customizations['ivpa_svariation'][$k] ) ? $customizations['ivpa_svariation'][$k] : null ),
			);
		}
		else {
			$array = array(
				'name' => '',
				'attr' => '',
				'style' => 'ivpa_text',
				'title' => '',
				'desc' => '',
				'custom' => array( 'style' => 'ivpa_border', 'normal' => '#bbbbbb', 'active' => '#1e73be', 'disabled' => '#dddddd', 'outofstock' => '#e45050' ),
				'ivpa_tooltip' => array(),
				'size' => null,
				'design' => null,
				'addprice' => null,
				'order' => null,
				'multiselect' => null,
				'required' => null,
				'condition' => null,
				'add_empty' => null,
				'price' => null,
				'archive_include' => null,
			);
		}

		if ( self::is_loop() == 'loop' && self::$settings['wc_settings_ivpa_archive_mode'] == 'ivpa_showonly' && isset( $array['archive_include'] ) && $array['archive_include'] == 'no' ) {
			$array = false;
		}

		if ( self::is_loop() == 'loop' && self::$settings['wc_settings_ivpa_archive_mode'] == 'ivpa_showonly' && $array['attr'] == 'ivpa_custom' && in_array( $array['style'], array( 'ivpac_input', 'ivpac_checkbox', 'ivpac_textarea', 'ivpac_system' ) ) ) {
			$array = false;
		}

		return $array;
	}

	public static function add_price_html( $opt, $price, $select ) {
	
		if ( $opt == 'ivpa_custom' && isset( $price ) && $price !== '' ) {
			if ( $select ) {
				echo 'data-add="' . esc_attr( $price ) . '"';
			}
			else {
		?>
			<span class="ivpa-addprice" data-add="<?php echo esc_attr( $price ); ?>"><?php echo strip_tags( wc_price( $price ) ); ?></span>
		<?php
			}
		}

	}

	function ivpa_add_to_cart_callback() {

		$product_id = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['ipo_product_id'] ) );
		$quantity = isset( $_POST['ipo_quantity'] ) && !empty( $_POST['ipo_quantity'] ) ? apply_filters( 'woocommerce_stock_amount', $_POST['ipo_quantity'] ) : 1;
		$variation_id = isset( $_POST['variation_id'] ) && !empty( $_POST['variation_id'] ) ? $_POST['variation_id'] : '';

		if ( isset( $_POST['variation' ] ) && is_array( $_POST['variation' ] ) ) {
			foreach ( $_POST['variation'] as $k => $v ) {
				$variation[$k] = self::utf8_urldecode($v);
			}
		}
		else {
			$variation = array();
		}

		$product_data = array();

		if ( isset( $_POST['ivpac'] ) ) {
			$ivpac = array();
			parse_str( $_POST['ivpac'], $ivpac );


			foreach ( $ivpac as $k => $v ) {
				if ( substr( $k, 0, 6 ) == 'ivpac_' && !empty( $v ) ) {
					if ( is_array( $v ) ) {
						$v = array_filter( $v );
					}
					if ( !empty( $v ) ) {
						$product_data['ivpac'][substr( $k, 6 )] = is_array( $v ) ? implode( ', ', $v ) : $v;
					}
				}
			}

		}

		$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );

		$id = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation, $product_data );

		if ( $passed_validation && $id ) {
			do_action( 'woocommerce_ajax_added_to_cart', $product_id );

			if ( get_option( 'woocommerce_cart_redirect_after_add' ) == 'yes' ) {
				wc_add_to_cart_message( $product_id );
			}

			$data = WC_AJAX::get_refreshed_fragments();
		}
		else {

				WC_AJAX::json_headers();

				$data = array(
					'error' => true,
					'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id )
				);

				$data = json_encode( $data );
		}

		wp_die( $data );
		exit();
	}

	function ivpa_repair_cart(){
		if ( defined( 'DOING_AJAX' ) ) {
			wc_setcookie( 'woocommerce_items_in_cart', 1 );
			wc_setcookie( 'woocommerce_cart_hash', md5( json_encode( WC()->cart->get_cart() ) ) );
			do_action( 'woocommerce_set_cart_cookies', true );
		}
	}

	public static function ivpa_wpml_language() {
		if( class_exists( 'SitePress' ) ) {
			global $sitepress;

			if ( method_exists( $sitepress, 'get_default_language' ) ) {

				$default_language = $sitepress->get_default_language();
				$current_language = $sitepress->get_current_language();

				if ( $default_language != $current_language ) {
					return sanitize_title( $current_language );
				}
			}
		}

		return false;
	}

	public static function ivpa_wpml_get_current_slug( $curr_term, $attr ) {
		if ( function_exists( 'icl_object_id' ) ) {
			global $sitepress;

			if ( method_exists( $sitepress, 'get_default_language' ) ) {
				$default_language = $sitepress->get_default_language();
				$current_language = $sitepress->get_current_language();

				if ( $default_language != $current_language ) {

					$term_id = icl_object_id( $curr_term->term_id, $attr, false, $default_language );
					$term = get_term( $term_id, $attr );

					return $term->slug;
				}
			}
		}

		return $curr_term->slug;
	}

}

add_action( 'init', array( 'XforWC_Improved_Options_Frontend', 'init' ), 998 );

if ( !function_exists( 'xforwc__add_meta_information' ) ) {
	function xforwc__add_meta_information_action() {
		echo '<meta name="generator" content="XforWooCommerce.com - ' . esc_attr( implode( ' - ', apply_filters( 'xforwc__add_meta_information_used', array() ) ) ) . '"/>';
	}
	function xforwc__add_meta_information() {
		add_action( 'wp_head', 'xforwc__add_meta_information_action', 99 );
	}
	xforwc__add_meta_information();
}
