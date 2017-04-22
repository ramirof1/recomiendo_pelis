<div class="sg-banner-wrapper updated notice notice-success is-dismissible below-h2">
	<p>If You like our plugin, please, leave us a review.
		<a class="sg-banner-hover-js" href="https://wordpress.org/support/view/plugin-reviews/review-builder" target="_blank" onclick="SGMainHelper.prototype.ajaxCloseBanner()"> Leave review</a>
	</p>
	<p>No, thanks, I don't want to do it.
		<a class="sg-banner-close-js sg-banner-hover-js" href="javascript:void(0)"> Close this message and never show me again</a>
	</p>
</div>
<script type="text/javascript">
	var sgBanner = jQuery('.sg-banner-hover-js');
	sgBanner.hover(
		function(){
			jQuery(this).parent().attr('style','color: #38abe2 !important');
		},
		function(){
			jQuery(this).parent().removeAttr('style');
		}
	);
</script>
