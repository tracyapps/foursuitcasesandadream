<?php defined( 'ABSPATH' ) || exit; $link = get_field( 'link' ); ?>
<article class="travel-card travel-<?php echo esc_attr( get_field( 'palette' ) ?: 'sand' ); ?>">
	<div class="travel-card-media"><?php echo wp_get_attachment_image( get_field( 'photo' ), 'medium_large', false, array( 'alt' => '' ) ); ?>
	<?php if ( get_field( 'clip' ) ) : ?><video muted loop playsinline preload="none" data-src="<?php echo esc_url( wp_get_attachment_url( get_field( 'clip' ) ) ); ?>" aria-hidden="true"></video><?php endif; ?></div>
	<div><h3><?php if ( ! empty( $link['url'] ) ) : ?><a href="<?php echo esc_url( $link['url'] ); ?>"><?php echo esc_html( get_field( 'title' ) ); ?></a><?php else : echo esc_html( get_field( 'title' ) ); endif; ?></h3>
	<p><?php echo esc_html( get_field( 'description' ) ); ?></p></div>
</article>
