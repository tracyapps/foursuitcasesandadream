<?php
/**
 * 404 template.
 *
 * @package Start
 */

get_header();
?>

<section class="page-header layout layout--narrow">
	<h1 class="page-title"><?php esc_html_e( 'Page not found', 'four-suitcases' ); ?></h1>
	<p><?php esc_html_e( 'The page you requested could not be found. Try a search or return to the homepage.', 'four-suitcases' ); ?></p>
	<?php get_search_form(); ?>
</section>

<?php
get_footer();
