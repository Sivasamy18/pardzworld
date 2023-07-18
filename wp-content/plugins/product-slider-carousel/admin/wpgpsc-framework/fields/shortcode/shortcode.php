<?php if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.
/**
 *
 * Field: shortcode
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'WPGPSC_Field_shortcode' ) ) {
	class WPGPSC_Field_shortcode extends WPGPSC_Fields {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {

			// Get the Post ID.
			$post_id = get_the_ID();

			echo ( ! empty( $post_id ) ) ? '<div class="wpgpsc--shortcode-field-wrap"><code title="Click to copy the Shortcode">[psc_product id="' . esc_attr( $post_id ) . '"]</code></div>' : '';
		}

	}
}
