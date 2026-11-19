<?php
/**
 * Theme setup.
 *
 * @package Start
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function foursuitcases_setup() {
	load_theme_textdomain( 'four-suitcases', FOURSUITCASES_PATH . 'languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'start-companion-content' );
	add_theme_support( 'custom-logo', array( 'flex-width' => true, 'flex-height' => true ) );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style', 'search-form', 'navigation-widgets' ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_editor_style( array( 'assets/dist/css/tokens.css', 'assets/dist/css/editor.css' ) );
	if ( foursuitcases_feature_enabled( 'curated_editor' ) && ! foursuitcases_feature_enabled( 'patterns' ) ) {
		add_theme_support( 'start-curated-editor' );
	}

	if ( foursuitcases_feature_enabled( 'menus' ) ) {
		register_nav_menus(
			array(
				'primary' => __( 'Primary Menu', 'four-suitcases' ),
				'footer'  => __( 'Footer Menu', 'four-suitcases' ),
				'utility' => __( 'Utility Menu', 'four-suitcases' ),
				'social' => __( 'Social Menu', 'four-suitcases' ),
				'legal' => __( 'Legal Menu', 'four-suitcases' ),
			)
		);
	}

	if ( ! foursuitcases_feature_enabled( 'patterns' ) ) {
		remove_theme_support( 'core-block-patterns' );
	}
}
add_action( 'after_setup_theme', 'foursuitcases_setup' );

function foursuitcases_widgets_init() {
	if ( ! foursuitcases_feature_enabled( 'widgets' ) ) {
		return;
	}

	register_sidebar(
		array(
			'name'          => __( 'Footer', 'four-suitcases' ),
			'id'            => 'footer',
			'description'   => __( 'Footer widget area.', 'four-suitcases' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'foursuitcases_widgets_init' );
