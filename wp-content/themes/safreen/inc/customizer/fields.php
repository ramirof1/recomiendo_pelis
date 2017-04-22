<?php

Kirki::add_config( 'safreen', array(
	'capability'  => 'edit_theme_options',
	'option_type' => 'theme_mod',
) );


/* adding layout_front_page_setting field */

Kirki::add_field( 'safreen', array(
    'type'        => 'toggle',
    'settings'    => 'safreen_body_preloder',
    'label'       => esc_attr__( 'Disable preloader', 'safreen' ),
    'section'     => 'layout_front_page',
    'default'     => '1',
    'priority'    => 10,
) );

Kirki::add_field( 'safreen', array(
    'type'        => 'toggle',
    'settings'    => 'safreen_sticky_menu',
    'label'       => esc_attr__( 'Disable sticky menu', 'safreen' ),
    'section'     => 'layout_front_page',
    'default'     => '0',
    'priority'    => 10,
) );




Kirki::add_field( 'safreen', array(
    'type'        => 'toggle',
    'settings'    => 'safreen_body_layout',
    'label'       => esc_attr__( 'Make website box layout', 'safreen' ),
    'section'     => 'layout_front_page',
    'default'     => '0',
    'priority'    => 10,
) );







Kirki::add_field( 'safreen', array(
    'type'        => 'radio-image',
    'settings'    => 'layout_select',
    'label'       => esc_attr__( 'Front Page post Layout', 'safreen' ),
    'description' => esc_attr__( 'Select a front page post layout', 'safreen' ),
    'section'     => 'layout_front_page',
    'default'     => 'layout1',
    'priority'    => 10,
    'choices'     => array(
        'layout1'   => get_template_directory_uri() . '/inc/images/layout1.png',
        'layout2' =>  get_template_directory_uri() . '/inc/images/layout2.png',
		
        
    ),
	
) );



Kirki::add_field( 'safreen', array(
    'type'        => 'text',
    'settings'    => 'safreen_latest_blog',
    'label'       => esc_attr__( 'Latest Blog Title', 'safreen' ),
    'section'     => 'layout_front_page',
    'default'     => esc_attr__( 'Latest Blog', 'safreen' ),
    'priority'    => 10,
	'transport' => 'postMessage',
	'js_vars'   => array(
        array(
            'element'  => '.text-center h2',
            'function' => 'html',
            'property' => '',
            
        ),
    ), 
) );



Kirki::add_field( 'safreen', array(
    'type'        => 'switch',
    'settings'    => 'safreen_latstpst_checkbox',
    'label'       => esc_attr__( 'Enable Latest Posts', 'safreen' ),
    'section'     => 'layout_front_page',
    'default'     => '1',
    'priority'    => 10,
    'choices'     => array(
        
        'off' => esc_attr__( 'off', 'safreen' ),
		'on'  =>esc_attr__ ( 'on', 'safreen' ),
    ),
) );

/* Footer section */


Kirki::add_field( 'safreen', array(
    'type'        => 'textarea',
    'settings'    => 'safreen_footertext',
    'label'       => esc_attr__( 'Footer Text.', 'safreen' ),
    'section'     => 'layout_front_page',
    'priority'    => 10,
	'transport' => 'postMessage',
	'js_vars'   => array(
        array(
            'element'  => '.copytext',
            'function' => 'html',
            'property' => '',
            
        ),
    ), 
	
    
) );


/* header & title */

Kirki::add_field( 'safreen', array(
    'type'        => 'toggle',
    'settings'    => 'safreen_header_checkbox',
    'label'       => esc_attr__( 'Disable Transparent Header ', 'safreen' ),
    'section'     => 'safreen_headtitle_settings',
    'default'     => '1',
    'priority'    => 10,
) );

		
		
/*  title typography */



Kirki::add_field( 'safreen', array(
    'type'     => 'select',
    'settings' => 'title_typography_font_family',
    'label'    => esc_attr__( 'Site title Typography', 'safreen' ),
    'section'  => 'safreen_headtitle_settings',
    'default'  => 'sans-serif',
    'priority' => 10,
    'choices'  => Kirki_Fonts::get_font_choices(),
    'output'   => array(
        array(
            'element'  => '#site-title a',
            'property' => 'font-family',
        ),
    ),
		
  'transport' => 'postMessage',
    'js_vars'   => array(
        array(
            'element'  => '#site-title a',
            'function' => 'css',
            'property' => 'font-family',
           
        ),
    ),
	) );
	
Kirki::add_field( 'safreen', array(
	'type'        => 'slider',
	'settings'    => 'title_typography_font_weight',
	'label'       => esc_attr__( 'Font Weight', 'safreen' ),
	'section'     => 'safreen_headtitle_settings',
	'default'     => 400,
	'priority' => 10,
	'choices'     => array(
		'min'  => '100',
		'max'  => '800',
		'step' => '100',
	),
	'output'   => array(
        array(
            'element'  => '#site-title a',
            'property' => 'font-weight',
        ),
    ),
	'transport' => 'postMessage',
	'js_vars'   => array(
        array(
            'element'  => '#site-title a',
            'function' => 'css',
            'property' => 'font-weight',
			'units'    => '',
           
        ),
    ),
) );

Kirki::add_field( 'safreen', array(
	'type'        => 'slider',
	'settings'    => 'title_typography_font_size',
	'label'       => esc_attr__( 'Font size', 'safreen' ),
	'section'     => 'safreen_headtitle_settings',
	'default'     => 48,
	 'priority'    => 10,
	'choices'     => array(
		'min'  => '12',
		'max'  => '200',
		'step' => '1',
	),
	'output'   => array(
        array(
            'element'  => '#site-title a',
            'property' => 'font-size',
			'units'    => 'px',
        ),
    ),
	'transport' => 'postMessage',
	'js_vars'   => array(
        array(
            'element'  => '#site-title a',
            'function' => 'css',
            'property' => 'font-size',
			'units'    => 'px',
           
        ),
    ),
) );

Kirki::add_field( 'safreen', array(
    'type'        => 'color',
    'settings'    => 'title_typography_font_color',
    'label'       => esc_attr__( 'Title  text color', 'safreen' ),
    'section'     => 'safreen_headtitle_settings',
    'default'     => '#ffffff',
    'priority'    => 10,
	
	'output' => array(
        array(
            'element'  => '#site-title a,.branding--clone #site-title a,.branding-single #site-title a,#site-title p',
			
            'property' => 'color',
            'units'    => '',
          ),
		),
       'transport' => 'postMessage',
    'js_vars'   => array(
        array(
            'element'  => '#site-title a,.branding--clone #site-title a,.branding-single #site-title a',
            'function' => 'css',
            'property' => 'color',
            
        ),
    ), 
	) );    
/*  menu typography */

Kirki::add_field( 'safreen', array(
    'type'     => 'select',
    'settings' => 'menu_typography_font_family',
    'label'    => esc_attr__( ' Menu Typography', 'safreen' ),
    'section'  => 'safreen_headtitle_settings',
    'default'  => 'sans-serif',
    'priority' => 10,
    'choices'  => Kirki_Fonts::get_font_choices(),
    'output'   => array(
        array(
            'element'  => '#navmenu li a,#navmenu ul li ul li a',
            'property' => 'font-family',
        ),
    ),
		
  'transport' => 'postMessage',
    'js_vars'   => array(
        array(
            'element'  => '#navmenu li a,#navmenu ul li ul li a',
            'function' => 'css',
            'property' => 'font-family',
           
        ),
    ),
	) );
	
Kirki::add_field( 'safreen', array(
	'type'        => 'slider',
	'settings'    => 'menu_typography_font_weight',
	'label'       => esc_attr__( 'Font Weight', 'safreen' ),
	'section'     => 'safreen_headtitle_settings',
	'default'     => 600,
	'choices'     => array(
		'min'  => '100',
		'max'  => '800',
		'step' => '100',
	),
	'output'   => array(
        array(
            'element'  => '#navmenu li a,#navmenu ul li ul li a',
            'property' => 'font-weight',
        ),
    ),
	'transport' => 'postMessage',
	'js_vars'   => array(
        array(
            'element'  => '#navmenu li a,#navmenu ul li ul li a',
            'function' => 'css',
            'property' => 'font-weight',
           
        ),
    ),
) );

Kirki::add_field( 'safreen', array(
	'type'        => 'slider',
	'settings'    => 'menu_typography_font_size',
	'label'       => esc_attr__( 'Font size', 'safreen' ),
	'section'     => 'safreen_headtitle_settings',
	'default'     => 14,
	'choices'     => array(
		'min'  => '12',
		'max'  => '200',
		'step' => '1',
	),
	'output'   => array(
        array(
            'element'  => '#navmenu li a,#navmenu ul li ul li a',
            'property' => 'font-size',
			'units'    => 'px',
        ),
    ),
	'transport' => 'postMessage',
	'js_vars'   => array(
        array(
            'element'  => '#navmenu li a,#navmenu ul li ul li a',
            'function' => 'css',
            'property' => 'font-size',
			'units'    => 'px',
           
        ),
    ),
) );

Kirki::add_field( 'safreen', array(
	'type'        => 'slider',
	'settings'    => 'menu_typography_font_space',
	'label'       => esc_attr__( 'letter-spacing', 'safreen' ),
	'section'     => 'safreen_headtitle_settings',
	'default'     => 0.8,
	'choices'     => array(
		'min'  => '0.5',
		'max'  => '20',
		'step' => '0.5',
	),
	'output'   => array(
        array(
            'element'  => '#navmenu li a,#navmenu ul li ul li a',
            'property' => 'letter-spacing',
			'units'    => 'px',
        ),
    ),
	'transport' => 'postMessage',
	'js_vars'   => array(
        array(
            'element'  => '#navmenu li a,#navmenu ul li ul li a',
            'function' => 'css',
            'property' => 'letter-spacing',
			'units'    => 'px',
           
        ),
    ),
) );

Kirki::add_field( 'safreen', array(
    'type'        => 'color',
    'settings'    => 'menu_typography_font_color',
    'label'       => esc_attr__( 'text color', 'safreen' ),
    'section'     => 'safreen_headtitle_settings',
    'default'     => '#ffffff',
    'priority'    => 10,
	
	'output' => array(
        array(
            'element'  => '#navmenu li a,#navmenu ul li ul li a,.branding--clone #navmenu li a,.branding--clone #navmenu ul li ul li a,.branding-single #navmenu li a,.branding-single #navmenu ul li ul li a,.home #navmenu ul li.current-menu-item > a',
			
            'property' => 'color',
            'units'    => '',
          ),
		),
       'transport' => 'postMessage',
    'js_vars'   => array(
        array(
            'element'  => '#navmenu li a,#navmenu ul li ul li a,.branding--clone #navmenu li a,.branding--clone #navmenu ul li ul li a,.branding-single #navmenu li a,.branding-single #navmenu ul li ul li a,.home #navmenu ul li.current-menu-item > a',
            'function' => 'css',
            'property' => 'color',
            
        ),
    ), 
	) );    


/*color and reorder */
Kirki::add_field( 'safreen', array(
    'type'        => 'custom',
    'settings'    => 'custom_demo2',
    'section'     => 'safreen_color_settings',
    'default'     => '<div style="padding: 20px;background-color:#D03232; color: #fff;">Reorder work in Pro version and have more color options </div>',
    'priority'    => 10,
) );

Kirki::add_field( 'safreen', array(
    'type'        => 'color',
    'settings'    => 'safreen_flavour_color',
    'label'       => esc_attr__( 'Flavour color', 'safreen' ),
    'section'     => 'safreen_color_settings',
    'default'     => '#36abfc',
    'priority'    => 10,
	
	'output' => array(
        array(
            'element'  => '.social-safreen i,.postitle_lay a,#footer .widgets .widgettitle, #midrow .widgets .widgettitle a,#sidebar .widgettitle, #sidebar .widgettitle a,#commentform a ,.feature-box i,#our-team-safreen h1,.comments-title, .post_content a,.node h1 a,#navmenu ul li.current-menu-item > a,#our-team-safreen .node h1 a,a',
			
            'property' => 'color',
            'units'    => '',
        ),
        array(
            'element'  => '#slider .hvr-sweep-to-top,.small-border,#navmenu .search-form .search-submit,.search-form .search-submit,#navmenu .search-form .search-submit,.search-form .search-submit',
            'property' => 'background-color',
            'units'    => '',
        ),
		 array(
            'element'  => '.h-line,.nivo-caption .h-line,.btn-slider-safreen',
            'property' => 'border-color',
            'units'    => '',
        ),
    )

) );



Kirki::add_field( 'safreen', array(
    'type'        => 'color',
    'settings'    => 'safreen_hover_color',
    'label'       => esc_attr__( 'Hover color', 'safreen' ),
    'section'     => 'safreen_color_settings',
    'default'     => '#D03232',
    'priority'    => 10,
	
	'output' => array(
        array(
            'element'  => '#navmenu li a:hover,.post_info a:hover,.comment-author vcard:hover',
			
            'property' => 'color',
            'units'    => '',
        ),
        array(
            'element'  => '#navmenu ul li ul li:hover,#navmenu ul > li ul li:hover,btn-slider-safreen:hover,btn-border-light:hover,#submit:hover, #searchsubmit:hover,#navmenu ul > li::after,.branding-single--clone #navmenu ul li ul:hover,#slider .hvr-sweep-to-bottom-border:before,#slider .hvr-sweep-to-top:before,.hvr-sweep-to-top:before,.box-container .bg-service-1 .hvr-sweep-to-top-border:before',
            'property' => 'background-color',
            'units'    => '',
        ),
		
		  array(
            'element'  => '#slider .hvr-sweep-to-bottom-border:hover,.hvr-sweep-to-bottom-border:hover,.box-container .bg-service-1 .hvr-sweep-to-top-border:hover',
            'property' => 'border-color',
            'units'    => '',
        ),
    )



) );

/* welcome section */

Kirki::add_field( 'safreen', array(
	'type'        => 'checkbox',
	'settings'    => 'safreen_welcome_enabel',
	'label'       => __( 'Remove Welcome section', 'safreen' ),
	'section'     => 'safreen_callout',
	'default'     => '1',
	'priority'    => 1,
) );

Kirki::add_field( 'safreen', array(
    'type'        => 'textarea',
    'settings'    => 'safreen_welcome',
    'label'       => esc_attr__( 'Home Page welcome section', 'safreen' ),
    'help'        => esc_attr__( 'This is a tooltip', 'safreen' ),
	'default'     => esc_attr__( 'Home Page welcome section', 'safreen' ),
    'section'     => 'safreen_callout',
    'priority'    => 1,
	'transport' => 'postMessage',
	'js_vars'   => array(
        array(
            'element'  => '#callout',
            'function' => 'html',
            'property' => '',
            
        ),
    ), 
	
) );

Kirki::add_field( 'safreen', array(
    'type'        => 'color',
    'settings'    => 'safreen_welcome_bg',
    'label'       => esc_attr__( 'change background color', 'safreen' ),
    'section'     => 'safreen_callout',
    'default'     => '#222533',
    'priority'    => 2,
	
	'output' => array(
        array(
            'element'  => '#callout',
			
            'property' => 'background-color',
            'units'    => '',
        ),
		),
		
		'transport' => 'postMessage',
    'js_vars'   => array(
        array(
            'element'  => '#callout',
            'function' => 'css',
            'property' => 'background-color',
            
        ),
    ), 
) );


Kirki::add_field( 'safreen', array(
    'type'        => 'color',
    'settings'    => 'safreen_welcome_text',
    'label'       => esc_attr__( 'change text color', 'safreen' ),
    'section'     => 'safreen_callout',
    'default'     => '#fffff',
    'priority'    => 2,
	
	
	'output' => array(
        array(
            'element'  => '#callout,#callout p ',
			
            'property' => 'color',
            'units'    => '',
        ),
		),
		
		'transport' => 'postMessage',
    'js_vars'   => array(
        array(
            'element'  => '#callout,#callout p',
            'function' => 'css',
            'property' => 'color',
            
        ),
    ), 
) );




Kirki::add_field( 'safreen', array(
    'type'        => 'slider',
    'settings'    => 'safreen_welcome_padding',
    'label'       => esc_attr__( 'Welcome section padding', 'safreen' ),
    'description' => esc_attr__( 'percentage', 'safreen' ),
        'section'     => 'safreen_callout',
    'default'     =>4,
    'priority'    => 3,
    'choices'     => array(
        'min'  => 1,
        'max'  => 20,
        'step' => 0.5
    ),
	'output' => array(
        array(
            'element'  => '#callout',
            'property' => 'padding',
            'units'    => '%',
        ),),
	'transport' => 'postMessage',
	'js_vars'   => array(
        array(
            'element'  => '#callout',
            'function' => 'css',
            'property' => 'padding',
			'units'    => '%',
            
        ),
    ), 
		
) );


/* Slider settings */



Kirki::add_field( 'safreen', array(
    'type'        => 'switch',
    'settings'    => 'safreen_slider_enabel',
    'label'       => esc_attr__( 'Enable/disabel Static slider', 'safreen' ),
    'section'     => 'slider_setup',
    'default'     => '1',
    'priority'    => 1,
    'choices'     => array(
        'off' => esc_attr__( 'off', 'safreen' ),
		'on'  =>esc_attr__ ( 'on', 'safreen' ),
    ),
) );



Kirki::add_field( 'safreen', array(
    'type'        => 'toggle',
    'settings'    => 'safreen_Static_Sliderpara',
    'label'       => esc_attr__( 'Make slider image half screen', 'safreen' ),
    'section'     => 'slider_setup',
    'default'     => '0',
    'priority'    => 1,
) );
 
Kirki::add_field('safreen', array(
                'type' => 'select',
                'settings' => 'Staticimage_post',
                'label' => esc_attr__('Choose Post For Static Slider', 'safreen'),
                'description' => esc_attr__('Choose for post Static image.', 'safreen'),
                'help' => '',
                'section' => 'slider_setup',
                'default' => 'Hello world!',
                'priority' => 1,
                'choices'     => Kirki_Helper::get_posts( array( 'posts_per_page' => -1, 'post_type' => 'post' ) ),
            ));
 
          



Kirki::add_field( 'safreen', array(
    'type'        => 'color',
    'settings'    => 'safreen_slider_textcolor',
    'label'       => esc_attr__( 'Slider title text color', 'safreen' ),
    'section'     => 'slider_setup',
    'default'     => '#ffffff',
    'priority'    => 1,
	
	'output' => array(
        array(
            'element'  => '#slider .masthead h1,.masthead .masthead-dsc p',
            'property' => 'color',
            'units'    => '',
        ),),
		'transport' => 'postMessage',
    'js_vars'   => array(
        array(
            'element'  => '#slider .masthead h1,.masthead .masthead-dsc p',
            'function' => 'css',
            'property' => 'color',
            
        ),
    ), 
		
) );


/* static slider settings */

	
	
	Kirki::add_field( 'safreen', array(
        'type'     => 'image',
        'settings'  => 'safreen_staticslider_image',
        'label'    => esc_attr__( 'Upload static slider image  ', 'safreen' ),
        'section'  => 'slider_setup',
		'default'     => get_template_directory_uri() . '/images/slider.jpg',
        'priority' => 1,
    ));	
	


Kirki::add_field( 'safreen', array(
	'type'        => 'slider',
	'settings'    => 'slider_title_font_size',
	'label'       => esc_attr__( 'Title Font size', 'safreen' ),
	'section'     => 'slider_setup',
	'default'     => 80,
	'priority' => 1,
	'choices'     => array(
		'min'  => '12',
		'max'  => '200',
		'step' => '1',
	),
	'output'   => array(
        array(
            'element'  => '.masthead h1',
            'property' => 'font-size',
			'units'    => 'px',
        ),
    ),
	'transport' => 'auto',
	
) );


	
Kirki::add_field( 'safreen', array(
	'type'        => 'slider',
	'settings'    => 'slider_tagline_font_size',
	'label'       => esc_attr__( 'Tagline Font size', 'safreen' ),
	'section'     => 'slider_setup',
	'default'     => 18,
	'priority' => 1,
	'choices'     => array(
		'min'  => '12',
		'max'  => '200',
		'step' => '1',
	),
	'output'   => array(
        array(
            'element'  => '.masthead .masthead-dsc p',
            'property' => 'font-size',
			'units'    => 'px',
        ),
    ),
	'transport' => 'auto',
	
) );

Kirki::add_field( 'safreen', array(
    'type'        => 'toggle',
    'settings'    => 'safreen_Static_Sliderbutton',
    'label'       => esc_attr__( 'Remove slider 1st button', 'safreen' ),
    'section'     => 'slider_setup',
    'default'     => '1',
    'priority'    => 1,
) );

	
Kirki::add_field( 'safreen', array(
        'type'     => 'text',
        'settings'  => 'safreen_link_name1',
        'label'    => esc_attr__( 'Slideer button 1st', 'safreen' ),
        'section'  => 'slider_setup',
        'default'  => esc_attr__( 'Know More', 'safreen' ),
        'priority' => 1,
		'transport' => 'postMessage',
	'js_vars'   => array(
        array(
            'element'  => '#slider .hvr-sweep-to-top',
            'function' => 'html',
            'property' => '',
            
        ),
    ), 
	
    ));	


Kirki::add_field( 'safreen', array(
        'type'     => 'text',
        'settings'  => 'safreen_staticslider_uri1',
        'label'    => esc_attr__( 'static slider link 1', 'safreen' ),
		'description'=> esc_attr__( 'If you leave url field blank. slider button link to single post page  ', 'safreen' ),
        'section'  => 'slider_setup',
        'priority' => 1,
		
    ));
	
Kirki::add_field( 'safreen', array(
        'type'     => 'text',
        'settings'  => 'safreen_link_name2',
        'label'    => esc_attr__( 'Slider button 2nd', 'safreen' ),
        'section'  => 'slider_setup',
        'default'  => esc_attr__( 'Try Now !', 'safreen' ),
        'priority' => 1,
		'transport' => 'postMessage',
	'js_vars'   => array(
        array(
            'element'  => '#slider .hvr-sweep-to-bottom-border',
            'function' => 'html',
            'property' => '',
            
        ),
    ), 
    ));	
	
Kirki::add_field( 'safreen', array(
        'type'     => 'text',
        'settings'  => 'safreen_staticslider_uri2',
		'description'=> esc_attr__( 'For remove 2nd button just leave url field blank  ', 'safreen' ),
        'label'    => esc_attr__( 'static slider link ', 'safreen' ),
		'default'  => esc_attr__( '#', 'safreen' ),
        'section'  => 'slider_setup',
        'priority' => 1,
		
    ));


	

	/* Service Block settings */
	
Kirki::add_field( 'safreen', array(
    'type'        => 'switch',
    'settings'    => 'safreen_enable_serviceblock',
    'label'       => esc_attr__( 'Enable service Blocks', 'safreen' ),
    'section'     => 'safreen_servicesetup',
    'default'     => '1',
    'priority'    => 10,
    'choices'     => array(
        'off' => esc_attr__( 'off', 'safreen' ),
		'on'  =>esc_attr__ ( 'on', 'safreen' ),
    ),
) );
	
	


Kirki::add_field( 'safreen', array(
    'type'        => 'color',
    'settings'    => 'safreen_service_text',
    'label'       => esc_attr__( 'Service Block text color', 'safreen' ),
    'section'     => 'safreen_servicesetup',
    'default'     => '#ffffff',
    'priority'    => 10,
	'transport' => 'postMessage',
	'output' => array(
        array(
            'element'  => '.box-container .bg-service-1  h2,.box-container .bg-service-1 p,.box-container .bg-service-1 .hvr-sweep-to-top-border ',
			
            'property' => 'color',
            'units'    => '',
        ),
		array(
            'element'  => '.box-container .bg-service-1 .hvr-sweep-to-top-border ',
			
            'property' => 'border-color',
            'units'    => '',
        ),
		),
		'js_vars'   => array(
        array(
            'element'  => '.box-container .bg-service-1  h2,.box-container .bg-service-1 p,.box-container .bg-service-1 .hvr-sweep-to-top-border ',
            'function' => 'css',
            'property' => 'color',
            
        ),
		array(
            'element'  => '.box-container .bg-service-1 .hvr-sweep-to-top-border ',
			 'function' => 'css',
            'property' => 'border-color',
            
        ),
    ), 
		
) );

/* About us settings */



/* social icon */
	
Kirki::add_field( 'safreen', array(
    'type'        => 'toggle',
    'settings'    => 'safreen_social1_checkbox',
    'label'       => esc_attr__( 'Show social Icon in header', 'safreen' ),
    'section'     => 'safreen_social',
    'default'     => '1',
    'priority'    => 1,
) );

Kirki::add_field( 'safreen', array(
    'type'        => 'toggle',
    'settings'    => 'safreen_social2_checkbox',
    'label'       => esc_attr__( 'Show social Icon in footer', 'safreen' ),
    'section'     => 'safreen_social',
    'default'     => '0',
    'priority'    => 1,
) );

Kirki::add_field( 'safreen', array(
    'type'        => 'toggle',
    'settings'    => 'safreen_search_box',
    'label'       => esc_attr__( 'Show search Icon in header', 'safreen' ),
    'section'     => 'safreen_social',
    'default'     => '0',
    'priority'    => 1,
) );


	
Kirki::add_field( 'safreen', array(
        'type'     => 'text',
        'settings'  => 'fbsoc_text_safreen',
        'label'    => esc_attr__( 'Facebook', 'safreen' ),
        'section'  => 'safreen_social',
        
        'priority' => 1,
    ));

Kirki::add_field( 'safreen', array(
        'type'     => 'text',
        'settings'  => 'ttsoc_text_safreen',
        'label'    => esc_attr__( 'Twitter', 'safreen' ),
        'section'  => 'safreen_social',
        'priority' => 2,
    ));
	
Kirki::add_field( 'safreen', array(
        'type'     => 'text',
        'settings'  => 'gpsoc_text_safreen',
        'label'    => esc_attr__( 'Google Plus', 'safreen' ),
        'section'  => 'safreen_social',
        'priority' => 3,
    ));
	
Kirki::add_field( 'safreen', array(
        'type'     => 'text',
        'settings'  => 'pinsoc_text_safreen',
        'label'    => esc_attr__( 'Pinterest', 'safreen' ),
        'section'  => 'safreen_social',
        'priority' => 4,
    ));
	
Kirki::add_field( 'safreen', array(
        'type'     => 'text',
        'settings'  => 'ytbsoc_text_safreen',
        'label'    => esc_attr__( 'YouTube', 'safreen' ),
        'section'  => 'safreen_social',
        'priority' => 5,
    ));


Kirki::add_field( 'safreen', array(
        'type'     => 'text',
        'settings'  => 'linsoc_text_safreen',
        'label'    => __( 'Linkedin', 'safreen' ),
        'section'  => 'safreen_social',
        'priority' => 6,
    ));
	
Kirki::add_field( 'safreen', array(
        'type'     => 'text',
        'settings'  => 'instagram_text_safreen',
        'label'    => __( 'Instagram', 'safreen' ),
        'section'  => 'safreen_social',
        'priority' => 7,
    ));
	
	
Kirki::add_field( 'safreen', array(
        'type'     => 'text',
        'settings'  => 'flisoc_text_safreen',
        'label'    => esc_attr__( 'Flickr', 'safreen' ),
        'section'  => 'safreen_social',
        'priority' => 8,
    ));
	
Kirki::add_field( 'safreen', array(
        'type'     => 'text',
        'settings'  => 'vimsoc_text_safreen',
        'label'    => esc_attr__( 'Vimeo', 'safreen' ),
        'section'  => '',
        'priority' => 9,
    ));
	
Kirki::add_field( 'safreen', array(
        'type'     => 'text',
        'settings'  => 'rsssoc_text_safreen',
        'label'    => esc_attr__( 'RSS', 'safreen' ),
        'section'  => 'safreen_social',
        'priority' => 10,
    ));


/* mobile layout settings */

Kirki::add_field( 'safreen', array(
    'type'        => 'toggle',
    'settings'    => 'safreen_mobile_slider',
    'label'       => esc_attr__( 'Disable slider in mobile ', 'safreen' ),
    'section'     => 'safreen_mobile',
    'default'     => '1',
    'priority'    => 10,
) );

Kirki::add_field( 'safreen', array(
    'type'        => 'toggle',
    'settings'    => 'safreen_mobile_sidebar',
    'label'       => esc_attr__( 'Disable sidebar in mobile ', 'safreen' ),
    'section'     => 'safreen_mobile',
    'default'     => '1',
    'priority'    => 10,
) );