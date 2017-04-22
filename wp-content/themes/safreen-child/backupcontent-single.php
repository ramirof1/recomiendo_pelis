
	 <!-- head select -->

        <?php get_template_part('headers/part','headsingle'); ?>

	<!-- / head select -->

	<div id="content" >
		<div class="row">
				<div class="large-9 columns <?php if ( !is_active_sidebar( 'sidebar' ) ){ ?> nosid <?php }?> ">

						<!--Content-->
   						<?php if(have_posts()): ?><?php while(have_posts()): ?><?php the_post(); ?>
                				<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">  </div>



									<div id="content" class=" content_blog blog_style_b1" role="main">
 										<?php if ( is_user_logged_in() || is_admin() ) { ?>
											<?php
											edit_post_link(
											sprintf(
											/* translators: %s: Name of current post */
											__( 'Edit<span class="screen-reader-text"> "%s"</span>', 'safreen' ),
											get_the_title()
											),
											'<span class="edit-link">',
											'</span>'
											);
											?>
										<?php } ?>

											<div class="title_area">
												<h1 class="wow fadeInup post_title"><?php the_title(); ?></h1>
											</div>



					<div class="post_content wow fadeIn">
						<p><?php the_content(); ?></p>
						 	<div class="post_wrap_n">
						<?php wp_link_pages( array(
								'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'safreen' ) . '</span>',
								'after'       => '</div>',
								'link_before' => '<span>',
								'link_after'  => '</span>',
								'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'safreen' ) . ' </span>%',
								'separator'   => '<span class="screen-reader-text">, </span>',
						) );?>

                         </div>
					</div>

                      <?php if( has_tag() ) { ?>
						<div class="post_info post_info_4 clearboth">
                        	<span class="post_tags">
								<span class="tags_label"><i class="fa fa-tag fa-lg"></i></span><?php } ?>
								<?php if( has_tag() ) { ?><a class="tag_link"><?php the_tags('','  '); ?></a>

							</span>
						</div>
					<?php } ?>

       		  <!--NEXT AND PREVIOUS POSTS START-->
       		  <div class="wp-pagenavi">
                    <?php previous_post_link( '<div class="alignleft">%link</div>', '&laquo; %title' ); ?>
                    <?php next_post_link( '<div class="alignright">%link</div>', '%title &raquo; ' ); ?>

                </div>
               <!--NEXT AND PREVIOUS POSTS END-->
  <?php endwhile ?>

    <!--POST END-->

    <!--COMMENT START-->
    	<a class="comments_template">
			<?php if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
			?>
        </a>
     <!--COMMENT END-->
<?php endif ?>

</div>

    </div>



  <div class=" wow fadeIn large-3 small-12 columns"><!--SIDEBAR START-->



	<?php get_sidebar();?>

</div><!--SIDEBAR END-->

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
