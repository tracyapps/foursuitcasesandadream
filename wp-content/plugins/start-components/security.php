<?php
/** Enforce verified TLS on the ACFE license transport, including its own UI. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
add_filter( 'http_request_args', static function ( $args, $url ) {
 $host = strtolower( (string) wp_parse_url( $url, PHP_URL_HOST ) );
 if ( in_array( $host, array( 'acf-extended.com', 'www.acf-extended.com' ), true ) ) {
  $args['sslverify'] = true;$args['redirection'] = 0;
 }
 return $args;
}, PHP_INT_MAX, 2 );
add_filter( 'pre_http_request', static function ( $pre, $args, $url ) {
 $host = strtolower( (string) wp_parse_url( $url, PHP_URL_HOST ) );
 if ( in_array( $host, array( 'acf-extended.com', 'www.acf-extended.com' ), true ) && 'https' !== strtolower( (string) wp_parse_url( $url, PHP_URL_SCHEME ) ) ) {
  return new WP_Error( 'start_license_tls_required', __( 'The license endpoint requires HTTPS.', 'start-components' ) );
 }
 return $pre;
}, PHP_INT_MAX, 3 );
