<?php if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.
/**
 *
 * Field: switcher
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'WPGPSC_Field_switcher' ) ) {
	class WPGPSC_Field_switcher extends WPGPSC_Fields {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {

			$active     = ( ! empty( $this->value ) ) ? ' wpgpsc--active' : '';
			$text_on    = ( ! empty( $this->field['text_on'] ) ) ? $this->field['text_on'] : esc_html__( 'On', 'wpgpsc' );
			$text_off   = ( ! empty( $this->field['text_off'] ) ) ? $this->field['text_off'] : esc_html__( 'Off', 'wpgpsc' );
			$text_width = ( ! empty( $this->field['text_width'] ) ) ? ' style="width: ' . esc_attr( $this->field['text_width'] ) . 'px;"' : '';

			echo wp_kses_post( $this->field_before() );

			echo '<div class="wpgpsc--switcher' . esc_attr( $active ) . '"' . wp_kses_post( $text_width ) . '>';
			echo '<span class="wpgpsc--on">' . esc_attr( $text_on ) . '</span>';
			echo '<span class="wpgpsc--off">' . esc_attr( $text_off ) . '</span>';
			echo '<span class="wpgpsc--ball"></span>';
			echo '<input type="hidden" name="' . esc_attr( $this->field_name() ) . '" value="' . esc_attr( $this->value ) . '"' . $this->field_attributes() . ' />'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $this->field_attributes() is escaped before being passed in.
			echo '</div>';

			echo ( ! empty( $this->field['label'] ) ) ? '<span class="wpgpsc--label">' . esc_attr( $this->field['label'] ) . '</span>' : '';

			echo wp_kses_post( $this->field_after() );

		}

	}
}
