<?php

/**
 * The configuration options for Kirki.
 * Change the assets URL for kirki so the customizer styles & scripts are properly loaded.
 */
function safreen_customizer_config( $args ) {

	$args['url_path'] = get_template_directory_uri() . '/inc/kirki/';
	
	return $args;
	
	 	
	
	

}


					
	
add_filter( 'kirki/config', 'safreen_customizer_config' );

function safreen_kirki_i10n( $config ) {

    $config['Kirki_l10n'] = array(
        'background-color'      => __( 'Background Color', 'safreen' ),
        'background-image'      => __( 'Background Image', 'safreen' ),
        'no-repeat'             => __( 'No Repeat', 'safreen' ),
        'repeat-all'            => __( 'Repeat All', 'safreen' ),
        'repeat-x'              => __( 'Repeat Horizontally', 'safreen' ),
        'repeat-y'              => __( 'Repeat Vertically', 'safreen' ),
        'inherit'               => __( 'Inherit', 'safreen' ),
        'background-repeat'     => __( 'Background Repeat', 'safreen' ),
        'cover'                 => __( 'Cover', 'safreen' ),
        'contain'               => __( 'Contain', 'safreen' ),
        'background-size'       => __( 'Background Size', 'safreen' ),
        'fixed'                 => __( 'Fixed', 'safreen' ),
        'scroll'                => __( 'Scroll', 'safreen' ),
        'background-attachment' => __( 'Background Attachment', 'safreen' ),
        'left-top'              => __( 'Left Top', 'safreen' ),
        'left-center'           => __( 'Left Center', 'safreen' ),
        'left-bottom'           => __( 'Left Bottom', 'safreen' ),
        'right-top'             => __( 'Right Top', 'safreen' ),
        'right-center'          => __( 'Right Center', 'safreen' ),
        'right-bottom'          => __( 'Right Bottom', 'safreen' ),
        'center-top'            => __( 'Center Top', 'safreen' ),
        'center-center'         => __( 'Center Center', 'safreen' ),
        'center-bottom'         => __( 'Center Bottom', 'safreen' ),
        'background-position'   => __( 'Background Position', 'safreen' ),
        'background-opacity'    => __( 'Background Opacity', 'safreen' ),
        'ON'                    => __( 'ON', 'safreen' ),
        'OFF'                   => __( 'OFF', 'safreen' ),
        'all'                   => __( 'All', 'safreen' ),
        'cyrillic'              => __( 'Cyrillic', 'safreen' ),
        'cyrillic-ext'          => __( 'Cyrillic Extended', 'safreen' ),
        'devanagari'            => __( 'Devanagari', 'safreen' ),
        'greek'                 => __( 'Greek', 'safreen' ),
        'greek-ext'             => __( 'Greek Extended', 'safreen' ),
        'khmer'                 => __( 'Khmer', 'safreen' ),
        'latin'                 => __( 'Latin', 'safreen' ),
        'latin-ext'             => __( 'Latin Extended', 'safreen' ),
        'vietnamese'            => __( 'Vietnamese', 'safreen' ),
        'serif'                 => _x( 'Serif', 'font style', 'safreen' ),
        'sans-serif'            => _x( 'Sans Serif', 'font style', 'safreen' ),
        'monospace'             => _x( 'Monospace', 'font style', 'safreen' ),
		
		
    );

    return $config;

}
add_filter( 'kirki/config', 'safreen_kirki_i10n' );

