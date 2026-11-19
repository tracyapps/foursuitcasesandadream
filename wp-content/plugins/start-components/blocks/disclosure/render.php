<?php
/** Server content remains usable before enhancement or when JavaScript fails. */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$summary = isset( $attributes['summary'] ) && is_string( $attributes['summary'] ) ? trim( wp_strip_all_tags( $attributes['summary'] ) ) : '';
$summary = '' !== $summary ? $summary : __( 'More information', 'start-components' );
$body    = isset( $attributes['content'] ) && is_string( $attributes['content'] ) ? $attributes['content'] : '';
?>
<div <?php echo get_block_wrapper_attributes(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WordPress escapes wrapper values. ?>>
	<details data-start-disclosure <?php echo ! empty( $attributes['open'] ) ? 'open' : ''; ?>>
		<summary><?php echo esc_html( $summary ); ?></summary>
		<div class="start-disclosure__body"><?php echo wp_kses_post( $body ); ?></div>
	</details>
</div>
