<?php
/** One-time local site content import. Run within WordPress only. */
defined( 'ABSPATH' ) || exit;
if ( 'local' !== wp_get_environment_type() || 'foursuitcasesandadream.local' !== wp_parse_url( home_url(), PHP_URL_HOST ) ) { return; }

$pages = array(
	'home' => array( 'Home', 'Where will your family go next?', 'A castle at dusk. A trail with a view. A morning with nowhere to rush. Plan a trip that feels like your family, with Lindsay by your side.', 'hero-1', 'tickets-passport-stuffed', 'plum' ),
	'services' => array( 'Services', 'Travel planning for your family', 'Start with what makes a good day for each person. Then build the route, the reservations, and the breathing room around that.', 'hero-6', 'compass', 'sea' ),
	'about-lindsay' => array( 'About Lindsay', 'Meet Lindsay', 'I plan family travel around the people going. Tell me who loves a full itinerary, who needs a quiet morning, and what you hope to remember when you get home.', 'lindsay-photo', '', 'apricot' ),
	'disney' => array( 'Disney', 'Find your kind of Disney magic', 'Maybe it’s the first glimpse of the castle. Maybe it’s a familiar snack and a shady bench after a busy morning. Let’s make room for both.', 'wdw', 'tickets-park-admission', 'plum' ),
	'cruises' => array( 'Cruises', 'Family adventures at sea', 'Choose a ship by the day you want on board. Think about cabin layout, meals, kids’ activities, and how much exploring feels right in port.', 'hero-3', 'tickets-cruise-itinerary', 'sea' ),
	'national-parks-road-trips' => array( 'National Parks & Road Trips', 'Take the scenic route', 'Build a road trip around a few wonderful stops, with time for a picnic, an unexpected overlook, or a shorter day on the road.', 'hero', 'trail-map-folded', 'sand' ),
	'autistic-travel' => array( 'Autism-friendly travel', 'Travel planning for autistic travelers', 'Predictability, sensory preferences, and recovery time can shape the whole trip. Start with the routines that work for your family at home.', 'hero-5', 'travel-guide-worn', 'plum' ),
	'food-allergy-gluten-free-travel' => array( 'Food allergy & gluten-free travel', 'Make food part of the plan', 'Share your dining priorities before choosing a resort, ship, or route. Lindsay can help organize questions for the people who prepare and serve your meals.', 'international', 'tickets-plane-train', 'apricot' ),
	'accessible-travel' => array( 'Accessible & special needs travel', 'Plan around access from the start', 'A room description answers only some questions. Map the journey from arrival to the room, from the room to meals, and from there to the places you want to explore.', 'road-trip', 'binoculars', 'sea' ),
	'plan-your-trip' => array( 'Plan Your Trip', 'Tell me about your trip', 'A rough idea is plenty to start with. Bring your travel dates or season, a comfortable budget range, and one thing each traveler would love to do.', 'hero-4', 'luggage-family-city', 'sand' ),
	'contact' => array( 'Contact', 'Get in touch with Lindsay', 'Have a destination in mind, a question about accommodations, or no idea where to start? Bring it to a planning conversation.', 'hero-6', '', 'plum' ),
	'travel-journal' => array( 'Travel Journal', 'Travel Journal', 'Family travel notes from Lindsay.', 'road-trip', '', 'sand' ),
);
$ids = array();
foreach ( $pages as $slug => $data ) {
	$page = get_page_by_path( $slug );
	$id = $page ? $page->ID : wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_name' => $slug, 'post_title' => $data[0] ), true );
	if ( is_wp_error( $id ) ) { throw new RuntimeException( $id->get_error_message() ); }
	$ids[$slug] = $id;
}
require_once ABSPATH . 'wp-admin/includes/image.php';
$media = function ( $name ) {
	if ( ! $name ) { return 0; }
	$key = 'travel_source_' . sanitize_key( $name );
	if ( get_post( (int) get_option( $key ) ) && get_option( $key ) ) { return (int) get_option( $key ); }
	$file = get_theme_file_path( '/assets/travel/' . $name . '.webp' );
	$upload = wp_upload_bits( $name . '.webp', null, file_get_contents( $file ) );
	if ( $upload['error'] ) { throw new RuntimeException( $upload['error'] ); }
	$id = wp_insert_attachment( array( 'post_title' => ucwords( str_replace( '-', ' ', $name ) ), 'post_mime_type' => 'image/webp', 'post_status' => 'inherit' ), $upload['file'], 0, true );
	if ( is_wp_error( $id ) ) { throw new RuntimeException( $id->get_error_message() ); }
	wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $upload['file'] ) );
	update_option( $key, $id, false );
	return $id;
};
$block = function ( $kind, $values ) {
	$data = array();
	foreach ( $values as $name => $value ) { $data[$name] = $value; $data['_' . $name] = "field_travel_{$kind}_{$name}"; }
	return get_comment_delimited_block_content( 'acf/travel-' . $kind, array( 'name' => 'acf/travel-' . $kind, 'data' => $data, 'mode' => 'preview' ), '' );
};
$heading = function ( $s ) { return '<!-- wp:heading --><h2 class="wp-block-heading">' . esc_html( $s ) . '</h2><!-- /wp:heading -->'; };
$para = function ( $s ) { return '<!-- wp:paragraph --><p>' . $s . '</p><!-- /wp:paragraph -->'; };
$url = function ( $slug ) use ( $ids ) { return wp_make_link_relative( get_permalink( $ids[$slug] ) ); };
$specialties = array( 'autistic-travel', 'food-allergy-gluten-free-travel', 'accessible-travel' );
$destinations = array( 'disney', 'cruises', 'national-parks-road-trips' );
$faqs = array(
	'autistic-travel' => array( 'What might a park day look like?', 'Pick one must-do experience, identify a quiet place nearby, and leave the afternoon open. A photo itinerary can help a traveler see what comes next.', 'Can we plan for a change of plans?', 'Yes. Talk through which changes are hardest and choose a familiar fallback for each busy day.' ),
	'food-allergy-gluten-free-travel' => array( 'What should we ask before booking?', 'Tell Lindsay which ingredients you need to discuss and which meals concern you most. She can help you identify dining contacts and ask about preparation spaces and cross-contact procedures.', 'Who confirms the dining arrangements?', 'The restaurant or operator confirms its own procedures. Reconfirm directly with the dining team when you arrive, since menus, suppliers, and staff can change.' ),
	'accessible-travel' => array( 'Can we check the details of a room?', 'Yes. Identify the features you use, such as step-free entry, space beside the bed, or a particular shower layout. Request measurements or photos from the property before booking.', 'What about transfers and excursions?', 'Review each leg separately. For example, ask about the vehicle entrance, space for mobility equipment, surfaces at the destination, and rest stops along the route.' ),
);
$callout = get_page_by_path( 'travel-consultation', OBJECT, 'wp_block' );
if ( ! $callout ) {
	$id = wp_insert_post( array( 'post_type' => 'wp_block', 'post_status' => 'publish', 'post_title' => 'Travel consultation', 'post_name' => 'travel-consultation', 'post_content' => $heading( 'Let’s plan something together' ) . $para( '<a href="' . esc_url( $url( 'plan-your-trip' ) ) . '">Start a planning conversation with Lindsay →</a>' ) ), true );
	if ( is_wp_error( $id ) ) { throw new RuntimeException( $id->get_error_message() ); }
	$callout = get_post( $id );
}
foreach ( $pages as $slug => [$title, $headline, $intro, $photo, $object, $palette] ) {
	if ( trim( get_post_field( 'post_content', $ids[$slug] ) ) || 'travel-journal' === $slug ) { continue; }
	$values = array( 'title' => $headline, 'description' => $intro, 'photo' => $media( $photo ), 'object' => $media( $object ), 'palette' => $palette, 'parallax' => 1, 'light_leak' => 1 );
	if ( 'home' === $slug ) { $values['link'] = array( 'title' => 'Plan your trip', 'url' => $url( 'plan-your-trip' ) ); }
	$content = $block( 'hero', $values );
	if ( isset( $faqs[$slug] ) ) {
		$content .= $heading( 'Planning questions' );
		foreach ( array_chunk( $faqs[$slug], 2 ) as [$question, $answer] ) {
			$content .= '<!-- wp:details --><details class="wp-block-details"><summary>' . esc_html( $question ) . '</summary>' . $para( esc_html( $answer ) ) . '</details><!-- /wp:details -->';
		}
	}
	if ( in_array( $slug, array( 'contact', 'plan-your-trip' ), true ) ) {
		$content .= $heading( 'Choose a consultation time' );
		if ( shortcode_exists( 'mwm_booking_form' ) ) { $content .= '<!-- wp:shortcode -->[mwm_booking_form]<!-- /wp:shortcode -->'; }
	} else {
		$groups = array();
		if ( ! in_array( $slug, $destinations, true ) ) { $groups['Explore destinations'] = $destinations; }
		$groups['Planning around your family'] = array_diff( $specialties, array( $slug ) );
		foreach ( $groups as $label => $related ) {
			$content .= $heading( $label ) . '<!-- wp:columns --><div class="wp-block-columns">';
			foreach ( $related as $other ) {
				$d = $pages[$other];
				$content .= '<!-- wp:column --><div class="wp-block-column">' . $block( 'card', array( 'title' => $d[0], 'description' => $d[2], 'photo' => $media( $d[3] ), 'palette' => $d[5], 'link' => array( 'url' => $url( $other ), 'title' => $d[0] ) ) ) . '</div><!-- /wp:column -->';
			}
			$content .= '</div><!-- /wp:columns -->';
		}
		$content .= '<!-- wp:block {"ref":' . (int) $callout->ID . '} /-->';
	}
	$result = wp_update_post( array( 'ID' => $ids[$slug], 'post_content' => wp_slash( $content ) ), true );
	if ( is_wp_error( $result ) ) { throw new RuntimeException( $result->get_error_message() ); }
}
update_option( 'show_on_front', 'page' );
update_option( 'page_on_front', $ids['home'] );
update_option( 'page_for_posts', $ids['travel-journal'] );
$locations = get_theme_mod( 'nav_menu_locations', array() );
if ( empty( $locations['primary'] ) ) {
	$menu = wp_get_nav_menu_object( 'Family travel' );
	$menu_id = $menu ? $menu->term_id : wp_create_nav_menu( 'Family travel' );
	if ( ! is_wp_error( $menu_id ) ) {
		foreach ( array( 'about-lindsay', 'services', 'travel-journal', 'plan-your-trip' ) as $slug ) {
			wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-object-id' => $ids[$slug], 'menu-item-object' => 'page', 'menu-item-type' => 'post_type', 'menu-item-status' => 'publish' ) );
		}
		$locations['primary'] = $menu_id;
		set_theme_mod( 'nav_menu_locations', $locations );
	}
}

// Complete the locally seeded pages once. Preserve subsequent editorial changes.
if ( get_option( 'travel_editorial_setup_v1' ) ) { return; }
$clip = get_option( 'travel_cruise_clip' );
if ( ! $clip ) {
	$file = 'cruise-clouds.mp4';
	$upload = wp_upload_bits( $file, null, file_get_contents( get_theme_file_path( '/assets/travel/' . $file ) ) );
	if ( $upload['error'] ) { throw new RuntimeException( $upload['error'] ); }
	$clip = wp_insert_attachment( array( 'post_title' => 'Clouds above the cruise deck', 'post_mime_type' => 'video/mp4', 'post_status' => 'inherit' ), $upload['file'], 0, true );
	if ( is_wp_error( $clip ) ) { throw new RuntimeException( $clip->get_error_message() ); }
	update_option( 'travel_cruise_clip', $clip, false );
}
$chapters = array(
	'disney' => array( 'Which Disney trip fits?', 'Walt Disney World', 'Start by choosing the parks that matter most to your family. A nearby hotel or a planned midday break can change how much energy everyone has left for the evening.', 'Disneyland', 'For a shorter visit, build each day around one or two must-dos. Leave space between reservations for a second ride on an unexpected favorite.' ),
	'cruises' => array( 'Choose your time at sea', 'Disney Cruise Line', 'Talk through how much time your family wants for characters, shows, and activities together. Pick a sailing with room for slow mornings as well as special occasions.', 'Royal Caribbean', 'Compare the ships on your shortlist. A particular activity, accessible cabin layout, or quieter spot on deck may matter more than the number of ports.' ),
	'national-parks-road-trips' => array( 'Build a route worth lingering over', 'Yellowstone and the wide-open West', 'Choose the landscape you most want to see, then work backward from that stop. A small cluster of walks and overlooks gives kids time to notice things along the way.', 'Zion, Yosemite, and the Grand Canyon', 'Compare walking surfaces, shade, distance from lodging, and places to sit. A scenic overlook and a picnic can be the whole outing.' ),
	'about-lindsay' => array( 'How I approach a family trip', 'Start with the people', 'What does a good morning look like? How does everyone feel about crowds, long drives, and unfamiliar meals? Those details help shape our first conversation.', 'Build in choices', 'We can look at destinations alongside sensory preferences, food allergies, gluten-free dining, and access needs, then decide together where to spend our planning time.' ),
);
foreach ( $pages as $slug => $data ) {
	$post = get_post( $ids[$slug] );
	$blocks = parse_blocks( $post->post_content );
	if ( 'acf/travel-hero' !== ( $blocks[0]['blockName'] ?? '' ) || $data[1] !== ( $blocks[0]['attrs']['data']['title'] ?? '' ) ) { continue; }
	$decorate = function ( &$blocks ) use ( &$decorate, $clip ) {
		foreach ( $blocks as &$item ) {
			if ( 'acf/travel-card' === $item['blockName'] && 'Cruises' === $item['attrs']['data']['title'] ) {
				$item['attrs']['data']['clip'] = $clip;
				$item['attrs']['data']['_clip'] = 'field_travel_card_clip';
			}
			if ( $item['innerBlocks'] ) { $decorate( $item['innerBlocks'] ); }
		}
	};
	$decorate( $blocks );
	if ( isset( $chapters[$slug] ) ) {
		[$section, $a, $one, $b, $two] = $chapters[$slug];
		$section = $heading( $section ) . $para( '<strong>' . $a . '.</strong> ' . $one ) . $para( '<strong>' . $b . '.</strong> ' . $two );
		array_splice( $blocks, 1, 0, parse_blocks( $section ) );
	}
	wp_update_post( array( 'ID' => $post->ID, 'post_excerpt' => $data[2], 'post_content' => wp_slash( serialize_blocks( $blocks ) ) ) );
}
$callout_body = $callout->post_content;
if ( ! has_block( 'core/group', $callout_body ) ) {
	wp_update_post( array( 'ID' => $callout->ID, 'post_content' => '<!-- wp:group {"className":"is-style-travel-plum"} --><div class="wp-block-group is-style-travel-plum">' . $callout_body . '</div><!-- /wp:group -->' ) );
}
$footer_menu = get_nav_menu_locations()['footer'] ?? 0;
if ( $footer_menu && ! in_array( (string) $ids['travel-journal'], array_map( 'strval', wp_list_pluck( wp_get_nav_menu_items( $footer_menu ) ?: array(), 'object_id' ) ), true ) ) {
	wp_update_nav_menu_item( $footer_menu, 0, array( 'menu-item-object-id' => $ids['travel-journal'], 'menu-item-object' => 'page', 'menu-item-type' => 'post_type', 'menu-item-status' => 'publish' ) );
}
update_option( 'travel_editorial_setup_v1', 1, false );
