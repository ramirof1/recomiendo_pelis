<?php get_header(); ?>

<?php get_header('shop');?>
	 <!-- head select -->   
	
        <?php get_template_part('headers/part','headsingle'); ?>
<!-- / head select --> 

<div class="row">
 <div class="woocomerce">

  <ul class="breadcrumbs">
  <?php woocommerce_breadcrumb(); ?>
   
    </li>
  </ul>
</nav>

<?php woocommerce_content(); ?>

</div></div>

<?php get_footer(); ?>