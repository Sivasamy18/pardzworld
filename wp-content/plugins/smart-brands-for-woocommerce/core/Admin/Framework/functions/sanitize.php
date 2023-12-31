<?php
/**
 * Framework sanitize file.
 *
 * @link       https://shapedplugin.com
 * @since      1.0.0
 *
 * @package    smart_brands_for_wc
 * @subpackage smart_brands_for_wc/Frontend
 * @author     ShapedPlugin<support@shapedplugin.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! function_exists( 'csf_sanitize_replace_a_to_b' ) ) {
	/**
	 *
	 * Sanitize
	 * Replace letter a to letter b
	 *
	 * @param  string $value string.
	 *
	 * @return string
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function csf_sanitize_replace_a_to_b( $value ) {
		return str_replace( 'a', 'b', $value );
	}
}

if ( ! function_exists( 'csf_sanitize_title' ) ) {
	/**
	 *
	 * Sanitize title
	 *
	 * @param  string $value string.
	 *
	 * @return string
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function csf_sanitize_title( $value ) {
		return sanitize_title( $value );
	}
}
