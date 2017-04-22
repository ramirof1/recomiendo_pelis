<?php
/*
 Plugin Name: Resize Post Thumbnails
 Plugin URI: http://wordpress.org/plugins/resize-post-thumbnails
 Description: This plugin will resize post thumbnails on the fly. (Go to: Dashboard -> Plugins -> Resize Post Thumbnails)
 Version: 1.2
 Author: Alexandru Vornicescu
 Author URI: http://alexvorn.com
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Function on plugin 
function resize_post_thumbnails__activation_action() {
	// Nothing, just because
}

// Function on plugin deactivation
function resize_post_thumbnails__deactivation_action() {
	// Nothing, just because
}

final class resize_post_thumbnails {

	public static function instance() {

		// Store the instance locally to avoid private static replication
		static $instance = null;

		// Only run these methods if they haven't been ran previously
		if ( null === $instance ) {
			$instance = new resize_post_thumbnails;
			$instance->setup_globals();
			$instance->includes();
		}

		// Always return the instance
		return $instance;
	}
	
	private function setup_globals() {

		/** Versions **********************************************************/

		$this->version         = '1.2';

		// Setup some base path and URL information
		$this->file            = __FILE__;
		$this->basename        = plugin_basename( $this->file );
		$this->plugin_dir      = plugin_dir_path( $this->file );
		$this->plugin_url      = plugin_dir_url ( $this->file );

		// Includes
		$this->includes_dir    = trailingslashit( $this->plugin_dir . 'includes'  );
		$this->includes_url    = trailingslashit( $this->plugin_url . 'includes'  );
		
		/** Misc **************************************************************/
		$this->domain          = 'resize_post_thumbnails';
		$this->settings_id     = 'resize_post_thumbnails_settings_';    
	}
	
	private function includes() {
		
		require( $this->plugin_dir . 'default-settings-values.php'                           );
		require( $this->plugin_dir . 'actions.php'                                           );
		
		require( $this->includes_dir . 'functions.php'                                       );
		require( $this->includes_dir . 'image-resize.php'                                    );
		
		/** Admin *************************************************************/
		if ( is_admin() ) {
			require( $this->includes_dir . 'admin/menu-pages/menu-pages.php'                               );
			require( $this->includes_dir . 'admin/menu-pages/menu-page-plugin-options.php'                 );
			require( $this->includes_dir . 'admin/menu-pages/actions.php'                                  );

			require( $this->includes_dir . 'admin/functions.php'                                           );
			require( $this->includes_dir . 'admin/actions.php'                                             );
			require( $this->includes_dir . 'admin/admin-panel.php'                                         );
		}
	}
}


function resize_post_thumbnails() {
	return resize_post_thumbnails::instance();
}

// Function that will activate our plugin
resize_post_thumbnails();


function resize_post_thumbnails__post_thumbnail_html_filter( $html = '', $post_id, $post_thumbnail_id, $size = 'post-thumbnail', $attr = '' ) {
	$get_image_size = resize_post_thumbnails__get_image_size( $size );

	$return = $html;
	
	if ( ! empty( $get_image_size ) ) {
		$instance = array();
		
		if ( isset( $get_image_size['width'] ) ) {
			$instance['thumbnail_width'] = $get_image_size['width'];
		}
		
		if ( isset( $get_image_size['height'] ) ) {
			$instance['thumbnail_height'] = $get_image_size['height'];
		}
		
		if ( isset( $get_image_size['crop'] ) ) {
			$instance['thumbnail_crop'] = $get_image_size['crop'];
		}
		
		if ( preg_match( '/src="([^"]+)"/', $html, $image_url ) ) {
			if ( ! empty( $image_url[1] ) ) {

				$new_image_array = resize_post_thumbnails__dynamically_image_resize( $instance );

				if ( ! empty( $new_image_array['url'] ) ) {
				
					$new_image = preg_replace( '/src="([^"]+)"/', 'src="' . $new_image_array['url'] . '"', $html );
					$new_image = preg_replace( '/width="([^"]+)"/', 'width="' . $new_image_array['width'] . '"', $new_image );
					$new_image = preg_replace( '/height="([^"]+)"/', 'height="' . $new_image_array['height'] . '"', $new_image );
					
					// Remove srcset attribute
					$new_image = preg_replace( '/srcset="([^"]+)"/', '', $new_image );
					
					$return = $new_image;
				}
			}
		}
		
	}

	return $return;
}

// Because this function is absent in WordPress core we use it here to get specific defined images settings
function resize_post_thumbnails__get_image_size( $size ) {
	global $_wp_additional_image_sizes;

	$return = false;
	
	if ( ! empty( $size ) ) {
		if ( is_array( $size ) ) {
			$return = array();

			$return['width'] = $size[0];

			$return['height'] = $size[1];

			$return['crop'] = true;
		} else {
			if ( isset( $_wp_additional_image_sizes[$size] ) ) {
				$return = $_wp_additional_image_sizes[$size];
			}
		}
	}

	return $return;
}

// Our get_option function
function resize_post_thumbnails__get_option( $option, $default = false, $use_default = false ) {
	
	if ( $default === false ) {
		$default_settings_values = resize_post_thumbnails__default_settings_values();
		
		if ( isset( $default_settings_values[$option] ) ) {
			$default = $default_settings_values[$option];
		} else if ( $use_default === true ) {
			$default = get_option( $option, $default );
		}
	}

	$default = apply_filters( 'resize_post_thumbnails__default_option_' . $option, $default );
	
	if ( $use_default === true ) {
		$get_option = $default;
	} else {
		$get_option = get_option( $option, $default );
	}
	
	return $get_option;
}