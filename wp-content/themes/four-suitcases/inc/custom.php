<?php
/** Project-owned hooks and extensions. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'enqueue_block_assets', function () {
	wp_enqueue_style( 'travel-fonts', 'https://fonts.googleapis.com/css2?family=Great+Vibes&family=Inter+Tight:wght@200..800&family=Petrona:ital,wght@0,300..900;1,300..900&display=swap', array(), null );
} );
add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_script( 'travel-clips', FOURSUITCASES_URI . 'assets/travel-clips.js', array(), foursuitcases_asset_version( 'assets/travel-clips.js' ), true );
} );

add_filter( 'allowed_block_types_all', function ( $allowed ) {
	if ( ! is_array( $allowed ) ) { return $allowed; }
	return array_values( array_unique( array_merge( $allowed, array( 'core/group', 'core/columns', 'core/column', 'core/details', 'core/block' ), function_exists( 'start_travel_specs' ) ? array_map( static fn( $kind ) => 'acf/travel-' . $kind, array_keys( start_travel_specs() ) ) : array() ) ) );
}, 30 );

add_action( 'init', function () {
	foreach ( array( 'plum' => 'Plum', 'sea' => 'Sea glass', 'apricot' => 'Apricot', 'sand' => 'Sand' ) as $slug => $name ) {
		register_block_style( 'core/group', array( 'name' => 'travel-' . $slug, 'label' => $name ) );
	}
} );

add_action( 'init', function () {
	foreach ( array( 'city' => 'City luggage', 'beach' => 'Beach luggage' ) as $slug => $label ) {
		register_block_style( 'core/columns', array( 'name' => 'travel-' . $slug, 'label' => $label ) );
	}
} );


require_once __DIR__ . '/travel.php';
