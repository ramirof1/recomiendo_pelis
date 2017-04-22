<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 */
 
 $safreen_facebook = esc_url( get_theme_mod ('fbsoc_text_safreen'));
$safreen_twitter = esc_url( get_theme_mod ('ttsoc_text_safreen'));
$safreen_googleplus = esc_url( get_theme_mod ('gpsoc_text_safreen'));
$safreen_pinterest = esc_url( get_theme_mod ('pinsoc_text_safreen'));
$safreen_youtube = esc_url( get_theme_mod ('ytbsoc_text_safreen'));
$safreen_linkedin = esc_url( get_theme_mod ('linsoc_text_safreen'));
$safreen_vimeo = esc_url( get_theme_mod ('vimsoc_text_safreen'));
$safreen_flickr = esc_url( get_theme_mod ('flisoc_text_safreen'));
$safreen_rss = esc_url( get_theme_mod ('rsssoc_text_safreen'));
$safreen_instagram = esc_url( get_theme_mod ('instagram_text_safreen'));
?>
<!--FOOTER SIDEBAR-->
 <div id="footer">
    		<?php if ( is_active_sidebar( 'foot_sidebar' ) ) { ?>
    			<div class="widgets">
    				<div class="row">
     					<?php if ( is_active_sidebar('dynamic_sidebar') || !dynamic_sidebar('foot_sidebar') ) : ?><?php endif; ?>
            		</div>
   				</div>
   	
     		<?php } ?> 
 
	<!--COPYRIGHT TEXT-->
    			<div id="copyright">
    				<div class="row">
    					 <div class="copytext">
          				 	<?php
							$safreen_footertext = esc_attr(get_theme_mod('safreen_footertext'));
							$safreen_footertext = html_entity_decode(get_theme_mod ('safreen_footertext'));
							echo $safreen_footertext;
							?>
            
		    				<a target="_blank" href="<?php echo esc_url( 'http://www.imonthemes.com/'); ?>"><?php printf( __( 'Theme by %s', 'safreen' ), 'Imon Themes' ); ?></a>
              
            			</div>
            
           					 <?php $safreen_social2_checkbox = get_theme_mod('safreen_social2_checkbox',0);?>
							 <?php if( isset($safreen_social2_checkbox) && $safreen_social2_checkbox == 1 ):?>

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
 
    			</div>
    			<a href="#" class="scrollup" > <i class=" fa fa-angle-up fa-2x"></i></a>
    		</div>

	</div><!--#FOOTER end-->





