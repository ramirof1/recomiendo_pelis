<?php

/**
 * Add panels
 */


/* adding layout panel */
Kirki::add_panel( 'theme_options', array(
    'priority'    => 10,
    'title'       => esc_attr__( 'Theme Options', 'safreen' ),
    'description' => esc_attr__( 'This panel will provide all the options of the Theme.', 'safreen' ),
) );

?>