<?php
defined( 'ABSPATH' ) || exit;
$layout = foursuitcases_travel_choice( 'layout', array( 'pass', 'poster', 'banner' ), 'pass' );
$link = get_field( 'link' );
$places = preg_split( '/[\r\n,]+/', get_field( 'destinations' ) ?: 'Orlando, Juneau, Zion, Anaheim, Nassau, Yellowstone, Anywhere!', -1, PREG_SPLIT_NO_EMPTY );
$places = array_values( array_filter( array_map( 'trim', $places ) ) ) ?: array( 'Anywhere!' );
foursuitcases_travel_open( 'cta', $block, $layout );
if ( 'poster' === $layout ) :
	foursuitcases_travel_ticker( array( 'Let’s go', 'Pack the snacks', 'Adventure awaits', 'Take the scenic route' ), 48, 'travel-poster-ticker' );
	?>
	<div class="travel-poster-art" aria-hidden="true">
		<?php $poster_items = array(
			'tickets-plane-train' => 'Tickets, please!', 'backpack-navy-commuter' => 'Room for snacks',
			'sunglasses-family' => 'Shades for the whole crew', 'trail-map-folded' => 'Take the scenic route',
			'headphones-black-side' => 'Cue the road-trip playlist',
		);
		foreach ( $poster_items as $item => $tag ) {
			foursuitcases_travel_image( $item, array( 'data-cut' => '', 'data-depth' => '.1', 'data-float' => '5', 'data-tag' => $tag ) );
		} ?>
	</div>
<?php endif; ?>
<div class="travel-inner">
	<div class="travel-cta-card">
		<?php if ( 'banner' === $layout ) { foursuitcases_travel_image( get_field( 'object' ) ?: 'sunglasses-adult-pair', array( 'alt' => '', 'class' => 'travel-banner-art', 'data-tag' => 'Big shades, little shades', 'data-cut' => '' ) ); } ?>
		<div class="travel-cta-copy" data-travel-protect>
			<?php foursuitcases_travel_intro(); ?>
			<?php if ( 'pass' === $layout ) : ?>
				<div class="travel-cta-route"><span><small>From</small>Home</span><span aria-hidden="true">✈</span><span><small>To</small><span aria-hidden="true" data-destinations="<?php echo esc_attr( wp_json_encode( $places ) ); ?>"><?php echo esc_html( $places[0] ); ?></span><span class="screen-reader-text"><?php echo esc_html( implode( ', ', $places ) ); ?></span></span><button class="travel-destination-pause" type="button" data-destination-pause aria-label="Pause destination names" aria-pressed="false" hidden>Ⅱ</button></div>
			<?php endif; ?>
		</div>
		<div class="travel-cta-action" data-travel-protect>
			<?php if ( 'pass' === $layout ) : ?><p class="travel-label">Gate A1</p><?php endif; ?>
			<?php foursuitcases_travel_link( $link, 'travel-pass-button', 'Plan your trip' ); ?>
			<?php if ( 'pass' === $layout ) : ?><span class="travel-barcode" aria-hidden="true"></span><?php foursuitcases_travel_compass( 'cta' ); ?><?php endif; ?>
		</div>
	</div>
</div>
</section>
