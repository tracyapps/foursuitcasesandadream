<?php
/** Defaults are portable; later WordPress Customizer values retain ownership. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
function start_components_valid_appearance( $a ) {
 if ( ! is_array( $a ) || array_diff( array_keys( $a ), array( 'logoId', 'darkMode', 'stickyHeader', 'siteSearch' ) ) ) { return false; }
 foreach ( $a as $key => $value ) { if ( 'logoId' === $key ? ! is_int( $value ) || $value < 0 : ! is_bool( $value ) ) { return false; } }return true;
}
foreach ( array( 'logoId' => 'custom_logo', 'darkMode' => 'start-dark-mode', 'stickyHeader' => 'start-sticky-header', 'siteSearch' => 'start-site-search' ) as $start_key => $start_mod ) {
 add_filter( 'theme_mod_' . $start_mod, static function ( $value ) use ( $start_key, $start_mod ) {
  $mods = get_theme_mods() ?: array();if ( array_key_exists( $start_mod, $mods ) ) { return $value; }
  $c = start_components_content_selection();return $c['appearance'][ $start_key ] ?? $value;
 } );
}
add_filter( 'body_class', static function ( $classes ) { if ( ! get_theme_mod( 'start-sticky-header', true ) ) { $classes[] = 'no-sticky-header'; }return $classes; } );
