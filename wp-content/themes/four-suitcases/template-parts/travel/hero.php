<?php
defined( 'ABSPATH' ) || exit;

// Preserve the original presentation of saved heroes until a layout is picked.
if ( ! empty( $block['data'] ) && ! array_key_exists( 'layout', $block['data'] ) && ! array_key_exists( 'field_travel_hero_layout', $block['data'] ) ) {
	require WP_PLUGIN_DIR . '/start-components/custom/travel/hero.php';
	return;
}
$layout = foursuitcases_travel_choice( 'layout', array( 'collage', 'bleed', 'split' ), 'collage' );
$size = foursuitcases_travel_choice( 'size', array( 'tall', 'compact' ), 'tall' );
$photo = get_field( 'photo' );
$object = get_field( 'object' );
$object_tag = foursuitcases_travel_tag( get_field( 'object_hover_tag' ) );
$cutout = get_field( 'cutout' );
$cutout_class = 'travel-hero-cut travel-hero-cut--second';
if ( $cutout && 'split' === $layout && is_numeric( $cutout ) ) {
	$dimensions = wp_get_attachment_metadata( (int) $cutout );
	$width = $dimensions['width'] ?? 0;
	$height = $dimensions['height'] ?? 0;
	if ( $height && $width >= .9 * $height ) {
		$cutout_class .= $width > 1.12 * $height ? ' travel-hero-cut--landscape' : ' travel-hero-cut--square';
	}
}
$depth = get_field( 'parallax' ) ? ' data-depth=".18"' : '';
$items = array_map( static fn( $item ) => foursuitcases_travel_asset( $item, true ), array( 'sunglasses-family', 'tickets-plane-train', 'trail-map-folded', 'compass', 'headphones-ivory-side' ) );
foursuitcases_travel_open( 'hero', $block, $layout, 'travel-hero-size--' . $size );
?>
<?php if ( 'bleed' === $layout ) : ?>
	<div class="travel-hero-photo" aria-hidden="true"><div<?php echo $depth; ?>><?php foursuitcases_travel_image( $photo, array( 'alt' => '', 'loading' => 'eager', 'fetchpriority' => 'high' ), 'full' ); ?></div></div>
<?php elseif ( 'collage' === $layout ) : ?>
	<div class="travel-hero-rose" <?php echo $depth; ?> aria-hidden="true"><?php foursuitcases_travel_image( 'compass-rose' ); ?></div>
<?php endif; ?>
<div class="travel-inner travel-hero-grid">
	<div class="travel-hero-copy" data-travel-protect>
		<?php foursuitcases_travel_intro( 'h1' ); ?>
		<div class="travel-actions">
			<?php foursuitcases_travel_link( get_field( 'link' ) ); ?>
			<?php foursuitcases_travel_link( get_field( 'secondary_link' ), 'travel-text-link' ); ?>
		</div>
	</div>
	<div class="travel-hero-stage">
		<?php if ( 'bleed' !== $layout ) : ?>
			<div class="travel-hero-print travel-hero-print--first"<?php echo $depth; ?>><?php foursuitcases_travel_print( $photo, get_field( 'caption' ), '', 'collage' === $layout ? -4 : 3, array( 'loading' => 'eager' ) ); ?></div>
			<?php if ( 'collage' === $layout ) : ?>
				<div class="travel-hero-print travel-hero-print--second" <?php if ( $depth ) { echo 'data-depth=".10"'; } ?>><?php foursuitcases_travel_print( get_field( 'second_photo' ), get_field( 'second_caption' ), '', 6 ); ?></div>
				<?php foursuitcases_travel_image( 'sunglasses-adult-pair', array( 'class' => 'travel-hero-cut travel-hero-cut--first', 'data-tag' => 'Shades for the whole crew', 'data-cut' => '', 'data-tilt' => '8', 'data-depth' => '.09', 'data-mouse' => '20' ) ); ?>
			<?php endif; ?>
			<?php foursuitcases_travel_image( $cutout, foursuitcases_travel_tag( get_field( 'cutout_hover_tag' ) ) + array( 'alt' => '', 'class' => $cutout_class, 'data-cut' => '', 'data-tilt' => '8', 'data-depth' => '.12', 'data-mouse' => '20', 'data-rot' => '-8' ) ); ?>
		<?php endif; ?>
		<?php if ( $object && get_field( 'unpack' ) ) : ?>
			<button type="button" class="travel-unpack" data-pop="<?php echo esc_attr( implode( ',', $items ) ); ?>" aria-label="Unpack suitcase" aria-expanded="false" hidden>
				<?php foursuitcases_travel_image( $object, $object_tag + array( 'alt' => '', 'data-cut' => '', 'data-tilt' => '8' ) ); ?>
			</button>
			<div class="travel-unpack travel-unpack--fallback" aria-hidden="true"><?php foursuitcases_travel_image( $object, $object_tag + array( 'alt' => '', 'data-cut' => '' ) ); ?></div>
		<?php elseif ( $object ) : ?>
			<div class="travel-unpack travel-unpack--decor" aria-hidden="true" <?php echo $depth; ?>><?php foursuitcases_travel_image( $object, $object_tag + array( 'alt' => '', 'data-cut' => '' ) ); ?></div>
		<?php endif; ?>
		<?php foursuitcases_travel_compass( 'hero' ); ?>
	</div>
</div>
</section>
