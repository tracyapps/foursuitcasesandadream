<?php
/**
 * Page template.
 *
 * @package Start
 */

get_header();
?>

<?php
while ( have_posts() ) :
	the_post();
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry layout' ); ?>>
		<?php if ( ! has_block( 'acf/travel-hero' ) ) : ?><header class="page-header">
			<?php foursuitcases_page_title(); ?>
		</header><?php endif; ?>

		<div class="entry-content flow">
			<?php the_content(); ?>
		</div>
	</article>
	<?php
endwhile;

get_footer();
