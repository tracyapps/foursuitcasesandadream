<?php
defined( 'ABSPATH' ) || exit;
$layout = foursuitcases_travel_choice( 'layout', array( 'route', 'marquee', 'scallop', 'certifications' ), 'route' );
$from = 'plain' === get_field( 'from' ) ? 'plain' : foursuitcases_travel_palette( get_field( 'from' ), 'plum' );
$to = foursuitcases_travel_palette( get_field( 'to' ), 'apricot' );
if ( 'certifications' === $layout && ! foursuitcases_certifications() ) {
	return;
}
foursuitcases_travel_open( 'divider', $block, $layout );
if ( 'route' === $layout ) : ?>
	<div class="travel-inner travel-route" data-route aria-hidden="true">
		<span><?php echo esc_html( get_field( 'start_label' ) ?: 'Home' ); ?></span>
		<div class="travel-route__line"><?php foursuitcases_travel_image( 'suitcase-single-stickers', array( 'data-route-runner' => '', 'data-cut' => '' ) ); ?></div>
		<span><?php echo esc_html( get_field( 'end_label' ) ?: 'Adventure' ); ?></span>
	</div>
<?php elseif ( 'marquee' === $layout ) :
	$words = preg_split( '/[\r\n,]+/', get_field( 'words' ) ?: 'Disney days, Ocean air, National parks, Road trips, Family adventures', -1, PREG_SPLIT_NO_EMPTY );
	foursuitcases_travel_ticker( $words, 52, 'travel-divider-ticker' );
elseif ( 'certifications' === $layout ) : ?>
	<div class="travel-inner"><?php get_template_part( 'template-parts/travel/certifications', null, array( 'presentation' => 'medals' ) ); ?></div>
<?php else : ?>
	<div class="travel-scallop travel-<?php echo esc_attr( $from ); ?>" aria-hidden="true"><div class="travel-<?php echo esc_attr( $to ); ?>"></div></div>
<?php endif; ?>
</section>
