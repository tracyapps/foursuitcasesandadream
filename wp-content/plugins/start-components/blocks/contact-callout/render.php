<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$email = isset( $attributes['email'] ) && is_string( $attributes['email'] ) ? sanitize_email( $attributes['email'] ) : '';
?>
<section <?php echo get_block_wrapper_attributes(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<h2><?php echo esc_html( $attributes['heading'] ?? '' ); ?></h2>
	<div><?php echo wp_kses_post( $attributes['content'] ?? '' ); ?></div>
	<?php if ( is_email( $email ) ) : ?>
		<a class="start-contact__link" href="<?php echo esc_url( 'mailto:' . $email ); ?>"><?php esc_html_e( 'Email us', 'start-components' ); ?></a>
	<?php endif; ?>
</section>
