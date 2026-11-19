<?php
defined( 'ABSPATH' ) || exit;

function foursuitcases_travel_page_link( $path, $title ) {
	$page = get_page_by_path( $path );
	return array( 'url' => $page && 'publish' === $page->post_status ? wp_make_link_relative( get_permalink( $page ) ) : '', 'title' => $title );
}

function foursuitcases_travel_examples() {
	$plan = foursuitcases_travel_page_link( 'plan-your-trip', 'Plan your trip' );
	$card = static function ( $title, $kicker, $description, $photo, $palette, $caption, $path, $label ) {
		return compact( 'title', 'kicker', 'description', 'photo', 'palette', 'caption' ) + array(
			'link' => foursuitcases_travel_page_link( $path, $label ),
		);
	};
	$destinations = array(
		$card( 'Disney Destinations', 'Theme parks', 'Magical experiences for every age, from Walt Disney World to Disneyland Paris.', 'wdw', 'plum', 'Castle at dusk', 'disney', 'Explore Disney' ),
		$card( 'Cruises', 'Ocean adventures', 'Sail the world together with itineraries for every family size and budget.', 'cruise', 'sky', 'Sea day!', 'cruises', 'Explore cruises' ),
		$card( 'National Parks', 'Great outdoors', 'America’s greatest wonders, from the Grand Canyon to Yellowstone.', 'yellowstone', 'mint', 'Old Faithful', 'national-parks-road-trips', 'Explore parks' ),
		$card( 'Road Trips', 'Open road', 'Epic routes through stunning landscapes your family will never forget.', 'road-trip', 'apricot', 'Scenic route', 'national-parks-road-trips', 'Explore road trips' ),
	);
	$specialties = array(
		$card( 'Autism-friendly travel', 'Sensory-aware', 'Predictability, sensory preferences and recovery time can shape the whole trip. Start with the routines that work for your family at home.', 'hero-5', 'plum', 'Quiet breaks', 'autistic-travel', 'Explore autistic travel' ),
		$card( 'Food allergy & gluten-free', 'Dining', 'Share your dining priorities before choosing a resort, ship or route. Lindsay helps organize questions for the people who prepare your meals.', 'international', 'apricot', 'Safe snacks', 'food-allergy-gluten-free-travel', 'Explore food allergy planning' ),
		$card( 'Accessible travel', 'Access', 'Map the journey from arrival to the room, from the room to meals, and from there to the places you want to explore.', 'road-trip', 'sea', 'Room to roll', 'accessible-travel', 'Explore accessible travel' ),
	);
	return array(
		'hero' => array(
			'layout' => 'collage', 'palette' => 'sun', 'size' => 'tall', 'eyebrow' => 'Family travel planning',
			'title' => 'Where will your family go', 'script' => 'next?',
			'description' => 'A castle at dusk. A trail with a view. A morning with nowhere to rush. Plan a trip that feels like your family, with Lindsay by your side.',
			'photo' => 'hero-1', 'second_photo' => 'wdw', 'caption' => 'Summer of magic', 'second_caption' => 'One for the scrapbook',
			'object' => 'luggage-family-beach', 'cutout' => 'tickets-passport-stuffed', 'unpack' => 1, 'parallax' => 1, 'link' => $plan,
			'secondary_link' => array( 'url' => '#destinations', 'title' => 'Explore destinations' ),
		),
		'card' => $destinations[0],
		'cards' => array( 'layout' => 'postcards', 'palette' => 'plum', 'eyebrow' => 'Where will you go?', 'title' => 'Pick your kind of', 'script' => 'adventure', 'cards' => $destinations ),
		'planning-cards' => array(
			'layout' => 'tags', 'palette' => 'coral', 'eyebrow' => 'Planning around your family', 'title' => 'Travel that fits', 'script' => 'everyone',
			'description' => 'Sensory preferences, food allergies and access needs can shape the whole trip. Lindsay plans around them from the very first conversation.', 'cards' => $specialties,
		),
		'steps' => array(
			'palette' => 'coral', 'eyebrow' => 'How it works', 'title' => 'Your journey starts', 'script' => 'here',
			'description' => 'Planning a family vacation shouldn’t feel like a second job. Here’s how Lindsay turns your travel dreams into a trip that feels like your family.',
			'steps' => array(
				array( 'title' => 'Share your dream', 'text' => 'Tell Lindsay about your family, your dream destinations, travel dates, and a comfortable budget.', 'cutout' => 'travel-guide-worn' ),
				array( 'title' => 'Discovery call', 'text' => 'Talk through your vision, ask questions, and find out if Lindsay is the right fit for your family’s adventure.', 'cutout' => 'headphones-tan-standing' ),
				array( 'title' => 'Custom itinerary', 'text' => 'Lindsay researches accommodations, dining, activities and insider tips and builds an itinerary around your family.', 'cutout' => 'trail-map-open' ),
				array( 'title' => 'Book & relax', 'text' => 'Review the plan with Lindsay, then get your bookings and confirmations organized before departure.', 'cutout' => 'tickets-passport-stuffed' ),
				array( 'title' => 'Travel with confidence', 'text' => 'Head out with a plan your family can use, from departure to the trip home.', 'cutout' => 'luggage-family-roadtrip-2' ),
			),
		),
		'about' => array(
			'layout' => 'portrait', 'palette' => 'apricot', 'background' => 'wash', 'eyebrow' => 'Why choose Lindsay',
			'title' => 'A travel advisor who puts your family', 'script' => 'first', 'photo' => 'lindsay-photo',
			'description' => 'Lindsay brings insider knowledge, personal attention and genuine care to every trip she plans.',
			'points' => array(
				array( 'title' => 'Certified travel advisor', 'text' => 'Disney Destinations Specialist with experience across theme parks, cruises and national parks.', 'palette' => 'plum' ),
				array( 'title' => 'Personalized itineraries', 'text' => 'Tailored to your family’s ages, interests, budget and travel style.', 'palette' => 'sea' ),
				array( 'title' => 'Insider access', 'text' => 'Get help with reservations, dining and park strategy.', 'palette' => 'apricot' ),
				array( 'title' => 'Start-to-finish support', 'text' => 'Guidance from the first inquiry through your return home.', 'palette' => 'sky' ),
			),
			'link' => foursuitcases_travel_page_link( 'about-lindsay', 'Meet Lindsay' ),
		),
		'testimonials' => array(
			'layout' => 'wall', 'palette' => 'sky', 'background' => 'wash', 'size' => 'cozy',
			'eyebrow' => 'Postcards from our travelers', 'title' => 'Happy', 'script' => 'campers',
			'quotes' => array(
				array( 'quote' => 'Lindsay has planned our last two Disney World trips and both were absolutely magical. She knew every shortcut, the best dining spots, and even helped us snag hard-to-get reservations. Our kids still talk about it two years later!', 'name' => 'Carol W.', 'trip' => 'Disney World', 'photo' => 'wdw', 'palette' => 'plum' ),
				array( 'quote' => 'Lindsay has helped our family plan multiple dream vacations, including two National Park trips and an upcoming Disney Alaska cruise. Her recommendations on where to stay, what to see, and how to make the most of our time were invaluable. Our recent Yellowstone trip exceeded all expectations. We couldn’t have done it without her guidance!', 'name' => 'Julie T.', 'trip' => 'Yellowstone & Disney Alaska Cruise', 'photo' => 'yellowstone', 'palette' => 'mint' ),
				array( 'quote' => 'Even through complicated changes with flights just prior to my cruise, Lindsay handled everything with ease and made sure I would have a seamless travel experience.', 'name' => 'Paula R.', 'trip' => 'Viking River Cruise', 'photo' => 'international', 'palette' => 'apricot' ),
			),
		),
		'gallery' => array( 'layout' => 'scatter', 'palette' => 'sun', 'background' => 'wash', 'title' => 'From the', 'script' => 'scrapbook', 'photos' => array( 'wdw', 'yosemite', 'hero-3', 'lindsay-photo', 'road-trip', 'disneyland', 'yellowstone', 'zion' ) ),
		'posts' => array( 'layout' => 'magazine', 'palette' => 'sand', 'title' => 'The travel', 'script' => 'journal', 'count' => 6, 'loop' => 1 ),
		'faq' => array(
			'layout' => 'split', 'palette' => 'sea', 'background' => 'wash', 'eyebrow' => 'Common questions', 'title' => 'Good to', 'script' => 'know',
			'questions' => array(
				array( 'question' => 'How much does it cost to use Lindsay’s planning services?', 'answer' => '<p>It depends on the trip. Lindsay walks you through how planning is priced on your discovery call, before anything is booked.</p>' ),
				array( 'question' => 'How far in advance should I start planning?', 'answer' => '<p>The earlier the better, especially for Disney, cruises and national park lodging, which can fill up many months out.</p>' ),
				array( 'question' => 'Do I need to know exactly where I want to go?', 'answer' => '<p>Bring a season, a comfortable budget range and one thing each traveler would love to do. You can narrow down destinations together.</p>' ),
				array( 'question' => 'What if something goes wrong during our trip?', 'answer' => '<p>Lindsay stays a message away while you travel and helps you work through changes, from delayed flights to rebooked reservations.</p>' ),
				array( 'question' => 'Can Lindsay help with travel insurance?', 'answer' => '<p>She can explain your travel protection options so you can choose the coverage that fits your trip.</p>' ),
				array( 'question' => 'Do you work with families of all sizes and ages?', 'answer' => '<p>Yes, from toddlers to grandparents, small families to multi-generational groups, including travelers with sensory, dietary or access needs.</p>' ),
			),
		),
		'cta' => array( 'layout' => 'pass', 'palette' => 'sun', 'eyebrow' => 'Your boarding pass awaits', 'title' => 'Let’s plan something', 'script' => 'together', 'description' => 'Tell Lindsay what your family has in mind. A rough idea is plenty to start.', 'link' => $plan ),
		'divider' => array( 'layout' => 'marquee', 'palette' => 'plum', 'from' => 'sun', 'to' => 'apricot' ),
	);
}

function foursuitcases_travel_reference_sections( $page ) {
	$examples = foursuitcases_travel_examples();
	if ( 'home' === $page ) {
		return array(
			array( 'hero', $examples['hero'] ),
			array( 'divider', $examples['divider'] ),
			array( 'cards', $examples['cards'], 'destinations' ),
			array( 'divider', array( 'layout' => 'scallop', 'palette' => 'apricot', 'from' => 'plain', 'to' => 'apricot' ) ),
			array( 'about', $examples['about'] ),
			array( 'divider', array( 'layout' => 'route', 'palette' => 'coral' ) ),
			array( 'cards', $examples['planning-cards'] ),
			array( 'testimonials', $examples['testimonials'] ),
			array( 'cta', $examples['cta'] ),
		);
	}
	$cards = array_slice( $examples['cards']['cards'], 0, 3 );
	$cards[0]['photo'] = 'disneyland';
	$cards[1]['photo'] = 'royal-caribbean';
	$cards[2]['photo'] = 'grand-canyon';
	$cards[2]['title'] = 'National Parks & Road Trips';
	foreach ( array( 'Gate D · Theme parks', 'Gate C · At sea', 'Gate P · Open road' ) as $index => $kicker ) {
		$cards[ $index ]['kicker'] = $kicker;
	}
	$faq = $examples['faq'];
	// Operational and pricing answers stay in the editor preview pending Lindsay’s review.
	$faq['questions'] = array_intersect_key( $faq['questions'], array_flip( array( 1, 2, 5 ) ) );
	return array(
		array( 'hero', array(
			'layout' => 'bleed', 'palette' => 'coral', 'size' => 'tall', 'eyebrow' => 'Family travel planning', 'title' => 'Plan your perfect family', 'script' => 'trip',
			'description' => 'From first dream to final boarding pass, Lindsay helps you plan the details so you can focus on making memories.',
			'photo' => 'hero-4', 'object' => 'luggage-family-city', 'unpack' => 1, 'parallax' => 1, 'link' => array( 'url' => '#inquiry', 'title' => 'Start your inquiry' ),
		) ),
		array( 'steps', $examples['steps'] ),
		array( 'divider', array( 'layout' => 'route', 'palette' => 'sky' ) ),
		array( 'cards', array( 'layout' => 'tickets', 'palette' => 'sky', 'eyebrow' => 'Where will you go?', 'title' => 'Explore your', 'script' => 'options', 'cards' => $cards ) ),
		array( 'faq', $faq ),
		array( 'cta', array( 'layout' => 'poster', 'palette' => 'plum', 'title' => 'Ready to start', 'script' => 'planning?', 'description' => 'Tell Lindsay a little about your family and the trip you have in mind.', 'link' => array( 'url' => '#inquiry', 'title' => 'Start your inquiry' ) ) ),
	);
}

function foursuitcases_travel_preview( $kind, $values, $anchor = '' ) {
	$block = array( 'name' => 'acf/travel-' . $kind, 'data' => $values, 'anchor' => $anchor, 'is_style_example' => true );
	// Preview fixture values use bundled photos. The editable blocks use media IDs.
	$preview = static function ( $value, $post_id, $field ) use ( $values ) {
		return $values[ $field['name'] ] ?? false;
	};
	add_filter( 'acf/pre_load_value', $preview, 10, 3 );
	add_filter( 'acf/pre_format_value', $preview, 10, 3 );
	ob_start();
	try {
		if ( 'card' === $kind && ! is_numeric( $values['photo'] ?? 0 ) ) {
			echo '<article class="travel-card travel-plum"><div class="travel-card-media">';
			foursuitcases_travel_image( $values['photo'] ?? '' );
			echo '</div><div><h3>' . esc_html( $values['title'] ?? '' ) . '</h3><p>' . esc_html( $values['description'] ?? '' ) . '</p></div></article>';
		} else {
			require get_theme_file_path( "template-parts/travel/{$kind}.php" );
		}
		return ob_get_clean();
	} finally {
		remove_filter( 'acf/pre_load_value', $preview, 10 );
		remove_filter( 'acf/pre_format_value', $preview, 10 );
	}
}

function foursuitcases_travel_style_kit() {
	if ( ! function_exists( 'start_travel_specs' ) ) {
		return;
	}
	$specs = start_travel_specs();
	$examples = foursuitcases_travel_examples();
	$fields = array( 'layout' => 'Layout', 'palette' => 'Palette', 'size' => 'Size', 'background' => 'Background', 'from' => 'From', 'to' => 'To', 'loop' => 'Carousel' );
	echo '<div class="travel-inner travel-style-kit-header"><h2>Travel block style kit</h2><p>Choose one block and preview its design options. Use the same controls in the WordPress block editor to set the style on a page.</p>';
	foursuitcases_travel_show_reference_control();
	echo '</div>';
	foreach ( $specs as $kind => [$title, $palette, $layouts, $definitions] ) {
		if ( 'card' === $kind ) { continue; }
		$values = $examples[ $kind ];
		$selects = array( 'palette' => start_travel_palettes() );
		if ( $layouts ) { $selects = array( 'layout' => $layouts ) + $selects; }
		foreach ( array( 'size', 'background', 'from', 'to', 'loop' ) as $name ) {
			if ( isset( $definitions[ $name ] ) ) {
				$selects[ $name ] = 'loop' === $name ? array( '0' => 'Stop at end', '1' => 'Loop' ) : $definitions[ $name ];
			}
		}
		echo '<div class="travel-style-kit-example"><form method="get" action="' . esc_url( home_url( '/' ) . '#example-' . $kind ) . '" class="travel-inner travel-style-kit-controls"><input type="hidden" name="start_style_guide" value="1"><input type="hidden" name="travel_example" value="' . esc_attr( $kind ) . '"><strong>' . esc_html( $title ) . '</strong>';
		foreach ( $selects as $name => $choices ) {
			if ( array_key_first( $choices ) === 0 && 'loop' !== $name ) {
				$choices = array_combine( $choices, array_map( 'ucfirst', $choices ) );
			}
			$requested = isset( $_GET['travel_example'], $_GET[ $name ] ) && $kind === $_GET['travel_example'] && is_string( $_GET[ $name ] ) ? sanitize_key( $_GET[ $name ] ) : '';
			$values[ $name ] = array_key_exists( $requested, $choices ) ? $requested : ( $values[ $name ] ?? array_key_first( $choices ) );
			echo '<label>' . esc_html( $fields[ $name ] ) . '<select name="' . esc_attr( $name ) . '">';
			foreach ( $choices as $choice => $label ) {
				echo '<option value="' . esc_attr( $choice ) . '"' . selected( $values[ $name ], $choice, false ) . '>' . esc_html( $label ) . '</option>';
			}
			echo '</select></label>';
		}
		echo '<button type="submit">Preview</button></form>';
		echo foursuitcases_travel_preview( $kind, $values, 'example-' . $kind ) . '</div>';
	}
}

function foursuitcases_travel_journal_examples() {
	return array(
		array( 'Our favorite midday breaks at Walt Disney World', 'Disney', '2026-10-01', 'wdw', 'A pool hour, a quiet snack spot and a nap-friendly ride list: the little pauses that keep everyone smiling until the fireworks.' ),
		array( 'What to pack for a first family cruise', 'Cruises', '2026-09-01', 'disney-cruise' ),
		array( 'A slower Yellowstone: overlooks kids love', 'National parks', '2026-08-01', 'yellowstone' ),
		array( 'Planning a sensory-friendly park day', 'Planning around you', '2026-07-01', 'hero-5' ),
		array( 'The road trip snack bin system', 'Road trips', '2026-06-01', 'road-trip' ),
	);
}