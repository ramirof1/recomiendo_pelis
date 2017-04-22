
<?php 
/* About Us section part */ 
?>

<?php if ( is_active_sidebar( 'sidebar-aboutus' ) ) :?>
		<section id="section-features">

				<?php dynamic_sidebar( 'sidebar-aboutus' );?>
		</section>

<?php endif;?>