<?php
/**
 * Display upgrade notice on customizer page
 */
function safreen_upsell_notice() {
 // Enqueue the script
 wp_enqueue_script(
 'safreen-customizer-upsell',
 get_template_directory_uri() . '/js/upsell.js',
 array(), '1.0.0',
 true
 );
 // Localize the script
 wp_localize_script(
 'safreen-customizer-upsell',
 'safreenL10n',
 array(
 'safreenURL'	=> esc_url( 'http://www.imonthemes.com/safreen-pro/' ),
 'safreenLabel'	=> __( 'Upgrade to Pro', 'safreen' ),
 
 )
 );
}
add_action( 'customize_controls_enqueue_scripts', 'safreen_upsell_notice' );