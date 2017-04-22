<?php
if (get_option(SG_REVIEW_BANNER)) {
	require_once('banner.php');
}
?>

<div class="wrap">
	<h2>
		<?php _e('Reviews', 'sgrb'); ?>
		<a href="<?php echo @esc_attr($createNewUrl);?>" class="page-title-action add-new-h2"><?php echo _e('Add new', 'sgrb'); ?></a>
	
		 <?php if (!SGRB_PRO_VERSION) : ?>
	    	<input type="button" value="Upgrade to PRO version" onclick="window.open('<?php echo SGRB_PRO_URL;?>')" style="float:right;background-color: #d54e21;border: 1px solid #d54e21;color:white;cursor:pointer;">
	    <?php endif;?>
    </h2>
	<?php echo @esc_attr($review); ?>
</div>