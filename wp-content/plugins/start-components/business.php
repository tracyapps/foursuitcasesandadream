<?php
/** Plain public business facts shared by visible output and structured data. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
function start_components_valid_business( $b ) {
 if ( ! is_array( $b ) || array_diff( array_keys( $b ), array( 'enabled', 'name', 'description', 'telephone', 'address', 'kind' ) ) || ! is_bool( $b['enabled'] ?? null ) || ! in_array( $b['kind'] ?? null, array( 'Organization', 'LocalBusiness' ), true ) ) { return false; }
 foreach ( array( 'name', 'description', 'telephone', 'address' ) as $key ) { if ( ! is_string( $b[ $key ] ?? null ) || strlen( $b[ $key ] ) > ( 'description' === $key ? 1200 : 800 ) || preg_match( '/[<>\x00-\x1f\x7f]/', $b[ $key ] ) ) { return false; } }
 return ( ! $b['enabled'] || trim( $b['name'] ) ) && ( ! $b['telephone'] || preg_match( '/^[+0-9(). x-]{3,40}$/D', $b['telephone'] ) ) && ( ! $b['enabled'] || 'LocalBusiness' !== $b['kind'] || trim( $b['address'] ) );
}
function start_components_seo_owner() {
 $owner = 'start';foreach ( array( 'WPSEO_VERSION' => 'Yoast', 'RANK_MATH_VERSION' => 'Rank Math', 'AIOSEO_VERSION' => 'All in One SEO', 'SEOPRESS_VERSION' => 'SEOPress' ) as $constant => $name ) { if ( defined( $constant ) ) { $owner = $name;break; } }return apply_filters( 'start_components_seo_owner', $owner );
}
function start_components_business() { $config = start_components_content_selection();return $config['business'] ?? null; }
function start_components_business_schema() {
 $b = start_components_business();if ( ! $b || ! $b['enabled'] || 'start' !== start_components_seo_owner() || is_search() || is_404() || is_preview() || is_singular() && ( 'publish' !== get_post_status() || post_password_required() ) ) { return; }
 $schema = array( '@context' => 'https://schema.org', '@type' => $b['kind'], '@id' => home_url( '/#organization' ), 'name' => $b['name'], 'url' => home_url( '/' ) );
 foreach ( array( 'description', 'telephone', 'address' ) as $key ) { if ( $b[ $key ] ) { $schema[ $key ] = $b[ $key ]; } }
 echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES ) . '</script>';
 $description = is_singular() ? get_the_excerpt() : $b['description'];$description = wp_trim_words( wp_strip_all_tags( $description ), 35, '' );
 if ( $description ) { echo '<meta name="description" content="' . esc_attr( $description ) . '">'; }
}
add_action( 'wp_head', 'start_components_business_schema', 30 );
function start_components_visible_business() {
 $b = start_components_business();if ( ! $b || ! $b['enabled'] ) { return; }
 echo '<aside class="start-business layout" aria-label="' . esc_attr__( 'Business information', 'start-components' ) . '"><p><strong>' . esc_html( $b['name'] ) . '</strong></p>';
 if ( $b['description'] ) { echo '<p>' . esc_html( $b['description'] ) . '</p>'; }if ( $b['address'] ) { echo '<address>' . esc_html( $b['address'] ) . '</address>'; }
 if ( $b['telephone'] ) { echo '<p><a href="' . esc_url( 'tel:' . preg_replace( '/[^+0-9]/', '', $b['telephone'] ) ) . '">' . esc_html( $b['telephone'] ) . '</a></p>'; }echo '</aside>';
}
add_action( 'wp_footer', 'start_components_visible_business', 5 );
function start_components_seo_report() {
 $empty = get_posts( array( 'post_type' => 'page', 'post_status' => 'publish', 'numberposts' => 50, 'fields' => 'ids' ) );$blank = 0;foreach ( $empty as $id ) { if ( ! trim( wp_strip_all_tags( get_post_field( 'post_content', $id ) ) ) ) { ++$blank; } }
 return array( 'owner' => start_components_seo_owner(), 'indexingAllowed' => '1' === (string) get_option( 'blog_public' ), 'publicBlankPages' => $blank, 'sitemap' => home_url( '/wp-sitemap.xml' ), 'businessConfigured' => ! empty( start_components_business()['enabled'] ), 'actions' => array( 'Replace blank pages with useful, original service and About information.', 'Keep visible business details accurate and consistent with business listings.', 'Choose one SEO/schema owner and verify its sitemap and page metadata.', 'Before launch, verify indexing, real form delivery, backup restoration and mobile accessibility.' ), 'aiSearch' => 'Helpful, crawlable content and standard SEO remain the foundation; appearance in search or AI answers is not guaranteed.' );
}
