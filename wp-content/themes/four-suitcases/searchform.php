<?php
/**
 * Search form.
 *
 * @package Start
 */

$search_id = wp_unique_id( 'search-field-' );
$search_label = ! empty( $args['aria_label'] ) ? $args['aria_label'] : __( 'Site search', 'four-suitcases' );
?>
<form aria-label="<?php echo esc_attr( $search_label ); ?>" role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="search-form__label" for="<?php echo esc_attr( $search_id ); ?>"><?php esc_html_e( 'Search', 'four-suitcases' ); ?></label>
	<div class="search-form__controls">
		<input id="<?php echo esc_attr( $search_id ); ?>" class="search-form__input" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" required>
		<button class="search-form__button" type="submit"><?php esc_html_e( 'Search', 'four-suitcases' ); ?></button>
	</div>
</form>
