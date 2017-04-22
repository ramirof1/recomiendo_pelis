  <div class="lay1 wow fadeInup">
 			 
   			<?php  
  		
		
       		$args = array(
                     'post_type' => 'post',
                     'paged' => ( get_query_var('paged') ? get_query_var('paged') : 1),
                     );
      		$the_query = new WP_Query( $args );
   		?>
        <?php if($the_query->have_posts()): ?><?php while($the_query->have_posts()): ?><?php $the_query->the_post(); ?>
        
			
			
                  			<div <?php post_class(); ?> id="post-<?php the_ID(); ?>"> 
                      			           			
              					<div class="matchhe post_warp large-4 medium-6 columns  ">
                   					<div class="single_latest_news">
              
                  						<div class="latest_news_image">
                          					<!--CALL TO POST IMAGE-->
                             
                       						<?php  if ( get_the_post_thumbnail() != '' ) {
						        				echo '<a href="';esc_url( get_permalink()); echo '" >';
							         			the_post_thumbnail();
									  			echo '</a>';
									
                                 				} else {
    							 				echo '<a href="';esc_url( get_permalink()); echo '" >';
                         						echo '<img src="';
     											echo  esc_url (safreen_catch_that_image());
     											echo '" alt="" />';
								 				echo '</a>';
     							
    							
    										};?>
                     		</div><!--end POST IMAGE-->
                  
                  
                  				<div class=" latest_news_desc">
               						<?php the_title( sprintf( '<h2><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
                      				<h4><?php the_author(); ?> | <?php the_time( get_option('date_format') ); ?></h4>
             						<p><?php the_excerpt(); ?></p> 
             						<a class="read_more" href="<?php echo esc_url(get_permalink());?>"><?php echo esc_attr__('Read more','marvel');?></a>
                   			  </div><!-- latest_news_desc-->
                 		</div>
               		</div>
          
                   </div>
              
          <?php endwhile ?>
		  <?php wp_reset_postdata(); ?>
          
         
          
            <?php endif ?>
          <?php get_template_part('pagination'); ?>   
  </div><!-- lay-->
              
                    
       