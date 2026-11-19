<?php
defined( 'ABSPATH' ) || exit;
$layout = foursuitcases_travel_choice( 'layout', array( 'spotlight', 'wall', 'simple' ), 'spotlight' );
$quotes = array_values( array_filter( (array) get_field( 'quotes' ), 'is_array' ) );
$track_id = wp_unique_id( 'travel-testimonials-' );
$palette = foursuitcases_travel_palette( get_field( 'palette' ), 'apricot' );
foursuitcases_travel_open( 'testimonials', $block, $layout );
?>
<div class="travel-inner">
	<header class="travel-section-intro" data-travel-protect><?php foursuitcases_travel_intro(); ?><?php foursuitcases_travel_compass( 'intro' ); ?></header>
	<div class="travel-quotes" <?php if ( 'spotlight' === $layout && count( $quotes ) > 1 ) { echo 'data-spotlight'; } ?>>
		<?php if ( 'spotlight' === $layout && count( $quotes ) > 1 ) : ?>
		<div class="travel-carousel-controls" role="group" aria-label="Testimonial controls" hidden>
			<button type="button" data-prev aria-controls="<?php echo esc_attr( $track_id ); ?>" aria-label="Previous testimonial">←</button>
			<?php foreach ( $quotes as $index => $quote ) : ?><button type="button" data-slide-dot aria-label="<?php echo esc_attr( 'Show testimonial ' . ( $index + 1 ) ); ?>"><?php echo $index + 1; ?></button><?php endforeach; ?>
			<button type="button" data-next aria-controls="<?php echo esc_attr( $track_id ); ?>" aria-label="Next testimonial">→</button>
			<button type="button" data-pause aria-label="Pause testimonials" aria-pressed="false">Ⅱ</button>
		</div>
		<?php endif; ?>
		<div class="travel-quotes__track" id="<?php echo esc_attr( $track_id ); ?>">
			<?php foreach ( $quotes as $index => $quote ) : ?>
				<div class="travel-quote travel-<?php echo esc_attr( foursuitcases_travel_palette( $quote['palette'] ?? '', $palette ) ); ?>" data-quote data-travel-protect <?php if ( 'wall' === $layout ) { echo 'data-drag'; } ?>>
					<figure class="travel-quote__note" <?php if ( 'wall' === $layout ) { echo 'data-tilt="9"'; } ?>>
						<blockquote><p><?php echo nl2br( esc_html( $quote['quote'] ?? '' ) ); ?></p></blockquote>
						<figcaption><strong><?php echo esc_html( $quote['name'] ?? '' ); ?></strong><?php if ( ! empty( $quote['trip'] ) ) : ?><span><?php echo esc_html( $quote['trip'] ); ?></span><?php endif; ?></figcaption>
					</figure>
					<?php if ( 'spotlight' === $layout && ! empty( $quote['photo'] ) ) : ?>
					<div class="travel-quote__stack">
						<?php
						$other_photo = $quotes[ ( $index + 1 ) % count( $quotes ) ]['photo'] ?? 0;
						if ( $other_photo && $other_photo !== $quote['photo'] ) {
							foursuitcases_travel_print( $other_photo, '', 'travel-quote__photo-back', -5, array( 'alt' => '', 'aria-hidden' => 'true' ) );
						}
						foursuitcases_travel_print( $quote['photo'], $quote['trip'] ?? '', 'travel-quote__photo', 5 );
						?>
					</div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
</section>
