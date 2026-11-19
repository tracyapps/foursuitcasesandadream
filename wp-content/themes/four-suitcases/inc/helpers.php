<?php
/**
 * General theme helpers.
 *
 * @package Start
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function foursuitcases_config( $key = null, $default = null ) {
	static $config = null;

	if ( null === $config ) {
		$config = require FOURSUITCASES_PATH . 'inc/starter-config.php';
	}

	if ( null === $key ) {
		return $config;
	}

	$parts = explode( '.', $key );
	$value = $config;

	foreach ( $parts as $part ) {
		if ( ! is_array( $value ) || ! array_key_exists( $part, $value ) ) {
			return $default;
		}
		$value = $value[ $part ];
	}

	return $value;
}

function foursuitcases_feature_enabled( $feature ) {
	return (bool) get_theme_mod( 'start-' . str_replace( '_', '-', $feature ), foursuitcases_config( 'features.' . $feature, false ) );
}

function foursuitcases_plugin_active( $plugin_file ) {
	if ( ! function_exists( 'is_plugin_active' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	return is_plugin_active( $plugin_file );
}

function foursuitcases_has_acf() {
	return class_exists( 'ACF' ) || foursuitcases_plugin_active( 'advanced-custom-fields-pro/acf.php' );
}

function foursuitcases_has_acfe() {
	return function_exists( 'acfe' ) || foursuitcases_plugin_active( 'acf-extended-pro/acf-extended.php' ) || foursuitcases_plugin_active( 'acf-extended/acf-extended.php' );
}

function foursuitcases_asset_version( $relative_path ) {
	$file = FOURSUITCASES_PATH . ltrim( $relative_path, '/' );

	return file_exists( $file ) ? (string) filemtime( $file ) : FOURSUITCASES_VERSION;
}

function foursuitcases_get_option( $field, $default = '' ) {
	if ( ! foursuitcases_has_acf() || ! function_exists( 'get_field' ) ) {
		return $default;
	}

	$value = get_field( $field, 'option' );

	return null !== $value && '' !== $value ? $value : $default;
}

function foursuitcases_get_theme_mode() {
	if ( ! foursuitcases_feature_enabled( 'dark_mode' ) ) { return 'light'; }
	$mode = foursuitcases_get_option( 'theme_mode_default', 'system' );

	return in_array( $mode, array( 'light', 'dark', 'system' ), true ) ? $mode : 'system';
}
