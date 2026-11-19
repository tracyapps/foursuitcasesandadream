<?php
defined( 'ABSPATH' ) || exit;
$object = get_field( 'object' );
$object_tag = foursuitcases_travel_tag( $object ? get_field( 'object_hover_tag' ) : 'Every answer, dog-eared' );
$layout = foursuitcases_travel_choice( 'layout', array( 'split', 'accordion' ), 'split' );
$group = wp_unique_id( 'travel-faq-' );
foursuitcases_travel_open( 'faq', $block, $layout );
?>
<div class="travel-inner travel-faq-grid">
	<header class="travel-section-intro" data-travel-protect>
		<?php foursuitcases_travel_intro(); ?>
		<?php if ( 'split' === $layout ) :
			foursuitcases_travel_image( $object ?: 'travel-guide-worn', $object_tag + array( 'alt' => '', 'class' => 'travel-faq-object', 'data-cut' => '', 'data-tilt' => '8', 'data-rot' => '-8' ) );
			foursuitcases_travel_compass( 'faq' );
		endif; ?>
	</header>
	<div class="travel-faq-list" data-travel-protect>
		<?php foreach ( (array) get_field( 'questions' ) as $index => $item ) :
			if ( ! is_array( $item ) ) { continue; } ?>
			<details name="<?php echo esc_attr( $group ); ?>">
				<summary><span class="travel-faq-number" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $index + 1 ) ); ?></span><span><?php echo esc_html( $item['question'] ?? '' ); ?></span><span class="travel-faq-toggle" aria-hidden="true">+</span></summary>
				<div class="travel-faq-answer"><?php echo wp_kses_post( $item['answer'] ?? '' ); ?></div>
			</details>
		<?php endforeach; ?>
	</div>
</div>
</section>
