<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Safreen
 */

get_header(); ?>
 <!-- head select -->

			<!-- head select -->

				<?php $safreen_slider_enabel = get_theme_mod('safreen_slider_enabel',1);?>
					<?php if( isset($safreen_slider_enabel) && $safreen_slider_enabel == 1 ):?>
      					 <?php $safreen_header_checkbox = get_theme_mod('safreen_header_checkbox',1);?>

							 <?php if( isset($safreen_header_checkbox) && $safreen_header_checkbox == 1){ ?>
       								<?php get_template_part('headers/part','head1'); ?>
							 <?php } ?>

 							<?php if( isset($safreen_header_checkbox) && $safreen_header_checkbox == 0){ ?>
       								<?php get_template_part('headers/part','headsingle'); ?>
 							<?php } ?>
 					   <?php else:?>
         				 <?php get_template_part('headers/part','headsingle'); ?>
 							<div class="clearfix"></div>
				<?php endif?>
			<!-- / head select -->
			<!-- / head select -->
 <?php $safreen_slider_enabel = get_theme_mod('safreen_slider_enabel',1);?>
    		<?php if( isset($safreen_slider_enabel) && $safreen_slider_enabel == 1 ):?>
 			<!--Slider-->
	  			<div id="slider">
  					<?php get_template_part('parts/salider','post'); ?>
 				 </div> <!--Slider end-->
   			<div class="clearfix"></div>
 			<?php endif?>

		<div class="latest-post-safreen" id="latset-postsaf">
			<div class="row ">
					<div id="safreen-latest">


 	<a id="showHere"></a>

		 	<div class="lay1 wow fadeInup">
  					<?php if ( have_posts() ) : ?>

						<?php /* Start the Loop */ ?>
							<?php while ( have_posts() ) : the_post(); ?>

							<?php the_title(); ?><br>


						 <?php endwhile; ?>

			 		<?php get_template_part('pagination'); ?>

				<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>
	</div><!--POST END-->
 </div>
 </div>
</div>


<?php get_footer(); ?>
