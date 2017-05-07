<?php
/**
* The front page template file
*
* If the user has selected a static page for their homepage, this is what will
* appear.
* Learn more: http://codex.wordpress.org/Template_Hierarchy
*
* @package WordPress
* @subpackage Twenty_Seventeen
* @since 1.0
* @version 1.0
*/

get_header(); ?>

<?php
/*
Template Name: Business
*/
?>

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

		<?php $safreen_slider_enabel = get_theme_mod('safreen_slider_enabel',1);?>
		<?php if( isset($safreen_slider_enabel) && $safreen_slider_enabel == 1 ):?>
			<!--Slider-->
			<div id="slider">
				<?php get_template_part('parts/salider','post'); ?>
			</div> <!--Slider end-->
			<div class="clearfix"></div>
		<?php endif?>


		<!--Slider end-->
		<div class="clearfix"></div>
		<!-- block -->

		<?php if( get_option( 'show_on_front' ) == 'posts' ): ?>
			<div class="latest-post-safreen" id="latset-postsaf">
				<div class="row ">
					<?php $safreen_latest_blog =  get_theme_mod('safreen_latest_blog',__('Latest Post ','safreen'));?>


					<a id="showHere"></a>



					<div id=stickybox>

						<?php  /*FEATURED POSTS*/

						$args = array(
							'posts_per_page' => 1,
							'post__in'  => get_option( 'sticky_posts' ),
							'ignore_sticky_posts' => 1
						);
						$query = new WP_Query( $args );

						if ($query->have_posts());	while ($query->have_posts()) : $query->the_post(); ?>

						<div class="movie-poster">
							<?php the_post_thumbnail(); ?>
						</div>


						<div class="zonatitulo">
							<h1 id="titulosticky" class="stickytitle"><?php the_title(); ?></h1>
						</div>


						<div class="stickycontent">
							<p class="content"><?php the_content(); ?></p>
							</div>


							<?php if (in_category( 'netflix' )) :  ?>

														<div class="movie-trailer">

                                <?php the_excerpt() ?>


											 		  </div>

							<?php elseif (in_category( 'peliculas' )): ?>
							     <div class="movie-trailer">

										 <a href="#trailer">Trailer</a>




													<div class="modal" id="trailer" >

														<div class="modal-content">

															<a href="#">X</a>

														</div>
													</div>

									  </div>
							<?php endif; ?>


						<?php  endwhile;	?>

					</div>




					<div class="movie-trailer">
						<?php  /*the_excerpt();*/ ?>
					</div>


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
			</div>
		</div>





	<?php else: ?>

		<?php $safreen_service_checkbox = get_theme_mod('safreen_enable_serviceblock',1);?>
		<?php if( isset($safreen_service_checkbox) && $safreen_service_checkbox == 1 ):?>

			<?php get_template_part('parts/part','service-block'); ?>

			<div class="clearfix"></div>
		<?php endif?>


		<!-- Start Callout section -->

		<?php $safreen_welcome_enabel = get_theme_mod('safreen_welcome_enabel',1);?>
		<?php if( isset($safreen_welcome_enabel) && $safreen_welcome_enabel == 1 ):?>


			<?php get_template_part('parts/part-welcome','text'); ?>
			<!-- END #callout -->
			<div class="clearfix"></div>

		<?php endif?>
		<a id="showHere"></a>
		<!--About Us Section -->

		<?php $safreen_enable_aboutus = get_theme_mod('safreen_enable_aboutus',1);?>
		<?php if( isset($safreen_enable_aboutus) && $safreen_enable_aboutus == 1 ):?>
			<?php get_template_part('parts/part','about-us'); ?>
			<div class="clearfix"></div>
		<?php endif;?>


		<!--About Us Section -->

		<!--LATEST POSTS-->

		<?php $safreen_latstpst_checkbox = get_theme_mod('safreen_latstpst_checkbox',1);?>
		<?php if( isset($safreen_latstpst_checkbox) && $safreen_latstpst_checkbox == 1 ):?>

			<?php get_template_part('parts/part','layout'); ?>
			<div class="clearfix"></div>
		<?php endif;?>


		<!--LATEST POSTS END-->

		<!--our team Section -->

		<?php $safreen_ourteam_enable = get_theme_mod('safreen_enable_ourteam',1);
		if( isset($safreen_ourteam_enable) && $safreen_ourteam_enable == 1):?>
		<?php get_template_part('parts/part','our-team');?>
		<div class="clearfix"></div>
	<?php endif;?>

	<!--Our team Section -->

	<!--our client Section -->


	<?php get_template_part('parts/part','our-client'); ?>

	<!--Our client Section -->
<?php endif; ?>

<?php get_footer(); ?>
