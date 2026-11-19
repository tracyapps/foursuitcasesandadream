<?php
/**
 * Site Options helpers.
 *
 * @package Start
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function foursuitcases_get_copyright_text() {
	$text = foursuitcases_get_option( 'copyright_text', 'Copyright [year] ' . get_bloginfo( 'name' ) );
	$text = str_replace( '[year]', gmdate( 'Y' ), $text );

	return $text;
}

function foursuitcases_copyright_text() {
	echo wp_kses_post( wptexturize( foursuitcases_get_copyright_text() ) );
}

function foursuitcases_get_contact_info() {
	return foursuitcases_get_option( 'contact_info', '' );
}

function foursuitcases_contact_info() {
	echo wp_kses_post( foursuitcases_get_contact_info() );
}

function foursuitcases_get_social_links() {
	if ( ! foursuitcases_has_acf() || ! have_rows( 'social_links', 'option' ) ) {
		return array();
	}

	$links = array();

	while ( have_rows( 'social_links', 'option' ) ) {
		the_row();

		$url = get_sub_field( 'url' );

		if ( empty( $url ) ) {
			continue;
		}

		$links[] = array(
			'service' => sanitize_title( get_sub_field( 'service' ) ),
			'label'   => get_sub_field( 'link_text' ) ?: get_sub_field( 'service' ),
			'url'     => $url,
		);
	}

	return $links;
}

function foursuitcases_social_links( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'class'       => 'social-links',
			'link_target' => '',
		)
	);

	$links = foursuitcases_get_social_links();

	if ( empty( $links ) ) {
		return;
	}

	echo '<ul class="' . esc_attr( $args['class'] ) . '">';

	foreach ( $links as $link ) {
		$target = $args['link_target'] ? ' target="' . esc_attr( $args['link_target'] ) . '" rel="noopener noreferrer"' : '';

		printf(
			'<li class="social-links__item social-links__item--%1$s"><a class="social-links__link" href="%2$s"%3$s data-tag="%4$s"><span class="screen-reader-text">%4$s</span><svg aria-hidden="true" focusable="false" class="icon"><use href="#icon-%1$s"></use></svg></a></li>',
			esc_attr( $link['service'] ),
			esc_url( $link['url'] ),
			$target, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			esc_html( $link['label'] )
		);
	}

	echo '</ul>';
}

function foursuitcases_excerpt_length( $length ) {
	$unit = foursuitcases_get_option( 'excerpt_length_unit', 'words' );

	if ( 'words' === $unit ) {
		return (int) foursuitcases_get_option( 'excerpt_length', $length );
	}

	return $length;
}
add_filter( 'excerpt_length', 'foursuitcases_excerpt_length' );

function foursuitcases_excerpt_more( $more ) {
	$text = foursuitcases_get_option( 'excerpt_link_text', __( 'Read more', 'four-suitcases' ) );

	return ' <a class="more-link" href="' . esc_url( get_permalink() ) . '">' . esc_html( $text ) . '</a>';
}
add_filter( 'excerpt_more', 'foursuitcases_excerpt_more' );

function foursuitcases_trim_excerpt_characters( $trimmed, $raw_excerpt ) {
	if ( '' !== $raw_excerpt || 'chars' !== foursuitcases_get_option( 'excerpt_length_unit', 'words' ) ) {
		return $trimmed;
	}

	$limit = (int) foursuitcases_get_option( 'excerpt_length', 0 );

	if ( ! $limit ) {
		return $trimmed;
	}

	$text = get_the_content( '' );
	$text = strip_shortcodes( $text );
	$text = apply_filters( 'the_content', $text );
	$text = wp_strip_all_tags( str_replace( ']]>', ']]&gt;', $text ) );
	$text = preg_replace( '/[\n\r\t ]+/', ' ', $text );

	if ( function_exists( 'mb_substr' ) ) {
		$text = mb_substr( $text, 0, $limit );
	} else {
		$text = substr( $text, 0, $limit );
	}

	$text = preg_replace( '/\s+\S*$/', '', $text );

	return trim( $text ) . apply_filters( 'excerpt_more', ' [&hellip;]' );
}
add_filter( 'wp_trim_excerpt', 'foursuitcases_trim_excerpt_characters', 10, 2 );

function foursuitcases_default_post_thumbnail_html( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
	if ( ! foursuitcases_feature_enabled( 'default_thumbnail' ) || $post_thumbnail_id || ! empty( $html ) ) {
		return $html;
	}

	$default_id = foursuitcases_get_option( 'default_post_thumbnail_id', 0 );

	if ( $default_id ) {
		return wp_get_attachment_image( $default_id, $size, false, $attr );
	}

	return $html;
}
add_filter( 'post_thumbnail_html', 'foursuitcases_default_post_thumbnail_html', 10, 5 );

function foursuitcases_get_motion_level() {
	$level = foursuitcases_get_option( 'travel_motion_level', 'lively' );
	return in_array( $level, array( 'calm', 'lively', 'max' ), true ) ? $level : 'lively';
}

add_action( 'acf/init', function () {
	acf_add_local_field_group( array(
		'key' => 'group_foursuitcases_travel_motion',
		'title' => 'Travel animations',
		'fields' => array( array(
			'key' => 'field_foursuitcases_travel_motion_level', 'name' => 'travel_motion_level',
			'label' => 'Motion level', 'type' => 'select',
			'choices' => array( 'calm' => 'Calm', 'lively' => 'Lively', 'max' => 'Max' ),
			'default_value' => 'lively',
			'instructions' => 'Sets the strength of movement. Visitors’ reduced-motion preferences take priority.',
		) ),
		'location' => array( array( array( 'param' => 'options_page', 'operator' => '==', 'value' => 'start-site-options' ) ) ),
	) );
} );

require_once __DIR__ . '/passport-stamps.php';
require_once __DIR__ . '/certifications.php';
