<?php
defined( 'ABSPATH' ) || exit;
$layout = foursuitcases_travel_choice( 'layout', array( 'scatter', 'grid', 'strip' ), 'scatter' );
$photos = array_values( array_filter( (array) get_field( 'photos' ) ) );
$captions = array( 'wdw' => 'Castle at dusk', 'yosemite' => 'Yosemite', 'hero-3' => 'Sea day', 'lindsay-photo' => 'Hi, Lindsay!', 'road-trip' => 'The scenic route', 'disneyland' => 'Main Street', 'yellowstone' => 'Old Faithful', 'zion' => 'Zion' );
$caption_for = static fn( $photo ) => is_numeric( $photo ) ? wp_get_attachment_caption( $photo ) : ( $captions[ $photo ] ?? '' );
foursuitcases_travel_open( 'gallery', $block, $layout );
?>
<div class="travel-inner">
	<header class="travel-section-intro" data-travel-protect><?php foursuitcases_travel_intro(); ?></header>
	<?php if ( 'strip' === $layout && $photos ) : ?>
	<div class="travel-film travel-ticker" data-marquee="44"><div class="travel-ticker__track">
		<?php for ( $repeat = 0; $repeat < 2; ++$repeat ) : ?>
		<div class="travel-ticker__group" <?php if ( $repeat ) { echo 'aria-hidden="true"'; } ?>>
			<?php foreach ( $photos as $photo ) :
				$attrs = $repeat ? array( 'alt' => '' ) : array();
				$caption = $caption_for( $photo );
				if ( $caption ) { $attrs['data-tag'] = wp_strip_all_tags( $caption ); }
				foursuitcases_travel_image( $photo, $attrs, 'medium_large' );
			endforeach; ?>
		</div>
		<?php endfor; ?>
	</div></div>
	<?php else : ?>
	<div class="travel-gallery<?php if ( 8 === count( $photos ) && 'scatter' === $layout ) { echo ' travel-gallery--table'; } ?>">
		<?php foreach ( $photos as $index => $photo ) :
			$caption = $caption_for( $photo );
			?>
			<div class="travel-gallery__item"<?php if ( 'scatter' === $layout && $index < 8 ) { echo ' data-drag'; } ?> data-travel-protect>
				<?php foursuitcases_travel_print( $photo, $caption, 'travel-gallery__print', 'scatter' === $layout ? array( -6, 4, -3, 7, 3, -7, 5, -4 )[ $index % 8 ] : 0, array( 'sizes' => '(max-width: 720px) 50vw, 30vw' ) ); ?>
			</div>
		<?php endforeach; ?>
	</div>
	<?php endif; ?>
</div>
</section>
