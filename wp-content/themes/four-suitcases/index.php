<?php
/**
 * Main template.
 *
 * @package Start
 */

$posts_page = is_home() && ! is_front_page() ? get_queried_object() : null;
$journal_layout = $posts_page instanceof WP_Post
	&& WP_Block_Type_Registry::get_instance()->is_registered( 'acf/travel-posts' )
	&& has_block( 'acf/travel-posts', $posts_page );

get_header();
?>

<?php if ( $journal_layout ) : ?>
	<div class="entry-content flow">
		<?php
		if ( ! has_block( 'acf/travel-hero', $posts_page ) ) {
			echo '<section class="page-header layout">';
			foursuitcases_page_title();
			echo '</section>';
		}
		echo do_blocks( $posts_page->post_content );
		?>
	</div>
	<div class="layout">
		<?php the_posts_pagination( array( 'screen_reader_text' => __( 'Posts navigation', 'four-suitcases' ) ) ); ?>
	</div>
<?php else : ?>
<section class="page-header layout">
	<?php foursuitcases_page_title(); ?>
</section>

<div class="content-index layout">
	<?php
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			get_template_part( 'template-parts/content', get_post_type() );
		endwhile;

		the_posts_pagination(
			array(
				'mid_size'           => 1,
				'prev_text'          => __( 'Previous', 'four-suitcases' ),
				'next_text'          => __( 'Next', 'four-suitcases' ),
				'screen_reader_text' => __( 'Posts navigation', 'four-suitcases' ),
			)
		);
	else :
		get_template_part( 'template-parts/content', 'none' );
	endif;
	?>
</div>
<?php endif; ?>

<?php
get_footer();
