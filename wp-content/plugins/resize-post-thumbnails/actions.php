<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


$default_settings_functions = resize_post_thumbnails__default_settings_functions();

if ( ! empty( $default_settings_functions ) ) {
	foreach ( (array) $default_settings_functions as $option_id => $option_function ) {
		add_filter( 'resize_post_thumbnails__default_option_' . $option_id, $option_function );
	}
}


$resize_post_thumbnails = resize_post_thumbnails();
$plugin_file = $resize_post_thumbnails->file;

// Plugin Activation
register_activation_hook( $plugin_file,                               'resize_post_thumbnails__activation_action'                          );

// Plugin Deactivation
register_deactivation_hook( $plugin_file,                             'resize_post_thumbnails__deactivation_action'                        );


$resize_post_thumbnails = resize_post_thumbnails__get_option( 'resize_post_thumbnails_settings_resize_post_thumbnails' );

// Check if option is enabled
if ( (bool) $resize_post_thumbnails ) {

	// Resize all the images in all posts
	add_filter( 'post_thumbnail_html',                                'resize_post_thumbnails__post_thumbnail_html_filter', 10, 5          );
}