<?php
/**
 * Template helpers.
 *
 * @package Start
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function foursuitcases_site_logo() {
	if ( has_custom_logo() ) {
		the_custom_logo();
		return;
	}

	if ( is_front_page() && is_home() ) {
		printf( '<h1 class="site-branding__name"><a href="%s">%s</a></h1>', esc_url( home_url( '/' ) ), esc_html( get_bloginfo( 'name' ) ) );
		return;
	}

	printf( '<p class="site-branding__name"><a href="%s">%s</a></p>', esc_url( home_url( '/' ) ), esc_html( get_bloginfo( 'name' ) ) );
}

function foursuitcases_posted_on() {
	printf(
		'<time class="entry-date" datetime="%1$s">%2$s</time>',
		esc_attr( get_the_date( DATE_W3C ) ),
		esc_html( get_the_date() )
	);
}

function foursuitcases_page_title() {
	if ( is_home() && ! is_front_page() ) {
		single_post_title( '<h1 class="page-title">', '</h1>' );
		return;
	}

	if ( is_archive() ) {
		the_archive_title( '<h1 class="page-title">', '</h1>' );
		the_archive_description( '<div class="archive-description">', '</div>' );
		return;
	}

	if ( is_search() ) {
		printf( '<h1 class="page-title">%s</h1>', esc_html( sprintf( __( 'Search results for "%s"', 'four-suitcases' ), get_search_query() ) ) );
		return;
	}

	if ( is_singular() ) {
		$title = get_the_title();

		if ( foursuitcases_has_acf() && get_field( 'override_page_title' ) && get_field( 'custom_page_title' ) ) {
			$title = get_field( 'custom_page_title' );
		}

		printf( '<h1 class="page-title">%s</h1>', esc_html( $title ) );

		if ( foursuitcases_has_acf() && get_field( 'page_tagline' ) ) {
			printf( '<div class="page-tagline">%s</div>', wp_kses_post( get_field( 'page_tagline' ) ) );
		}
	}
}

function foursuitcases_the_svg_sprite() {
	$sprite = FOURSUITCASES_PATH . 'assets/dist/svg/icons.svg';

	if ( file_exists( $sprite ) ) {
		echo '<div hidden aria-hidden="true">';
		readfile( $sprite ); // Build accepts only trusted local SVG sources.
		echo '</div>';
	}
}
