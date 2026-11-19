<?php defined( 'ABSPATH' ) || exit;
$photo = get_field( 'photo' );
$dimensions = wp_get_attachment_metadata( $photo );
$portrait = ! empty( $dimensions['height'] ) && $dimensions['height'] > $dimensions['width'];
?>
<section class="travel-hero <?php echo $portrait ? 'travel-portrait' : ''; ?> travel-<?php echo esc_attr( get_field( 'palette' ) ?: 'plum' ); ?> <?php echo get_field( 'parallax' ) ? 'has-parallax' : ''; ?> <?php echo get_field( 'light_leak' ) ? 'has-light' : ''; ?>">
	<div class="<?php echo $portrait ? 'travel-print' : 'travel-scene'; ?>"><?php echo wp_get_attachment_image( $photo, $portrait ? 'large' : 'full', false, $portrait ? array( 'loading' => 'eager' ) : array( 'alt' => '', 'loading' => 'eager' ) ); ?></div>
	<div class="travel-intro">
		<h1><?php echo esc_html( get_field( 'title' ) ?: get_the_title() ); ?></h1>
		<p><?php echo esc_html( get_field( 'description' ) ); ?></p>
		<?php $cta = get_field( 'link' ); if ( ! empty( $cta['url'] ) ) : ?><a class="travel-button" href="<?php echo esc_url( $cta['url'] ); ?>"><?php echo esc_html( $cta['title'] ?: 'Explore' ); ?> <span aria-hidden="true">↗</span></a><?php endif; ?>
	</div>
	<?php echo wp_get_attachment_image( get_field( 'object' ), 'medium_large', false, array( 'class' => 'travel-object', 'alt' => '' ) ); ?>
</section>
