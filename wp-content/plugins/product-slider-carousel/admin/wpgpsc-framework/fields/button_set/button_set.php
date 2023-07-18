<?php if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.
/**
 *
 * Field: button_set
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'WPGPSC_Field_button_set' ) ) {
	class WPGPSC_Field_button_set extends WPGPSC_Fields {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {

			$kses_defaults     = wp_kses_allowed_html( 'post' );
			$svg_args          = array(
				'svg'   => array(
					'class'           => true,
					'aria-hidden'     => true,
					'aria-labelledby' => true,
					'role'            => true,
					'xmlns'           => true,
					'width'           => true,
					'height'          => true,
					'viewbox'         => true, // <= Must be lower case!
				),
				'g'     => array( 'fill' => true ),
				'title' => array( 'title' => true ),
				'path'  => array(
					'd'    => true,
					'fill' => true,
				),
			);
			$kses_allowed_tags = array_merge( $kses_defaults, $svg_args );

			$args = wp_parse_args(
				$this->field,
				array(
					'multiple'   => false,
					'options'    => array(),
					'query_args' => array(),
				)
			);

			$value = ( is_array( $this->value ) ) ? $this->value : array_filter( (array) $this->value );

			echo wp_kses_post( $this->field_before() );

			if ( isset( $this->field['options'] ) ) {

				$options = $this->field['options'];
				$options = ( is_array( $options ) ) ? $options : array_filter( $this->field_data( $options, false, $args['query_args'] ) );

				if ( is_array( $options ) && ! empty( $options ) ) {

					echo '<div class="wpgpsc-siblings wpgpsc--button-group" data-multiple="' . esc_attr( $args['multiple'] ) . '">';

					foreach ( $options as $key => $option ) {

						$type    = ( $args['multiple'] ) ? 'checkbox' : 'radio';
						$extra   = ( $args['multiple'] ) ? '[]' : '';
						$active  = ( in_array( $key, $value ) || ( empty( $value ) && empty( $key ) ) ) ? ' wpgpsc--active' : '';
						$checked = ( in_array( $key, $value ) || ( empty( $value ) && empty( $key ) ) ) ? ' checked' : '';

						echo '<div class="wpgpsc--sibling wpgpsc--button' . esc_attr( $active ) . '">';
						echo '<input type="' . esc_attr( $type ) . '" name="' . esc_attr( $this->field_name( $extra ) ) . '" value="' . esc_attr( $key ) . '"' . $this->field_attributes() . esc_attr( $checked ) . '/>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						echo wp_kses( $option, $kses_allowed_tags );
						echo '</div>';

					}

					echo '</div>';

				} else {

					echo ! empty( $this->field['empty_message'] ) ? esc_attr( $this->field['empty_message'] ) : esc_html__( 'No data available.', 'wpgpsc' );

				}
			}

			echo wp_kses_post( $this->field_after() );

		}

	}
}
