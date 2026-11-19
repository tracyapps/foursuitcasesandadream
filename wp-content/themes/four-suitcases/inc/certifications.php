<?php
/** Shared certifications and specialties for the travel theme. */
defined( 'ABSPATH' ) || exit;

function foursuitcases_default_certifications() {
	return array(
		array(
			'title' => 'Certified Autism Travel Professional (CATP)',
			'detail' => 'Certified through IBCCES. Lindsay helps families plan for sensory needs, accommodations, and what to expect along the way.',
		),
		array(
			'title' => 'Special Needs at Sea',
			'detail' => 'Trained through Special Needs at Sea. Lindsay can help coordinate mobility equipment and other accessibility needs for your trip.',
		),
		array(
			'title' => 'Certified Travel Advisor',
			'detail' => '',
		),
		array(
			'title' => 'Food Allergy & Gluten-Free Travel Specialist',
			'detail' => 'Lindsay researches dining options and helps families talk through their dietary needs with resorts and cruise lines.',
		),
		array(
			'title' => 'Disney Destinations Specialist',
			'detail' => '',
		),
	);
}

function foursuitcases_certifications() {
	$rows = function_exists( 'get_field' ) && false !== get_option( '_options_travel_certifications', false )
		? (array) get_field( 'travel_certifications', 'option' )
		: foursuitcases_default_certifications();
	return array_values( array_filter( $rows, static function ( $row ) {
		return is_array( $row ) && '' !== trim( (string) ( $row['title'] ?? '' ) );
	} ) );
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
		'page_title' => 'Certifications', 'menu_title' => 'Certifications',
		'menu_slug' => 'four-suitcases-certifications', 'parent_slug' => $parent,
		'capability' => 'edit_theme_options', 'update_button' => 'Save Certifications',
	) );

	acf_add_local_field_group( array(
		'key' => 'group_travel_certifications', 'title' => 'Certifications & Specialties',
		'fields' => array( array(
			'key' => 'field_travel_certifications', 'name' => 'travel_certifications',
			'label' => 'Certifications & Specialties', 'type' => 'repeater', 'layout' => 'block',
			'instructions' => 'Drag rows to change their order on the About page, in the footer, and in Certifications dividers.',
			'collapsed' => 'field_travel_certification_title', 'button_label' => 'Add certification or specialty',
			'sub_fields' => array(
				array(
					'key' => 'field_travel_certification_title', 'name' => 'title',
					'label' => 'Name', 'type' => 'text', 'required' => 1,
				),
				array(
					'key' => 'field_travel_certification_detail', 'name' => 'detail',
					'label' => 'Description for the About page', 'type' => 'textarea', 'rows' => 3,
				),
			),
		) ),
		'location' => array( array( array( 'param' => 'options_page', 'operator' => '==', 'value' => 'four-suitcases-certifications' ) ) ),
	) );
}, 40 );

add_filter( 'acf/load_value/key=field_travel_certifications', function ( $value ) {
	if ( false !== get_option( '_options_travel_certifications', false ) ) {
		return $value;
	}
	return array_map( static function ( $row ) {
		return array(
			'field_travel_certification_title' => $row['title'],
			'field_travel_certification_detail' => $row['detail'],
		);
	}, foursuitcases_default_certifications() );
} );
