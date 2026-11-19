<?php
/**
 * Plugin Name: Start Components
 * Description: Portable, curated client blocks with progressive Web Awesome enhancement.
 * Version: 0.2.0
 * Requires at least: 6.5
 * Requires PHP: 8.1
 * License: GPL-2.0-or-later
 * Text Domain: start-components
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/security.php';
require_once __DIR__ . '/vendor-config.php';
require_once __DIR__ . '/content-config.php';
require_once __DIR__ . '/content-acf.php';

/** Inserter availability only: saved block registrations/content are retained. */
function start_components_block_flags() {
	$defaults = array( 'disclosure' => true, 'contactCallout' => true, 'designElement' => true );
	$stored = get_option( 'start_components_features', array() );
	if ( ! is_array( $stored ) || array_diff( array_keys( $stored ), array_keys( $defaults ) ) ) { return $defaults; }
	foreach ( $stored as $value ) { if ( ! is_bool( $value ) ) { return $defaults; } }
	return array_merge( $defaults, $stored );
}

function start_components_curated_editor() {
	return current_theme_supports( 'start-curated-editor' ) || ( function_exists( 'start_feature_enabled' ) && start_feature_enabled( 'curated_editor' ) && ! start_feature_enabled( 'patterns' ) );
}

function start_components_register() {
	$base = plugin_dir_url( __FILE__ );
	$vendor = start_components_validate_vendor( get_option( 'start_components_vendor', start_components_default_vendor() ) );
	if ( is_wp_error( $vendor ) ) {
		add_action( 'admin_notices', function () use ( $vendor ) {
			echo '<div class="notice notice-error"><p>' . esc_html( $vendor->get_error_message() ) . '</p></div>';
		} );
		$vendor = array( 'provider' => 'unavailable' );
	}
	$vendor['previewScript'] = add_query_arg( 'ver', filemtime( __DIR__ . '/dist/details.js' ), $base . 'dist/details.js' );
	if ( 'kit' === $vendor['provider'] ) {
		$vendor['base'] = 'https://ka-p.webawesome.com/kit/' . $vendor['kitId'] . '/webawesome@' . $vendor['version'] . '/';
	}
	if ( 'npm' === $vendor['provider'] ) {
		$vendor['base'] = $base . 'dist/';
	}
	$runtime_file = 'kit-details.js';

	wp_register_script( 'start-components-editor', $base . 'dist/editor.js', array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n' ), filemtime( __DIR__ . '/dist/editor.js' ), true );
	wp_localize_script( 'start-components-editor', 'startComponentsEditor', array( 'vendor' => $vendor, 'hidePatterns' => start_components_curated_editor(), 'elementsScript' => $base . 'dist/elements.js', 'blocks' => start_components_block_flags() ) );
	wp_register_script( 'start-components-details', $base . 'dist/' . $runtime_file, array(), filemtime( __DIR__ . '/dist/' . $runtime_file ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
	wp_localize_script( 'start-components-details', 'startComponentsVendor', $vendor );
	wp_register_style( 'start-components', $base . 'dist/style.css', array(), filemtime( __DIR__ . '/dist/style.css' ) );
	wp_register_script_module( 'start-components-elements', $base . 'dist/elements.js', array(), filemtime( __DIR__ . '/dist/elements.js' ) );
	$flags = start_components_block_flags();
	foreach ( array( 'disclosure' => 'disclosure', 'contact-callout' => 'contactCallout', 'design-element' => 'designElement' ) as $name => $flag ) {
		$block = register_block_type( __DIR__ . '/blocks/' . $name );
		if ( $block ) { $block->supports['inserter'] = $flags[ $flag ]; }
	}
}
add_action( 'init', 'start_components_register' );

function start_components_allowed_blocks( $allowed, $context ) {
	if ( false === $allowed ) { return false; }
	$flags = start_components_block_flags();
	$names = array( 'start/disclosure' => $flags['disclosure'], 'start/contact-callout' => $flags['contactCallout'], 'start/design-element' => $flags['designElement'] );
	if ( current_theme_supports( 'start-companion-content' ) && WP_Block_Type_Registry::get_instance()->is_registered( 'acf/hero' ) ) { $selection = start_components_content_selection();$names['acf/hero'] = $selection['groups']['group_start_block_hero'] ?? true; }
	if ( true === $allowed && ! in_array( false, $names, true ) ) { return true; }
	$allowed = true === $allowed ? array_keys( WP_Block_Type_Registry::get_instance()->get_all_registered() ) : $allowed;
	if ( ! is_array( $allowed ) ) { return $allowed; }
	foreach ( $names as $name => $enabled ) {
		$allowed = array_values( array_diff( $allowed, array( $name ) ) );
		if ( $enabled ) { $allowed[] = $name; }
	}
	return array_values( array_unique( $allowed ) );
}
add_filter( 'allowed_block_types_all', 'start_components_allowed_blocks', 20, 2 );

function start_components_editor_settings( $settings, $context ) {
	if ( start_components_curated_editor() ) {
		$settings['__experimentalBlockPatterns']          = array();
		$settings['__experimentalBlockPatternCategories'] = array();
	}
	return $settings;
}
add_filter( 'block_editor_settings_all', 'start_components_editor_settings', 100, 2 );

function start_components_editor_curation_assets() {
	if ( start_components_curated_editor() ) {
		wp_enqueue_style( 'start-components-curation', plugin_dir_url( __FILE__ ) . 'dist/curation.css', array(), filemtime( __DIR__ . '/dist/curation.css' ) );
	}
}
add_action( 'enqueue_block_editor_assets', 'start_components_editor_curation_assets' );

if ( is_file( __DIR__ . '/custom/functions.php' ) ) { require_once __DIR__ . '/custom/functions.php'; }
