<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
$kind = $attributes['kind'] ?? 'button';if ( ! in_array( $kind, array( 'button', 'card', 'badge', 'tag', 'icon', 'divider', 'progress-bar', 'progress-ring', 'rating', 'avatar' ), true ) ) { $kind = 'card'; }
$label = is_string( $attributes['label'] ?? null ) ? wp_strip_all_tags( $attributes['label'] ) : '';
$content = wp_kses_post( $attributes['content'] ?? '' );$href = esc_url_raw( $attributes['href'] ?? '' );$value = max( 0, min( 'rating' === $kind ? 5 : 100, (float) ( $attributes['value'] ?? 0 ) ) );
$icon = is_string( $attributes['icon'] ?? null ) && preg_match( '/^[a-z0-9-]{1,80}$/D', $attributes['icon'] ) ? $attributes['icon'] : 'star';
$config = start_components_validate_vendor( get_option( 'start_components_vendor', start_components_default_vendor() ) );
$props = array( 'kind' => $kind, 'label' => $label, 'content' => $content, 'href' => $href, 'value' => $value, 'icon' => $icon, 'outline' => str_contains( $attributes['className'] ?? '', 'is-style-outline' ), 'faKit' => is_wp_error( $config ) ? '' : $config['faKit'] );
?>
<div <?php echo get_block_wrapper_attributes(); ?> data-start-element="<?php echo esc_attr( $kind ); ?>" data-start-props="<?php echo esc_attr( wp_json_encode( $props ) ); ?>">
<?php if ( 'button' === $kind ) : ?>
 <?php if ( $href ) : ?><a class="wp-element-button" href="<?php echo esc_url( $href ); ?>"><?php echo esc_html( $label ); ?></a><?php else : ?><button type="button"><?php echo esc_html( $label ); ?></button><?php endif; ?>
<?php elseif ( 'card' === $kind ) : ?><section><h2><?php echo esc_html( $label ); ?></h2><?php echo $content; ?></section>
<?php elseif ( 'divider' === $kind ) : ?><hr>
<?php elseif ( in_array( $kind, array( 'progress-bar', 'progress-ring' ), true ) ) : ?><label><?php echo esc_html( $label ); ?><progress max="100" value="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $value ); ?>%</progress></label>
<?php elseif ( 'rating' === $kind ) : ?><p><?php echo esc_html( $label . ': ' . $value . '/5' ); ?></p>
<?php else : ?><span><?php echo esc_html( $label ); ?></span><?php endif; ?>
</div>
