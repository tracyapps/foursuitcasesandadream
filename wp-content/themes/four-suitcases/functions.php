<?php
/**
 * Start 2.0 theme bootstrap.
 *
 * @package Start
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'FOURSUITCASES_VERSION', '2.0.0' );
define( 'FOURSUITCASES_PATH', trailingslashit( get_template_directory() ) );
define( 'FOURSUITCASES_URI', trailingslashit( get_template_directory_uri() ) );

$foursuitcases_includes = array(
	'inc/starter-config.php',
	'inc/helpers.php',
	'inc/setup.php',
	'inc/enqueue.php',
	'inc/editor.php',
	'inc/admin.php',
	'inc/acf.php',
	'inc/site-options.php',
	'inc/blocks.php',
	'inc/post-types.php',
	'inc/template-tags.php',
	'inc/client-tools.php',
);

foreach ( $foursuitcases_includes as $foursuitcases_include ) {
	require_once FOURSUITCASES_PATH . $foursuitcases_include;
}

require_once FOURSUITCASES_PATH . 'inc/custom.php';
