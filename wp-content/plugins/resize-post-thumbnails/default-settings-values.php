<?php

// Do not edit this file

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Default theme settings values
function resize_post_thumbnails__default_settings_values( $settings = array() ) {

	$resize_post_thumbnails                 = resize_post_thumbnails();
	$settings_id                            = $resize_post_thumbnails->settings_id;

	$settings[$settings_id . 'resize_post_thumbnails']              = true;

	return $settings;
}

// Default theme settings functions (that will be used later and we can't call them now)
function resize_post_thumbnails__default_settings_functions( $settings = array() ) {
	$resize_post_thumbnails                                         = resize_post_thumbnails();
	$settings_id                                                    = $resize_post_thumbnails->settings_id;
	
	$settings[$settings_id . 'site_info']                           = 'resize_post_thumbnails__settings_site_info';

	return $settings;
}