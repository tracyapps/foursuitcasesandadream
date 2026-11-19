<?php
/** Portable ACF definition owner for generated client projects. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
add_filter( 'acf/settings/load_json', static function ( $paths ) {
 $path = __DIR__ . '/acf-json';if ( is_dir( $path ) ) { $paths[] = $path; }return array_values( array_unique( $paths ) );
}, 30 );
add_filter( 'acf/settings/save_json', static function ( $path ) { return current_theme_supports( 'start-companion-content' ) && is_dir( __DIR__ . '/acf-json' ) ? __DIR__ . '/acf-json' : $path; }, 30 );
add_action( 'acf/init', static function () {
 if ( ! current_theme_supports( 'start-companion-content' ) || ! function_exists( 'acf_add_options_page' ) ) { return; }
 if ( function_exists( 'acf_get_options_page' ) && acf_get_options_page( 'start-site-options' ) ) { return; }
 acf_add_options_page( array( 'page_title' => __( 'Site Options', 'start-components' ), 'menu_title' => __( 'Site Options', 'start-components' ), 'menu_slug' => 'start-site-options', 'capability' => 'edit_theme_options', 'redirect' => false, 'autoload' => false ) );
}, 30 );
add_action( 'init', static function () {
 if ( ! function_exists( 'acf_get_field_groups' ) || ! is_file( __DIR__ . '/blocks/hero/block.json' ) || WP_Block_Type_Registry::get_instance()->is_registered( 'acf/hero' ) ) { return; }
 register_block_type( __DIR__ . '/blocks/hero' );
}, 30 );

// Once selected definitions are present here, their stable keys save back to this owner.
foreach ( glob( __DIR__ . '/acf-json/group_*.json' ) ?: array() as $start_json_file ) {
 $start_group_key = basename( $start_json_file, '.json' );
 if ( preg_match( '/^group_[a-zA-Z0-9_]+$/D', $start_group_key ) ) {
  add_filter( 'acf/settings/save_json/key=' . $start_group_key, static fn( $path ) => __DIR__ . '/acf-json', 40 );
 }
}
