<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Theme options
add_filter( 'resize_post_thumbnails__add_menu_page_settings', 'resize_post_thumbnails__menu_page' );