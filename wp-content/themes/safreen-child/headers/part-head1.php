<?php
$safreen_facebook =  get_theme_mod ('fbsoc_text_safreen');
$safreen_twitter =  get_theme_mod ('ttsoc_text_safreen');
$safreen_googleplus = get_theme_mod ('gpsoc_text_safreen');
$safreen_pinterest = get_theme_mod ('pinsoc_text_safreen');
$safreen_youtube =  get_theme_mod ('ytbsoc_text_safreen');
$safreen_linkedin =  get_theme_mod ('linsoc_text_safreen');
$safreen_vimeo =  get_theme_mod ('vimsoc_text_safreen');
$safreen_flickr =  get_theme_mod ('flisoc_text_safreen');
$safreen_rss = get_theme_mod ('rsssoc_text_safreen');
$safreen_instagram = get_theme_mod ('instagram_text_safreen');

?> 

<div class="branding">

 <div class="row">
 
 <?php if (  is_front_page() || is_home() ) { ?>
    	<!--LOGO START-->
   <div id="site-title">
        <?php if ( function_exists( 'has_custom_logo' ) && has_custom_logo() ) : ?>
        	<?php safreen_the_custom_logo(); ?>
       		 <?php else : ?>
   				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
	    	
        	<?php
                $description = get_bloginfo( 'description', 'display' );
                if ( $description || is_customize_preview() ) : ?>
                    <p class="site-description"><?php echo $description; /* WPCS: xss ok. */ ?></p>
            <?php endif; ?>
         <?php endif;?>
    </div><!--site-title END-->
    
 <?php } ?>
         <!--LOGO END-->
   <div id="menu_wrap">
    <?php $safreen_search_checkbox = get_theme_mod('safreen_search_box',0);?>
<?php if( isset($safreen_search_checkbox) && $safreen_search_checkbox == 1):?>
	<div class="social-safreen">
        <a><i class="fa fa-search"></i></a>
     </div>
<?php endif; ?>

       <?php $safreen_social1_checkbox = get_theme_mod('safreen_social1_checkbox',1);?>
 <?php if( isset($safreen_social1_checkbox) && $safreen_social1_checkbox == 1):?>

             <!--social-->    
         <div class="social-safreen">
	            
				<?php if( !empty($safreen_facebook) ):?>

         <a  href="<?php echo esc_url($safreen_facebook);?>" target="_blank" title="<?php echo esc_attr__('facebook','safreen');?>"><i class="fa fa-facebook "></i></a><?php endif;?>
         
                <?php if( !empty($safreen_twitter) ):?>
               <a  href="<?php echo esc_url($safreen_twitter);?>" target="_blank" title="<?php echo esc_attr__('twitter','safreen');?>"><i class="fa fa-twitter"></i></a><?php endif; ?>
                
                 <?php if( !empty($safreen_googleplus) ):?>
                <a href="<?php echo esc_url($safreen_googleplus);?>" title=" <?php echo esc_attr__('Google Plus','safreen');?>" target="_blank"> <i class="fa fa-google-plus"></i></a><?php endif;?>
                
                 <?php if( !empty($safreen_pinterest) ):?>
                <a href="<?php echo esc_url($safreen_pinterest);?>" title=" Pinterest" target="_blank"><i class="fa fa-pinterest-p"></i> </a><?php endif; ?>

                 <?php if( !empty($safreen_youtube) ):?>
                <a href="<?php echo esc_url($safreen_youtube);?>" title=" <?php echo esc_attr__('Youtube','safreen');?>" target="_blank"> <i class="fa fa-youtube"></i></a><?php endif; ?>
                
                <?php if( !empty($safreen_linkedin) ):?>
               <a href="<?php echo esc_url($safreen_linkedin);?>" title="<?php echo esc_attr__(' linkedin','safreen');?>" target="_blank"> <i class="fa fa-linkedin"></i></a><?php endif; ?>
                
                <?php if( !empty($safreen_vimeo) ):?>
                <a href="<?php echo esc_url($safreen_vimeo);?>" title=" <?php echo esc_attr__(' Vimeo','safreen');?>" target="_blank"> <i class="fa fa-vimeo"></i></a><?php endif; ?>                
                 <?php if( !empty($safreen_flickr) ):?>
                 <a href="<?php echo esc_url($safreen_flickr);?>" title=" <?php echo esc_attr__(' flickr','safreen');?>" target="_blank"> <i class="fa fa-flickr"></i></a><?php endif; ?>                
                
                 <?php if( !empty($safreen_rss) ):?>
                 <a href="<?php echo esc_url($safreen_rss);?>" title="<?php echo esc_attr__(' rss','safreen');?>" target="_blank"> <i class="fa fa-rss"></i></a><?php endif; ?>                          
                
                <?php if( !empty($safreen_instagram) ):?>
                 <a href="<?php echo esc_url($safreen_instagram);?>" title="<?php echo esc_attr__(' instagram','safreen');?>" target="_blank"> <i class="fa fa-instagram"></i></a><?php endif; ?>
           </div>
 <?php endif?>
       
        <!--MENU STARTS-->
       <h3 class="menu-toggle"><?php _e( 'Menu', 'safreen' ); ?></h3>
       
     <div id="navmenu">
        
 		<?php 
		wp_nav_menu( array( 
		
		  'container_class' => 'menu-header', 
		  'theme_location' => 'primary' 
		  ) ); 
		 
		 ?> 
         <?php get_search_form(); ?>
        
       </div><!--navmenu END-->
     </div><!--menu_wrap END-->
    </div><!--row END-->
  </div><!--branding END-->
         
             <!--MENU END-->
