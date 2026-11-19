<?php
/** Editable passport artwork for this site. */
defined( 'ABSPATH' ) || exit;

function foursuitcases_default_stamps() {
	$destinations = array(
		'Orlando' => 'Florida', 'Anaheim' => 'California', 'Juneau' => 'Alaska',
		'Yellowstone' => 'Wyoming', 'Zion' => 'Utah', 'Nassau' => 'Bahamas',
		'Grand Canyon' => 'Arizona', 'Yosemite' => 'California', 'Cozumel' => 'Mexico',
		'Wisconsin' => 'Home sweet home', 'Glacier Bay' => 'Alaska',
	);
	$shapes = array( 'round', 'rectangle', 'oval' );
	$stamps = array();
	foreach ( $destinations as $title => $region ) {
		$stamps[] = array(
			'enabled' => true, 'shape' => $shapes[ count( $stamps ) % count( $shapes ) ],
			'top' => 'Adventure · ' . $region, 'title' => $title, 'bottom' => '{date}',
		);
	}
	return $stamps;
}

function foursuitcases_passport_settings() {
	$enabled = get_option( 'options_travel_passport_enabled', '1' );
	$stamps = function_exists( 'get_field' ) && false !== get_option( '_options_travel_passport_stamps', false )
		? (array) get_field( 'travel_passport_stamps', 'option' )
		: foursuitcases_default_stamps();
	$active = array();
	foreach ( $stamps as $stamp ) {
		if ( empty( $stamp['enabled'] ) || empty( $stamp['title'] ) ) {
			continue;
		}
		$active[] = array(
			'shape' => in_array( $stamp['shape'] ?? '', array( 'round', 'rectangle', 'oval' ), true ) ? $stamp['shape'] : 'round',
			'top' => sanitize_text_field( $stamp['top'] ?? '' ),
			'title' => sanitize_text_field( $stamp['title'] ),
			'bottom' => sanitize_text_field( $stamp['bottom'] ?? '' ),
		);
	}
	return array( 'enabled' => (bool) $enabled, 'stamps' => $active );
}

add_action( 'acf/init', function () {
	if ( ! function_exists( 'acf_add_options_sub_page' ) ) {
		return;
	}
	$parent = acf_get_options_page( 'start-site-options' ) ? 'start-site-options' : 'four-suitcases-site-options';
	if ( ! acf_get_options_page( $parent ) ) {
		return;
	}
	acf_add_options_sub_page( array(
		'page_title' => 'Passport Stamps', 'menu_title' => 'Passport Stamps',
		'menu_slug' => 'four-suitcases-passport-stamps', 'parent_slug' => $parent,
		'capability' => 'edit_theme_options', 'update_button' => 'Save Passport Stamps',
	) );
	$fields = array(
		array( 'key' => 'field_travel_stamp_enabled', 'name' => 'enabled', 'label' => 'Include in rotation', 'type' => 'true_false', 'default_value' => 1, 'ui' => 1 ),
		array(
			'key' => 'field_travel_stamp_shape', 'name' => 'shape', 'label' => 'Shape', 'type' => 'select',
			'choices' => array( 'round' => 'Round', 'rectangle' => 'Rectangle', 'oval' => 'Oval' ), 'default_value' => 'round',
		),
	);
	foreach ( array( 'top' => 'Top line', 'title' => 'Main line', 'bottom' => 'Bottom line' ) as $name => $label ) {
		$fields[] = array(
			'key' => 'field_travel_stamp_' . $name, 'name' => $name, 'label' => $label,
			'type' => 'text', 'maxlength' => 36, 'required' => 'title' === $name,
			'default_value' => 'bottom' === $name ? '{date}' : '',
		);
	}
	$fields[] = array( 'key' => 'field_travel_stamp_preview', 'name' => 'preview', 'label' => 'Live preview', 'type' => 'message', 'message' => '<div data-passport-preview></div>' );
	acf_add_local_field_group( array(
		'key' => 'group_travel_passport_stamps', 'title' => 'Passport Stamps',
		'fields' => array(
			array(
				'key' => 'field_travel_passport_enabled', 'name' => 'travel_passport_enabled',
				'label' => 'Enable passport stamps on the site', 'type' => 'true_false', 'default_value' => 1, 'ui' => 1,
			),
			array(
				'key' => 'field_travel_passport_stamps', 'name' => 'travel_passport_stamps', 'label' => 'Stamp collection',
				'instructions' => 'Colors are picked at random. Use {date} in any line to show today’s date. Leave a line blank to omit it.',
				'type' => 'repeater', 'layout' => 'table', 'collapsed' => 'field_travel_stamp_title',
				'button_label' => 'Add stamp', 'sub_fields' => $fields,
			),
		),
		'location' => array( array( array( 'param' => 'options_page', 'operator' => '==', 'value' => 'four-suitcases-passport-stamps' ) ) ),
	) );
}, 40 );

add_filter( 'acf/load_value/key=field_travel_passport_stamps', function ( $value ) {
	if ( false !== get_option( '_options_travel_passport_stamps', false ) ) {
		return $value;
	}
	return array_map( static function ( $stamp ) {
		$row = array();
		foreach ( $stamp as $key => $line ) {
			$row[ 'field_travel_stamp_' . $key ] = $line;
		}
		return $row;
	}, foursuitcases_default_stamps() );
} );

add_action( 'admin_enqueue_scripts', function () {
	if ( 'four-suitcases-passport-stamps' !== ( $_GET['page'] ?? '' ) || ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	wp_enqueue_style( 'travel-fonts', 'https://fonts.googleapis.com/css2?family=Inter+Tight:wght@700&family=Petrona:wght@800&display=swap', array(), null );
	wp_enqueue_style( 'passport-tokens', FOURSUITCASES_URI . 'assets/dist/css/tokens.css', array(), foursuitcases_asset_version( 'assets/dist/css/tokens.css' ) );
	wp_enqueue_style( 'passport-preview', FOURSUITCASES_URI . 'assets/dist/css/passport-preview.css', array( 'passport-tokens', 'travel-fonts' ), foursuitcases_asset_version( 'assets/dist/css/passport-preview.css' ) );
	wp_enqueue_script( 'passport-preview', FOURSUITCASES_URI . 'assets/dist/js/passport-preview.js', array( 'acf-input' ), foursuitcases_asset_version( 'assets/dist/js/passport-preview.js' ), true );
} );
