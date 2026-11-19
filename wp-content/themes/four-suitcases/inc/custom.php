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

function foursuitcases_has_block_width_control( $name ) {
	return is_string( $name ) && ( str_starts_with( $name, 'acf/travel-' )
		|| in_array( $name, array( 'acf/hero', 'start/contact-callout', 'start/design-element', 'start/disclosure' ), true ) );
}

add_filter( 'register_block_type_args', function ( $args, $name ) {
	if ( ! foursuitcases_has_block_width_control( $name ) ) {
		return $args;
	}
	// Keep the WordPress alignment attribute and use one toolbar that also offers Contained.
	$args['supports']['align'] = false;
	$args['attributes']['align'] = array(
		'type' => 'string',
		'enum' => array( '', 'none', 'contained', 'wide', 'full' ),
	);
	return $args;
}, 10, 2 );

add_filter( 'render_block', function ( $content, $block ) {
	$width = $block['attrs']['align'] ?? '';
	if ( ! in_array( $width, array( 'none', 'contained', 'wide', 'full' ), true )
		|| ! foursuitcases_has_block_width_control( $block['blockName'] ) ) {
		return $content;
	}
	$markup = new WP_HTML_Tag_Processor( $content );
	if ( $markup->next_tag() ) {
		$markup->set_attribute( 'data-fsa-width', $width );
	}
	return $markup->get_updated_html();
}, 10, 2 );

add_action( 'enqueue_block_editor_assets', function () {
	$file = 'assets/dist/js/editor-block-width.js';
	wp_enqueue_script(
		'four-suitcases-editor-block-width',
		FOURSUITCASES_URI . $file,
		array( 'wp-block-editor', 'wp-components', 'wp-element', 'wp-hooks', 'wp-i18n' ),
		foursuitcases_asset_version( $file ),
		true
	);
} );
