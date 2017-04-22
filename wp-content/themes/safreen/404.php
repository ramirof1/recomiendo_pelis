<?php 
/**
 * The template for displaying 404 pages (Not Found)
 *
 * @package Imonthemes
 * @subpackage safreen
 *
 */

get_header(); ?>
<!-- head select -->   
	
        <?php get_template_part('headers/part','headsingle'); ?>
<!-- / head select --> 
<div class="row">
	<div class="large-12">
	
		<div class="error1">
			<h1 class="error2"><?php _e( '404', 'safreen' ); ?></h1>
            <h4><?php _e( 'Page not found!', 'safreen' ); ?></h4>
			 <a> <?php _e( "Can't find what you need? Take a moment and do a search below!", 'safreen' ); ?></a>
             <div id="error3">
             <?php get_search_form(); ?></div>
             
				<br /><br /><br />
              
			<a class="gray_btn" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'Or Return home?', 'safreen' ); ?></a>
		</div>	
		
	</div>
    
  </div>

<?php get_footer(); ?>