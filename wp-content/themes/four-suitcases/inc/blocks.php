<?php
/**
 * ACF block registration.
 *
 * @package Start
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function foursuitcases_register_acf_blocks() {
	if ( ! foursuitcases_feature_enabled( 'acf_blocks' ) || ! foursuitcases_has_acf() ) {
		return;
	}

	foreach ( glob( FOURSUITCASES_PATH . 'blocks/*/block.json' ) as $block_json ) {
		register_block_type( dirname( $block_json ) );
	}
}
add_action( 'init', 'foursuitcases_register_acf_blocks' );

function foursuitcases_block_categories( $categories ) {
	return array_merge(
		array(
			array(
				'slug'  => 'four-suitcases',
				'title' => __( 'Start Blocks', 'four-suitcases' ),
			),
		),
		$categories
	);
}
add_filter( 'block_categories_all', 'foursuitcases_block_categories' );
