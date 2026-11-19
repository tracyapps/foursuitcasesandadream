<?php
/**
 * ACF and ACFE integration.
 *
 * @package Start
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function foursuitcases_acf_json_save_path( $path ) {
	return FOURSUITCASES_PATH . 'acf-json';
}
add_filter( 'acf/settings/save_json', 'foursuitcases_acf_json_save_path' );

function foursuitcases_acf_json_load_paths( $paths ) {
	$paths[] = FOURSUITCASES_PATH . 'acf-json';

	return array_values( array_unique( $paths ) );
}
add_filter( 'acf/settings/load_json', 'foursuitcases_acf_json_load_paths' );

function foursuitcases_acf_options_pages() {
	if ( ! foursuitcases_feature_enabled( 'acf_options' ) || ! foursuitcases_has_acf() || ! function_exists( 'acf_add_options_page' ) ) {
		return;
	}

	acf_add_options_page(
		array(
			'page_title'  => __( 'Site Options', 'four-suitcases' ),
			'menu_title'  => __( 'Site Options', 'four-suitcases' ),
			'menu_slug'   => 'four-suitcases-site-options',
			'capability'  => 'edit_theme_options',
			'redirect'    => false,
			'position'    => 58,
			'icon_url'    => 'dashicons-admin-generic',
			'autoload'    => true,
			'update_button' => __( 'Save Site Options', 'four-suitcases' ),
		)
	);
}
add_action( 'acf/init', 'foursuitcases_acf_options_pages' );

function foursuitcases_acf_admin_notice() {
	if ( foursuitcases_has_acf() || ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	printf(
		'<div class="notice notice-warning"><p>%s</p></div>',
		esc_html__( 'Start 2.0 works best with Advanced Custom Fields Pro active. The theme will keep running, but Site Options and ACF blocks are disabled until ACF is active.', 'four-suitcases' )
	);
}
add_action( 'admin_notices', 'foursuitcases_acf_admin_notice' );
