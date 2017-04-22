<?php 
/**
 *
 * Displays The Author page template
 *
 * @package safreen
 * 
 */
?>

<?php get_header(); ?>
	 <!-- head select -->   
	
        <?php get_template_part('headers/part','headsingle'); ?>
        <div id="sub_banner">
 <h1>
	<?php
		/**
		 * Filter the Safreen author bio avatar size.
		 *
		 * @since Safreen
		 *
		 * @param int $size The avatar height and width size in pixels.
		 */
		$author_bio_avatar_size = apply_filters( 'safreen_author_bio_avatar_size', 42 );

		echo get_avatar( get_the_author_meta( 'user_email' ), $author_bio_avatar_size );
	?>
   <?php _e('Posts by ', 'safreen');?><?php echo get_the_author(); ?>
        
    <!--AUTHOR BIO END-->
</h1>
<div class='h-line'></div>

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
								get_template_part( 'contents', get_post_format() );
								?>

						 <?php endwhile; ?>

			 		<?php get_template_part('pagination'); ?>  

				<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>
			
		<?php endif; ?>
	</div><!--POST END-->
</div>
<?php get_footer(); ?>