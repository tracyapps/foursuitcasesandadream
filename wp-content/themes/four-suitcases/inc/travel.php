<?php
defined( 'ABSPATH' ) || exit;

function foursuitcases_travel_choice( $name, $choices, $fallback ) {
	$value = get_field( $name );
	return in_array( $value, $choices, true ) ? $value : $fallback;
}

function foursuitcases_travel_palette( $value, $fallback = 'plum' ) {
	return isset( start_travel_palettes()[ $value ?: '' ] ) ? $value : $fallback;
}

function foursuitcases_travel_asset( $name, $small = false ) {
	return get_theme_file_uri( 'assets/travel/' . $name . ( $small && file_exists( get_theme_file_path( 'assets/travel/' . $name . '-small.webp' ) ) ? '-small' : '' ) . '.webp' );
}

function foursuitcases_travel_image( $image, $attributes = array(), $size = 'large' ) {
	if ( ! $image ) {
		return;
	}
	if ( is_numeric( $image ) ) {
		echo wp_get_attachment_image( (int) $image, $size, false, $attributes );
		return;
	}
	if ( ! preg_match( '/^[a-z0-9-]+$/D', $image ) || ! file_exists( get_theme_file_path( 'assets/travel/' . $image . '.webp' ) ) ) {
		return;
	}
	$src = foursuitcases_travel_asset( $image );
	$small = foursuitcases_travel_asset( $image, true );
	$attributes += array( 'alt' => '', 'loading' => 'lazy', 'decoding' => 'async' );
	if ( $small !== $src ) {
		$attributes += array( 'srcset' => "$small 520w, $src 1100w", 'sizes' => '(max-width: 720px) 45vw, 32vw' );
	}
	echo '<img src="' . esc_url( $src ) . '"';
	foreach ( $attributes as $key => $value ) {
		printf( ' %s="%s"', esc_attr( $key ), esc_attr( $value ) );
	}
	echo '>';
}

function foursuitcases_travel_tag( $label ) {
	$label = trim( (string) $label );
	return '' === $label ? array() : array( 'data-tag' => $label );
}

function foursuitcases_travel_heading( $tag = 'h2', $class = 'travel-heading' ) {
	$title = get_field( 'title' );
	$script = get_field( 'script' );
	if ( 'h1' === $tag && ! $title && ! $script ) {
		$title = get_the_title();
	}
	if ( $title || $script ) {
		printf( '<%1$s class="%2$s">%3$s%4$s</%1$s>', $tag, esc_attr( $class ), esc_html( $title ), $script ? ' <span class="travel-script">' . esc_html( $script ) . '</span>' : '' );
	}
}

function foursuitcases_travel_intro( $tag = 'h2' ) {
	if ( get_field( 'eyebrow' ) ) {
		echo '<p class="travel-eyebrow">' . esc_html( get_field( 'eyebrow' ) ) . '</p>';
	}
	foursuitcases_travel_heading( $tag );
	if ( get_field( 'description' ) ) {
		echo '<p class="travel-lead">' . nl2br( esc_html( get_field( 'description' ) ) ) . '</p>';
	}
}

function foursuitcases_travel_link( $link, $class = 'travel-pass-button', $label = 'Explore' ) {
	if ( empty( $link['url'] ) ) {
		return;
	}
	$text = esc_html( ($link['title'] ?? '') ?: $label );
	$target = ( $link['target'] ?? '' ) === '_blank' ? ' target="_blank" rel="noopener noreferrer"' : '';
	printf( '<a class="%s" href="%s"%s%s>%s</a>', esc_attr( $class ), esc_url( $link['url'] ), $target, 'travel-pass-button' === $class ? ' data-magnet' : '',
		'travel-pass-button' === $class ? '<span>' . $text . '</span><span aria-hidden="true">↗</span>' : $text
	);
}

function foursuitcases_travel_open( $kind, $block, $layout = '', $extra = '' ) {
	$spec = start_travel_specs()[ $kind ];
	$classes = array( 'travel-section', 'travel-v2-' . $kind, 'travel-' . foursuitcases_travel_palette( get_field( 'palette' ), $spec[1] ) );
	if ( $layout ) {
		$classes[] = "travel-v2-{$kind}--{$layout}";
	}
	if ( isset( $spec[3]['background'] ) ) {
		$fallback = in_array( $kind, array( 'about', 'faq', 'testimonials' ), true ) ? 'wash' : 'plain';
		$classes[] = 'travel-background--' . foursuitcases_travel_choice( 'background', array( 'plain', 'wash' ), $fallback );
	}
	if ( in_array( $kind, array( 'faq', 'testimonials' ), true ) ) {
		$classes[] = 'travel-size--' . foursuitcases_travel_choice( 'size', array( 'roomy', 'cozy' ), 'roomy' );
	}
	echo '<section class="' . esc_attr( implode( ' ', $classes ) . ' ' . $extra . ' ' . ( $block['className'] ?? '' ) ) . '"' .
		( empty( $block['anchor'] ) ? '' : ' id="' . esc_attr( $block['anchor'] ) . '"' ) . ' data-stamps>';
}

function foursuitcases_travel_compass( $spot ) {
	echo '<button class="travel-compass travel-compass--' . esc_attr( $spot ) . '" data-compass hidden type="button" aria-label="Collect compass rose">';
	foursuitcases_travel_image( 'compass-rose' );
	echo '</button>';
}

function foursuitcases_travel_print( $photo, $caption = '', $class = '', $rotation = 0, $extra = array() ) {
	if ( ! $photo ) {
		return;
	}
	printf( '<figure class="travel-paper-print %s" data-tilt="8" data-rot="%s">', esc_attr( $class ), esc_attr( $rotation ) );
	foursuitcases_travel_image( $photo, $extra );
	if ( $caption ) {
		echo '<figcaption>' . esc_html( $caption ) . '</figcaption>';
	}
	echo '</figure>';
}

function foursuitcases_travel_ticker( $items, $speed = 40, $class = '' ) {
	echo '<div class="travel-ticker ' . esc_attr( $class ) . '" data-marquee="' . esc_attr( $speed ) . '" aria-hidden="true"' . ( 'travel-divider-ticker' === $class ? ' data-travel-keep-clear' : '' ) . '><div class="travel-ticker__track">';
	for ( $repeat = 0; $repeat < 2; ++$repeat ) {
		echo '<div class="travel-ticker__group">';
		foreach ( $items as $item ) {
			echo '<span>' . esc_html( $item ) . '</span>';
		}
		echo '</div>';
	}
	echo '</div></div>';
}

function foursuitcases_travel_plan_url() {
	$page = get_page_by_path( 'plan-your-trip' );
	return $page && 'publish' === $page->post_status ? get_permalink( $page ) : '';
}

function foursuitcases_travel_footer_pages( $paths ) {
	$links = array();
	foreach ( $paths as $path ) {
		$page = get_page_by_path( $path );
		if ( $page && 'publish' === $page->post_status ) {
			$links[] = '<li><a href="' . esc_url( get_permalink( $page ) ) . '">' . esc_html( get_the_title( $page ) ) . '</a></li>';
		}
	}
	if ( $links ) {
		echo '<ul class="travel-footer-links">' . implode( '', $links ) . '</ul>';
	}
}

add_filter( 'nav_menu_css_class', function ( $classes, $item, $args ) {
	$page = get_page_by_path( 'plan-your-trip' );
	if ( 'primary' === ( $args->theme_location ?? '' ) && $page && 'publish' === $page->post_status && (int) $item->object_id === $page->ID && ! $item->menu_item_parent ) {
		$classes[] = 'travel-plan-menu-item';
	}
	return $classes;
}, 10, 3 );

require_once __DIR__ . '/travel-recipes.php';
require_once __DIR__ . '/travel-pages.php';
