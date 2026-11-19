<?php
/**
 * Single template.
 *
 * @package Start
 */

get_header();
?>

<?php
while ( have_posts() ) :
	the_post();
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry layout layout--narrow' ); ?>>
		<header class="page-header">
			<?php foursuitcases_page_title(); ?>
			<div class="entry-meta"><?php foursuitcases_posted_on(); ?></div>
		</header>

		<?php if ( has_post_thumbnail() ) : ?>
			<figure class="entry-media">
				<?php the_post_thumbnail( 'large' ); ?>
			</figure>
		<?php endif; ?>

		<div class="entry-content flow">
			<?php the_content(); ?>
		</div>
	</article>
	<?php
endwhile;

get_footer();
