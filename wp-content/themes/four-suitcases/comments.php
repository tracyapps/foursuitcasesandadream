<?php
/**
 * Comments are intentionally quiet by default.
 *
 * @package Start
 */

if ( ! foursuitcases_feature_enabled( 'comments' ) ) {
	return;
}

comment_form();
