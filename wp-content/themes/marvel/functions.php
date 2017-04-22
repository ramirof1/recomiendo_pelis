<?php
/**
 * Loads the child theme textdomain.
 */
function marvel_child_theme_setup() {
    load_child_theme_textdomain( 'marvel');
}
add_action( 'after_setup_theme', 'marvel_child_theme_setup' );

/**
 * Enqueue styles and scripts
 */
function marvel_enqueue_styles() {
	$parent_style = 'marvel-parent-style';
	wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
	
}
add_action( 'wp_enqueue_scripts', 'marvel_enqueue_styles' );

?>