<?php
/**
 * Curate the block editor for client editing.
 *
 * @package Start
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function foursuitcases_disable_remote_patterns() {
	return false;
}

if ( ! foursuitcases_feature_enabled( 'patterns' ) ) {
	add_filter( 'should_load_remote_block_patterns', 'foursuitcases_disable_remote_patterns' );
}

function foursuitcases_allowed_block_types( $allowed_blocks, $editor_context ) {
	if ( ! foursuitcases_feature_enabled( 'curated_editor' ) ) {
		return $allowed_blocks;
	}

	$blocks = array(
		'core/paragraph',
		'core/heading',
		'core/list',
		'core/list-item',
		'core/quote',
		'core/image',
		'core/gallery',
		'core/buttons',
		'core/button',
		'core/separator',
		'core/spacer',
		'core/embed',
		'core/shortcode',
		'core/table',
		'core/html',
	);

	if ( foursuitcases_feature_enabled( 'acf_blocks' ) && foursuitcases_has_acf() ) {
		$blocks[] = 'acf/hero';
	}

	return $blocks;
}
add_filter( 'allowed_block_types_all', 'foursuitcases_allowed_block_types', 10, 2 );

function foursuitcases_remove_editor_noise() {
	if ( ! foursuitcases_feature_enabled( 'curated_editor' ) ) {
		return;
	}

	remove_post_type_support( 'page', 'comments' );
	remove_post_type_support( 'post', 'comments' );
}
add_action( 'init', 'foursuitcases_remove_editor_noise', 20 );

function foursuitcases_unregister_block_styles() {
	if ( ! foursuitcases_feature_enabled( 'curated_editor' ) ) {
		return;
	}

	wp_enqueue_script(
		'four-suitcases-editor-curation',
		FOURSUITCASES_URI . 'assets/dist/js/editor.js',
		array( 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' ),
		foursuitcases_asset_version( 'assets/dist/js/editor.js' ),
		true
	);
}
add_action( 'enqueue_block_editor_assets', 'foursuitcases_unregister_block_styles' );

/** Load the link guard in both the editor canvas iframe and the classic canvas. */
function foursuitcases_editor_preview_links() {
	$screen = is_admin() ? get_current_screen() : null;
	if ( ! $screen || ! $screen->is_block_editor() ) {
		return;
	}

	$file = 'assets/dist/js/editor-preview-links.js';
	wp_enqueue_script( 'four-suitcases-editor-preview-links', FOURSUITCASES_URI . $file, array(), foursuitcases_asset_version( $file ), true );
}
add_action( 'enqueue_block_assets', 'foursuitcases_editor_preview_links' );
