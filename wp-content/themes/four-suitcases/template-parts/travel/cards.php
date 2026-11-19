<?php
defined( 'ABSPATH' ) || exit;
$layout = foursuitcases_travel_choice( 'layout', array( 'prints', 'postcards', 'tags', 'tickets' ), 'prints' );
$palette = foursuitcases_travel_palette( get_field( 'palette' ) );
foursuitcases_travel_open( 'cards', $block, $layout );
?>
<div class="travel-inner">
	<header class="travel-section-intro" data-travel-protect><?php foursuitcases_travel_intro(); ?><?php if ( 'tags' !== $layout ) { foursuitcases_travel_compass( 'intro' ); } ?></header>
	<div class="travel-destination-grid">
		<?php foreach ( (array) get_field( 'cards' ) as $index => $card ) :
			if ( ! is_array( $card ) ) { continue; }
			$card = start_travel_destination_card( $card );
			$photo = $card['photo'] ?? 0;
			$link = $card['link'] ?? array();
			$title = $card['title'] ?? '';
			$description = $card['description'] ?? '';
			$caption = $card['caption'] ?? '';
			$kicker = $card['kicker'] ?? '';
			$orientation = $card['orientation'] ?? 'auto';
			if ( ! in_array( $orientation, array( 'horizontal', 'vertical' ), true ) ) {
				$dimensions = is_numeric( $photo ) ? wp_get_attachment_metadata( (int) $photo ) : ( $photo && preg_match( '/^[a-z0-9-]+$/D', $photo ) ? wp_getimagesize( get_theme_file_path( 'assets/travel/' . $photo . '.webp' ) ) : array() );
				$orientation = ( $dimensions['height'] ?? $dimensions[1] ?? 0 ) > ( $dimensions['width'] ?? $dimensions[0] ?? 0 ) * 1.02 ? 'vertical' : 'horizontal';
			}
			$rotation = array( -3, 2.5, -1.5, 3, -2.5, 1.5 )[ $index % 6 ];
			?>
			<article class="travel-destination travel-<?php echo esc_attr( foursuitcases_travel_palette( $card['palette'] ?? '', $palette ) ); ?> travel-destination--<?php echo esc_attr( $orientation ); ?>"
				<?php if ( 'postcards' === $layout ) { echo 'data-flip'; } ?> <?php if ( 'tags' === $layout ) { echo 'data-reveal="drop"'; } else { echo 'data-reveal="pop"'; } echo ' data-delay="' . esc_attr( min( 4, $index ) ) . '"'; ?> data-travel-protect>
				<?php if ( 'postcards' === $layout ) : ?><div class="travel-postcard" data-flip-inner>
					<div class="travel-postcard__front" aria-hidden="true">
						<?php foursuitcases_travel_image( $photo, array( 'alt' => '' ) ); ?>
						<span class="travel-postcard__greeting">Greetings from</span><?php if ( $kicker ) : ?><span class="travel-postcard__kicker travel-label"><?php echo esc_html( $kicker ); ?></span><?php endif; ?><strong><?php echo esc_html( $title ); ?></strong>
					</div>
				<?php endif; ?>
				<div class="travel-destination__face">
					<?php if ( 'prints' === $layout ) : ?>
						<?php foursuitcases_travel_print( $photo, $caption, 'travel-destination__print', $rotation, array( 'alt' => '', 'sizes' => '(max-width: 640px) 90vw, 32vw' ) ); ?>
					<?php elseif ( 'postcards' !== $layout ) : ?>
						<div class="travel-destination__photo"><?php foursuitcases_travel_image( $photo, array( 'alt' => '' ) ); ?>
						<?php if ( ! empty( $card['clip'] ) ) : ?><video muted loop playsinline preload="none" aria-hidden="true" data-src="<?php echo esc_url( wp_get_attachment_url( $card['clip'] ) ); ?>"></video><?php endif; ?></div>
					<?php endif; ?>
					<div class="travel-destination__copy">
						<?php if ( $kicker ) : ?><p class="travel-eyebrow"><?php echo esc_html( $kicker ); ?></p><?php endif; ?>
						<h3><?php if ( 'tags' === $layout ) : ?><span class="travel-label">To </span><?php endif; ?>
							<?php if ( ! empty( $link['url'] ) ) : ?><a class="travel-cover-link" href="<?php echo esc_url( $link['url'] ); ?>"<?php if ( ( $link['target'] ?? '' ) === '_blank' ) { echo ' target="_blank" rel="noopener noreferrer"'; } ?>><?php echo esc_html( $title ); ?></a><?php else : echo esc_html( $title ); endif; ?>
						</h3>
						<?php if ( $description ) : ?><p><?php echo esc_html( $description ); ?></p><?php endif; ?>
						<?php if ( 'tags' === $layout && $caption ) : ?><p><span class="travel-label">Bring </span><span class="travel-script"><?php echo esc_html( $caption ); ?></span></p><?php endif; ?>
						<?php if ( 'postcards' === $layout ) : ?><p class="travel-script">xo, Lindsay</p><?php endif; ?>
						<?php if ( ! empty( $link['url'] ) ) : ?><span class="travel-text-link" aria-hidden="true"><?php echo esc_html( ($link['title'] ?? '') ?: 'Explore' ); ?> ↗</span><?php endif; ?>
					</div>
					<?php if ( 'tickets' === $layout ) : ?><div class="travel-ticket__stub" aria-hidden="true"><span class="travel-label">Admit the family</span><span class="travel-barcode"></span></div><?php endif; ?>
					<?php if ( 'postcards' === $layout ) : ?><div class="travel-postcard__address" aria-hidden="true"><?php foursuitcases_travel_image( $photo, array( 'alt' => '' ), 'thumbnail' ); ?><span class="travel-postcard__postmark">Family<br>Adventures</span><span class="travel-postcard__address-line">Wisconsin</span></div><?php endif; ?>
				</div>
				<?php if ( 'postcards' === $layout ) : ?></div><button class="travel-flip-toggle" type="button" hidden aria-expanded="false"><?php echo esc_html__( 'Flip postcard', 'four-suitcases' ); ?> <span aria-hidden="true">↻</span></button><?php endif; ?>
			</article>
		<?php endforeach; ?>
	</div>
</div>
</section>
