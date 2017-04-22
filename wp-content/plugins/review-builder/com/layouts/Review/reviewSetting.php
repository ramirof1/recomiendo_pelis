<?php
	$saveTables = get_option('SGRB_SAVE_TABLES');
	$checked = '';
	if ($saveTables) {
		$checked = ' checked';
	}
?>

<div class="sgrb-settings-wrapper">
	<h2>
		<?php _e('General settings', 'sgrb'); ?>
		<input type="button" class="sgrb-save-tables button-primary" value="Save changes">
	</h2>
	<form class="sgrb-js-settings-form">
		<div class="sg-row">
			<div class="sg-col-12">
				<p class="sgrb-review-setting-notice">Successfully saved.</p>
			</div>
		</div>
		<div class="sg-row">
			<div class="sg-col-12">
				<p><span>Save review data: </span>
				<input type="checkbox" name="saveFreeTables" value="1"<?=$checked?>></p>
			</div>
		</div>
		<div class="sg-row sgrb-setting-row">
			<div class="sg-col-12">
				<span class="sgrb-review-setting-contact">Got something to say? Need help?</span>
			</div>
			<div class="sg-col-12">
				<span class="sgrb-review-setting-contact">Contact Us <a href="mailto:wp-review@sygnoos.com" rel="nofollow">wp-review@sygnoos.com</a></span>
			</div>
		</div>
	</form>
</div>
