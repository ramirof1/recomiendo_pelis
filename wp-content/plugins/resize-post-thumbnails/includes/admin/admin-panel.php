<?php 

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Admin panel HTML data
function resize_post_thumbnails__get_admin_panel( $function_id, $function_variables ) {
	global $title; 
	
	$data = '<div id="resize-post-thumbnails-admin-panel">' . "\n";
		$data .= '<form enctype="multipart/form-data" id="' . $function_id . '">' . "\n";
			$data .= '<div id="resize-post-thumbnails-admin-panel-header" class="wp-ui-primary">' . "\n";
				$data .= '<div id="resize-post-thumbnails-admin-header-overlay">' . "\n";
					$data .= '<span>' . $title . '</span>' . "\n";
				$data .= '</div>' . "\n";
			$data .= '</div>' . "\n";
			
			$data .= '<div id="resize-post-thumbnails-admin-panel-main">' . "\n";
			
				$menus = '<div id="resize-post-thumbnails-admin-panel-menu">' . "\n";
					$menus .= resize_post_thumbnails__machine_menu( $function_variables );
				$menus .= '</div>' . "\n";

				$content = '<div id="resize-post-thumbnails-admin-panel-content">' . "\n";
					$content .= resize_post_thumbnails__machine_content( $function_variables );
				$content .= '</div>' . "\n";
				
				$content_array = array(
					array(
						'content'    => $menus,
						'class'      => 'resize-post-thumbnails-admin-panel-menu',
						'style'      => 'vertical-align: top;'
					),
					array(
						'content'    => $content,
						'class'      => 'resize-post-thumbnails-admin-panel-content',
					)
				);
				
				$data .= resize_post_thumbnails__content_display( $content_array, 'table', 'cell' );
				
				$data .= '<div style="clear: both;"></div>' . "\n";
			$data .= '</div>' . "\n";

			$data .= '<div id="resize-post-thumbnails-admin-panel-footer">' . "\n";
				$data .= '<div id="resize-post-thumbnails-admin-panel-footer-submit">' . "\n";
					$data .= '<input type="button" value="' . __( 'Default Values To All', 'resize_post_thumbnails' ) . '" class="button" name="revert" onclick="resize_post_thumbnails__reset_all_elements()" />' . "\n";
					$data .= '<input type="submit" value="' . __( 'Apply Changes', 'resize_post_thumbnails' ) . '" class="button button-primary" id="submit-button" />' . "\n";
					$data .= '<div style="clear: both;"></div>' . "\n";
				$data .= '</div>' . "\n";
				$data .= '<div style="clear: both;"></div>' . "\n";
			$data .= '</div>' . "\n";
			$data .= wp_nonce_field( 'wp_ajax_resize_post_thumbnails__ajax', 'resize_post_thumbnails__nonce', true, false ) . "\n";
		$data .= '</form>' . "\n";

	$data .= '</div>' . "\n";
	
	return $data;
}

// Function to show admin panel
function resize_post_thumbnails__admin_panel( $function_id, $function_variables ) { 
	
	echo resize_post_thumbnails__get_admin_panel( $function_id, $function_variables );
	?>

	<script type="text/javascript">
		
		//Reset all elements with wDefault() function
		function resize_post_thumbnails__reset_all_elements() {
		
<?php echo resize_post_thumbnails__reset_all_elements( $function_variables ); ?>

		}

		// Reset element by id
		function resize_post_thumbnails__reset_element_by_id( id ) {
			var data;
			
<?php echo resize_post_thumbnails__reset_element_by_id( $function_variables ); ?>

		}
	</script>

<?php }

// Function for adding menu tab
function resize_post_thumbnails__machine_menu( $settings ) {

	if ( ! empty ( $settings ) ) {
		
		$menu_number = 0;
		$menu_count = count( $settings );
		
		$output = '<ul>' . "\n";
		
		foreach( (array) $settings as $key => $arr ) {
			
			$menu_number = $menu_number + 1;

			if ( (int) $menu_number == 1 ) {
				$add_class = ' open menu_first';
			} else {
				$add_class = '';
			}
			
			if ( (int) $menu_count == (int) $menu_number ) {
				$add_class .= ' menu_last';
			}

			if ( ! empty ( $arr['submenus'] ) ) {
				
				$submenu_number = 0;
				$submenu_count = count( (array) $arr['submenus'] );
				
				$output .= '<li class="control-section accordion-section' . $add_class . '">' . "\n";
					
					if ( ! empty( $arr['name'] ) ) {
						$output .= '<h3 class="accordion-section-title" title="' . $arr['name'] . '" id="resize-post-thumbnails-admin-panel-menu-' . $key . '">' . $arr['name'] . '</h3>' . "\n";
					}
				
					if ( (int) $menu_number != 1 ) {
						$ul_style = ' style="display: none;" ';
					} else {
						$ul_style = '';
					}
				
					$output .= '<ul' . $ul_style . '>' . "\n";
					
					foreach( (array) $arr['submenus'] as $submenu_id => $submenu_arr ) {
						$submenu_number = $submenu_number + 1;
					
						if ( (int) $submenu_number == 1 ) {
							$submenu_add_class = ' submenu_open submenu_first';
						} else {
							$submenu_add_class = '';
						}
						
						if ( (int) $submenu_count == (int) $submenu_number ) {
							$submenu_add_class .= ' submenu_last';
						}
					
						$output .= '<li class="resize-post-thumbnails-submenu' . $submenu_add_class . '">' . "\n";
						
							if ( ! empty( $submenu_arr['name'] ) ) {
								$output .= '<h3 class="accordion-section-title" title="' . $submenu_arr['name'] . '" id="resize-post-thumbnails-admin-panel-submenu-' . $submenu_id . '">' . $submenu_arr['name'] . '<span class="wp-ui-highlight"></span></h3>' . "\n";
							}
							
						$output .= '</li>' . "\n";
					}
					
					$output .= '</ul>' . "\n";
				$output .= '</li>' . "\n";
			}
		}
		$output .= '</ul>' . "\n";
	}

	return $output;
}

// Function for generating inputs
function resize_post_thumbnails__machine_content( $settings ) {
	$output = '';

	if ( ! empty ( $settings ) ) {
		
		$menu_number = 0;
		$menu_count = count( $settings );
		
		foreach( (array) $settings as $menu_id => $menu ) {
			
			$menu_number = $menu_number + 1;
			
			if ( isset( $menu['class'] ) ) {
				$menu_class = ' ' . $menu['class'];
			} else {
				$menu_class = '';
			}

			if ( (int) $menu_number == 1 ) {
				$menu_class .= ' open menu_first';
			}
			
			if ( (int) $menu_count == (int) $menu_number ) {
				$menu_class .= ' menu_last';
			}
		
			$output .= '<div class="resize-post-thumbnails-menu-content' . $menu_class . '" id="resize-post-thumbnails-menu-content-' . $menu_id . '">' . "\n";

			if ( ! empty ( $menu['submenus'] ) ) {
				
				$submenu_number = 0;
				$submenu_count = count( (array) $menu['submenus'] );
				
				foreach( (array) $menu['submenus'] as $submenu_id => $submenu_arr ) {
				
					$submenu_number = $submenu_number + 1;

					if ( isset( $submenu_arr['class'] ) ) {
						$submenu_class = ' ' . $submenu_arr['class'];
					} else {
						$submenu_class = '';
					}
					
					if ( (int) $submenu_number == 1 ) {
						$submenu_class .= ' submenu_open submenu_first';
					}
					
					if ( (int) $submenu_count == (int) $submenu_number ) {
						$submenu_class .= ' submenu_last';
					}

					$output .= '<div class="resize-post-thumbnails-submenu-content' . $submenu_class . '" id="resize-post-thumbnails-submenu-content-' . $menu_id . '-' . $submenu_id . '">' . "\n";
				
					if ( ! empty ( $submenu_arr['rows'] ) ) {
						foreach( (array) $submenu_arr['rows'] as $row ) {

							// ======= ROW =======

							// Description
							if ( ! empty( $row['description'] ) ) {
								$row_description = $row['description'];
							} else {
								$row_description = '';
							}

							// Row class
							if ( ! empty( $row['class'] ) ) {
								$row_class = ' ' . $row['class'];
							} else {
								$row_class = '';
							}
							
							// Row style
							if ( ! empty( $row['style'] ) ) {
								$row_style = ' style="' . $row['style'] . '"';
							} else {
								$row_style = '';
							}

							$output .= '<div class="resize-post-thumbnails-row' . $row_class . '"' . $row_style . '>' . "\n";
								
								// Inside table
								$output .= '<div class="resize-post-thumbnails-tags-group resize-post-thumbnails-inside-table">' . "\n";

									$row_output = array();
	
									if ( ! empty ( $row['tags'] ) ) {
										foreach ( (array) $row['tags'] as $tag ) {
											$output_1 = resize_post_thumbnails__machine_tag( $tag, 'admin_panel' );
											
											$classes_str = '';
											$classes_arr = array();
											
											if ( empty( $tag['class'] ) and isset( $tag['tag'] ) ) {
												$tag['class'] = 'resize_post_thumbnails__' . $tag['tag'];
											} else if ( ! empty( $tag['class'] ) and isset( $tag['tag'] ) ) {
												$tag['class'] = $tag['class'] . ' resize_post_thumbnails__' . $tag['tag'];
											}
											
											if ( ! empty( $tag['class'] ) ) {
												$classes_arr = explode( ' ', (string) $tag['class'] );
												
												foreach ( (array) $classes_arr as $class_id => $class ) {
													$classes_arr[$class_id] = 'table_cell_' . $class;
												}
												
												$classes_str = implode( ' ', $classes_arr );
											}

											$row_output[] = array(
												'content'    => $output_1,
												'class'      => $classes_str
											);
										}
									}

									// Table
									$output .= resize_post_thumbnails__content_display( $row_output, 'table', 'cell' );

								$output .= '</div>' . "\n";

								$output .= '<div class="clear"></div>' . "\n";
				
								if ( ! empty( $row_description ) ) {
									$output .= '<div class="resize-post-thumbnails-row-description"><small>' . $row_description . '</small></div>' . "\n";
								}
								
							$output .= '</div>' . "\n";
						}
					}
					
					$output .= '</div>' . "\n";
				}
			}

			$output .= '</div>' . "\n";
		}
	}
	
	return $output;
}

// Additional function for reseting elements
function resize_post_thumbnails__reset_all_elements( $settings_options = array() ) {
	$output = '';
	
	if ( ! empty ( $settings_options ) ) {
		foreach( (array) $settings_options as $menu_id => $menu ) {

			if ( ! empty ( $menu['submenus'] ) ) {
				foreach( (array) $menu['submenus'] as $submenu_id => $submenu ) {

					if ( ! empty ( $submenu['rows'] ) ) {
						foreach ( (array) $submenu['rows'] as $row ) {

							if ( ! empty ( $row['tags'] ) ) {
								foreach ( (array) $row['tags'] as $tag ) {

									if ( is_array( $tag ) ) {
										if ( isset( $tag['id'] ) ) {
											
										if ( isset( $tag['use_default_value'] ) ) {
												$use_default_value = (bool) $tag['use_default_value'];
											} else {
												$use_default_value = false;
											}
											
											if ( ! (bool) $use_default_value ) {
												$tag_id = $tag['id'];
												
												$output .= 'resize_post_thumbnails__reset_element_by_id( "' . $tag_id . '" );' . "\n";
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
	
	return $output;
}

// Function for displaying script code for reseting the default values
function resize_post_thumbnails__reset_element_by_id( $settings_options = array() ) {
	$output = '';

	if ( ! empty ( $settings_options ) ) {
		foreach( (array) $settings_options as $menu_id => $menu ) {
		
			if ( ! empty ( $menu['submenus'] ) ) {
				foreach( (array) $menu['submenus'] as $submenu_id => $submenu ) {

					if ( ! empty ( $submenu['rows'] ) ) {
						foreach ( (array) $submenu['rows'] as $row ) {

							if ( ! empty ( $row['tags'] ) ) {
								foreach ( (array) $row['tags'] as $tag ) {

									if ( is_array( $tag ) ) {
										if ( isset( $tag['id'] ) ) {
											
											if ( isset( $tag['use_default_value'] ) ) {
												$use_default_value = (bool) $tag['use_default_value'];
											} else {
												$use_default_value = false;
											}
											
											if ( ! (bool) $use_default_value ) {
												$tag_id = $tag['id'];

												// If the value is from an array
												if ( isset( $tag['merge_into'] ) ) {
													$merge_into = resize_post_thumbnails__get_option( $tag['merge_into'], false, true );
													
													if ( isset( $merge_into[$tag_id] ) ) {
														$element_value = $merge_into[$tag_id];
													} else {
														$element_value = '';
													}
												} else {
													$element_value = resize_post_thumbnails__get_option( $tag_id, false, true );
												}

												// If element is checkbox
												if ( isset( $tag['type'] ) and (string) $tag['type'] == 'checkbox' ) {
													$output .=  "if ( id == '" . $tag_id . "' ) {" . "\n";
													$output .= '	jQuery( "#' . $tag_id . '" ).prop( "checked", ' . (int) $element_value . ' );' . "\n";
													$output .=  "}" . "\n";		
												} else {
													$output .=  "if ( id == '" . $tag_id . "' ) {" . "\n";
													$output .=  "	document.getElementById( id ).value = '" . esc_js( $element_value ) . "';" . "\n";
													$output .=  "}" . "\n";		
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
	
	return $output;
}