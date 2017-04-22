<div class="box-container">

			<?php if ( is_active_sidebar( 'sidebar-serviceblock' ) ) :?>
			<?php	dynamic_sidebar( 'sidebar-serviceblock' );?>
            
            <?php else :?>
            
            <?php the_widget( 'safreen_serviceblock','cat=sample-page&link='.esc_url ( '#' ).'&link_name='.esc_attr__('Modern Design','safreen').'&image_uri='.esc_url(get_template_directory_uri()."/images/bg_service_1.jpg") , array('before_widget' => '', 'after_widget' => '') );?>
           
           <?php the_widget( 'safreen_serviceblock','cat=sample-page&link='.esc_url ( '#' ).'&link_name='.esc_attr__('High Quality','safreen').'&image_uri='.esc_url(get_template_directory_uri()."/images/bg_service_3.jpg") , array('before_widget' => '', 'after_widget' => '') );?>
               
                             <?php the_widget( 'safreen_serviceblock','cat=	sample-page&link='.esc_url ( '#' ).'&link_name='.esc_attr__('Ultra Responsive','safreen').'&image_uri='.esc_url(get_template_directory_uri()."/images/bg_service_2.jpg") , array('before_widget' => '', 'after_widget' => '') );?>

		<?php endif;?>
				
			 
 </div>

