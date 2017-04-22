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


					<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>



						<div id=stickybox>


							<div class="movie-poster">
								<?php the_post_thumbnail(); ?>
							</div>

							<div class="posttitulo">
								<h1 id="titulosticky" class="stickytitle"><?php the_title(); ?></h1>
							</div>


							<div class="postcontent">
								<p class="textocontent"><?php the_content(); ?></p>
							</div>


							<div class="movie-trailer">
								<?php the_excerpt(); ?>
							</div>


						</div>

					<?php endwhile; ?>


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
