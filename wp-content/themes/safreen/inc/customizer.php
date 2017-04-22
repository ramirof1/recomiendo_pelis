<?php
/**
 * safreen Theme Customizer
 *
 * @package safreen
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function safreen_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
	
	
}
add_action( 'customize_register', 'safreen_customize_register' );


/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function safreen_customize_preview_js() {
	wp_enqueue_script( 'safreen_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'safreen_customize_preview_js' );

function safreen_registers() {

	wp_enqueue_script( 'safreen_customizer_script', get_template_directory_uri() . '/js/admin.js', array("jquery"), '20120206', true  );
	
	
}
add_action( 'customize_controls_enqueue_scripts', 'safreen_registers' );



require get_template_directory() . '/inc/customizer/config.php';
require get_template_directory() . '/inc/customizer/panels.php';
require get_template_directory() . '/inc/customizer/sections.php';
require get_template_directory() . '/inc/customizer/fields.php';


