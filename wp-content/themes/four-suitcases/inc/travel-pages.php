<?php
defined( 'ABSPATH' ) || exit;

function foursuitcases_travel_pattern_data( $kind, $values, $media ) {
	$definition = start_travel_specs()[ $kind ][3];
	$data = array();
	$prefix = 'field_travel_' . $kind;
	foreach ( $values as $name => $value ) {
		$key = $prefix . '_' . $name;
		$field = $definition[ $name ] ?? '';
		$data[ '_' . $name ] = $key;
		if ( isset( $field['rows'] ) ) {
			$data[ $name ] = count( $value );
			foreach ( $value as $index => $row ) {
				foreach ( $row as $column => $cell ) {
					$row_key = $name . '_' . $index . '_' . $column;
					$data[ $row_key ] = 'image' === ( $field['rows'][ $column ] ?? '' ) ? $media( $cell ) : $cell;
					$data[ '_' . $row_key ] = $key . '_' . $column;
				}
			}
		} elseif ( 'image' === $field ) {
			$data[ $name ] = $media( $value );
		} elseif ( 'gallery' === $field ) {
			$data[ $name ] = array_values( array_filter( array_map( $media, $value ) ) );
		} else {
			$data[ $name ] = $value;
		}
	}
	return $data;
}

function foursuitcases_travel_reference_media( $name ) {
	if ( ! $name || is_numeric( $name ) ) {
		return (int) $name;
	}
	// The two collection files were re-cropped for v2. Preserve the original media references.
	$is_recrop = in_array( $name, array( 'luggage-family-beach', 'luggage-family-city' ), true );
	$key = ( $is_recrop ? 'travel_source_v2_' : 'travel_source_' ) . sanitize_key( $name );
	$id = (int) get_option( $key );
	if ( $id && 'attachment' === get_post_type( $id ) ) {
		return $id;
	}
	$file = get_theme_file_path( "assets/travel/{$name}.webp" );
	if ( ! is_readable( $file ) ) {
		throw new RuntimeException( 'Missing travel asset ' . $name );
	}
	require_once ABSPATH . 'wp-admin/includes/image.php';
	$filename = $is_recrop ? $name . '-recropped.webp' : $name . '.webp';
	$upload = wp_upload_bits( $filename, null, file_get_contents( $file ) );
	if ( $upload['error'] ) {
		throw new RuntimeException( $upload['error'] );
	}
	$id = wp_insert_attachment( array( 'post_title' => ucwords( str_replace( '-', ' ', $name ) ), 'post_mime_type' => 'image/webp', 'post_status' => 'inherit' ), $upload['file'], 0, true );
	if ( is_wp_error( $id ) ) {
		throw new RuntimeException( $id->get_error_message() );
	}
	$metadata = wp_generate_attachment_metadata( $id, $upload['file'] );
	wp_update_attachment_metadata( $id, $metadata );
	update_option( $key, $id, false );
	return $id;
}

function foursuitcases_travel_reference_block( $kind, $values, $anchor = '' ) {
	$name = 'acf/travel-' . $kind;
	$attrs = array( 'name' => $name, 'data' => foursuitcases_travel_pattern_data( $kind, $values, 'foursuitcases_travel_reference_media' ), 'mode' => 'preview' );
	if ( $anchor ) {
		$attrs['anchor'] = $anchor;
	}
	return get_comment_delimited_block_content( $name, $attrs, '' );
}

function foursuitcases_travel_inquiry( $blocks ) {
	foreach ( $blocks as $block ) {
		$inside = foursuitcases_travel_inquiry( $block['innerBlocks'] );
		if ( $inside ) {
			return $inside;
		}
		if ( in_array( $block['blockName'], array( 'core/shortcode', 'formidable/simple-form' ), true )
			|| preg_match( '/\[(mwm_booking_form|formidable)\b/', $block['innerHTML'] ?? '' ) ) {
			return $block;
		}
	}
	return null;
}

function foursuitcases_travel_apply_reference_pages() {
	if ( 'local' !== wp_get_environment_type() || 'foursuitcasesandadream.local' !== wp_parse_url( home_url(), PHP_URL_HOST ) ) {
		throw new RuntimeException( 'The reference-page update targets the local Four Suitcases site.' );
	}
	if ( ! function_exists( 'start_travel_specs' ) || ! acf_has_block_type( 'acf/travel-cards' ) ) {
		throw new RuntimeException( 'Activate Start Components and ACF Pro first.' );
	}
	$prepared = array();
	foreach ( array( 'home', 'plan-your-trip' ) as $slug ) {
		$page = get_page_by_path( $slug );
		if ( ! $page ) {
			throw new RuntimeException( 'Missing page ' . $slug );
		}
		if ( has_block( 'acf/travel-divider', $page ) || has_block( 'acf/travel-steps', $page ) ) {
			continue;
		}
		$preserved = array();
		$blocks = parse_blocks( $page->post_content );
		$inquiry = foursuitcases_travel_inquiry( $blocks );
		for ( $index = 0, $count = count( $blocks ); $index < $count; ++$index ) {
			$existing = $blocks[ $index ];
			$name = $existing['blockName'];
			if ( 'acf/travel-hero' === $name || ( null === $name && '' === trim( $existing['innerHTML'] ) ) ) {
				continue;
			}
			// Retire the seeded destination grids. Leave other editable content in place.
			if ( 'core/heading' === $name && in_array( trim( wp_strip_all_tags( $existing['innerHTML'] ) ), array( 'Explore destinations', 'Planning around your family' ), true ) ) {
				$next = $index + 1;
				while ( isset( $blocks[ $next ] ) && null === $blocks[ $next ]['blockName'] && '' === trim( $blocks[ $next ]['innerHTML'] ) ) { ++$next; }
				$grid = $blocks[ $next ] ?? null;
				if ( $grid && 'core/columns' === $grid['blockName'] && $grid['innerBlocks'] && ! array_filter( $grid['innerBlocks'], static function ( $column ) {
					return count( $column['innerBlocks'] ) !== 1 || 'acf/travel-card' !== $column['innerBlocks'][0]['blockName'];
				} ) ) {
					$index = $next;
					continue;
				}
			}
			if ( 'home' === $slug && 'core/block' === $name && 'travel-consultation' === get_post_field( 'post_name', (int) ( $existing['attrs']['ref'] ?? 0 ) ) ) {
				continue;
			}
			if ( 'plan-your-trip' === $slug && $inquiry && 'core/heading' === $name && 'Choose a consultation time' === trim( wp_strip_all_tags( $existing['innerHTML'] ) ) ) {
				continue;
			}
			$preserved[] = $existing;
		}

		$sections = foursuitcases_travel_reference_sections( $slug );
		if ( 'home' === $slug ) {
			$clip = (int) get_option( 'travel_cruise_clip' );
			if ( $clip && 'attachment' === get_post_type( $clip ) ) {
				$sections[2][1]['cards'][1]['clip'] = $clip;
			}
		} elseif ( ! $inquiry && ! shortcode_exists( 'mwm_booking_form' ) ) {
			$contact = foursuitcases_travel_page_link( 'contact', 'Get in touch with Lindsay' );
			if ( empty( $contact['url'] ) ) {
				throw new RuntimeException( 'Plan Your Trip needs its inquiry form or a published Contact page.' );
			}
			$sections[0][1]['link'] = $contact;
			$sections[5][1]['link'] = $contact;
		}
		$content = '';
		foreach ( $sections as $section ) {
			$content .= foursuitcases_travel_reference_block( ...$section ) . "\n\n";
		}
		if ( 'plan-your-trip' === $slug && ( $inquiry || shortcode_exists( 'mwm_booking_form' ) ) ) {
			$content .= '<!-- wp:heading {"anchor":"inquiry"} --><h2 class="wp-block-heading" id="inquiry">Choose a consultation time</h2><!-- /wp:heading -->' . "\n";
			if ( ! $inquiry ) {
				$content .= '<!-- wp:shortcode -->[mwm_booking_form]<!-- /wp:shortcode -->' . "\n\n";
			}
		}
		$prepared[] = array( 'ID' => $page->ID, 'post_content' => $content . serialize_blocks( $preserved ) );
	}
	foreach ( $prepared as $update ) {
		$original = get_post( $update['ID'] );
		wp_save_post_revision( $original->ID );
		$result = wp_update_post( wp_slash( $update ), true );
		if ( is_wp_error( $result ) ) {
			throw new RuntimeException( $result->get_error_message() );
		}
	}
	return count( $prepared );
}

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	WP_CLI::add_command( 'four-suitcases apply-reference-pages', static function () {
		try {
			WP_CLI::success( sprintf( 'Updated %d reference pages.', foursuitcases_travel_apply_reference_pages() ) );
		} catch ( RuntimeException $error ) {
			WP_CLI::error( $error->getMessage() );
		}
	} );
}

function foursuitcases_travel_show_reference_control() {
	if ( 'local' !== wp_get_environment_type() || 'foursuitcasesandadream.local' !== wp_parse_url( home_url(), PHP_URL_HOST ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$home = get_page_by_path( 'home' );
	$plan = get_page_by_path( 'plan-your-trip' );
	if ( $home && $plan && has_block( 'acf/travel-divider', $home ) && has_block( 'acf/travel-steps', $plan ) ) {
		echo '<p>Home and Plan Your Trip use the v2 layouts.</p>';
		return;
	}
	echo '<form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '" class="travel-style-kit-controls"><input type="hidden" name="action" value="four_suitcases_apply_reference_pages">';
	wp_nonce_field( 'four_suitcases_apply_reference_pages' );
	echo '<p>Apply the reference layouts to the two local WordPress pages. Their previous versions are available in Revisions.</p><button type="submit">Apply v2 layouts to Home and Plan Your Trip</button></form>';
}

add_action( 'admin_post_four_suitcases_apply_reference_pages', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'You do not have permission to update these pages.', '', array( 'response' => 403 ) );
	}
	check_admin_referer( 'four_suitcases_apply_reference_pages' );
	try {
		foursuitcases_travel_apply_reference_pages();
	} catch ( RuntimeException $error ) {
		wp_die( esc_html( $error->getMessage() ) );
	}
	wp_safe_redirect( add_query_arg( 'start_style_guide', '1', home_url( '/' ) ) );
	exit;
} );
