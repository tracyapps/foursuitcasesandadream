<?php
/**
 * Hero ACF block template.
 *
 * @package Start
 */

$anchor = ! empty( $block['anchor'] ) ? ' id="' . esc_attr( $block['anchor'] ) . '"' : '';
$classes = 'acf-block-hero';

if ( ! empty( $block['className'] ) ) {
	$classes .= ' ' . sanitize_html_class( $block['className'] );
}

$eyebrow = get_field( 'hero_eyebrow' );
$title   = get_field( 'hero_title' ) ?: get_the_title();
$text    = get_field( 'hero_text' );
$image   = get_field( 'hero_image' );
$link    = get_field( 'hero_link' );
?>

<section<?php echo $anchor; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="<?php echo esc_attr( $classes ); ?>">
	<div class="acf-block-hero__inner">
		<div class="acf-block-hero__content flow">
			<?php if ( $eyebrow ) : ?>
				<p class="acf-block-hero__eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
			<?php endif; ?>

			<h2 class="acf-block-hero__title"><?php echo esc_html( $title ); ?></h2>

			<?php if ( $text ) : ?>
				<div class="acf-block-hero__text"><?php echo wp_kses_post( $text ); ?></div>
			<?php endif; ?>

			<?php if ( ! empty( $link['url'] ) && ! empty( $link['title'] ) ) : ?>
				<p>
					<a class="wp-element-button" href="<?php echo esc_url( $link['url'] ); ?>"<?php echo ! empty( $link['target'] ) ? ' target="' . esc_attr( $link['target'] ) . '" rel="noopener noreferrer"' : ''; ?>>
						<?php echo esc_html( $link['title'] ); ?>
					</a>
				</p>
			<?php endif; ?>
		</div>

		<?php if ( $image ) : ?>
			<figure class="acf-block-hero__media">
				<?php echo wp_get_attachment_image( $image, 'large' ); ?>
			</figure>
		<?php endif; ?>
	</div>
</section>
