<?php
/** Browser-visible kit identifiers only. Private npm credentials never belong here. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function start_components_validate_vendor( $input ) {
	if ( ! is_array( $input ) || array_diff( array_keys( $input ), array( 'provider', 'kitId', 'version', 'faKit' ) ) ) {
		return new WP_Error( 'start_vendor_config', 'Invalid component provider configuration.' );
	}
	if ( 'npm' === ( $input['provider'] ?? '' ) ) {
		if ( '3.14.0' !== ( $input['version'] ?? '' ) || ! is_string( $input['faKit'] ?? null ) || ( '' !== $input['faKit'] && ! preg_match( '/^[a-f0-9]{10}$/D', $input['faKit'] ) ) || isset( $input['kitId'] ) ) {
			return new WP_Error( 'start_vendor_config', 'Expected the installed Pro version and optional public Font Awesome kit code.' );
		}
		return array( 'provider' => 'npm', 'version' => $input['version'], 'faKit' => $input['faKit'] );
	}
	if ( 'kit' !== ( $input['provider'] ?? '' ) || ! is_string( $input['kitId'] ?? null ) || ! preg_match( '/^[a-f0-9]{16}$/D', $input['kitId'] ) || '3.14.0' !== ( $input['version'] ?? '' ) || ! is_string( $input['faKit'] ?? null ) || ! preg_match( '/^[a-f0-9]{10}$/D', $input['faKit'] ) ) {
		return new WP_Error( 'start_vendor_config', 'Expected a valid kit ID, Font Awesome kit code, and supported pinned version.' );
	}
	return array( 'provider' => 'kit', 'kitId' => $input['kitId'], 'version' => $input['version'], 'faKit' => $input['faKit'] );
}

function start_components_default_vendor() {
 $default = array( 'provider' => 'npm', 'version' => '3.14.0', 'faKit' => '' );
 $file = __DIR__ . '/vendor-runtime.json';if ( ! is_file( $file ) || filesize( $file ) > 4096 ) { return $default; }
 $config = start_components_validate_vendor( json_decode( file_get_contents( $file ), true ) );return is_wp_error( $config ) ? $default : $config;
}
