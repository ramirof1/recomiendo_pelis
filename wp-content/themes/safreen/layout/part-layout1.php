  <div class="lay1 wow fadeInup">
 			 
   			<?php  
  		
		
       		$args = array(
                     'post_type' => 'post',
                     'paged' => ( get_query_var('paged') ? get_query_var('paged') : 1),
                     );
      		$the_query = new WP_Query( $args );
   		?>
         <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
			
			
                  			<div <?php post_class(); ?> id="post-<?php the_ID(); ?>"> 
                      			<div class="matchhe post_warp large-3 medium-6 columns wow fadeInLeft page-delay   ">
              
                  					<div class="post_image">
                       				<!--CALL TO POST IMAGE-->
                             
                       					<?php  if ( has_post_thumbnail() != '' ) {
						        
									 		echo '<div class=" imgwrap">';
    
                                	 		echo '<a href="'; the_permalink(); echo '" >';
                                	 		the_post_thumbnail();
                                 	 		echo '</a>';
                                 	 		echo '</div>';
                                 		  } else {
    
                                			echo '<div class=" imgwrap">';
                                			echo '<a href="';  the_permalink(); echo '">';
     										echo '<img src="';
     										echo  esc_url(safreen_catch_that_image());
     										echo '" alt="" />';
     										echo '</a>';
    										echo '</div>';
    								};?>
						 		</div><!-- post image -->
                  
                  
                  					<div class=" post_content2">
                 						<div class=" post_content3">
                      
   								 			<?php the_title( sprintf( '<h2 class="postitle_lay"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
                       
                      						<p><?php the_excerpt(); ?></p> 
                      
                 					  </div> <!-- .post_content2 -->
                  				 </div><!-- post_content3 -->
   						</div><!-- columns -->
                   </div>
              
          <?php endwhile ?>
		  <?php wp_reset_postdata(); ?>
          
          <?php get_template_part('pagination'); ?>  
  </div><!-- lay-->
              
                    
       