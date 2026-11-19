<?php
/** Portable, data-only content selection. No callbacks or executable recipe values. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
require_once __DIR__ . '/content-relations.php';
require_once __DIR__ . '/business.php';
require_once __DIR__ . '/appearance.php';
function start_components_validate_content( $c ) {
 if ( ! is_array( $c ) || array_diff( array_keys( $c ), array( 'version', 'groups', 'tabs', 'postTypes', 'taxonomies', 'meta', 'business', 'appearance' ) ) || 1 !== ( $c['version'] ?? null ) || ! is_array( $c['postTypes'] ?? null ) || ! array_is_list( $c['postTypes'] ) || count( $c['postTypes'] ) > 20 ) { return new WP_Error( 'content_config', 'Invalid content configuration.' ); }
 foreach ( array( 'groups' => 'group_', 'tabs' => 'field_' ) as $kind => $prefix ) {
  if ( ! is_array( $c[ $kind ] ?? null ) || count( $c[ $kind ] ) > 100 ) { return new WP_Error( 'content_flags', 'Invalid ACF selections.' ); }
  foreach ( $c[ $kind ] as $key => $flag ) { if ( ! is_string( $key ) || strlen( $key ) > 100 || ! preg_match( '/^' . $prefix . '[a-zA-Z0-9_]+$/D', $key ) || ! is_bool( $flag ) ) { return new WP_Error( 'content_flags', 'Invalid ACF selection key or flag.' ); } }
 }
 $seen = array();
 foreach ( $c['postTypes'] as $t ) {
  if ( ! is_array( $t ) || array_diff( array_keys( $t ), array( 'key', 'label', 'singular', 'enabled', 'public', 'archive', 'supports', 'rewriteSlug' ) ) || ! is_string( $t['key'] ?? null ) || ! preg_match( '/^[a-z][a-z0-9_]{1,19}$/D', $t['key'] ) || str_starts_with( $t['key'], 'wp_' ) || in_array( $t['key'], array( 'post', 'page', 'attachment', 'revision', 'nav_menu_item', 'custom_css', 'customize_changeset', 'oembed_cache', 'user_request', 'block', 'action', 'author', 'order', 'theme', 'type', 'status', 'search', 'feed', 'name', 'p', 's', 'cpage', 'embed' ), true ) || isset( $seen[ $t['key'] ] ) ) { return new WP_Error( 'content_type', 'Invalid, reserved or duplicate post type.' ); }
  $seen[ $t['key'] ] = true;
  foreach ( array( 'label', 'singular' ) as $key ) { if ( ! is_string( $t[ $key ] ?? null ) || ! trim( $t[ $key ] ) || strlen( $t[ $key ] ) > 320 || preg_match( '/[<>\x00-\x1f\x7f]/', $t[ $key ] ) ) { return new WP_Error( 'content_label', 'Invalid post type label.' ); } }
  if ( isset( $t['rewriteSlug'] ) && ( ! is_string( $t['rewriteSlug'] ) || ! preg_match( '/^[a-z][a-z0-9-]{1,59}$/D', $t['rewriteSlug'] ) ) ) { return new WP_Error( 'content_rewrite', 'Invalid rewrite slug.' ); }
  foreach ( array( 'enabled', 'public', 'archive' ) as $key ) { if ( ! is_bool( $t[ $key ] ?? null ) ) { return new WP_Error( 'content_flag', 'Invalid post type flag.' ); } }
  if ( ! is_array( $t['supports'] ?? null ) || ! array_is_list( $t['supports'] ) || ! $t['supports'] || array_filter( $t['supports'], static fn( $v ) => ! is_string( $v ) || ! in_array( $v, array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'custom-fields' ), true ) ) || count( array_unique( $t['supports'] ) ) !== count( $t['supports'] ) ) { return new WP_Error( 'content_support', 'Invalid post type supports.' ); }
 }
 if ( isset( $c['appearance'] ) && ! start_components_valid_appearance( $c['appearance'] ) ) { return new WP_Error( 'content_appearance', 'Invalid appearance settings.' ); }
 if ( isset( $c['business'] ) && ! start_components_valid_business( $c['business'] ) ) { return new WP_Error( 'content_business', 'Invalid public business information.' ); }
 return start_components_validate_relations( $c );
}
function start_components_content_selection() {
 static $selection = false;
 if ( false !== $selection ) { return $selection; }
 $file = __DIR__ . '/project-content.json';
 if ( ! file_exists( $file ) ) { $selection = null; return null; }
 $raw = is_readable( $file ) && filesize( $file ) <= 131072 ? json_decode( file_get_contents( $file ), true ) : null;
 $valid = is_array( $raw ) && 1 === ( $raw['version'] ?? null ) && is_string( $raw['projectId'] ?? null ) && preg_match( '/^[a-f0-9-]{36}$/D', $raw['projectId'] ) && ! array_diff( array_keys( $raw ), array( 'version', 'projectId', 'selection' ) ) ? start_components_validate_content( $raw['selection'] ?? null ) : new WP_Error( 'content_manifest', 'Invalid project content manifest.' );
 if ( is_wp_error( $valid ) ) {
  add_action( 'admin_notices', static function () { if ( current_user_can( 'manage_options' ) ) { echo '<div class="notice notice-error"><p>' . esc_html__( 'Start content configuration is invalid. Restore or reconcile project-content.json before continuing.', 'start-components' ) . '</p></div>'; } } );
  $selection = null; return null;
 }
 $selection = $valid; return $selection;
}
/** Register late, refuse collisions, and never replace another owner's definition. */
function start_components_register_content() {
 $c = start_components_content_selection(); if ( null === $c ) { return; }
 foreach ( $c['postTypes'] as $t ) {
  if ( post_type_exists( $t['key'] ) ) {
   $GLOBALS['start_components_content_conflicts'][] = $t['key'];
   add_action( 'admin_notices', static function () use ( $t ) { if ( current_user_can( 'manage_options' ) ) { echo '<div class="notice notice-error"><p>' . esc_html( sprintf( __( 'Start did not register %s because another definition already owns that post type.', 'start-components' ), $t['key'] ) ) . '</p></div>'; } } );
   continue;
  }
  $enabled = $t['enabled']; $public = $enabled && $t['public'];
  $object = register_post_type( $t['key'], array( 'labels' => array( 'name' => $t['label'], 'singular_name' => $t['singular'] ), 'public' => $public, 'publicly_queryable' => $public, 'show_ui' => $enabled, 'show_in_menu' => $enabled, 'show_in_rest' => $enabled, 'exclude_from_search' => ! $public, 'show_in_nav_menus' => $public, 'has_archive' => $public && $t['archive'], 'rewrite' => $public && isset( $t['rewriteSlug'] ) ? array( 'slug' => $t['rewriteSlug'], 'with_front' => false ) : false, 'query_var' => false, 'supports' => $t['supports'], 'map_meta_cap' => true ) );
  if ( ! is_wp_error( $object ) ) { $GLOBALS['start_components_content_owned'][ $t['key'] ] = $object; }
 }
}
add_action( 'init', 'start_components_register_content', 100 );
/** Display filtering only. Raw definitions, JSON sync and stored field values remain intact. */
function start_components_content_group_match( $match, $rule, $screen, $group ) {
 $c = start_components_content_selection();
 return null !== $c && false === ( $c['groups'][ $group['key'] ?? '' ] ?? true ) ? false : $match;
}
add_filter( 'acf/location/match_rule', 'start_components_content_group_match', 100, 4 );
function start_components_content_prepare_field( $field ) {
 $c = start_components_content_selection(); if ( ! $field || null === $c || ! function_exists( 'acf_get_field_groups' ) ) { return $field; }
 static $hidden = null;
 if ( null === $hidden ) {
  $hidden = array();
  foreach ( acf_get_field_groups() as $group ) {
   $hide_group = false === ( $c['groups'][ $group['key'] ] ?? true ); $hide_tab = false;
   foreach ( acf_get_fields( $group ) ?: array() as $sibling ) {
    if ( 'tab' === $sibling['type'] ) { $hide_tab = false === ( $c['tabs'][ $sibling['key'] ] ?? true ); }
    if ( $hide_group || $hide_tab ) { $hidden[ $sibling['key'] ] = true; }
   }
  }
 }
 return isset( $hidden[ $field['key'] ?? '' ] ) ? false : $field;
}
add_filter( 'acf/prepare_field', 'start_components_content_prepare_field', 100 );
/** Local inspection only: no values, user information or private field content. */
function start_components_content_inventory() {
 $groups = $tabs = array();
 if ( function_exists( 'acf_get_field_groups' ) ) {
  foreach ( acf_get_field_groups() as $group ) {
   $fields = acf_get_fields( $group ) ?: array();
   $groups[ $group['key'] ] = hash( 'sha256', wp_json_encode( array( $group, $fields ) ) );
   foreach ( $fields as $field ) { if ( 'tab' === $field['type'] ) { $tabs[ $field['key'] ] = $group['key']; } }
  }
 }
 ksort( $groups ); ksort( $tabs ); $types = array();
 foreach ( get_post_types( array(), 'objects' ) as $key => $object ) {
  $owned = isset( $GLOBALS['start_components_content_owned'][ $key ] ) && $GLOBALS['start_components_content_owned'][ $key ] === $object;
  $types[ $key ] = array( 'owned' => $owned, 'customFields' => post_type_supports( $key, 'custom-fields' ), 'signature' => hash( 'sha256', wp_json_encode( array( $object->labels, $object->public, $object->show_ui, $object->show_in_rest, get_all_post_type_supports( $key ) ) ) ) );
 }
 ksort( $types );
 $taxonomies = $meta = array();
 foreach ( get_taxonomies( array(), 'objects' ) as $key => $object ) { $taxonomies[ $key ] = array( 'owned' => isset( $GLOBALS['start_components_taxonomy_owned'][ $key ] ) && $GLOBALS['start_components_taxonomy_owned'][ $key ] === $object ); }
 foreach ( array_keys( $types ) as $post_type ) { foreach ( get_registered_meta_keys( 'post', $post_type ) as $key => $registration ) { $id = $post_type . '.' . $key;$meta[ $id ] = array( 'owned' => isset( $GLOBALS['start_components_meta_owned'][ $id ] ) && $GLOBALS['start_components_meta_owned'][ $id ] === $registration ); } }
 ksort( $taxonomies );ksort( $meta );
 $appearance = array();$mods = get_theme_mods() ?: array();foreach ( array( 'logoId' => 'custom_logo', 'darkMode' => 'start-dark-mode', 'stickyHeader' => 'start-sticky-header', 'siteSearch' => 'start-site-search' ) as $key => $mod ) { $appearance[ $key ] = array( 'owned' => array_key_exists( $mod, $mods ), 'value' => $mods[ $mod ] ?? null ); }
 $routes = array();foreach ( get_posts( array( 'post_type' => 'page', 'post_status' => 'publish', 'numberposts' => 1000, 'fields' => 'ids' ) ) as $id ) { $routes[] = array( 'path' => get_page_uri( $id ), 'owner' => 'page:' . $id ); }
 foreach ( get_post_types( array(), 'objects' ) as $key => $type ) { if ( $type->public && is_array( $type->rewrite ) && ! empty( $type->rewrite['slug'] ) ) { $routes[] = array( 'path' => $type->rewrite['slug'], 'owner' => 'type:' . $key ); } }
 $images = array_map( 'intval', get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'post_mime_type' => 'image', 'numberposts' => 1000, 'fields' => 'ids' ) ) );sort( $images );
 return array( 'version' => 1, 'routes' => $routes, 'appearance' => $appearance, 'images' => $images, 'groups' => $groups, 'tabs' => $tabs, 'postTypes' => $types, 'taxonomies' => $taxonomies, 'meta' => $meta );
}
/** Gutenberg needs REST; internal types still require editing capability for every REST route. */
function start_components_content_rest_guard( $result, $server, $request ) {
 foreach ( $GLOBALS['start_components_content_owned'] ?? array() as $key => $owned ) {
  $type = get_post_type_object( $key );
  if ( $type !== $owned || $type->public || ! preg_match( '~^/wp/v2/' . preg_quote( $key, '~' ) . '(?:/|$)~D', $request->get_route() ) ) { continue; }
  if ( ! current_user_can( $type->cap->edit_posts ) ) { return new WP_Error( 'rest_forbidden', __( 'You are not allowed to access this internal content.', 'start-components' ), array( 'status' => rest_authorization_required_code() ) ); }
 }
 return $result;
}
add_filter( 'rest_pre_dispatch', 'start_components_content_rest_guard', 10, 3 );
