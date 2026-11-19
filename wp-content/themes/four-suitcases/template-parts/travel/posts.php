<?php
defined( 'ABSPATH' ) || exit;
$track_id = wp_unique_id( 'travel-journal-' );
$layout = foursuitcases_travel_choice( 'layout', array( 'magazine', 'prints', 'list' ), 'magazine' );
if ( ! empty( $block['is_style_example'] ) ) {
	$posts = array_map( static function ( $example ) {
		return array(
			'title' => $example[0], 'category' => $example[1], 'date' => $example[2], 'date_label' => wp_date( 'M Y', strtotime( $example[2] . ' 12:00:00 UTC' ) ),
			'photo' => $example[3], 'description' => $example[4] ?? '', 'url' => '',
		);
	}, foursuitcases_travel_journal_examples() );
} else {
	$is_journal_index = is_home() && ! is_front_page() && 'journal-posts' === ( $block['anchor'] ?? '' );
	if ( $is_journal_index ) {
		$post_items = $GLOBALS['wp_query']->posts;
	} else {
		$query = new WP_Query( array(
			'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => max( 1, min( 24, (int) ( get_field( 'count' ) ?: 6 ) ) ),
			'cat' => (int) get_field( 'category' ), 'ignore_sticky_posts' => true, 'no_found_rows' => true, 'has_password' => false,
		) );
		$post_items = $query->posts;
	}
	$posts = array();
	foreach ( $post_items as $index => $journal_post ) {
		$categories = get_the_category( $journal_post->ID );
		$show_excerpt = 0 === $index && 'magazine' === $layout && ! post_password_required( $journal_post );
		$posts[] = array(
			'title' => get_the_title( $journal_post ), 'category' => $categories[0]->name ?? '',
			'date' => get_the_date( 'c', $journal_post ), 'date_label' => get_the_date( 'M j, Y', $journal_post ),
			'photo' => (int) get_post_thumbnail_id( $journal_post ), 'url' => get_permalink( $journal_post ),
			'description' => $show_excerpt ? wp_trim_words( wp_strip_all_tags( $journal_post->post_excerpt ?: strip_shortcodes( $journal_post->post_content ) ), 24 ) : '',
		);
	}
}
foursuitcases_travel_open( 'posts', $block, $layout );
?>
<div class="travel-inner" <?php if ( 'prints' === $layout ) { echo 'data-post-carousel data-loop="' . ( get_field( 'loop' ) ? 'true' : 'false' ) . '"'; } ?>>
	<header class="travel-section-intro" data-travel-protect><?php foursuitcases_travel_intro(); ?></header>
	<?php if ( $posts ) : ?>
	<?php if ( 'prints' === $layout && count( $posts ) > 1 ) : ?>
	<div class="travel-carousel-controls" role="group" aria-label="Journal carousel controls" hidden><button type="button" data-prev aria-controls="<?php echo esc_attr( $track_id ); ?>" aria-label="Previous posts">←</button><span data-carousel-count aria-live="polite"></span><button type="button" data-next aria-controls="<?php echo esc_attr( $track_id ); ?>" aria-label="Next posts">→</button></div>
	<?php endif; ?>
	<div class="travel-post-window">
		<div class="travel-posts" id="<?php echo esc_attr( $track_id ); ?>">
		<?php foreach ( $posts as $index => $item ) :
			$photo = $item['photo'];
			?>
			<article class="travel-post" data-travel-protect>
				<?php if ( 'prints' === $layout ) : foursuitcases_travel_print( $photo, '', '', $index % 2 ? 3 : -3, array( 'alt' => '' ) );
				elseif ( $photo ) : ?><div class="travel-post__photo" data-tilt="6"><?php foursuitcases_travel_image( $photo, array( 'alt' => '' ) ); ?></div><?php endif; ?>
				<time datetime="<?php echo esc_attr( $item['date'] ); ?>"><?php echo esc_html( $item['date_label'] ); ?></time>
				<div class="travel-post__copy">
					<?php if ( $item['category'] ) : ?><p class="travel-eyebrow"><?php echo esc_html( $item['category'] ); ?></p><?php endif; ?>
					<h3><?php if ( $item['url'] ) : ?><a class="travel-cover-link" href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['title'] ); ?></a><?php else : echo esc_html( $item['title'] ); endif; ?></h3>
					<?php if ( 0 === $index && 'magazine' === $layout && $item['description'] ) : ?><p><?php echo esc_html( $item['description'] ); ?></p><?php endif; ?>
				</div>
			</article>
		<?php endforeach; ?>
		</div>
	</div>
	<?php else : ?><p>Travel journal posts will appear here.</p><?php endif; ?>
</div>
</section>
