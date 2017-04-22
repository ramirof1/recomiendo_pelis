<div class="latest-post-safreen" id="latset-postsaf">
<div class="row "> 


<?php $safreen_latest_blog =  get_theme_mod('safreen_latest_blog',__('Latest Post ','safreen'));?>
<div id="safreen-latest">
<div class="text-center">
                            <h2 class="wow fadeIn" ><?php if( !empty($safreen_latest_blog) ):?>
                            
                            <?php echo esc_html( $safreen_latest_blog); ?>
                            
                            <?php endif;?>
                     

                            </h2>
                            <div  class="small-border wow flipInY" ></div>
                        </div>
</div>	
<?php if( get_theme_mod( 'layout_select' )){ ?>

<?php $template_parts_safreen = get_theme_mod( 'layout_select', array( 'layout1', 'layout2' ) );
        get_template_part('layout/part',''.$template_parts_safreen .''); ?>
   
  <?php }else{ ?>
 
 <?php get_template_part('layout/part','layout1'); ?>
        <?php } ?>  	
 </div></div>