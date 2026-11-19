<?php
defined( 'ABSPATH' ) || exit;
$certifications = foursuitcases_certifications();
if ( ! $certifications ) {
	return;
}
$presentation = $args['presentation'] ?? 'seals';
$heading = 'footer' === $presentation ? 'h3' : 'h2';
?>
<div class="travel-certifications travel-certifications--<?php echo esc_attr( $presentation ); ?>" data-travel-protect>
	<<?php echo $heading; ?> class="travel-certifications__heading"><?php esc_html_e( 'Certifications & specialties', 'four-suitcases' ); ?></<?php echo $heading; ?>>
	<ul class="travel-certifications__list" role="list">
		<?php foreach ( $certifications as $item ) : ?>
			<li>
				<?php if ( 'footer' !== $presentation ) : ?><span class="travel-certifications__mark" aria-hidden="true">✦</span><?php endif; ?>
				<strong><?php echo esc_html( $item['title'] ); ?></strong>
				<?php if ( 'seals' === $presentation && ! empty( $item['detail'] ) ) : ?>
					<p><?php echo esc_html( $item['detail'] ); ?></p>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
