<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


function resize_post_thumbnails__admin_panel_settings( $settings_options = array() ) {
	$resize_post_thumbnails           = resize_post_thumbnails();
	$settings_id                      = $resize_post_thumbnails->settings_id;
	
	$settings_options['general'] = array(
		'name'              => __( 'General', 'resize_post_thumbnails' ),
		'priority'          => 10,
		'submenus'          => array(
		
			'quick_start' => array(
				'name'              => __( 'Quick Start', 'resize_post_thumbnails' ),
				'priority'          => 10,
				'rows'              => array(

					'resize_post_thumbnails' => array(
						'description'   => __( 'Uncheck this if you want the plugin to not work.', 'resize_post_thumbnails' ),
						'priority'      => 20,
						'tags'          => array(
						
							array(
								'tag'             => 'h3',
								'text'            => __( 'Resize Post Thumbnails?', 'resize_post_thumbnails' )
							),
							array(
								'tag'             => 'input',
								'type'            => 'checkbox',
								'id'              => $settings_id . 'resize_post_thumbnails'
							)
						)
					),

				)
			),
			
			'info' => array(
				'name'               => __( 'Info', 'resize_post_thumbnails' ),
				'priority'           => 90,
				'rows'               => array(
				
					'view_information' => array(
						'description'          => __( 'View information about your site.', 'resize_post_thumbnails' ),
						'priority'             => 10,
						'tags'                 => array(
						
							array(
								'tag'                  => 'h3',
								'text'                 => __( 'Site information', 'resize_post_thumbnails' )
							),
							array(
								'tag'                  => 'textarea',
								'class'                => 'code-text',
								'readonly'             => 'readonly',
								'id'                   => $settings_id . 'site_info',
								'use_default_value'    => true
							)
						)
					)
				)
			)
		)
	);

	return $settings_options;
}