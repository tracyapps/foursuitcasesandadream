<?php
defined( 'ABSPATH' ) || exit;

$kind = substr( $block['name'], strlen( 'acf/travel-' ) );
if ( ! isset( start_travel_specs()[ $kind ] ) ) {
	return;
}
$template = locate_template( 'template-parts/travel/' . $kind . '.php' );
if ( $template ) {
	require $template;
} elseif ( in_array( $kind, array( 'hero', 'card' ), true ) ) {
	require __DIR__ . '/' . $kind . '.php';
}
