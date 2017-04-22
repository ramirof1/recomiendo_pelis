

<div class="row">

<div class="large-9 columns <?php if ( !is_active_sidebar( 'sidebar' ) ){ ?> nosid <?php }?>">

<!--Content-->
 		<?php  
  		
		
       		$args = array(
                     'post_type' => 'post',
                     'paged' => ( get_query_var('paged') ? get_query_var('paged') : 1),
                     );
      		$the_query = new WP_Query( $args );
   		?>
               
               
                <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                   
                		<div <?php post_class(); ?> id="post-<?php the_ID(); ?>"> </div>
               
							<div  class="content_blog blog_style_b1" role="main">
								<article class="post_format_standard odd">
						                        
									<div class="title_area">
							
         								<?php the_title( sprintf( '<h1 class="post_title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>
									</div>
                        
										<div class="post_info post_info_2">
 											<span class="post_author"> <i class="fa fa-calendar"></i>
        										<a class="post_date"><?php the_time( get_option('date_format') ); ?></a>
        									</span>
                       							<span class="post_info_delimiter"></span>
												<span class="post_author"><i class="fa fa-user"></i> <a class="post_author" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php the_author(); ?></a></span>
												<span class="post_info_delimiter"></span>
                           						<?php if( has_category() ) { ?>
													<span class="post_categories">
													<span class="cats_label"><i class="fa fa-th-list"></i></span>
													<a class="cat_link"><?php the_category(' '); ?></a>
							
												</span>
							
											  <?php } ?>
                          						<div class="post_comments"><a><i class="fa fa-comments"></i><span class="comments_number"> <?php comments_popup_link( __('0 Comment', 'safreen'), __('1 Comment', 'safreen'), __('% Comments', 'safreen'), '', __('Off' , 'safreen')); ?> </span><span class="icon-comment"></span></a></div>  
                            
										</div>
                        
						
											<div class="post_content">
												<p><?php the_excerpt(); ?></p>
							
											</div>
												<div class="readmore">
													<a href="<?php echo esc_url(get_permalink());?>" rel="bookmark" class="more-link">
														<?php echo esc_attr__('Read more','safreen');?>
     												</a>
												</div>
													<div class="post_info post_info_3 clearboth">
                       
														<span class="post_tags">
															<?php if( has_tag() ) { ?><i class="fa fa-tag fa-lg"></i><?php } ?>
															<?php if( has_tag() ) { ?><a class="tag_link"><?php the_tags('','  '); ?></a>
								
														</span><?php } ?>
										</div>
									</article>
                    
                    					<div class="sep-20"><img  src="<?php echo esc_url (get_template_directory_uri(). '/images/sep-shadow.png');?>" /></div>
                    		</div>
  			<?php endwhile ?>
            
		<?php get_template_part('pagination'); ?>  
	</div>
	<div class=" wow fadeIn large-3 columns"> 
	<?php get_sidebar();?>
	</div>
</div>