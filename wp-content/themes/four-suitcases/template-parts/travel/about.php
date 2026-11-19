<?php
defined( 'ABSPATH' ) || exit;
$object = get_field( 'object' );
$object_tag = foursuitcases_travel_tag( $object ? get_field( 'object_hover_tag' ) : 'Always carry-on only' );
$layout = foursuitcases_travel_choice( 'layout', array( 'portrait', 'passport' ), 'portrait' );
$signature = get_field( 'signature' ) ?: 'Lindsay';
$caption = get_field( 'caption' ) ?: 'Hi, I’m Lindsay!';
foursuitcases_travel_open( 'about', $block, $layout );
?>
<div class="travel-inner travel-about-spread">
	<div class="travel-about-image" data-travel-protect>
		<?php if ( 'passport' === $layout ) : ?><p class="travel-label">Passport · United States of Adventure</p><?php endif; ?>
		<?php foursuitcases_travel_print( get_field( 'photo' ), $caption, 'travel-about-print', -4 ); ?>
		<?php if ( 'passport' === $layout ) : ?>
			<dl class="travel-passport-fields"><div><dt>Name</dt><dd><?php echo esc_html( $signature ); ?></dd></div><div><dt>Occupation</dt><dd>Family travel planner</dd></div></dl>
			<p class="travel-script"><?php echo esc_html( $signature ); ?></p>
			<p class="travel-passport-code" aria-hidden="true">P&lt;FSA&lt;FAMILY&lt;&lt;ADVENTURES&lt;&lt;&lt;</p>
		<?php else :
			foursuitcases_travel_image( $object ?: 'backpack-cognac-leather', $object_tag + array( 'alt' => '', 'class' => 'travel-about-object', 'data-cut' => '', 'data-tilt' => '8', 'data-mouse' => '20', 'data-depth' => '.11' ) );
		endif; ?>
	</div>
	<div class="travel-about-copy" data-travel-protect>
		<?php if ( 'passport' === $layout ) : ?><p class="travel-label">Visas · Entries</p><?php endif; ?>
		<?php foursuitcases_travel_intro(); ?>
		<ol class="travel-about-points">
		<?php foreach ( (array) get_field( 'points' ) as $index => $point ) :
			if ( ! is_array( $point ) ) { continue; } ?>
			<li><span aria-hidden="true" class="travel-about-number travel-<?php echo esc_attr( foursuitcases_travel_palette( $point['palette'] ?? '', 'sun' ) ); ?>"><?php echo esc_html( sprintf( '%02d', $index + 1 ) ); ?></span>
			<div><strong><?php echo esc_html( $point['title'] ?? '' ); ?></strong><?php if ( ! empty( $point['text'] ) ) : ?><p><?php echo esc_html( $point['text'] ); ?></p><?php endif; ?></div></li>
		<?php endforeach; ?>
		</ol>
		<?php if ( 'portrait' === $layout ) : ?><p class="travel-about-signature travel-script"><?php echo esc_html( $signature ); ?></p><?php endif; ?>
		<?php foursuitcases_travel_link( get_field( 'link' ), 'travel-text-link' ); ?>
	</div>
</div>
<div class="travel-inner"><?php get_template_part( 'template-parts/travel/certifications' ); ?></div>
</section>
