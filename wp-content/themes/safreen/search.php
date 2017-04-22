<?php get_header(); ?>
 
 <!-- head select -->   
	
        <?php get_template_part('headers/part','headsingle'); ?>
<!-- / head select --> 

<div class="row ">
 
<div id="search_term">
<h2 class="postsearch"><?php printf( __( 'Search Results for: %s', 'safreen' ), '<span>' . esc_html( get_search_query() ) . '</span>'); ?></h2>
<div class="h-line"></div>
            
      <h5 class="search_count"><?php _e('Total posts found - ', 'safreen'); ?> <?php global $wp_query; echo $wp_query->found_posts; wp_reset_query(); ?></h5>
      <ul class="medium-6 medium-centered columns">
            <?php get_search_form(); ?>
                  </ul>  
            </div>
   
<!--Content-->
 
 <div class="lay1 wow fadeInup">
  					<?php if ( have_posts() ) : ?>
			
						<?php /* Start the Loop */ ?>
							<?php while ( have_posts() ) : the_post(); ?>
								<?php
								/*
							 	* Include the Post-Format-specific template for the content.
								 * If you want to override this in a child theme, then include a file
					 		 	* called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 			*/
								get_template_part( 'content', get_post_format() );
								?>

						 <?php endwhile; ?>

			 		<?php get_template_part('pagination'); ?>  

				<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>
			
		<?php endif; ?>
	</div><!--POST END-->
</div>
<?php get_footer(); ?>
