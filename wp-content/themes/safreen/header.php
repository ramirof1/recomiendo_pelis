<?php
/**
 * The Header template for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 */
?>

<!DOCTYPE html >
<html <?php language_attributes();?>>
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset');?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
<link rel="pingback" href="<?php bloginfo( 'pingback_url' );?>" />
<?php endif; ?>
	<?php wp_head();?>
<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
</head>

<body <?php body_class();?> id="top" >

 	<?php $safreen_body_preloder = get_theme_mod('safreen_body_preloder',1);?>


	<?php if( isset($safreen_body_preloder) && $safreen_body_preloder == 1 ):?>
  			<!-- Site Preloader -->
    		<div id="page-loader">
        		<div class="page-loader-inner">
            		<div class="loader"><strong><?php echo esc_html__('Loading', 'safreen'); ?></strong></div>
        		</div>
    		</div>
   <?php endif;?>
    <!-- END Site Preloader -->
<div id="wrapper">
