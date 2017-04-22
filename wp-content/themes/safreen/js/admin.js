jQuery(document).ready(function() {

	
	/* Move  widgets */
	
	wp.customize.section( 'sidebar-widgets-sidebar-serviceblock' ).panel( 'theme_options' );
	wp.customize.section( 'sidebar-widgets-sidebar-serviceblock' ).priority( '5' );
	wp.customize.section( 'sidebar-widgets-sidebar-aboutus' ).panel( 'theme_options' );
	wp.customize.section( 'sidebar-widgets-sidebar-aboutus' ).priority( '7' );
	
	wp.customize.section( 'sidebar-widgets-sidebar-ourteam' ).panel( 'theme_options' );
	wp.customize.section( 'sidebar-widgets-sidebar-ourteam' ).priority( '9' );
	wp.customize.section( 'sidebar-widgets-sidebar-ourclient' ).panel( 'theme_options' );
	wp.customize.section( 'sidebar-widgets-sidebar-ourclient' ).priority( '10' );


	
	

	
});


