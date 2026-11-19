<?php
/** Taxonomy and scalar meta definitions owned by the companion plugin. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
function start_components_validate_relations( $c ) {
 if ( isset( $c['taxonomies'] ) ) {
  if ( ! is_array( $c['taxonomies'] ) || ! array_is_list( $c['taxonomies'] ) || count( $c['taxonomies'] ) > 30 ) { return new WP_Error( 'content_taxonomies', 'Invalid taxonomy list.' ); }$keys = array();
  foreach ( $c['taxonomies'] as $t ) {
   if ( ! is_array( $t ) || array_diff( array_keys( $t ), array( 'key', 'label', 'singular', 'enabled', 'public', 'hierarchical', 'postTypes' ) ) || ! is_string( $t['key'] ?? null ) || ! preg_match( '/^[a-z][a-z0-9_]{1,31}$/D', $t['key'] ) || str_starts_with( $t['key'], 'wp_' ) || in_array( $t['key'], array( 'category', 'post_tag', 'nav_menu', 'link_category', 'post_format' ), true ) || isset( $keys[ $t['key'] ] ) ) { return new WP_Error( 'content_taxonomy', 'Invalid or reserved taxonomy.' ); }$keys[ $t['key'] ] = true;
   foreach ( array( 'label', 'singular' ) as $key ) { if ( ! is_string( $t[ $key ] ?? null ) || ! trim( $t[ $key ] ) || strlen( $t[ $key ] ) > 320 || preg_match( '/[<>\x00-\x1f\x7f]/', $t[ $key ] ) ) { return new WP_Error( 'content_taxonomy_label', 'Invalid taxonomy label.' ); } }
   foreach ( array( 'enabled', 'public', 'hierarchical' ) as $key ) { if ( ! is_bool( $t[ $key ] ?? null ) ) { return new WP_Error( 'content_taxonomy_flag', 'Invalid taxonomy flag.' ); } }
   if ( ! is_array( $t['postTypes'] ?? null ) || ! array_is_list( $t['postTypes'] ) || ! $t['postTypes'] || count( array_unique( $t['postTypes'] ) ) !== count( $t['postTypes'] ) ) { return new WP_Error( 'content_taxonomy_target', 'Invalid taxonomy targets.' ); }
   foreach ( $t['postTypes'] as $target ) { if ( ! is_string( $target ) || ! preg_match( '/^[a-z][a-z0-9_]{1,19}$/D', $target ) ) { return new WP_Error( 'content_taxonomy_target', 'Invalid taxonomy target.' ); } }
  }
 }
 if ( isset( $c['meta'] ) ) {
  if ( ! is_array( $c['meta'] ) || ! array_is_list( $c['meta'] ) || count( $c['meta'] ) > 100 ) { return new WP_Error( 'content_meta', 'Invalid meta list.' ); }$keys = array();
  foreach ( $c['meta'] as $m ) {
   if ( ! is_array( $m ) || array_diff( array_keys( $m ), array( 'postType', 'key', 'type', 'public' ) ) || ! is_string( $m['postType'] ?? null ) || ! preg_match( '/^[a-z][a-z0-9_]{1,19}$/D', $m['postType'] ) || ! is_string( $m['key'] ?? null ) || ! preg_match( '/^[a-z][a-z0-9_]{1,59}$/D', $m['key'] ) || str_starts_with( $m['key'], 'wp_' ) || ! in_array( $m['type'] ?? null, array( 'string', 'boolean', 'integer', 'number' ), true ) || ! is_bool( $m['public'] ?? null ) || isset( $keys[ $m['postType'] . '.' . $m['key'] ] ) ) { return new WP_Error( 'content_meta_field', 'Invalid or reserved meta field.' ); }$keys[ $m['postType'] . '.' . $m['key'] ] = true;
  }
 }
 return $c;
}
function start_components_register_relations() {
 $c = start_components_content_selection();if ( null === $c ) { return; }
 foreach ( $c['taxonomies'] ?? array() as $t ) {
  if ( taxonomy_exists( $t['key'] ) || array_filter( $t['postTypes'], static fn( $key ) => ! post_type_exists( $key ) ) ) { $GLOBALS['start_components_content_conflicts'][] = $t['key'];continue; }
  $public = $t['enabled'] && $t['public'];
  $object = register_taxonomy( $t['key'], $t['postTypes'], array( 'labels' => array( 'name' => $t['label'], 'singular_name' => $t['singular'] ), 'public' => $public, 'publicly_queryable' => $public, 'show_ui' => $t['enabled'], 'show_in_rest' => $public, 'hierarchical' => $t['hierarchical'], 'show_in_nav_menus' => $public, 'rewrite' => false, 'query_var' => false ) );
  if ( ! is_wp_error( $object ) ) { $GLOBALS['start_components_taxonomy_owned'][ $t['key'] ] = $object; }
 }
 foreach ( $c['meta'] ?? array() as $m ) {
  if ( ! post_type_exists( $m['postType'] ) || isset( get_registered_meta_keys( 'post', $m['postType'] )[ $m['key'] ] ) ) { $GLOBALS['start_components_content_conflicts'][] = $m['postType'] . '.' . $m['key'];continue; }
  $sanitize = static function ( $value ) use ( $m ) { switch ( $m['type'] ) { case 'string': return is_scalar( $value ) ? sanitize_text_field( (string) $value ) : '';case 'boolean': return filter_var( $value, FILTER_VALIDATE_BOOLEAN );case 'integer': return (int) $value;case 'number': $value = (float) $value;return is_finite( $value ) ? $value : 0; } };
  register_post_meta( $m['postType'], $m['key'], array( 'type' => $m['type'], 'single' => true, 'show_in_rest' => $m['public'], 'sanitize_callback' => $sanitize, 'auth_callback' => static fn( $allowed, $key, $id ) => current_user_can( 'edit_post', $id ) ) );
  $GLOBALS['start_components_meta_owned'][ $m['postType'] . '.' . $m['key'] ] = get_registered_meta_keys( 'post', $m['postType'] )[ $m['key'] ];
 }
}
add_action( 'init', 'start_components_register_relations', 110 );
