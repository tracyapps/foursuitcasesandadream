<?php
/**
 * Assets.
 *
 * @package Start
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function foursuitcases_enqueue_assets() {
	wp_enqueue_style( 'four-suitcases-tokens', FOURSUITCASES_URI . 'assets/dist/css/tokens.css', array(), foursuitcases_asset_version( 'assets/dist/css/tokens.css' ) );
	wp_enqueue_style(
		'four-suitcases-main',
		FOURSUITCASES_URI . 'assets/dist/css/main.css',
		array( 'four-suitcases-tokens' ),
		foursuitcases_asset_version( 'assets/dist/css/main.css' )
	);

	wp_enqueue_script(
		'four-suitcases-main',
		FOURSUITCASES_URI . 'assets/dist/js/main.js',
		array(),
		foursuitcases_asset_version( 'assets/dist/js/main.js' ),
		array( 'strategy' => 'defer', 'in_footer' => true )
	);

	wp_localize_script(
		'four-suitcases-main',
		'foursuitcasesTheme',
		array(
			'themeMode' => foursuitcases_get_theme_mode(),
			'passport'  => foursuitcases_passport_settings(),

			'labels'    => array(
				'openMenu'  => __( 'Open menu', 'four-suitcases' ),
				'closeMenu' => __( 'Close menu', 'four-suitcases' ),
			),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'foursuitcases_enqueue_assets' );

function foursuitcases_dev_reload() {
 if ( defined( 'FOURSUITCASES_DEV_RELOAD' ) && FOURSUITCASES_DEV_RELOAD && 'local' === wp_get_environment_type() ) {
  wp_enqueue_script( 'start-reload', 'http://127.0.0.1:35729/client.js', array(), null, true );
 }
}
add_action( 'wp_enqueue_scripts', 'foursuitcases_dev_reload' );
