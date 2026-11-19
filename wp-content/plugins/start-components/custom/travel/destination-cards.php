<?php
defined( 'ABSPATH' ) || exit;

// Pages can supply the same editable card summary as posts.
add_action( 'init', static function () {
	add_post_type_support( 'page', 'excerpt' );
} );

function start_travel_destination_overrides() {
	return array(
		'title'       => 'Title',
		'kicker'      => 'Tagline',
		'photo'       => 'Featured image',
		'description' => 'Short description',
	);
}

// Treat populated fields on cards saved before the overrides existed as deliberate edits.
foreach ( start_travel_destination_overrides() as $name => $label ) {
	add_filter( "acf/load_value/key=field_travel_cards_cards_override_{$name}", static function ( $value, $post_id, $field ) use ( $name ) {
		if ( null !== acf_get_metadata_by_field( $post_id, $field ) ) {
			return $value;
		}
		$original = $field;
		$original['name'] = substr( $field['name'], 0, -strlen( 'override_' . $name ) ) . $name;
		return empty( acf_get_metadata_by_field( $post_id, $original ) ) ? 0 : 1;
	}, 10, 3 );
}

function start_travel_destination_source( $url ) {
	$parts = wp_parse_url( $url );
	if ( ! is_array( $parts ) || isset( $parts['user'] ) || isset( $parts['pass'] )
		|| ( isset( $parts['scheme'] ) && ! in_array( strtolower( $parts['scheme'] ), array( 'http', 'https' ), true ) ) ) {
		return null;
	}

	if ( empty( $parts['host'] ) ) {
		if ( ! str_starts_with( $url, '/' ) || str_starts_with( $url, '//' ) ) {
			return null;
		}
		// A root-relative link already includes the installation path on subdirectory sites.
		$home = wp_parse_url( home_url() );
		$url = $home['scheme'] . '://' . $home['host'] . ( isset( $home['port'] ) ? ':' . $home['port'] : '' ) . $url;
	}
	$id = url_to_postid( $url );
	$post = $id ? get_post( $id ) : null;
	return $post && empty( $post->post_password ) && is_post_publicly_viewable( $post ) ? $post : null;
}

function start_travel_destination_hero( $blocks ) {
	foreach ( $blocks as $block ) {
		if ( 'acf/travel-hero' === $block['blockName'] ) {
			return $block['attrs']['data'] ?? array();
		}
		if ( $block['innerBlocks'] ) {
			$hero = start_travel_destination_hero( $block['innerBlocks'] );
			if ( $hero ) {
				return $hero;
			}
		}
	}
	return array();
}

function start_travel_destination_card( $card ) {
	$link = $card['link'] ?? array();
	$url = $link['url'] ?? '';
	$post = $url ? start_travel_destination_source( $url ) : null;
	$defaults = array(
		'title'       => $link['title'] ?? '',
		'kicker'      => '',
		'photo'       => 0,
		'description' => '',
	);

	if ( $post ) {
		$tagline = get_post_meta( $post->ID, 'page_tagline', true );
		$photo = get_post_thumbnail_id( $post );
		$description = trim( $post->post_excerpt );
		if ( ( ! $tagline || ! $photo || '' === $description ) && has_block( 'acf/travel-hero', $post ) ) {
			$hero = start_travel_destination_hero( parse_blocks( $post->post_content ) );
			$tagline = $tagline ?: ( $hero['eyebrow'] ?? $hero['field_travel_hero_eyebrow'] ?? '' );
			$photo = $photo ?: (int) ( $hero['photo'] ?? $hero['field_travel_hero_photo'] ?? 0 );
			$description = $description ?: wp_trim_words( wp_strip_all_tags( $hero['description'] ?? $hero['field_travel_hero_description'] ?? '' ), 25 );
		}
		if ( '' === $description ) {
			$text = wp_strip_all_tags( strip_shortcodes( excerpt_remove_blocks( $post->post_content ) ), true );
			$description = wp_trim_words( $text, 25 );
		}
		$defaults = array(
			'title'       => get_the_title( $post ),
			'kicker'      => $tagline,
			'photo'       => $photo,
			'description' => $description,
		);
	}

	foreach ( $defaults as $name => $value ) {
		// Style Kit examples and older presets may bypass ACF's load filters.
		$override = $card[ 'override_' . $name ] ?? ! empty( $card[ $name ] );
		$card[ $name ] = $override ? ( $card[ $name ] ?? '' ) : $value;
		if ( 'photo' !== $name ) {
			$card[ $name ] = trim( wp_strip_all_tags( $card[ $name ] ) );
		}
	}
	if ( '' === $card['title'] && $url ) {
		$card['title'] = sprintf( __( 'Explore %s', 'start-components' ), wp_parse_url( $url, PHP_URL_HOST ) ?: __( 'destination', 'start-components' ) );
	}
	return $card;
}
