<?php
defined( 'ABSPATH' ) || exit;
$steps = array_values( array_filter( (array) get_field( 'steps' ), 'is_array' ) );
foursuitcases_travel_open( 'steps', $block );
?>
<header class="travel-inner travel-section-intro" data-travel-protect><?php foursuitcases_travel_intro(); ?><?php foursuitcases_travel_compass( 'intro' ); ?></header>
<?php if ( $steps ) : ?>
<div class="travel-steps-scroll" data-steps data-count="<?php echo count( $steps ); ?>">
	<div class="travel-steps-stage">
		<div class="travel-boarding-pass" data-travel-protect hidden>
			<div class="travel-pass-band travel-label"><span>Boarding pass</span><span class="travel-pass-airline">Four Suitcases Air</span></div>
			<div class="travel-boarding-pass__body">
				<p class="travel-boarding-pass__route" aria-hidden="true"><span><small class="travel-label">From</small>WIS</span><span><small class="travel-label">To</small>YAY</span></p>
				<div class="travel-boarding-pass__stops" role="group" aria-label="Planning steps">
					<?php foursuitcases_travel_image( 'suitcase-single-stickers', array( 'class' => 'travel-step-runner', 'data-cut' => '', 'aria-hidden' => 'true' ) ); ?>
					<?php foreach ( $steps as $index => $step ) : ?>
						<button type="button" data-step-dot aria-label="<?php echo esc_attr( sprintf( 'Go to step %d: %s', $index + 1, $step['title'] ?? '' ) ); ?>"><?php echo esc_html( sprintf( '%02d', $index + 1 ) ); ?></button>
					<?php endforeach; ?>
				</div>
				<div class="travel-boarding-pass__guide" aria-hidden="true">
					<div class="travel-boarding-pass__number">
						<?php foreach ( $steps as $index => $step ) : ?><span data-step-num><?php echo esc_html( sprintf( '%02d', $index + 1 ) ); ?></span><?php endforeach; ?>
					</div>
					<ol>
						<?php foreach ( $steps as $index => $step ) : ?><li data-step-label><?php echo esc_html( sprintf( '%02d · %s', $index + 1, $step['title'] ?? '' ) ); ?></li><?php endforeach; ?>
					</ol>
				</div>
			</div>
			<div class="travel-boarding-pass__stub"><span><small>Passenger</small>Your family</span><span><small>Seat</small>Window</span><span><small>Class</small>Adventure</span><span class="travel-barcode" aria-hidden="true"></span></div>
		</div>
		<ol class="travel-step-panels">
			<?php foreach ( $steps as $index => $step ) : ?>
			<li data-step data-travel-protect>
				<div class="travel-step-copy"><p class="travel-eyebrow"><?php echo esc_html( sprintf( 'Step %02d of %02d', $index + 1, count( $steps ) ) ); ?></p>
				<h3><?php echo esc_html( $step['title'] ?? '' ); ?></h3><p><?php echo nl2br( esc_html( $step['text'] ?? '' ) ); ?></p></div>
				<?php foursuitcases_travel_image( $step['cutout'] ?? 0, array( 'alt' => '', 'data-step-img' => '', 'data-cut' => '' ) ); ?>
			</li>
			<?php endforeach; ?>
		</ol>
	</div>
</div>
<?php endif; ?>
</section>
