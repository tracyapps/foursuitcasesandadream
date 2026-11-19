<?php
/**
 * Header template.
 *
 * @package Start
 */

?><!doctype html>
<html <?php language_attributes(); ?> data-motion="<?php echo esc_attr( foursuitcases_get_motion_level() ); ?>" <?php if ( in_array( foursuitcases_get_theme_mode(), array( 'light', 'dark' ), true ) ) { echo 'data-theme="' . esc_attr( foursuitcases_get_theme_mode() ) . '"'; } ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#site-main"><?php esc_html_e( 'Skip to content', 'four-suitcases' ); ?></a>

<div class="site-shell">
	<header class="site-header" role="banner">
		<div class="site-header__inner layout">
			<div class="site-branding">
				<a class="travel-brand" data-tag="Home base: Wisconsin" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php foursuitcases_travel_image( 'compass-rose', array( 'loading' => 'eager', 'width' => '46', 'height' => '48' ) ); ?>
					<span>Four Suitcases<br><small>and a Dream</small></span>
				</a>
			</div>

			<?php foursuitcases_extra_menu( 'utility', __( 'Utility navigation', 'four-suitcases' ) ); ?>
			<?php if ( has_nav_menu( 'primary' ) ) : ?>
				<button class="menu-toggle" type="button" aria-controls="primary-menu" aria-expanded="false">
					<span class="menu-toggle__bar" aria-hidden="true"></span>
					<span class="screen-reader-text"><?php esc_html_e( 'Open menu', 'four-suitcases' ); ?></span>
				</button>

				<nav class="primary-navigation" data-submenu-label="<?php esc_attr_e( 'More pages under %s', 'four-suitcases' ); ?>" aria-label="<?php esc_attr_e( 'Primary menu', 'four-suitcases' ); ?>">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'menu_id'        => 'primary-menu',
							'menu_class'     => 'primary-menu',
							'container'      => false,
							'fallback_cb'    => false,
						)
					);
					?>
				</nav>
			<?php endif; ?>

			<?php foursuitcases_travel_link( array( 'url' => foursuitcases_travel_plan_url(), 'title' => 'Plan your trip' ), 'travel-header-pass' ); ?>
			<?php if ( foursuitcases_feature_enabled( 'dark_mode' ) ) : ?>
				<button class="theme-toggle" data-tag="Day and night" type="button" aria-pressed="false">
					<span class="theme-toggle__icon" aria-hidden="true"></span>
					<span class="screen-reader-text"><?php esc_html_e( 'Toggle dark mode', 'four-suitcases' ); ?></span>
				</button>
			<?php endif; ?>
		</div>
	</header>

	<main id="site-main" class="site-main" data-stamps>
