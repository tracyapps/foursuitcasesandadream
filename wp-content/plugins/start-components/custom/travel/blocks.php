<?php
defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/destination-cards.php';

function start_travel_palettes() {
	return array( 'plum' => 'Plum', 'sea' => 'Sea glass', 'apricot' => 'Apricot', 'sand' => 'Sand', 'sun' => 'Sun', 'coral' => 'Coral', 'sky' => 'Sky', 'mint' => 'Mint', 'night' => 'Night' );
}

function start_travel_specs() {
	$palettes = start_travel_palettes();
	$heading = array( 'eyebrow' => 'text', 'title' => 'text', 'script' => 'text', 'description' => 'textarea' );
	$card = array( 'title' => 'text', 'description' => 'textarea', 'photo' => 'image', 'link' => 'link', 'palette' => $palettes, 'clip' => 'file' );
	$destination_card = array( 'link' => 'link' );
	foreach ( start_travel_destination_overrides() as $name => $label ) {
		$destination_card[ 'override_' . $name ] = 'true_false';
		$destination_card[ $name ] = $card[ $name ] ?? 'text';
	}
	$destination_card += $card + array( 'caption' => 'text', 'orientation' => array( 'auto', 'horizontal', 'vertical' ) );
	$background = array( 'plain' => 'Plain', 'wash' => 'Color wash' );
	$size = array( 'roomy' => 'Roomy', 'cozy' => 'Cozy' );
	return array(
		'hero' => array( 'Travel page hero', 'sun', array( 'collage', 'bleed', 'split' ), $heading + array(
			'photo' => 'image', 'link' => 'link', 'palette' => $palettes, 'object' => 'image', 'object_hover_tag' => 'text', 'parallax' => 'true_false', 'light_leak' => 'true_false',
			'size' => array( 'tall', 'compact' ), 'second_photo' => 'image', 'caption' => 'text', 'second_caption' => 'text',
			'secondary_link' => 'link', 'cutout' => 'image', 'cutout_hover_tag' => 'text', 'unpack' => 'true_false',
		) ),
		'card' => array( 'Destination card', 'sand', array(), $card ),
		'cards' => array( 'Destination cards', 'plum', array( 'prints', 'postcards', 'tags', 'tickets' ), $heading + array(
			'background' => $background, 'cards' => array( 'rows' => $destination_card ),
		) ),
		'steps' => array( 'How it works', 'coral', array(), $heading + array(
			'steps' => array( 'rows' => array( 'title' => 'text', 'text' => 'textarea', 'cutout' => 'image', 'cutout_hover_tag' => 'text' ) ),
		) ),
		'about' => array( 'About Lindsay', 'apricot', array( 'portrait', 'passport' ), $heading + array(
			'background' => $background, 'photo' => 'image', 'caption' => 'text', 'object' => 'image', 'object_hover_tag' => 'text', 'signature' => 'text', 'link' => 'link',
			'points' => array( 'rows' => array( 'title' => 'text', 'text' => 'text', 'palette' => $palettes ) ),
		) ),
		'testimonials' => array( 'Travel testimonials', 'apricot', array( 'spotlight', 'wall', 'simple' ), $heading + array(
			'background' => $background, 'size' => $size, 'quotes' => array( 'rows' => array( 'quote' => 'textarea', 'name' => 'text', 'trip' => 'text', 'photo' => 'image', 'palette' => $palettes ) ),
		) ),
		'gallery' => array( 'Travel photo gallery', 'sun', array( 'scatter', 'grid', 'strip' ), $heading + array( 'background' => $background, 'photos' => 'gallery' ) ),
		'posts' => array( 'Travel journal posts', 'sand', array( 'magazine', 'prints', 'list' ), $heading + array( 'count' => 'number', 'category' => 'taxonomy', 'loop' => 'true_false' ) ),
		'faq' => array( 'Travel FAQs', 'sea', array( 'split', 'accordion' ), $heading + array(
			'background' => $background, 'size' => $size, 'object' => 'image', 'object_hover_tag' => 'text', 'questions' => array( 'rows' => array( 'question' => 'text', 'answer' => 'wysiwyg' ) ),
		) ),
		'cta' => array( 'Travel call to action', 'sky', array( 'pass', 'poster', 'banner' ), $heading + array( 'link' => 'link', 'destinations' => 'textarea', 'object' => 'image', 'object_hover_tag' => 'text' ) ),
		'divider' => array( 'Travel divider', 'coral', array( 'route', 'marquee', 'scallop', 'certifications' ), array( 'from' => array( 'plain' => 'Page background' ) + $palettes, 'to' => $palettes, 'words' => 'textarea', 'start_label' => 'text', 'end_label' => 'text' ) ),
	);
}

function start_travel_field( $prefix, $name, $spec, $default = '' ) {
	$field = array( 'key' => $prefix . '_' . $name, 'label' => ucfirst( str_replace( '_', ' ', $name ) ), 'name' => $name );
	if ( is_array( $spec ) ) {
		if ( isset( $spec['rows'] ) ) {
			$field += array( 'type' => 'repeater', 'layout' => 'block', 'collapsed' => $field['key'] . '_' . array_key_first( $spec['rows'] ), 'sub_fields' => array() );
			foreach ( $spec['rows'] as $sub_name => $sub_spec ) {
				$sub_field = start_travel_field( $field['key'], $sub_name, $sub_spec );
				if ( 'field_travel_cards_cards' === $field['key'] ) {
					$labels = start_travel_destination_overrides();
					if ( isset( $labels[ $sub_name ] ) ) {
						$sub_field['label'] = $labels[ $sub_name ];
						$sub_field['required'] = 'title' === $sub_name;
						$sub_field['conditional_logic'] = array( array( array(
							'field' => $field['key'] . '_override_' . $sub_name, 'operator' => '==', 'value' => '1',
						) ) );
					} elseif ( str_starts_with( $sub_name, 'override_' ) ) {
						$sub_field['label'] = 'Override ' . strtolower( $labels[ substr( $sub_name, strlen( 'override_' ) ) ] );
					} elseif ( 'link' === $sub_name ) {
						$sub_field['label'] = 'Destination link';
					$sub_field['instructions'] = 'Each override controls one field. With overrides off, a linked page supplies its WordPress title, Page Header → Page Tagline, Featured image, and Excerpt. The Travel page hero can supply a missing tagline, image, or description. For external links or cards without a link, enter custom details using the overrides.';
					}
				}
				$field['sub_fields'][] = $sub_field;
			}
		} else {
			$field += array(
				'type' => 'select', 'allow_null' => '' === $default && 'palette' === $name,
				'choices' => array_key_first( $spec ) === 0 ? array_combine( $spec, array_map( 'ucfirst', $spec ) ) : $spec,
				'default_value' => $default,
			);
		}
		return $field;
	}
	if ( str_ends_with( $name, '_hover_tag' ) ) {
		$image = str_replace( '_hover_tag', '', $name );
		$field['label'] = ucfirst( $image ) . ' hover tag';
		$field['instructions'] = 'Leave blank to hide the hover tag.';
		$field['maxlength'] = 100;
		$field['conditional_logic'] = array( array( array(
			'field' => $prefix . '_' . $image, 'operator' => '!=empty', 'value' => '',
		) ) );
	}
	$field['type'] = $spec;
	switch ( $spec ) {
		case 'file':
			$field['mime_types'] = 'webm,mp4';
			// Fall through to the attachment-ID format.
		case 'image':
		case 'gallery':
			$field['return_format'] = 'id';
			break;
		case 'taxonomy':
			$field += array( 'taxonomy' => 'category', 'field_type' => 'select', 'allow_null' => 1, 'add_term' => 0 );
			break;
		case 'number':
			$field += array( 'min' => 1, 'max' => 24, 'default_value' => 6 );
			break;
		case 'true_false':
			$field += array( 'ui' => 1, 'default_value' => in_array( $name, array( 'parallax', 'unpack' ), true ) ? 1 : 0 );
			break;
		case 'textarea':
			$field['rows'] = 3;
			break;
		case 'wysiwyg':
			$field += array( 'tabs' => 'all', 'toolbar' => 'basic', 'media_upload' => 0 );
			break;
	}
	return $field;
}

add_action( 'acf/init', function () {
	foreach ( start_travel_specs() as $kind => [$title, $palette, $layouts, $spec] ) {
		acf_register_block_type( array(
			'name' => 'travel-' . $kind, 'title' => $title, 'category' => 'design', 'icon' => 'location-alt', 'mode' => 'preview',
			'description' => 'Choose layout and palette independently. Use one or two palettes per page and keep script accents to a word or short phrase. Save full-bleed heroes and poster calls to action for landing pages.',
			'render_template' => __DIR__ . '/render.php',
			'supports' => array( 'anchor' => true, 'align' => false ),
		) );
		$prefix = "field_travel_{$kind}";
		$fields = array();
		if ( $layouts ) {
			$fields[] = start_travel_field( $prefix, 'layout', $layouts, $layouts[0] );
		}
		$spec = array( 'palette' => start_travel_palettes() ) + $spec;
		foreach ( $spec as $name => $definition ) {
			$default = match ( $name ) {
				'palette', 'from', 'to' => $palette,
				'background' => in_array( $kind, array( 'about', 'faq', 'testimonials' ), true ) ? 'wash' : 'plain',
				'size' => array_key_first( $definition ) === 0 ? $definition[0] : array_key_first( $definition ),
				default => '',
			};
			$fields[] = start_travel_field( $prefix, $name, $definition, $default );
		}
		acf_add_local_field_group( array(
			'key' => 'group_travel_' . $kind, 'title' => $title, 'fields' => $fields,
			'location' => array( array( array( 'param' => 'block', 'operator' => '==', 'value' => 'acf/travel-' . $kind ) ) ),
		) );
	}
} );
