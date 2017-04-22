<?php


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Display content in a table style if needed
function resize_post_thumbnails__content_display( $array_content = array(), $table_or_normal = 'table', $cell_or_row = 'cell' ) {
	$content = '';
	$style = ' style="';
	
	if ( (string) $table_or_normal == 'table' ) {
		foreach ( (array) $array_content as $element => $element_arr ) {
		
			// Content
			if ( isset( $element_arr['content'] ) ) {
				$element_content = $element_arr['content'];
			} else {
				$element_content = '';
			}
			
			// ID
			if ( isset( $element_arr['id'] ) ) {
				$element_id = ' id="' . $element_arr['id'] . '"';
			} else {
				$element_id = '';
			}
			
			// Class
			if ( isset( $element_arr['class'] ) ) {
				$element_class = ' class="resize_post_thumbnails__table_' . $cell_or_row . ' ' . $element_arr['class'] . '"';
			} else {
				$element_class = ' class="resize_post_thumbnails__table_' . $cell_or_row . '"';
			}

			// Style
			$element_style = ' style="display: table-' . $cell_or_row . ';';
			
			if ( isset( $element_arr['style'] ) ) {
				$element_style .= ' ' . $element_arr['style'];
			} else {
				$element_style .= '';
			}
			
			$element_style .= '"';
		
			$content .= '<div' . $element_id . $element_class . $element_style . '>' . "\n";
				$content .= $element_content;
			$content .= '</div>' . "\n";
		}
	} else if ( (string) $table_or_normal == 'normal' ) {
		foreach ( (array) $array_content as $element => $element_arr ) {
		
			// Content
			if ( isset( $element_arr['content'] ) ) {
				$element_content = $element_arr['content'];
			} else {
				$element_content = '';
			}
			
			$content .= $element_content . "\n";
		}
	}
	
	return $content;
}


function resize_post_thumbnails__machine_tag( $tag_args = array(), $admin_panel_or_meta_box = 'admin_panel', $post = null ) {

	$tag_id = '';
	$merge_tag_id = '';
	$select_value = '';
	$tag_html = '';
	$tag_close = false;
	$unpaired_tags_arr = array( 'area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input', 'keygen', 'link', 'meta', 'param', 'source', 'track', 'wbr' );

	// ======= Tag =======
	if ( is_array( $tag_args ) and ! empty( $tag_args['tag'] ) ) {

		$tag = strtolower( $tag_args['tag'] );
		unset( $tag_args['tag'] );
		
		// Adding name attribute if empty for input, textarea, button and select tags
		$form_elements = array( 'input', 'textarea', 'button', 'select' );
		if ( in_array( $tag, $form_elements ) ) {
			if ( ! isset( $tag_args['name'] ) and ! empty( $tag_args['id'] ) ) {
				$tag_args['name'] = $tag_args['id'];
			}
		}

		// Adding class
		$tag_class = 'resize_post_thumbnails__' . $tag; 
		if ( ! empty( $tag_args['type'] ) ) {
			$tag_class .= ' resize_post_thumbnails__' . $tag . '_' . $tag_args['type']; 
		}
		if ( ! empty( $tag_args['class'] ) ) {
			$tag_class .= ' ' . $tag_args['class'];
		}
		$tag_args['class'] = $tag_class;

		// Check if is array element
		if ( ! empty( $tag_args['merge_into'] ) ) {
			$merge_tag_id = $tag_args['merge_into'];
			unset( $tag_args['merge_into'] );
			$array_values = true;
		} else {
			$array_values = false;
		}
		
		if ( isset( $tag_args['id'] ) ) {
			$tag_id = $tag_args['id'];
		} else {
			$tag_id = '';
		}

		// Adding value attribute
		if ( ! isset( $tag_args['value'] ) and ! empty( $tag_id ) ) {
			
			// Use default value?
			if ( isset( $tag_args['use_default_value'] ) ) {
				$use_default_value = (bool) $tag_args['use_default_value'];
			} else {
				$use_default_value = false;
			}
			
			unset( $tag_args['use_default_value'] );

			if ( (bool) $array_values ) {
				if ( (string) $admin_panel_or_meta_box == 'admin_panel' ) {
					$resize_post_thumbnails__get_option = resize_post_thumbnails__get_option( $merge_tag_id, false, $use_default_value );

					if ( $resize_post_thumbnails__get_option !== false ) {
						$element_arr_val = $resize_post_thumbnails__get_option;
					}
				} else if ( (string) $admin_panel_or_meta_box == 'meta_box' ) {
					$element_arr_val = get_post_meta( $post->ID, $merge_tag_id, true );
				}
				
				if ( isset( $element_arr_val[$tag_id] ) ) {
					$tag_args['value'] = esc_textarea( $element_arr_val[$tag_id] );
				}
			} else {
				if ( (string) $admin_panel_or_meta_box == 'admin_panel' ) {
					$resize_post_thumbnails__get_option = resize_post_thumbnails__get_option( $tag_id, false, $use_default_value );

					if ( $resize_post_thumbnails__get_option !== false ) {
						$tag_args['value'] = esc_textarea( resize_post_thumbnails__get_option( $tag_id, false, $use_default_value ) );
					}
					
				} else if ( (string) $admin_panel_or_meta_box == 'meta_box' ) {
					$tag_args['value'] = esc_textarea( get_post_meta( $post->ID, $tag_id, true ) );
				}
			}
		}

		if ( isset( $tag_args['type'] ) ) {
			
			// If checkbox
			if ( (string) $tag_args['type'] == (string) 'checkbox' ) {
				if ( ! empty( $tag_args['value'] ) ) {
					$tag_args['checked'] = 'checked';
				}
				
				if ( isset( $tag_args['value'] ) ) {
					unset( $tag_args['value'] );
				}
				
				if ( ! empty( $tag_id ) ) {
					$before_checkbox_args = array(
						'tag'       => 'input',
						'type'      => 'hidden',
						'id'        => $tag_id . '_hidden',
						'name'      => $tag_args['name'],
						'value'     => '0'
					);
					
					$tag_html .= resize_post_thumbnails__machine_tag( $before_checkbox_args );
				}
			}
		}

		// If textarea
		if ( (string) $tag == (string) 'textarea' ) {
			if ( isset( $tag_args['value'] ) ) {
				$tag_args['text'] = $tag_args['value'];
				unset( $tag_args['value'] );
			}
		}
		
		// If select
		else if ( (string) $tag == (string) 'select' ) {
			if ( isset( $tag_args['value'] ) ) {
				$select_value = $tag_args['value'];
				unset( $tag_args['value'] );
			}
		}
		
		// If option group
		else if ( (string) $tag == (string) 'optgroup' ) {
			if ( ! empty( $tag_args['id'] ) ) {
				unset( $tag_args['id'] );
			}
			
			if ( ! empty( $tag_args['select_value'] ) ) {
				$select_value = $tag_args['select_value'];
				unset( $tag_args['select_value'] );
			}
		}
		
		// If option
		else if ( (string) $tag == (string) 'option' ) {
			if ( ! empty( $tag_args['id'] ) ) {
				unset( $tag_args['id'] );
			}
			
			if ( ! empty( $tag_args['select_value'] ) ) {
				if ( ! empty( $tag_args['value'] ) ) {
					if ( is_array( $tag_args['select_value'] ) ) {
						foreach ( $tag_args['select_value'] as $option_value ) {
							if ( (string) $option_value == (string) $tag_args['value'] ) {
								$tag_args['selected'] = 'selected';
							}
						}
					} else {
						if ( (string) $tag_args['select_value'] == (string) $tag_args['value'] ) {
							$tag_args['selected'] = 'selected';
						}
					}
				}
				
				unset( $tag_args['select_value'] );
			}
		}
		
		// Check if paired tag
		if ( ! in_array( $tag, $unpaired_tags_arr ) ) {
			$tag_close = true;
		}

		// Tag text
		if ( isset( $tag_args['text'] ) ) {
			$tag_text = $tag_args['text'];
			unset( $tag_args['text'] );
		} else {
			$tag_text = '';
		}

		// Start tag
		$tag_html .= '<' . $tag;
		
		// Adding attributes
		if ( ! empty( $tag_args ) ) {
			foreach ( (array) $tag_args as $attr_id => $attr_value ) {
				if ( is_array( $attr_value ) ) {
					if ( ! empty( $attr_value ) ) {
						foreach ( (array) $attr_value as $new_tag_args ) {

							if ( is_array( $new_tag_args ) ) {
								if ( ! empty( $new_tag_args ) ) {
									
									if ( (string) $tag == 'select' or (string) $tag == 'optgroup' ) {
										if ( ! empty( $tag_id ) ) {
											$new_tag_args['id'] = $tag_id;
										}

										if ( ! empty( $select_value ) ) {
											$new_tag_args['select_value'] = $select_value;
										}
									}

									$tag_text .= "\n";
									$tag_text .= resize_post_thumbnails__machine_tag( $new_tag_args, $admin_panel_or_meta_box, $post );
								}
							}
						}
					}
				} else {
					$tag_html .= ' ' . $attr_id . '="' . $attr_value . '"';
				}
			}
		}
		
		// End tag
		$tag_html .= '>';
		
		if ( (bool) $tag_close ) {
			$tag_html .= $tag_text . '</' . $tag . '>';
		}

	} else {
		if ( ! empty( $tag_args['text'] ) ) {
			$tag_html = $tag_args['text'];
		} else {
			$tag_html = '';
		}
	}

	return $tag_html;
}