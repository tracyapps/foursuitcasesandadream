<?php
/**
 * Optional custom post types.
 *
 * @package Start
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function foursuitcases_register_optional_post_types() {
	$post_types = (array) foursuitcases_config( 'post_types', array() );

	if ( ! empty( $post_types['people'] ) ) {
		register_post_type(
			'person',
			array(
				'labels'       => array(
					'name'          => __( 'People', 'four-suitcases' ),
					'singular_name' => __( 'Person', 'four-suitcases' ),
				),
				'public'       => true,
				'menu_icon'    => 'dashicons-groups',
				'show_in_rest' => true,
				'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ),
				'has_archive'  => true,
				'rewrite'      => array( 'slug' => 'people' ),
			)
		);
	}

	if ( ! empty( $post_types['work'] ) ) {
		register_post_type(
			'work',
			array(
				'labels'       => array(
					'name'          => __( 'Work', 'four-suitcases' ),
					'singular_name' => __( 'Work Item', 'four-suitcases' ),
				),
				'public'       => true,
				'menu_icon'    => 'dashicons-portfolio',
				'show_in_rest' => true,
				'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ),
				'has_archive'  => true,
				'rewrite'      => array( 'slug' => 'work' ),
			)
		);
	}

	if ( ! empty( $post_types['events'] ) ) {
		register_post_type(
			'event',
			array(
				'labels'       => array(
					'name'          => __( 'Events', 'four-suitcases' ),
					'singular_name' => __( 'Event', 'four-suitcases' ),
				),
				'public'       => true,
				'menu_icon'    => 'dashicons-calendar-alt',
				'show_in_rest' => true,
				'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ),
				'has_archive'  => true,
				'rewrite'      => array( 'slug' => 'events' ),
			)
		);
	}
}
add_action( 'init', 'foursuitcases_register_optional_post_types' );
