<?php 
/**
 * The template for displaying Category pages
 * Imonthemes
 */
get_header(); ?>
<!-- head select -->   
	
 <?php get_template_part('headers/part','headsingle'); ?>
<!-- / head select --> 
<div id="sub_banner">
<h1>
  <?php printf( __( ' %s', 'safreen' ), single_cat_title( '', false ) ); ?>
</h1>
</div>
<div class="row">
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