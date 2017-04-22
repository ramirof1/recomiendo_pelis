<?php

//Load the widgets




add_action('admin_enqueue_scripts', 'safreen_widget_scripts');

function safreen_widget_scripts() {    

	if( function_exists( 'wp_enqueue_media' ) ) {

	wp_enqueue_media();
	}
    wp_enqueue_script('safreen_widget_scripts', get_template_directory_uri() . '/js/widget.js', false, '1.0', true);
	wp_enqueue_style('safreen_widgets_custom_css', get_template_directory_uri() . '/css/safreen_widgets_custom_css.css');
	wp_enqueue_style( 'safreen_fontawesome_custom_css', get_template_directory_uri() . '/fonts/awesome/css/font-awesome.min.css' );
}


/*****************************************/
/******          WIDGETS     *************/
/*****************************************/

add_action('widgets_init', 'safreentheme_register_widgets');

function safreentheme_register_widgets() {    

		register_widget('safreen_serviceblock');
	
	$safreen_sidebars = array ( 'sidebar-serviceblock' => 'sidebar-serviceblock');
	
	/* Register sidebars */
	foreach ( $safreen_sidebars as $safreen_sidebar ):
	
		
			
			if( $safreen_sidebar == 'sidebar-serviceblock' ):
		
				$safreen_name = __('Service Block', 'safreen');
			
            endif;
	endforeach;
	}?>