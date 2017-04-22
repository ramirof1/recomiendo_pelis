<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Page menus array
function resize_post_thumbnails__menu_page( $settings_options = array() ) {

	$admin_panel_help_theme_options = array();
	
	$admin_panel_help_theme_options['id_3'] = array(
		'title'      => __( 'About Admin Panel' , 'resize_post_thumbnails' ),
		'content'    => '<p>' . __( 'The admin panel will help to change some settings of the plugin.', 'resize_post_thumbnails' ) . '</p>'
	);

	$admin_panel_help_theme_options['id_4'] = array(
		'title'      => __( 'About Resize Post Thumbnails Options' , 'resize_post_thumbnails' ),
		'content'    => '<p>' . __( 'If you want to resize featured images (post thumbnails) on the fly for your posts automatically then this plugin can help you.' , 'resize_post_thumbnails' ) . '</p>' .
		'<p>' . __( 'What settings you can change:' , 'resize_post_thumbnails' ) . '</p>' .
		
		'<ul>' .
			'<li>' . __( 'Check or Uncheck to make the plugin work or to disable it.', 'resize_post_thumbnails' ) . '</li>' .
		'</ul>'
	);
	
	// Theme options
	$settings_options['theme_options'] = array(
		'use_admin_panel'       => true,
		'page_title'            => __( 'Resize Post Thumbnails Settings', 'resize_post_thumbnails' ),
		'menu_title'            => __( 'Resize Post Thumbnails', 'resize_post_thumbnails' ),
		'capability'            => 'edit_theme_options',
		'menu_slug'             => 'resize_post_thumbnails',
		'function'              => 'resize_post_thumbnails__admin_panel_settings',
		'help'                  => apply_filters( 'resize_post_thumbnails__filter_admin_menu_help_text', $admin_panel_help_theme_options ),
		'scripts'               => array(
			'resize-post-thumbnails-admin-panel',
			'jquery-ui-accordion'
		),
		'styles'                => array(
			'resize-post-thumbnails-admin-panel'
		)
	);

	return $settings_options;
}