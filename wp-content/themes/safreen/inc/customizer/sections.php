<?php
/**
 * Add sections
 */
 
/* adding layout_front_page section*/
Kirki::add_section( 'layout_front_page', array(
    'title'          =>esc_attr__( 'General setting', 'safreen' ),
    'panel'          => 'theme_options', // Not typically needed.
    'priority'       => 1,
    
    
) );


Kirki::add_section( 'safreen_headtitle_settings', array(
    'title'          =>esc_attr__( 'Header and Title settings', 'safreen' ),
     'panel'          => 'theme_options', // Not typically needed.
    'priority'       => 1,
    
    
) );

Kirki::add_section( 'safreen_color_settings', array(
    'title'          =>esc_attr__( 'Color and Reorder settings', 'safreen' ),
     'panel'          => 'theme_options', // Not typically needed.
    'priority'       => 1,
    
    
) );





Kirki::add_section( 'slider_setup', array(
    'title'          => esc_attr__( 'Slider setup', 'safreen' ),
    'panel'          => 'theme_options', // Not typically needed.
    'priority'       => 2,
    
    
) );



Kirki::add_section( 'safreen_servicesetup', array(
    'title'          =>esc_attr__( 'Service block setup  ' , 'safreen'),
    'panel'          => 'theme_options', // Not typically needed.
    'priority'       => 4,
   
    
) );

Kirki::add_section( 'safreen_aboutus_setting', array(
    'title'          =>esc_attr__( 'About us setup  ' , 'safreen'),
    'panel'          => 'theme_options', // Not typically needed.
    'priority'       => 6,
   
    
) );

Kirki::add_section( 'safreen_ourteam_setting', array(
    'title'          =>esc_attr__( 'Our Team setup  ' , 'safreen'),
    'panel'          => 'theme_options', // Not typically needed.
    'priority'       => 8,
   
    
) );


Kirki::add_section( 'safreen_callout',array(
    'title'          => esc_attr__( 'Welcome Section', 'safreen' ),
    'panel'          => 'theme_options', // Not typically needed.
    'priority'       => 11,
    
    
) );

Kirki::add_section( 'safreen_typography', array(
    'title'          => esc_attr__( 'Typography settings', 'safreen' ),
    'panel'          => 'theme_options', // Not typically needed.
    'priority'       => 12,
    
    
) );



Kirki::add_section( 'safreen_social', array(
    'title'          => esc_attr__( 'social', 'safreen' ),
    'panel'          => 'theme_options', // Not typically needed.
    'priority'       => 13,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '', // Rarely needed.
) );


Kirki::add_section( 'safreen_mobile', array(
    'title'          => esc_attr__( 'Mobile Layout', 'safreen' ),
    'panel'          => 'theme_options', // Not typically needed.
    'priority'       => 14,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '', // Rarely needed.
) );