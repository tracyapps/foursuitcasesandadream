<?php
/** Client resources derived from the live theme. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
function foursuitcases_extra_menu( $location, $label ) {
 if ( ! has_nav_menu( $location ) ) { return; }
 echo '<nav aria-label="' . esc_attr( $label ) . '">';
 wp_nav_menu( array( 'theme_location' => $location, 'menu_class' => 'footer-menu', 'container' => false, 'fallback_cb' => false ) );
 echo '</nav>';
}
/** Search includes only published, publicly searchable content, even for authenticated editors. */
function foursuitcases_public_search( $query ) {
 if ( is_admin() || ! $query->is_main_query() || ! $query->is_search() ) { return; }
 $types = get_post_types( array( 'public' => true, 'exclude_from_search' => false ), 'names' );
 $query->set( 'post_type', array_values( $types ) ?: array( 'post' ) );
 $query->set( 'post_status', 'publish' );$query->set( 'has_password', false );
}
add_action( 'pre_get_posts', 'foursuitcases_public_search', 100 );
function foursuitcases_style_guide() {
 if ( ! isset( $_GET['start_style_guide'] ) ) { return; }
 if ( ! current_user_can( 'edit_pages' ) ) { status_header( 404 );nocache_headers();exit; }
 status_header( 200 );nocache_headers();header( 'X-Robots-Tag: noindex, nofollow' );
 add_filter( 'wp_robots', static fn( $r ) => array_merge( $r, array( 'noindex' => true, 'nofollow' => true ) ) );
 $data = json_decode( file_get_contents( FOURSUITCASES_PATH . 'assets/dist/data/style-guide.json' ), true );
 // Render before wp_head so registered block assets are enqueued normally.
 $example = render_block( array( 'blockName' => 'start/disclosure', 'attrs' => array( 'summary' => __( 'Disclosure example', 'four-suitcases' ), 'content' => '<p>' . esc_html__( 'Reusable content uses the same styles as this guide.', 'four-suitcases' ) . '</p>' ), 'innerBlocks' => array(), 'innerHTML' => '', 'innerContent' => array() ) );
 get_header();
 echo '<div class="travel-inner travel-style-kit-header"><h1>' . esc_html__( 'Your site style guide', 'four-suitcases' ) . '</h1><p>' . esc_html__( 'Preview the travel blocks and see the design tokens used across the site.', 'four-suitcases' ) . '</p></div>';
 foursuitcases_travel_style_kit();
 echo '<div class="travel-inner travel-style-kit-header"><h2>' . esc_html__( 'Design tokens', 'four-suitcases' ) . '</h2><dl>';
 foreach ( $data['tokens'] ?? array() as $key => $token ) {
  if ( ! preg_match( '/^[a-z][a-z0-9.-]+$/D', $key ) ) { continue; }
  echo '<dt><code>' . esc_html( $key ) . '</code></dt><dd>';
  if ( 'color' === $token['type'] ) { echo '<span aria-hidden="true" class="travel-token-swatch has-' . esc_attr( substr( $key, 6 ) ) . '-background-color"></span> '; }
  echo esc_html( $token['value'] ) . '</dd>';
 }
 echo '</dl><h2>' . esc_html__( 'Headings and text', 'four-suitcases' ) . '</h2><h3>' . esc_html__( 'A supporting heading', 'four-suitcases' ) . '</h3><p>' . esc_html__( 'Clear, helpful content with readable type and comfortable spacing.', 'four-suitcases' ) . '</p><h2>' . esc_html__( 'Components', 'four-suitcases' ) . '</h2>' . $example . '</div>'; // Escaped by block renderer.
 get_footer();exit;
}
add_action( 'template_redirect', 'foursuitcases_style_guide', 1 );
function foursuitcases_style_guide_link( $bar ) {
 if ( current_user_can( 'edit_pages' ) ) { $bar->add_node( array( 'id' => 'start-style-guide', 'title' => __( 'Style guide', 'four-suitcases' ), 'href' => add_query_arg( 'start_style_guide', '1', home_url( '/' ) ) ) ); }
}
add_action( 'admin_bar_menu', 'foursuitcases_style_guide_link', 100 );
