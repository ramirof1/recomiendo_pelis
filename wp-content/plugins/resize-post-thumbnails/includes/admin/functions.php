<?php 

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Registering scripts and sryles for later
function resize_post_thumbnails__register_scripts_and_styles_admin_action() {
	$resize_post_thumbnails                  = resize_post_thumbnails();
	$includes_url                            = $resize_post_thumbnails->includes_url;
	$version                                 = $resize_post_thumbnails->version;
	
	// Admin Panel
	wp_register_script( 'resize-post-thumbnails-admin-panel',                        $includes_url . 'admin/js/admin-menu.js',                         array(), $version, true ); 
	wp_register_style( 'resize-post-thumbnails-admin-panel',                         $includes_url . 'admin/css/admin-default.css',                    array(), $version );
}

// Function that is used with AJAX to save all settings from the admin panel
function resize_post_thumbnails__admin_save_action() {
	$json_success                  = array();
	$json_error                    = array();
	$function_exists               = false;

	if ( ! check_ajax_referer( 'wp_ajax_resize_post_thumbnails__ajax', 'resize_post_thumbnails__nonce', false ) ) {
		$json_error[] = __( 'Invalid nonce.', 'resize_post_thumbnails' );
	}
	
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		$json_error[] = __( "Current user can't change options.", 'resize_post_thumbnails' );
	}

	if ( empty( $json_error ) ) {
		
		// If we have function id and data
		if ( ! empty( $_POST['function_id'] ) ) {
			if ( ! empty( $_POST['data'] ) ) {

				// Function id
				$function_name = $_POST['function_id'];

				// The data
				$data = $_POST['data'];
				
				$settings = apply_filters( 'resize_post_thumbnails__add_menu_page_settings', array() );

				if ( ! empty ( $settings ) ) {
					foreach ( (array) $settings as $setting_arr ) {
						if ( in_array( $function_name, $setting_arr, true ) ) {
							if ( function_exists( $function_name ) ) {
								$function_exists = true;
							}
						}
					}
				}
			} else {
				$json_error[] = __( 'Data is empty.', 'resize_post_thumbnails' );
			}
		} else {
			$json_error[] = __( 'Empty function ID value.', 'resize_post_thumbnails' );
		}

		// If function exists
		if ( (bool) $function_exists ) {

			// Function options
			$function_options = call_user_func( $function_name );

			// Filter the options
			$options = apply_filters( 'resize_post_thumbnails__filter_admin_menu_options_' . $function_name, $function_options );

			// Parses the string into variables
			wp_parse_str( $data, $ajax_data );

			if ( ! empty ( $options ) ) {
				foreach( (array) $options as $menu ) {
				
					if ( ! empty( $menu['submenus'] ) ) {
						foreach( (array) $menu['submenus'] as $submenu ) {
						
							if ( ! empty( $submenu['rows'] ) ) {
								foreach ( (array) $submenu['rows'] as $row ) {
								
									if ( ! empty( $row['tags'] ) ) {
										foreach ( (array) $row['tags'] as $tag ) {
											
											if ( is_array( $tag ) ) {
												if ( isset( $tag['id'] ) ) {
													$tag_id = $tag['id'];

													if ( ! empty( $tag['merge_into'] ) ) {
														$save_tag_id = $tag['merge_into'];
														$array_upload = true;
													} else {
														$array_upload = false;
													}

													// Use default value?
													if ( isset( $tag['use_default_value'] ) ) {
														$use_default_value = (bool) $tag['use_default_value'];
													} else {
														$use_default_value = false;
													}

													// Check if such ID we have in the ajax data
													if ( isset( $ajax_data[$tag_id] ) and ! $use_default_value ) {

														if ( (bool) $array_upload ) {
															$array_value = resize_post_thumbnails__get_option( $save_tag_id );
															$array_value[$tag_id] = $ajax_data[$tag_id];
															update_option( $save_tag_id, $array_value );
														} else {
															update_option( $tag_id, $ajax_data[$tag_id] );
														}
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		} else {
			$json_error[] = __( 'Function ID does not exist.', 'resize_post_thumbnails' );
		}

	}
	
	if ( empty( $json_error ) ) {
		wp_send_json_success( $json_success );
	} else {
		wp_send_json_error( implode( "\n", $json_error ) );
	}
}

// Function for sorting the menu panels options
function resize_post_thumbnails__function_call_admin_panel() {
	global $plugin_page;

	$function_id = '';

	$settings = apply_filters( 'resize_post_thumbnails__add_menu_page_settings', array() );

	if ( ! empty ( $settings ) ) {
		foreach ( (array) $settings as $setting_arr ) {
			if ( in_array( $plugin_page, $setting_arr, true ) ) {
				if ( ! empty( $setting_arr['function'] ) ) {
					$function_id = $setting_arr['function'];
				}
			}
		}
	}

	// Does function exist?
	if ( function_exists( $function_id ) ) {
		$function_options = call_user_func( $function_id );
		
		// Filter the options
		$data = apply_filters( 'resize_post_thumbnails__filter_admin_menu_options_' . $function_id, $function_options );

		$priority = array();
		
		if ( ! empty ( $data ) ) {
			foreach ( (array) $data as $key => $row ) {
				if ( isset( $row['priority'] ) ) {
					$priority[$key] = (int) $row['priority'];
				} else {
					$priority[$key] = 50;
				}
				
				$submenu_priority = array();
				
				if ( ! empty( $row['submenus'] ) ) {
					$submenus = $row['submenus'];
					foreach ( (array) $submenus as $submenu_key => $submenu ) {
						if ( isset( $submenu['priority'] ) ) {
							$submenu_priority[$submenu_key] = (int) $submenu['priority'];
						} else {
							$submenu_priority[$submenu_key] = 50;
						}

						$option_priority = array();

						if ( ! empty( $submenu['rows'] ) ) {
							$rows = $submenu['rows'];
							foreach ( (array) $rows as $option_key => $option ) {
								if ( isset( $option['priority'] ) ) {
									$option_priority[$option_key] = (int) $option['priority'];
								} else {
									$option_priority[$option_key] = 50;
								}
							}

							array_multisort( $option_priority, SORT_ASC, $rows );

							$submenus[$submenu_key]['rows'] = $rows;
						}
					}
					
					array_multisort( $submenu_priority, SORT_ASC, $submenus );

					$data[$key]['submenus'] = $submenus;
				}
			}
		}

		array_multisort( $priority, SORT_ASC, $data );

		resize_post_thumbnails__admin_panel( $function_id, $data );
	}
}

// This function is called to add admin menus in the WordPress left menu
function resize_post_thumbnails__admin_menu_action() {
	$settings = apply_filters( 'resize_post_thumbnails__add_menu_page_settings', array() );

	if ( ! empty ( $settings ) ) {
		foreach ( (array) $settings as $settings_id => $wpi ) {
			if ( isset( $wpi['page_title'], $wpi['menu_title'], $wpi['capability'], $wpi['menu_slug'] ) ) {
				
				if ( isset( $wpi['use_admin_panel'] ) and (bool) $wpi['use_admin_panel'] ) {
					$function = 'resize_post_thumbnails__function_call_admin_panel';
				} else {
					$function = $wpi['function'];
				}
				
				$add_plugins_page = add_plugins_page( $wpi['page_title'], $wpi['menu_title'], $wpi['capability'], $wpi['menu_slug'], $function );

				// Add help data and sidebar
				add_action( 'load-' . $add_plugins_page, 'resize_post_thumbnails__admin_add_help_and_sidebar_action' );				
			}
		}
	}
}

// Function for adding help data and sidebar to the help tab
function resize_post_thumbnails__admin_add_help_and_sidebar_action() {
	global $plugin_page;
	
	$screen = get_current_screen();
	$settings = apply_filters( 'resize_post_thumbnails__add_menu_page_settings', array() );
	
	// Default admin panel help text
	$resize_post_thumbnails__admin_panel_help = resize_post_thumbnails__admin_panel_help();
	
	// Default sidebar text
	$sidebar_tab_help = resize_post_thumbnails__sidebar_tab_help();
	
	if ( ! empty ( $settings ) ) {
		foreach ( (array) $settings as $sub_settings ) {
		
			// Filter the admin_panel_help
			$resize_post_thumbnails__admin_panel_help_filtered = apply_filters( 'resize_post_thumbnails__filter_admin_panel_help', $resize_post_thumbnails__admin_panel_help, $plugin_page );
			
			// Filter the sidebar help tab text
			$sidebar_tab_help_filtered = apply_filters( 'resize_post_thumbnails__filter_admin_panel_help', $sidebar_tab_help, $plugin_page );
		
			// If is the current page we need
			if ( (string) $sub_settings['menu_slug'] == (string) $plugin_page ) {

				// Get help data
				if ( ! empty( $sub_settings['content'] ) and (string) $sub_settings['content'] == 'data' ) {
					if ( ! empty( $sub_settings['help'] ) ) {
						$admin_panel_help = array_merge( $resize_post_thumbnails__admin_panel_help_filtered, $sub_settings['help'] );
					} else {
						$admin_panel_help = $resize_post_thumbnails__admin_panel_help_filtered;
					}
				} else if ( ! empty( $sub_settings['help'] ) ) {
					$admin_panel_help = $sub_settings['help'];
				} else {
					$admin_panel_help = array();
				}
				
				// Add help bdata to admin page
				if ( ! empty( $admin_panel_help ) ) {
					foreach ( (array) $admin_panel_help as $key => $tab ) {
						$screen->add_help_tab( array(
							'id'	    => $key,
							'title'	    => $tab['title'],
							'content'	=> $tab['content']
						) );
						
						$screen->set_help_sidebar( $sidebar_tab_help_filtered );
					}
				}
			}
		}
	}
}

// Function for printing styles in the header for Admin Panel
function resize_post_thumbnails__enqueue_style_scripts_action() {
	global $plugin_page;
	
	wp_enqueue_media();
	
	$styles = array();
	$scripts = array();

	$settings = apply_filters( 'resize_post_thumbnails__add_menu_page_settings', array() );
	
	if ( ! empty ( $settings ) ) {
		foreach ( (array) $settings as $setting ) {
			if ( ! empty( $setting['menu_slug'] ) ) {
		
				if ( ! empty( $setting['styles'] ) ) {
					if ( (string) $plugin_page == (string) $setting['menu_slug'] ) {
						foreach ( (array) $setting['styles'] as $style ) {
							wp_enqueue_style( $style );
						}
						
					}
				}
				
				if ( ! empty( $setting['scripts'] ) ) {
					if ( (string) $plugin_page == (string) $setting['menu_slug'] ) {
						foreach ( (array) $setting['scripts'] as $script ) {
							wp_enqueue_script( $script );
						}
					}
				}
			
			}
		}
	}
}

// Admin Panel help default text
function resize_post_thumbnails__admin_panel_help() {
	$admin_panel_help = array(
		'id_1'   => array(
			'title'      => __( 'About Admin Panel', 'resize_post_thumbnails' ),
			'content'    => '<p>' . __( 'Admin Panel will help you to change the settings of the theme.', 'resize_post_thumbnails' ) . '</p>' . 
			'<p>' . __( 'To save the settings after change - click "Apply Changes".', 'resize_post_thumbnails' ) . '</p>' .
			'<p>' . __( 'To reset all settings click "Default Values To All" button and save.', 'resize_post_thumbnails' ) . '</p>'
		)
	);
	
	return $admin_panel_help;
}

// Sidebar tab help default text
function resize_post_thumbnails__sidebar_tab_help() {
	$sidebar_tab_help = '<p><strong>' . __( 'For more information:', 'resize_post_thumbnails' ) . '</strong></p>' .
		'<p>' . __( '<a href="https://wordpress.org/plugins/resize-post-thumbnails/" target="_blank">' . __( 'Documentation', 'resize_post_thumbnails' ) . '</a>' ) . '</p>' .
		'<p>' . __( '<a href="https://wordpress.org/support/plugin/resize-post-thumbnails" target="_blank">' . __( 'Support Forum', 'resize_post_thumbnails' ) . '</a>' ) . '</p>';
	
	return $sidebar_tab_help;
}


function resize_post_thumbnails__settings_site_info() {
	$resize_post_thumbnails = resize_post_thumbnails();
	$wp_get_theme = wp_get_theme();
	
	$site_info = '';
	
	$site_info .= __( 'Theme:', 'resize_post_thumbnails' ) . ' ' . $wp_get_theme->get( 'Name' ) . "\n";

	$site_info .= __( 'Versions:', 'resize_post_thumbnails' ) . "\n";
	$site_info .= __( '- Plugin:', 'resize_post_thumbnails' ) . ' ' .  $resize_post_thumbnails->version . "\n";
	$site_info .= '- WordPress:' . ' ' . get_bloginfo( 'version' ) . "\n";
	$site_info .= '- PHP:' . ' ' . phpversion() . "\n";
	
	return $site_info;
}