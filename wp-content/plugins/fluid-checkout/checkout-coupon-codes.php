<?php
defined( 'ABSPATH' ) || exit;

$Client_id = constant("Client_id");
$Client_secret_key = constant("Client_secret_key");


/**
 * Feature for adding coupon codes to checkout.
 */
class FluidCheckout_CouponCodes extends FluidCheckout {

	/**
	 * __construct function.
	 */
	public function __construct() {
		$this->hooks();

	}



	/**
	 * Initialize hooks.
	 */
	public function hooks() {
		// Very late hooks
		add_action( 'wp', array( $this, 'very_late_hooks' ), 100 );

		// Integrated coupon code section at checkout
		if ( 'yes' === get_option( 'fc_enable_checkout_coupon_codes', 'yes' ) ) {
			// Checkout coupon notice
			remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );

			// Coupon code substep
			add_action( 'fc_output_step_payment', array( $this, 'output_substep_coupon_codes' ), 10 );
			add_filter( 'woocommerce_update_order_review_fragments', array( $this, 'add_coupon_codes_text_fragment' ), 10 );
		}
	}

	/**
	 * Add or remove very late hooks.
	 */
	public function very_late_hooks() {
		// Bail if use of coupons not enabled
		if ( ! wc_coupons_enabled() ) { return; }

		// Body Class
		add_filter( 'body_class', array( $this, 'add_body_class' ) );

		// Enqueue
		add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ), 5 );
		add_action( 'wp_enqueue_scripts', array( $this, 'maybe_enqueue_assets' ), 10 );

		// JS settings object
		add_filter( 'fc_js_settings', array( $this, 'add_js_settings' ), 10 );

		// Prevent hiding coupon code field behind a link button, as it is implemented directly
		add_filter( 'fc_hide_optional_fields_skip_list', array( $this, 'prevent_hide_optional_fields_coupon_code' ), 10 );

		// Actions
		add_action( 'wc_ajax_fc_add_coupon_code', array( $this, 'add_coupon_code' ), 10 );
		add_action( 'wc_ajax_fc_remove_coupon_code', array( $this, 'remove_coupon_code' ), 10 );
	}



	/**
	 * Add page body class for feature detection.
	 *
	 * @param   array  $classes  Body classes array.
	 */
	public function add_body_class( $classes ) {
		// Bail if not at checkout
		if ( ! function_exists( 'is_checkout' ) || ! is_checkout() || is_order_received_page() || is_checkout_pay_page() ) { return $classes; }

		return array_merge( $classes, array( 'has-fc-coupon-code-fields' ) );
	}



	/**
	 * Register assets.
	 */
	public function register_assets() {
		// Maybe load RTL file
		$rtl_suffix = is_rtl() ? '-rtl' : '';

		// Scripts
		wp_register_script( 'fc-checkout-coupons', self::$directory_url . 'js/checkout-coupons'. self::$asset_version . '.js', array( 'jquery', 'require-bundle' ), NULL, true );
		wp_add_inline_script( 'fc-checkout-coupons', 'window.addEventListener("DOMContentLoaded",function(){CheckoutCoupons.init(fcSettings.checkoutCoupons);})' );

		// Styles
		wp_register_style( 'fc-checkout-coupons', self::$directory_url . 'css/checkout-coupons'. $rtl_suffix . self::$asset_version . '.css', NULL, NULL );
	}

	/**
	 * Enqueue assets.
	 */
	public function enqueue_assets() {
		wp_enqueue_script( 'fc-checkout-coupons' );
		wp_enqueue_style( 'fc-checkout-coupons' );
	}

	/**
	 * Maybe enqueue assets on the checkout page.
	 */
	public function maybe_enqueue_assets() {
		// Bail if not at checkout
		if ( ! function_exists( 'is_checkout' ) || ! is_checkout() || is_order_received_page() || is_checkout_pay_page() ) { return; }

		// Bail if coupon feature is not enabled
		if ( 'yes' !== get_option( 'fc_enable_checkout_coupon_codes', 'yes' ) ) { return; }

		$this->enqueue_assets();
	}



	/**
	 * Add settings to the plugin settings JS object.
	 *
	 * @param   array  $settings  JS settings object of the plugin.
	 */
	public function add_js_settings( $settings ) {

		$settings[ 'checkoutCoupons' ] = apply_filters( 'fc_checkout_coupons_script_settings', array(
			'isEnabled'                => get_option( 'fc_enable_checkout_coupon_codes', 'yes' ),
			'addCouponCodeNonce'       => wp_create_nonce( 'fc-add-coupon-code' ),
			'removeCouponCodeNonce'    => wp_create_nonce( 'fc-remove-coupon-code' ),
		) );

		return $settings;
	}



	/**
	 * Output coupon codes substep.
	 *
	 * @param   string  $step_id     Id of the step in which the substep will be rendered.
	 */
	public function output_substep_coupon_codes( $step_id ) {
		$substep_id = 'coupon_codes';
		$substep_title = get_option( 'fc_display_coupon_code_section_title', 'no' ) === 'yes' ? apply_filters( 'fc_substep_coupon_codes_section_title', __( 'Coupon code', 'fluid-checkout' ) ) : null;
		FluidCheckout_Steps::instance()->output_substep_start_tag( $step_id, $substep_id, $substep_title );

		$this->output_coupon_codes_messages_container();
		$this->output_substep_text_coupon_codes();

		FluidCheckout_Steps::instance()->output_substep_fields_start_tag( $step_id, $substep_id, false );
		$this->output_substep_coupon_codes_fields();
		FluidCheckout_Steps::instance()->output_substep_fields_end_tag();

		FluidCheckout_Steps::instance()->output_substep_end_tag( $step_id, $substep_id, $substep_title, false );
	}



	/**
	 * Output coupon codes fields.
	 *
	 * @param   array  $field_args               The coupon code field arguments. Will use default values if attributes are not passed in.
	 * @param   array  $expansible_section_args  The attributes for the coupon code expansible section. Will use default values if attributes are not passed in.
	 */
	public function output_substep_coupon_codes_fields( $field_args = array(), $expansible_section_args = array(), $output_handle = true, $section_key = null ) {
		$coupon_code_field_label = apply_filters( 'fc_coupon_code_field_label', __( 'Coupon code', 'fluid-checkout' ) );
		$coupon_code_field_placeholder = apply_filters( 'fc_coupon_code_field_placeholder', __( 'Enter your code here', 'fluid-checkout' ) );
		$coupon_code_button_label = apply_filters( 'fc_coupon_code_button_label', _x( 'Apply', 'Button label for applying coupon codes', 'fluid-checkout' ) );

		// Maybe define section key
		$section_id = 'coupon_code';
		if ( empty( $section_key ) ) {
			$section_key = $section_id . '--' . rand();
		}

		// Coupon code field args
		$field_key = 'coupon_code';
		$coupon_code_field_args = array_merge( array(
			'required'                   => false,
			'fc_skip_server_validation'  => true,
			'class'                      => array( 'form-row-wide' ),
			'placeholder'                => $coupon_code_field_placeholder,
			'custom_attributes'          => array(
				'aria-label'             => $coupon_code_field_label,
				'data-autofocus'         => true,
			),
			'input_class'                => array('CouponInputOnCheckOut'),
		), $field_args );

		// Expansible section args
		$coupon_code_expansible_args = array_merge( array(
			'initial_state' => true === apply_filters( 'fc_coupon_code_field_initially_expanded', false ) ? 'expanded' : 'collapsed',
			'section_attributes' => array(
				'class' => 'fc-coupon_code__collapsible',
			),
		), $expansible_section_args );

		// Define toggle label
		$coupon_code_toggle_label = null;
		if ( $output_handle ) {
			// Output coupon code field and button in an expansible form section
			$coupon_code_toggle_label = 'yes' === get_option( 'fc_optional_fields_link_label_lowercase', 'yes' ) ? strtolower( $coupon_code_field_label ) : $coupon_code_field_label;
			/* translators: %s: Form field label */
			$coupon_code_toggle_label = apply_filters( 'fc_expansible_section_toggle_label_' . $section_id, sprintf( __( 'Add %s', 'fluid-checkout' ), $coupon_code_toggle_label ) );
		}

		// Output section
		FluidCheckout_Steps::instance()->output_expansible_form_section_start_tag( $section_key, $coupon_code_toggle_label, $coupon_code_expansible_args );

		if(isset($_POST['ApplyCouponCode'])){
			$CouponValue = $_POST['ApplyCouponCode'];
			WC()->cart->apply_coupon($CouponValue);
		}

		?>
		<div class="fc-coupon-code-section">
			<?php woocommerce_form_field( $field_key, $coupon_code_field_args ); ?>
			<!-- <button type="button" class="fc-coupon-code__apply <?php echo esc_attr( apply_filters( 'fc_coupon_code_apply_button_classes', 'button' ) ); ?>" data-apply-coupon-button><?php echo esc_html( $coupon_code_button_label ); ?></button> -->

			<!-- If any problem arries please command the below button and command out the above button -->
			<button type="button" class="Checkout_code__apply <?php echo esc_attr( apply_filters( 'fc_coupon_code_apply_button_classes', 'button' ) ); ?>" data-apply-coupon-button><?php echo esc_html( $coupon_code_button_label ); ?></button>
			<script  type="text/javascript">

function fetchCall(apiCall, headerAddon = {}){
   let header = {
	"Content-type": "application/json; charset=UTF-8",
	'Accept': 'application/json',
};
header = {...header, ...headerAddon}; 

console.log(header);

let outputdata = [];
	outputdata= fetch(<?php echo "'".site_url()."'";?>+'/php_api/coupon_apiCall.php/', {
	method: "POST",
	body:JSON.stringify(apiCall),
	headers: header
})
.then((response) => response.json())
.then((json)=>json);
console.log("outputdata: ",outputdata);
return outputdata;

}
let wcDetail2 = <?php echo json_encode(WC()->cart);?>;
let wcDetail = <?php echo json_encode(get_users( array( 'role' => 'administrator' )));?>;
console.log(wcDetail);
console.log(wcDetail2)

class formCreation {
constructor(couponID){
	this.couponId = couponID;
}

 form(){
	let form3 = document.createElement('form');
	form3.setAttribute('action',"");
	form3.setAttribute('method','post');
	let createInput = document.createElement('input');
	createInput.setAttribute('name','ApplyCouponCode');
	createInput.value = this.couponId;
	form3.appendChild(createInput, true);
	form3.submit();
	document.body.appendChild(form3); // Append the form to the document body

	form3.submit();
	// console.log(form3.children);

}
}


// let form3 = new formCreation('SKDYAP5048748');
// form3.form();



document.querySelector('.Checkout_code__apply.button').onclick = ()=>{
		let couponCode = document.querySelector('.CouponInputOnCheckOut').value;
	// Generate a random UUID

function uuidv4() {
return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'
.replace(/[xy]/g, function (c) {
	const r = Math.random() * 16 | 0, 
	v = c == 'x' ? r : (r & 0x3 | 0x8);

	// console.log(r,v,c, r & 0x3 | 0x8, 16 & 10, 0x3 & 1);
	return v.toString(16);
});
}

const random_uuid = uuidv4();

async function ajaxCall(couponCode) {
	let	apicall = { 'couponCode':{
			'couponCode': couponCode,
			'X_Correlation_Id': random_uuid, 
			'X_Client_Id': '<?php echo $Client_id;?>', 
			'X_Client_Secret': '<?php echo $Client_secret_key;?>'
			}	
		}; 
		
		await fetchCall(apicall).then( (data)=>{
			let data2;
			console.log(typeof(data));
			if(typeof(data) == "object"){
				data2 = data;
			}else if(typeof(data) == "string"){
			data2 = JSON.parse(data);
			}
			if(data2.meta.status == 'succeeded'){
			  console.log(data2);

			  
//   {
//    "data": {
//     "product_code": "S6HVW",
//     "product_label": "Hesh Ev0 1000/-",
//     "remaining_value": 100000,
//     "expiration_date": "2023-08-29 23:59:59"
//   },
//   "meta": {
//     "status": "succeeded"
//   },
//   "links": [
//     {
//       "rel": "authorize",
//       "href": "/api/transactions"
//     }
//   ]
// }


const date = new Date();
const day = date.getDate();
const month = date.getMonth() + 1; // getMonth() returns month from 0 to 11
const year = date.getFullYear();

const CurrentDate = `${year}-${month}-${day}`;

const dateString = "2023-08-29 23:59:59";
const date2 = new Date(dateString.replace(' ','T')+'.000Z');
const timestamp = Math.floor(date2.getTime() / 1000); // Divide by 1000 to convert from milliseconds to seconds


let postMetaKey = {
		'_edit_last': 1,
		'_wp_old_date':CurrentDate,
		'discount_type':'fixed_cart',
		'coupon_amount':(data2.data.remaining_value)/100,
		'individual_use':"yes",
		'usage_limit':'',
		'usage_limit_per_user':'',
		'limit_usage_to_x_items':0,
		'usage_count':'',
		'date_expires':timestamp,
		'free_shipping':'no',
		'exclude_sale_items':'yes',
		'_edit_lock':String(Math.floor(Math.random()*10000000000)) + ':1',
		'_used_by':'',
};
		let value =  data2.data.remaining_value;
		if(value > 0){

				fetchCall({'addCart':couponCode,"postMetaKey":postMetaKey}).then((data)=>{
					console.log(data);
					let form1 = new formCreation(couponCode);
					form1.form();
			

				});
			}
		}else if(data2.meta.status == 'error'){
			let form1 = new formCreation(couponCode);
					form1.form();
		}

		});

	}


	ajaxCall(couponCode);

}

			</script>
		</div>
		<ul class="woocommerce-error" role="alert">
			<li>
			Coupon code already applied!		</li>
	</ul>
	<div class="woocommerce-message" role="alert">
		Coupon code applied successfully.	</div>


		<?php
		FluidCheckout_Steps::instance()->output_expansible_form_section_end_tag();
	}



	/**
	 * Prevent hiding optional coupon code field behind a link button, as it is implemented directly.
	 *
	 * @param   array  $skip_list  List of optional fields to skip hidding.
	 */
	public function prevent_hide_optional_fields_coupon_code( $skip_list ) {
		$skip_list[] = 'coupon_code';
		return $skip_list;
	}



	/**
	 * Get coupon codes substep added coupon codes.
	 */
	public function get_substep_text_coupon_codes() {
		$html = '<div class="fc-step__substep-text-content fc-step__substep-text-content--coupon-codes">';
		ob_start();

		do_action( 'fc_substep_coupon_codes_text_before' );

		foreach ( WC()->cart->get_coupons() as $code => $coupon ) :
			// Get coupon label with changed "remove" link
			ob_start();
			wc_cart_totals_coupon_html( $coupon );
			$coupon_html_esc = str_replace( esc_html( __( '[Remove]', 'woocommerce' ) ), esc_html( __( 'Remove', 'fluid-checkout' ) ), ob_get_clean() );
			?>
			<?php // The function `sanitize_title` is used below to convert the string into a CSS-class-like string ?>
			<div class="fc-coupon-codes__coupon coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
				<strong class="fc-coupon-codes__coupon-code"><?php wc_cart_totals_coupon_label( $coupon ); ?></strong>
				<span class="fc-coupon-codes__coupon-amount"><?php echo $coupon_html_esc; // WPCS: XSS ok. ?></span>
			</div>
			<?php
		endforeach;

		do_action( 'fc_substep_coupon_codes_text_after' );

		$html .= ob_get_clean();
		$html .= '</div>';

		return apply_filters( 'fc_substep_coupon_codes_text', $html );
	}

	/**
	 * Add coupon codes substep review text as checkout fragment.
	 *
	 * @param array $fragments Checkout fragments.
	 */
	public function add_coupon_codes_text_fragment( $fragments ) {
		$html = $this->get_substep_text_coupon_codes();
		$fragments['.fc-step__substep-text-content--coupon-codes'] = $html;
		return $fragments;
	}

	/**
	 * Output coupon codes substep review text.
	 */
	public function output_substep_text_coupon_codes() {
		echo $this->get_substep_text_coupon_codes();
	}

	/**
	 * Output the coupon codes messages container element.
	 */
	public function output_coupon_codes_messages_container() {
		echo '<div class="fc-coupon-code-messages"></div>';
	}



	/**
	 * AJAX Add a coupon code to the cart.
	 * @see WC_AJAX::apply_coupon
	 */
	public function add_coupon_code() {
		check_ajax_referer( 'fc-add-coupon-code', 'security' );

		$coupon_code = sanitize_text_field( wp_unslash( $_REQUEST['coupon_code'] ) );
		$reference_id = sanitize_text_field( wp_unslash( $_REQUEST['reference_id'] ) );

		if ( ! empty( $coupon_code ) ) {
			WC()->cart->add_discount( wc_format_coupon_code( $coupon_code ) );

			// Intercept notices to avoid them being displayed on other pages
			ob_start();
			wc_print_notices();
			$message = ob_get_clean();
			
			wp_send_json(
				array(
					'result'           => false === strpos( $message, 'woocommerce-error' ) ? 'success' : 'error',
					'coupon_code'      => $coupon_code,
					'reference_id'     => $reference_id,
					'message'          => $message,
				)
			);
		}
		else {
			wp_send_json(
				array(
					'result'           => 'error',
					'coupon_code'      => $coupon_code,
					'reference_id'     => $reference_id,
					'error_slug'       => 'coupon_code_does_not_exist',
					'message'          => WC_Coupon::get_generic_coupon_error( WC_Coupon::E_WC_COUPON_PLEASE_ENTER ),
				)
			);
		}
	}

	/**
	 * AJAX Remove a coupon code from the cart.
	 * @see WC_AJAX::remove_coupon
	 */
	public function remove_coupon_code() {
		check_ajax_referer( 'fc-remove-coupon-code', 'security' );

		$coupon_code = sanitize_text_field( wp_unslash( $_REQUEST['coupon_code'] ) );
		$reference_id = sanitize_text_field( wp_unslash( $_REQUEST['reference_id'] ) );

		if ( ! empty( $coupon_code ) ) {
			WC()->cart->remove_coupon( wc_format_coupon_code( $coupon_code ) );
			wc_add_notice( __( 'Coupon has been removed.', 'woocommerce' ) );

			// Intercept notices to avoid them being displayed on other pages
			ob_start();
			wc_print_notices();
			$message = ob_get_clean();
			
			wp_send_json(
				array(
					'result'           => false === strpos( $message, 'woocommerce-error' ) ? 'success' : 'error',
					'coupon_code'      => $coupon_code,
					'reference_id'     => $reference_id,
					'message'          => $message,
				)
			);
		}
		else {
			wc_add_notice( __( 'Sorry there was a problem removing this coupon.', 'woocommerce' ), 'error' );

			// Intercept notices to avoid them being displayed on other pages
			ob_start();
			wc_print_notices();
			$message = ob_get_clean();

			wp_send_json(
				array(
					'result'           => 'error',
					'coupon_code'      => $coupon_code,
					'reference_id'     => $reference_id,
					'error_slug'       => 'coupon_code_not_provided',
					'message'          => $message,
				)
			);
		}
	}

}

FluidCheckout_CouponCodes::instance();
