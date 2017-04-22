 <?php 
// Define all Variables.
$bnt_safreen = esc_html ( get_theme_mod('safreen_link_name1',esc_attr__('Know More','safreen')) );
$bnt2_safreen = esc_html ( get_theme_mod('safreen_link_name2',esc_attr__('Buy Theme','safreen')) );
$safreen_staticslider_uri1 = esc_url( get_theme_mod('safreen_staticslider_uri1') );
$safreen_staticslider_uri2 = esc_url( get_theme_mod('safreen_staticslider_uri2',esc_url('#','safreen') ));
$safreen_staticslider_image = esc_url( get_theme_mod('safreen_staticslider_image',esc_url(get_template_directory_uri().'/images/slider.jpg')) );
							?>
 <?php 
 $sticky_safreen = get_option( 'sticky_posts' );
 $safreen_num_post =  esc_attr(get_theme_mod ('Staticimage_post',esc_attr('Hello world!')));		
			$args_safreen = array(
					'post_type'=> 'post',
					'p' => $safreen_num_post,
					'posts_per_page'=>1,
					'post__not_in' => $sticky_safreen, 
					
				);

			$loop_safreen = new WP_Query($args_safreen);
          	wp_reset_postdata(); 
			    
				if($loop_safreen->have_posts()) : ?>
			  		<?php while($loop_safreen->have_posts()) : 
						$loop_safreen->the_post(); ?>
							<section class="masthead" style="background-image:url( <?php echo $safreen_staticslider_image ?>);" >
                    			<div class="masthead-overlay"></div>  
									<div class="masthead-arrow"></div>
					 					<div class="masthead-dsc">
                                        <div class="row">
											<h1><?php the_title(); ?></h1>
												<?php the_excerpt(); ?> 
		
        									<?php $safreen_Static_Sliderbutton = get_theme_mod('safreen_Static_Sliderbutton',1);?>
        							<?php if( isset($safreen_Static_Sliderbutton) && $safreen_Static_Sliderbutton == 1 ):?>
        								 <?php if( !empty($safreen_staticslider_uri1) ):?>
                                   					 <a href="<?php echo $safreen_staticslider_uri1 ?>" class='hvr-sweep-to-top'>  <?php echo $bnt_safreen ?>  </a>
                                    			<?php else:?>
                                     		<a href="<?php echo esc_url( get_permalink() ); ?>" class='hvr-sweep-to-top'>  <?php echo $bnt_safreen ?>  </a>
                                    	<?php endif;?>
                                    <?php endif;?>
                                <?php if( !empty($safreen_staticslider_uri2) ):?>
                                    <a href="<?php echo $safreen_staticslider_uri2 ?>" class='hvr-sweep-to-bottom-border'> <?php echo $bnt2_safreen ?></a>
                                <?php endif;?>
                                </div>
                         </div>
					</section>
			<?php endwhile; ?>
		<?php endif;?>