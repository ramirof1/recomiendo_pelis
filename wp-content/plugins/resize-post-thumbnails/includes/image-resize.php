<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// New function
function resize_post_thumbnails__dynamically_image_resize( $instance = array() ) {
	
	$return = false;
	
	$defaults = array(
		'thumbnail_url'                      => '',
		'thumbnail_id'                       => '',
		'thumbnail_width'                    => 0,
		'thumbnail_height'                   => 0,
		'thumbnail_crop'                     => false,
		'thumbnail_quality'                  => 90,
		'thumbnail_resize_gif'               => true
	);
	
	$instance = wp_parse_args( $instance, $defaults );
	
	// If we have no thumbnail_url value
	if ( empty( $instance['thumbnail_url'] ) ) {
		$thumbnail_url = get_post_meta( get_the_ID(), 'image', true );
		
		if ( ! empty( $thumbnail_url ) ) {
			$instance['thumbnail_url'] = $thumbnail_url;
		} else {
			$instance['thumbnail_id'] = get_post_thumbnail_id();
		}
	}

	// If thumbnail_id value is not empty then we have a featured image
	if ( ! empty( $instance['thumbnail_id'] ) ) {
		$image_id_array = wp_get_attachment_image_src( $instance['thumbnail_id'], 'full' );
		$instance['thumbnail_url'] = $image_id_array[0];
	}

	if ( ! empty( $instance['thumbnail_url'] ) ) {
		$wp_upload_dir = wp_upload_dir();

		$return = resize_post_thumbnails__dynamically_image_resize_base( $instance );
	}
	
	return $return;
}


function resize_post_thumbnails__dynamically_image_resize_base( $instance = array() ) {
	
	$return = false;
	
	$defaults = array(
		'thumbnail_url'                      => '',
		'thumbnail_width'                    => 0,
		'thumbnail_height'                   => 0,
		'thumbnail_crop'                     => false,
		'thumbnail_quality'                  => 90,
		'thumbnail_resize_gif'               => true
	);
	
	$instance = wp_parse_args( $instance, $defaults );

	if ( ! empty( $instance['thumbnail_url'] ) ) {
		
		$url_decoded = urldecode( $instance['thumbnail_url'] );
		$url_decoded_parse = parse_url( $url_decoded );
		
		if ( (string) $url_decoded_parse['host'] == (string) $_SERVER['SERVER_NAME'] ) {
			$wp_upload_dir = wp_upload_dir();
			$get_template_directory = get_template_directory();
			$get_template_directory_uri = get_template_directory_uri();
			
			$crop_in_url = '';
			$quality_in_url = '';
			
			// If crop value is true
			if ( $instance['thumbnail_crop'] === true ) {
				$crop_in_url .= '-c';
			}
			
			// If $instance['thumbnail_crop'] value is array
			if ( is_array( $instance['thumbnail_crop'] ) and count( $instance['thumbnail_crop'] ) == 2 ) {
				list( $x, $y ) = $instance['thumbnail_crop'];
				
				$crop_in_url .= '-c';
				
				if ( ! ( (string) $x == 'center' and (string) $y == 'center' ) ) {
					$crop_in_url .= '-' . (string) $x . '-' . (string) $y;
				}
			}
			
			if ( (int) $instance['thumbnail_width'] == 0 or (int) $instance['thumbnail_height'] == 0 ) {
				$crop_in_url = '';
			}
			
			// If change $instance['thumbnail_quality'] of the image (must be from 0 to 100)
			if ( (int) $instance['thumbnail_quality'] != 90 and (int) $instance['thumbnail_quality'] > 0 and (int) $instance['thumbnail_quality'] <= 100 ) {
				$quality_value = (int) $instance['thumbnail_quality'];
				
				$quality_in_url .= '-q' . $quality_value;
			} else {
				$quality_value = 90;
			}

			if ( stripos( $url_decoded, $wp_upload_dir['baseurl'] ) !== false ) {
				$image_file = str_replace( $wp_upload_dir['baseurl'], $wp_upload_dir['basedir'], $url_decoded );
			} else {
				$image_file = str_replace( $get_template_directory_uri, $get_template_directory, $url_decoded );
			}

			$image_path_info = pathinfo( $image_file );

			$image_file_no_extension = $image_path_info['dirname'] . '/' . $image_path_info['filename'] . '-' . (int) $instance['thumbnail_width'] . 'x' . (int) $instance['thumbnail_height'] . $crop_in_url . $quality_in_url;

			if ( ! empty( $image_path_info['extension'] ) ) {
				if ( (string) $image_path_info['extension'] == (string) 'gif' and ! (bool) $instance['thumbnail_resize_gif'] ) {
					$resize_the_image = false;
				} else {
					$resize_the_image = true;
				}
				
				if ( (bool) $resize_the_image ) {
					$image_file_resized = $image_file_no_extension . '.' . $image_path_info['extension'];

					$image_url_resized = str_replace( wp_basename( $url_decoded ), wp_basename( $image_file_resized ), $url_decoded );

					if ( ! file_exists( $image_file_resized ) ) {
						if ( file_exists( $image_file ) and function_exists( 'getimagesize' ) ) {
							$original_image_sizes = @getimagesize( $image_file );

							if ( (bool) $original_image_sizes != false ) {
								$orig_w = $original_image_sizes[0];
								$orig_h = $original_image_sizes[1];

								// If the original width and height are smaller than desired width and height
								if ( ( (int) $instance['thumbnail_width'] >= (int) $orig_w and (int) $instance['thumbnail_height'] >= (int) $orig_h ) or ( (int) $instance['thumbnail_width'] == 0 and (int) $instance['thumbnail_height'] == 0 ) ) {
									$additional_info_url = '';
									
									$image_resize_dimensions = true;
									
									$new_w = $orig_w;
									$new_h = $orig_h;
								} else {
									$image_resize_dimensions = image_resize_dimensions( (int) $orig_w, (int) $orig_h, (int) $instance['thumbnail_width'], (int) $instance['thumbnail_height'], $instance['thumbnail_crop'] );
									
									$new_w = $image_resize_dimensions[4];
									$new_h = $image_resize_dimensions[5];
			
									$additional_info_url = '-' . (int) $new_w . 'x' . (int) $new_h . $crop_in_url;
								}

								// If requirements fits
								if ( (bool) $image_resize_dimensions != false ) {
									$image_file_no_extension = $image_path_info['dirname'] . '/' . $image_path_info['filename'] . $additional_info_url . $quality_in_url;
									
									$image_file_resized = $image_file_no_extension . '.' . $image_path_info['extension'];
									
									$image_url_resized = str_replace( wp_basename( $url_decoded ), wp_basename( $image_file_resized ), $url_decoded );

									if ( ! file_exists( $image_file_resized ) ) {
										$image = wp_get_image_editor( $image_file );

										if ( ! is_wp_error( $image ) ) {
											$image->resize( (int) $new_w, (int) $new_h, $instance['thumbnail_crop'] );
											$image->set_quality( $quality_value );
											$image->save( $image_file_resized );

											$resize_post_thumbnails__image = array(
												'url'              => $image_url_resized,
												'width'            => (int) $new_w,
												'height'           => (int) $new_h,
												'crop'             => $instance['thumbnail_crop'],
												'original_url'     => $url_decoded,
												'is_local_file'    => true
											);

											$return = $resize_post_thumbnails__image;
										}
									} else {
									
										$resize_post_thumbnails__image = array(
											'url'              => $image_url_resized,
											'width'            => (int) $new_w,
											'height'           => (int) $new_h,
											'crop'             => $instance['thumbnail_crop'],
											'original_url'     => $url_decoded,
											'is_local_file'    => true
										);

										$return = $resize_post_thumbnails__image;
									}
								}
							}
						}
					} else {
						$resize_post_thumbnails__image = array(
							'url'              => $image_url_resized,
							'width'            => (int) $instance['thumbnail_width'],
							'height'           => (int) $instance['thumbnail_height'],
							'crop'             => $instance['thumbnail_crop'],
							'original_url'     => $url_decoded,
							'is_local_file'    => true
						);

						$return = $resize_post_thumbnails__image;
					}
				}
			}
		}
	}
	
	return $return;
}