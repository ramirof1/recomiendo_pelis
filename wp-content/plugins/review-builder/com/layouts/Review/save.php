<?php
	global $sgrb;
	$sgrb->includeStyle('page/styles/general/jquery-ui-dialog');
	$sgrb->includeScript('core/scripts/jquery-ui-dialog');
	$allTags = get_tags();
	$allTerms = get_categories();
?>
<div class="wrap">
	<form class="sgrb-js-form">
		<div class="sgrb-top-bar">
			<h1 class="sgrb-add-edit-title">
				<?php echo (@$sgrbRev->getId() != 0) ? _e('Edit Review', 'sgrb') : _e('Add New Review', 'sgrb');?>
				<span class="sgrb-spinner-save-button-wrapper">
					<i class="sgrb-loading-spinner"><img src='<?php echo $sgrb->app_url.'/assets/page/img/spinner-2x.gif';?>'></i>
					<a href="javascript:void(0)"
						class="sgrb-review-js-update button-primary sgrb-pull-right"> <?php _e('Save changes', 'sgrb'); ?></a>
				</span>
			</h1>
			<input class="sgrb-text-input sgrb-title-input" value="<?php echo esc_attr(@$sgrbDataArray['title']); ?>"
					type="text" autofocus name="sgrb-title" placeholder="<?php _e('Enter title here', 'sgrb'); ?>">
			<div class="sgrb-template-box">
				<strong><?php _e('Template: ', 'sgrb'); ?></strong><span id="sgrb-template-name"><?php echo isset($sgrbDataArray['template']) ? esc_attr($sgrbDataArray['template']) : 'full_width'; ?></span>
				<input  class="sgrb-template-selector button-small button" type="button" value="<?php _e('Select template', 'sgrb')?>"/>
			</div>
		</div>
		<input type="hidden" name="sgrb-id" value="<?php echo esc_attr(@$_GET['id']); ?>">
		<input type="hidden" name="sgrb-template" value="<?php echo isset($sgrbDataArray['template']) ? esc_attr($sgrbDataArray['template']) : 'full_width'; ?>">
		<input class="sgrb-link" type="hidden" data-href="<?php echo esc_attr($sgrb->app_url);?>">
		<div class="sgrb-main-container">
			<div class="sg-row">
				<div class="sg-col-8">
					<div class="sg-box sgrb-template-post-box"<?php echo ((@$sgrbDataArray['template'] == 'post_review') || (@$sgrbDataArray['template'] == 'woo_review')) ? ' style="min-height:150px;"' : '';?>>
						<div class="sg-box-title"><?php echo _e('General', 'sgrb');?></div>
						<div class="sg-box-content">
							<div class="sgrb-main-template-wrapper"><?php echo (@$sgrbDataArray['template'] != 'post_review') ? @$res : '';?></div>
<!-- Post review template -->
<?php if (SGRB_PRO_VERSION) :?>
							<div class="sgrb-post-template-wrapper"<?php echo (@$sgrbDataArray['template'] == 'post_review') ? '' : ' style="display:none;"';?>>
								<div class="sg-row">
									<div class="sg-col-4">
										<span><?php echo _e('Select post category to show current review:', 'sgrb');?></span>
									</div>
									<div class="sg-col-8">
										<select class="sgrb-post-category" name="post-category">
											<?php foreach ($allTerms as $postCategory) :?>
												<option value="<?php echo $postCategory->term_id;?>"<?php echo (@$sgrbDataArray['post-category'] == $postCategory->term_id) ? ' selected': '';?>><?php echo $postCategory->name?></option>
											<?php endforeach;?>
										</select>
									</div>
								</div>
								<div class="sg-row">
									<div class="sg-col-6">
										<label for="sgrb-disable-wp-comments">
											<input id="sgrb-disable-wp-comments" value="true" type="checkbox" name="disableWPcomments"<?php echo (@$sgrbDataArray['disableWPcomments']) ? ' checked' : '';?>> <?php echo _e('Disable Wordpress default comments', 'sgrb');?>
										</label>
									</div>
								</div>
								<div class="sg-row">
									<div class="sg-col-6">
										<p class="sgrb-type-warning"><?php echo _e('This review will only be shown in posts with selected category', 'sgrb');?></p>
									</div>
								</div>
							</div>
<!-- end-->

<!-- WooCommerce review template -->
							<div class="sgrb-woo-template-wrapper"<?php echo (@$sgrbDataArray['template'] == 'woo_review') ? '' : ' style="display:none;"';?>>
								<div class="sg-row">
									<div class="sg-col-5">
										<label for="wooReviewShowType"><input id="wooReviewShowType" type="radio" value="showByCategory" name="wooReviewShowType"<?php echo (@$sgrbDataArray['wooReviewShowType'] == 'showByCategory' || !@$_GET['id']) ? ' checked' : '' ;?>><?php echo _e('Show review on products with selected categories', 'sgrb');?></label>
									</div>
								</div>
								<div class="sg-row">
									<div class="sg-col-6">
										<div class="sgrb-woo-categories-select-all"<?php echo (@$sgrbDataArray['wooReviewShowType'] != 'showByCategory') ? ' style="display:none;"' : '' ;?>>
											<div class="sgrb-select-checkbox">
												<input type="checkbox" class="sgrb-select-all-categories">
											</div>
										</div>
										<div class="sgrb-woo-category-wrapper"<?php echo (@$sgrbDataArray['wooReviewShowType'] != 'showByCategory') ? ' style="display:none;"' : '' ;?>>
										<?php if (empty($termsArray)):?>
										<div class="sgrb-each-category-wrapper">
											<span class="sgrb-woo-product-category-name"><?php echo _e('No product categories found', 'sgrb');?></span>
										</div>
										<?php else :?>
											<?php for ($i=0;$i<count(@$termsArray['id']);$i++) :?>
												<?php $categoryClass = 'sgrb-selected-categories';?>
												<div class="sgrb-each-category-wrapper">
													<label for="sgrb-woo-category-<?=$termsArray['id'][$i];?>">
													<?php for ($j=0;$j<count(@$sgrbDataArray['woo-category']);$j++) :?>
														<?php $checked = '';?>
														<?php $disabled = '';?>

														<?php $categoryClass = 'sgrb-selected-categories';?>
														<?php if (@$termsArray['id'][$i] == @$sgrbDataArray['woo-category'][$j]) :?>
															<?php $checked = ' checked';?>
															<?php break;?>
														<?php endif;?>
													<?php endfor;?>
													<?php for ($k=0;$k<count(@$matchesCategories['id']);$k++) :?>
														<?php $matchReview = '';?>
														<?php $disabled = '';?>
														<?php if (@$matchesCategories['id'][$k] == @$termsArray['id'][$i]) :?>
															<?php $checked = '';?>
															<?php $disabled = ' disabled';?>
															<?php $categoryClass = '';?>
															<?php $matchReview = ' - <i class="sgrb-is-used">used in </i> '.@$matchesCategories['review'][$k].'<i class="sgrb-is-used"> review</i>';?>
															<?php break;?>
														<?php endif;?>
													<?php endfor;?>
														<input class="sgrb-woo-category <?=$categoryClass?>" id="sgrb-woo-category-<?=@$termsArray['id'][$i];?>" type="checkbox" value="<?php echo @$termsArray['id'][$i];?>" <?php echo @$checked.' '.@$disabled;?>>
														<span class="sgrb-woo-product-category-name"><?php echo @$termsArray['name'][$i]?><?php echo @$matchReview?></span>
													</label>
												</div>
											<?php endfor;?>
										<?php endif;?>
										</div>
									</div>
								</div>
								<div class="sg-row">
									<div class="sg-col-4">
										<label for="sgrbWooReviewShowTypeProduct"><input id="sgrbWooReviewShowTypeProduct" type="radio" value="showByProduct" name="wooReviewShowType"<?php echo (@$sgrbDataArray['wooReviewShowType'] == 'showByProduct') ? ' checked' : '' ;?>><?php echo _e('Show review on selected products', 'sgrb');?></label>
									</div>
								</div>
								<div class="sg-row">
									<div class="sg-col-6">
										<div class="sgrb-woo-products-select-all"<?php echo (@$sgrbDataArray['wooReviewShowType'] != 'showByProduct') ? ' style="display:none;"' : '' ;?>>
											<div class="sgrb-select-checkbox">
												<input type="checkbox" class="sgrb-select-all-products">
												<span><?php echo _e('Products to load:', 'sgrb');?> <input name="productsToLoad" maxlength="3" value="500" type="text"></span>
											</div>
										</div>
										<div class="sgrb-woo-products-wrapper"<?php echo (@$sgrbDataArray['wooReviewShowType'] != 'showByProduct') ? ' style="display:none;"' : '' ;?>>
											<div class="sgrb-load-more-woo">
												<input class="button-small button sgrb-products-selector" value="<?php echo _e('Loading...', 'sgrb');?>" type="button">
											</div>
										</div>
									</div>
								</div>
								<input type="hidden" class="sgrb-all-products-categories" name="allProductsCategories" value="<?php echo @$sgrbDataArray['woo-products'];?>">
								<input type="hidden" class="sgrb-all-products-count" name="allProductsCount" value="<?php echo @$allProductsCount;?>">
								<div class="sg-row sgrb-disable-woo-comments-wrapper">
									<div class="sg-col-6">
										<label for="sgrb-disable-woo-comments">
											<input id="sgrb-disable-woo-comments" value="true" type="checkbox" name="disableWooComments"<?php echo (@$sgrbDataArray['disableWooComments']) ? ' checked' : '';?>> <?php echo _e('Disable WooCommerce products default reviews and comments', 'sgrb');?>
										</label>
										 <p class="sgrb-type-warning"><?php echo _e('Note: if the product has more than one review, priority will be given to the review attached directly to the product.', 'sgrb');?></p>
									</div>
								</div>
							</div>
<!-- end-->
<?php endif ;?>
						</div>
					</div>
					<div class="sg-box">
						<div class="sg-box-title"><?php echo _e('Rate options ', 'sgrb');?> <a href="javascript:void(0)" class="sgrb-reset-options button-small button"> <?php echo _e('Reset to default','sgrb');?></a></div>
						<div class="sg-box-content">
							<div class="sgrb-preview-container">
							<div class="sg-row">
								<div class="sg-col-6">
									<div class="sgrb-rate-options-rows-rate-type-preview">
										<p><b><?php echo _e('Rate skin style', 'sgrb');?>: </b></p>
										<?php if (SGRB_PRO_VERSION == 1) :?>
											<p class="sgrb-type-warning"><?php echo (@$sgrbRev->getId() != 0) ? _e('If you change the type, you\'ll lose all comments for this review', 'sgrb') : '';?></p>
										<?php endif; ?>
										<input name="rate-type-notice" class="sgrb-rate-type-notice" type="hidden" value="<?php echo esc_attr(@$sgrbDataArray['rate-type']) ;?>">
										<label class="sgrb-preview-1" for="sgrb-rate-type-1">
											<?php if (SGRB_PRO_VERSION == 0) :?>
												<input id="sgrb-rate-type-1" class="sgrb-preview-1 sgrb-rate-type" type="radio" name="rate-type" value="<?php echo SGRB_RATE_TYPE_STAR;?>" onclick="SGRateSkin.prototype.changeType(<?php echo SGRB_RATE_TYPE_STAR;?>)" checked>
											<?php else :?>
												<input id="sgrb-rate-type-1" class="sgrb-preview-1 sgrb-rate-type" type="radio" name="rate-type" value="<?php echo SGRB_RATE_TYPE_STAR;?>" onclick="SGRateSkin.prototype.changeType(<?php echo SGRB_RATE_TYPE_STAR;?>)"<?php echo (@$sgrbDataArray['rate-type'] == SGRB_RATE_TYPE_STAR) ? ' checked' : '' ;?> <?php echo (@$sgrbRevId == 0) ? ' checked' : '';?>>
											<?php endif; ?>
											<?php echo _e('Star', 'sgrb');?>
										</label>
										<?php if (SGRB_PRO_VERSION == 0) :?>
											<div style="position:relative;float:right;width:200px;">
											<div class="sgrb-version"><input type="button" class="sgrb-upgrade-button-second" value="PRO" onclick="window.open('<?php echo SGRB_PRO_URL;?>')"></div>
												<label class="" for="">
													<input id="" class="sgrb-preview-2 sgrb-rate-type" type="radio" name="rate-type" value="<?php echo SGRB_RATE_TYPE_PERCENT;?>" onclick="SGRateSkin.prototype.changeType(<?php echo SGRB_RATE_TYPE_PERCENT;?>)"<?php echo (@$sgrbDataArray['rate-type'] == SGRB_RATE_TYPE_PERCENT) ? ' checked' : '' ;?>>
													<?php echo _e('Percent', 'sgrb');?>
												</label>
												<label class="" for="">
													<input id="" class="" type="radio" name="rate-type" value="<?php echo SGRB_RATE_TYPE_POINT;?>" onclick="SGRateSkin.prototype.changeType(<?php echo SGRB_RATE_TYPE_POINT;?>)"<?php echo (@$sgrbDataArray['rate-type'] == SGRB_RATE_TYPE_POINT) ? ' checked' : '' ;?>>
													<?php echo _e('Point', 'sgrb');?>
												</label>
											</div>
										<?php elseif (SGRB_PRO_VERSION == 1) :?>
											<label class="sgrb-preview-2" for="sgrb-rate-type-2">
												<input id="sgrb-rate-type-2" class="sgrb-preview-2 sgrb-rate-type" type="radio" name="rate-type" value="<?php echo SGRB_RATE_TYPE_PERCENT;?>" onclick="SGRateSkin.prototype.changeType(<?php echo SGRB_RATE_TYPE_PERCENT;?>)"<?php echo (@$sgrbDataArray['rate-type'] == SGRB_RATE_TYPE_PERCENT) ? ' checked' : '' ;?>>
												<?php echo _e('Percent', 'sgrb');?>
											</label>
											<label class="sgrb-preview-3" for="sgrb-rate-type-3">
												<input id="sgrb-rate-type-3" class="sgrb-preview-3 sgrb-rate-type" type="radio" name="rate-type" value="<?php echo SGRB_RATE_TYPE_POINT;?>" onclick="SGRateSkin.prototype.changeType(<?php echo SGRB_RATE_TYPE_POINT;?>)"<?php echo (@$sgrbDataArray['rate-type'] == SGRB_RATE_TYPE_POINT) ? ' checked' : '' ;?>>
												<?php echo _e('Point', 'sgrb');?>
											</label>
										<?php endif ; ?>
									</div>
								</div>
								<div class="sg-col-6">
									<div class="sgrb-skin-style-preview">
										<div class=""></div>
									</div>
								</div>
							</div>
							</div>
							<div class="sgrb-skin-color"<?php echo (@$sgrbDataArray['rate-type'] == SGRB_RATE_TYPE_POINT) ? ' style="display:none;"' : '' ;?>>
								<p><b><?php echo _e('Rate Skin color', 'sgrb');?>: </b></p>
								<span><input name="skin-color" type="text" value="<?php echo esc_attr(@$sgrbDataArray['skin-color']);?>" class="color-picker" /></span>
							</div>

							<div class="sgrb-total-show">
								<p><b><?php echo _e('Show total rate', 'sgrb');?>: </b></p>
								<select name="totalRate" class="sgrb-total-rate">
									<option value="1"<?php echo (@$sgrbDataArray['total-rate'] == 1 || (!@$sgrbRev->getId())) ? ' selected' : '';?>><?php echo _e('Yes', 'sgrb');?></option>
									<option value="0"<?php echo (@$sgrbDataArray['total-rate'] == 0 && (@$sgrbRev->getId())) ? ' selected' : '';?>><?php echo _e('No', 'sgrb');?></option>
								</select>
							</div>
							<div class="sgrb-comment-show">
								<p><b><?php echo _e('Show comments', 'sgrb');?>: </b></p>
								<select name="showComments" class="sgrb-total-rate">
									<option value="1"<?php echo (@$sgrbDataArray['show-comments'] == 1 || (!@$sgrbRev->getId())) ? ' selected' : '';?>><?php echo _e('Yes', 'sgrb');?></option>
									<option value="0"<?php echo (@$sgrbDataArray['show-comments'] == 0 && (@$sgrbRev->getId())) ? ' selected' : '';?>><?php echo _e('No', 'sgrb');?></option>
								</select>
							</div>

						<?php if (SGRB_PRO_VERSION == 0) :?>
							<div class="sgrb-captcha-ribbon-wrapper" style='width: 19%;'>
							<div class="sgrb-version"><input type="button" class="sgrb-upgrade-button" value="PRO" onclick="window.open('<?php echo SGRB_PRO_URL;?>')"></div>
						<?php endif; ?>
							<div class="sgrb-comment-show" style="min-width:150px;">
								<p><b><?php echo _e('Include captcha', 'sgrb');?>: </b></p>
								<select name="captchaOn" class="sgrb-total-rate">
									<option value="1"<?php echo (@$sgrbDataArray['captcha-on'] == 1 || (!@$sgrbRev->getId())) ? ' selected' : '';?>><?php echo _e('Yes', 'sgrb');?></option>
									<option value="0"<?php echo (@$sgrbDataArray['captcha-on'] == 0 && (@$sgrbRev->getId())) ? ' selected' : '';?>><?php echo _e('No', 'sgrb');?></option>
								</select>
							</div>
						<?php if (SGRB_PRO_VERSION == 0) :?>
							</div>
						<?php endif;?>

							<div class="sgrb-total-color-options">
								<input type="hidden" class="sgrb-show-total" value="<?php echo (@$sgrbDataArray['total-rate']) ? 1 : 0;?>">
								<div class="sgrb-total-options-rows-rate-type">
									<p><b><?php echo _e('Form & content text color', 'sgrb');?>: </b></p>
									<span><input name="rate-text-color" type="text" value="<?php echo esc_attr(@$sgrbDataArray['rate-text-color']);?>" class="color-picker" /></span>
								</div>
								<div class="sgrb-total-options-rows-rate-type">
									<p><b><?php echo _e('Form & content background color', 'sgrb');?>: </b></p>
									<span><input name="total-rate-background-color" type="text" value="<?php echo esc_attr(@$sgrbDataArray['total-rate-background-color']);?>" class="color-picker" /></span>
								</div>
							</div>

						</div>
					</div>

					<div class="sg-box sgrb-template-options-box" style="min-height:100px;<?php echo (@$sgrbDataArray['template'] == 'post_review') ? 'display:none;' : '';?>">
						<div class="sg-box-title"><?php echo _e('Template customize options', 'sgrb');?></div>
						<div class="sg-box-content">
							<?php if (SGRB_PRO_VERSION == 1) :?>
								<?php require_once('templatesOptionsPro.php');?>
							<?php else :?>
								<div style='position: relative;'>
								<div class="sgrb-version"><input type="button" class="sgrb-upgrade-button" value="Upgrade to PRO version" onclick="window.open('<?php echo SGRB_PRO_URL;?>')"></div>
									<div class="sgrb-template-options">
										<div class="sg-row">
											<div class="sg-col-4">
												<div class="sgrb-total-options-rows-rate-type">
													<p style="margin: 4px 0"><?php echo _e('Font', 'sgrb');?>: </p>
													<select>
														<option>Your custom font</option>
													</select>
												</div>
												<div class="sgrb-total-options-rows-rate-type">
													<p style="margin: 3px 0"><?php echo _e('Background color', 'sgrb');?>: </p>
													<span><input type="text" class="color-picker" /></span>
												</div>
												<div class="sgrb-total-options-rows-rate-type">
													<p style="margin: 3px 0"><?php echo _e('Text color', 'sgrb');?>: </p>
													<span><input type="text" class="color-picker" /></span>
												</div>
											</div>
											<div class="sg-col-5">
												<div class="sgrb-total-options-rows-rate-type">
													<p><label for="sgrb-template-shadow-on"><input id="sgrb-template-shadow-on" type="checkbox"> <?php echo _e('Template inner boxes shadow effect', 'sgrb');?> </label></p>
												</div>
												<div class="sgrb-single-option">
													<div class="sgrb-option-title-side"><?php echo _e('Color', 'sgrb');?>: </div><div style="float:right;"><input type="text" class="color-picker" /></div>
												</div>
												<div class="sgrb-single-option">
													<div class="sgrb-option-title-side"><i class="sgrb-required-asterisk"> * </i><?php echo _e('To Left / Right (- / +)', 'sgrb');?>: </div><div class="sgrb-option-input-side"><input name="shadow-left-right" class="sgrb-template-shadow-directions" type="text" value="<?=@$sgrbDataArray['shadow-left-right']?>"/> - px</div>
												</div>
												<div class="sgrb-single-option">
													<div class="sgrb-option-title-side"><i class="sgrb-required-asterisk"> * </i><?php echo _e('To Top / Bottom (- / +)', 'sgrb');?>: </div><div class="sgrb-option-input-side"><input name="shadow-top-bottom" class="sgrb-template-shadow-directions" type="text" value="<?=@$sgrbDataArray['shadow-top-bottom']?>"/> - px</div>
												</div>
												<div class="sgrb-single-option">
													<div class="sgrb-option-title-side"><?php echo _e('Blur effect', 'sgrb');?>:</div><div class="sgrb-option-input-side"><input class="sgrb-template-shadow-directions" type="text"/> - px</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							<?php endif ;?>
						</div>
					</div>

					<div class="sg-box sgrb-google-search-box">
						<div class="sg-box-title"><?php echo _e('Search options', 'sgrb');?></div>
						<div class="sg-box-content">
							<?php if (SGRB_PRO_VERSION == 1) :?>
								<?php require_once('googleSearchPreviewOptions.php');?>
							<?php else :?>
								<div style='position: relative;'>
									<div class="sgrb-version"><input type="button" class="sgrb-upgrade-button" value="Upgrade to PRO version" onclick="window.open('<?php echo SGRB_PRO_URL;?>')"></div>
									<div class="sg-row">
										<div class="sg-col-12">
											<label>
												<input type="checkbox"><?php echo _e('Show Your review in Google search', 'sgrb');?>
											</label>
										</div>
									</div>
									<div class="sg-row">
									<div class="sg-col-12">
										<div class="sgrb-google-search-preview">
											<div class="sgrb-google-box-wrapper">
												<div class="sgrb-google-box-title">Your review title</div>
												<div class="sgrb-google-box-url">www.your-web-page.com/your-review-site/...</div>
												<div class="sgrb-google-box-image-votes"><img width="70px" height="20px" src="<?php echo $sgrb->app_url.'/assets/page/img/google_search_preview.png';?>"><span>Rating - 5 - 305 votes</span></div>
												<div class="sgrb-google-box-description"><span>Your description text, if description field in Your selected template not exist, then there will be another field's text, e.g. title,subtitle ...</span></div>
											</div>
										</div>
									</div>
								</div>
								</div>

							<?php endif;?>
						</div>
					</div>

				</div>

				<div class="sg-col-4">
					<div class="sg-box">
						<div class="sg-box-title"><?php echo _e('Options', 'sgrb');?></div>
						<div class="sg-box-content">
						<?php if (SGRB_PRO_VERSION == 0) :?>
							<div style="position:relative;">
								<div class="sgrb-version"><input type="button" class="sgrb-upgrade-button" value="PRO" onclick="window.open('<?php echo SGRB_PRO_URL;?>')"></div>
								<div class="sgrb-require-options-fields">
									<div class="sg-row">
										<div class="sg-col-5">
											<span class="sgrb-comments-count-options"><?php echo _e('Comments to show:', 'sgrb');?></span>
										</div>
										<div class="sg-col-2">
											<input class="sgrb-comments-count-to-show" value="10" type="text">
										</div>
									</div>
									<div class="sg-row">
										<div class="sg-col-5">
											<span class="sgrb-comments-count-options"><?php echo _e('Comments to load:', 'sgrb');?></span>
										</div>
										<div class="sg-col-2">
											<input class="sgrb-comments-count-to-load" value="3" type="text">
										</div>
									</div>
								</div>
								<div class="sgrb-require-options-fields">
									<p><label for=""><input id="" class="sgrb-email-notification-checkbox" value="true" type="checkbox"><?php echo _e('Notify for new comments to this email:', 'sgrb');?></label>
									<input class="sgrb-email-notification"></p>
									<p><label for="sgrb-required-login-checkbox"><input id="sgrb-required-login-checkbox" class="sgrb-email-notification-checkbox" type="checkbox"><?php echo _e('Require login for new comments', 'sgrb');?></label>
									<p><label for="sgrb-hide-comment-form"><input id="sgrb-hide-comment-form" class="sgrb-email-notification-checkbox" type="checkbox"><?php echo _e('Hide comment form for all users', 'sgrb');?></label>
								</div>

							</div>
						<?php elseif (SGRB_PRO_VERSION == 1) :?>
							<div class="sgrb-require-options-fields">
								<div class="sg-row">
									<div class="sg-col-5">
										<span class="sgrb-comments-count-options"><?php echo _e('Comments to show:', 'sgrb');?></span>
									</div>
									<div class="sg-col-2">
										<input class="sgrb-comments-count-to-show" name="comments-count-to-show" value="<?php echo (@$sgrbDataArray['comments-count-to-show']) ? @$sgrbDataArray['comments-count-to-show'] : 10;?>" type="text">
									</div>
								</div>
								<div class="sg-row">
									<div class="sg-col-5">
										<span class="sgrb-comments-count-options"><?php echo _e('Comments to load:', 'sgrb');?></span>
									</div>
									<div class="sg-col-2">
										<input class="sgrb-comments-count-to-load" name="comments-count-to-load" value="<?php echo (@$sgrbDataArray['comments-count-to-load']) ? @$sgrbDataArray['comments-count-to-load'] : 3;?>" type="text">
									</div>
								</div>
							</div>
							<div class="sgrb-require-options-fields">
								<p><label for="sgrb-email-checkbox"><input id="sgrb-email-checkbox" class="sgrb-email-notification-checkbox sgrb-email-hide-show-js" value="true" type="checkbox" name="email-notification-checkbox"<?php echo (@$sgrbDataArray['notify']) ? ' checked' : '';?><?php echo (@$sgrbRevId != 0) ? '' : ' checked';?>><?php echo _e('Notify for new comments to this email:', 'sgrb');?></label>
								<input class="sgrb-email-notification" type="email" value="<?php echo (@$sgrbRevId != 0) ? @$sgrbDataArray['notify'] : get_option('admin_email');?>" name="email-notification"></p>
								<input class="sgrb-admin-email" type="hidden" value="<?php echo get_option('admin_email') ;?>">
								<p><label for="sgrb-required-login-checkbox"><input id="sgrb-required-login-checkbox" class="sgrb-email-notification-checkbox" value="true" type="checkbox" name="required-login-checkbox"<?php echo (@$sgrbDataArray['required-login-checkbox']) ? ' checked' : '';?>><?php echo _e('Require login for new comments', 'sgrb');?></label>
								<p><label for="sgrb-hide-comment-form"><input id="sgrb-hide-comment-form" class="sgrb-email-notification-checkbox" value="true" type="checkbox" name="hide-comment-form"<?php echo (@$sgrbDataArray['hide-comment-form']) ? ' checked' : '';?>><?php echo _e('Hide comment form for all users', 'sgrb');?></label>
							</div>
						<?php endif ; ?>
							<div class="sgrb-require-options-fields">
								<p><label for="sgrb-required-title-checkbox"><input id="sgrb-required-title-checkbox" class="sgrb-email-notification-checkbox" value="true" type="checkbox" name="required-title-checkbox"<?php echo (@$sgrbDataArray['required-title-checkbox']) ? ' checked' : '';?><?php echo (@$sgrbRevId != 0) ? '' : ' checked';?>><?php echo (SGRB_PRO_VERSION) ?  _e('Title required (for default comment form)', 'sgrb') : _e('Title required', 'sgrb');?></label>
								<p><label for="sgrb-required-email-checkbox"><input id="sgrb-required-email-checkbox" class="sgrb-email-notification-checkbox" value="true" type="checkbox" name="required-email-checkbox"<?php echo (@$sgrbDataArray['required-email-checkbox']) ? ' checked' : '';?><?php echo (@$sgrbRevId != 0) ? '' : ' checked';?>><?php echo (SGRB_PRO_VERSION) ? _e('Email required (for default comment form)', 'sgrb') : _e('Email required', 'sgrb');?></label>
							</div>
							<div class="sgrb-require-options-fields">
								<p><label for="sgrb-auto-approve-checkbox"><input id="sgrb-auto-approve-checkbox" class="sgrb-auto-approve-checkbox" value="true" type="checkbox" name="auto-approve-checkbox"<?php echo (@$sgrbDataArray['auto-approve-checkbox']) ? ' checked' : '';?>><?php echo _e('Auto approve comments', 'sgrb');?></label>
							</div>
							<div class="sgrb-require-options-fields sgrb-categories-title"<?php echo (@$sgrbRevId != 0) ? ' style="margin-bottom:30px;"' : '';?>>
								<p><b><?php echo _e('Features to rate', 'sgrb');?>: </b><a class="button-primary sgrb-add-field"<?php echo (@$sgrbRevId != 0) ? ' style="pointer-events:none;" disabled' : '';?>><span class="dashicons dashicons-plus-alt button-icon"></span> Add new</a></p>
								<i class="sgrb-category-empty-warning"> <?php echo (@$sgrbRevId != 0) ? '' : _e('At least one feature is required', 'sgrb');?></i>
							</div>
							<div class="sgrb-field-container">
							<input type="hidden" class="sgrbSaveUrl" value="<?php echo esc_attr($sgrbSaveUrl);?>">
								<?php if (empty($fields)) :?>
									<div class="sgrb-one-field" id="clone_1">
									<div class="sgrb-require-options-fields">
										<input class="sgrb-fieldId" name="fieldId[]" type="hidden" value="0">
										<p>
											<input name="field-name[]" type="text" value="" placeholder="<?php echo _e('e.g. quality/speed/price of review item', 'sgrb');?>" class="sgrb-field-name">
											<a class="button-secondary sgrb-remove-button" onclick="SGReview.remove('clone_1')"><span class="sgrb-dashicon dashicons dashicons-trash button-icon"></span> Remove</a>
										</p>
										<input type="hidden" class="fake-sgrb-id" name="fake-id[]" value="66">
									</div>
								</div>
								<?php else :?>
								<?php $i = 1;?>
								<?php foreach (@$fields as $field) : ?>
								<div class="sgrb-one-field" id="sgrb_<?php echo @$field->getId();?>">
									<div>
										<input class="sgrb-fieldId" name="fieldId[]" type="hidden" value="<?php echo esc_attr(@$field->getId());?>">
									<p>
										<input<?php echo (@$sgrbRevId != 0) ? ' readonly' : '';?> name="field-name[]" type="text" value="<?php echo esc_attr(@$field->getName());?>"placeholder="<?php echo _e('e.g. quality/speed/price of review item', 'sgrb');?>" class="sgrb-border sgrb-field-name">
										<a class="button-secondary sgrb-remove-button" onclick="SGReview.remove(<?php echo esc_attr(@$field->getId());?>)"><span class="sgrb-dashicon dashicons dashicons-trash button-icon"></span> Remove</a>
									</p>
										<input type="hidden" class="fake-sgrb-id" name="fake-id[]" value="<?php echo $i++;?>">
									</div>
								</div>
								<?php endforeach;?>
								<?php endif;?>
							</div>

						</div>
					</div>
<!-- Comment form edit -->

					<div class="sg-box sgrb-comment-form-box">
						<div class="sg-box-title"><?php echo (SGRB_PRO_VERSION) ? _e('Comment form options', 'sgrb') : _e('Comment form options', 'sgrb').'<i class="sgrb-how-to"> - available in PRO</i>';?></div>
						<div class="sg-box-content">
							<select class="sgrb-add-comment-form" name="sgrb-add-comment-form">
								<?php if (@$sgrbCommentForms && SGRB_PRO_VERSION) :?>
										<option value="0">Default</option>
									<?php foreach ($sgrbCommentForms as $form) :?>
										<option value="<?php echo $form->getId();?>"<?php echo (@$sgrbDataArray['sgrb-add-comment-form'] == $form->getId()) ? ' selected' : '';?>><?php echo $form->getTitle();?></option>
									<?php endforeach;?>
								<?php else :?>
									<option value="0">Default</option>
								<?php endif;?>
							</select>
							<p class="howto">Important: If you already have comments collected from your users via any comment form, then it's not recommended to change the comment form.</p>
						</div>
					</div>

<!-- Comment form edit -->
					<div class="sg-box" style="min-height: 165px">
						<div class="sg-box-title"><?php echo _e('Connect review to your posts by tag', 'sgrb');?></div>
						<div class="sg-box-content">
							<input id="new-tag-post_tag" name="sgrbNewtag[post_tag]" class="sgrb-newtag form-input-tip" size="16" autocomplete="off" aria-describedby="new-tag-post_tag-desc" value="" type="text" style="width: 80%;">
							<input class="button sgrb-tagadd" value="Add" type="button" style="width: 15%;">
							<p class="howto" id="sgrb-new-tag-post_tag-desc"><?php echo _e('Separate tags with commas', 'sgrb');?></p>
							<p class="howto" id="sgrb-new-tag-post_tag-desc">Note: If you do not have any posts with a new created tag, it will not be visible</p>
							<div class="tagchecklist">
								<?php if (@$sgrbDataArray['tags']) :?>
									<?php $index=0;?>
									<?php foreach ($sgrbDataArray['tags'] as $tag) :?>
											<span id="sgrb-tag-index-<?=$index?>"><a href="javascript:void(0)" id="post_tag-check-num-<?=$index?>" onclick="SGReview.prototype.deleteTag(<?=$index?>)" class="ntdelbutton" tabindex="<?=$index?>"></a></span><span id="sgrb-tag-<?=$index?>" class="sgrb-new-tag-text"><?php echo $tag;?></span> <input type="hidden" value="<?php echo $tag;?>" name="tagsArray[]">
										<?php $index++;?>
									<?php endforeach;?>
								<?php endif ;?>
							</div>
							<a href="javascript:void(0)" class="sgrb-tagcloud-link" id="link-post_tag"><?php echo _e('Choose from your tags', 'sgrb');?></a>

							<p id="tagcloud-post_tag" class="the-tagcloud sgrb-tags-cloud" style="display: none;">
							<?php if (!@empty($allTags)) :?>
								<?php foreach ($allTags as $tags) :?>
									<a href="javascript:void(0)" class="tag-link-10 tag-link-position-1" title="2 topics" onclick="SGReviewHelper.prototype.setTags('<?php echo $tags->name;?>');" style="font-size: 22pt;"><?php echo $tags->name;?></a>
								<?php endforeach ;?>
							<?php endif;?>
							</p>
						</div>
					</div>

					<div class="sg-box">
						<div class="sg-box-title"><?php echo _e('Localization', 'sgrb');?></div>
						<div class="sg-box-content">
							<div class="sgrb-require-options-fields">
								<p><b>Form fields and placeholders:</b></p>
								<div class="sg-row">
									<div class="sg-col-12">
										<span class="sgrb-comments-count-options"><?php echo _e('Success post text:', 'sgrb');?></span>
									</div>
									<div class="sg-col-12">
										<input class="sgrb-comments-count-to-show sgrb-localization-input" name="success-comment-text" value="<?php echo (@$sgrbDataArray['success-comment-text']) ? @$sgrbDataArray['success-comment-text'] : 'Thank You for Your Comment!';?>" type="text">
									</div>
								</div>
								<div class="sg-row">
									<div class="sg-col-12">
										<span class="sgrb-comments-count-options"><?php echo _e('Total rating:', 'sgrb');?></span>
									</div>
									<div class="sg-col-12">
										<input class="sgrb-comments-count-to-show sgrb-localization-input" name="total-rating-text" value="<?php echo (@$sgrbDataArray['total-rating-text']) ? @$sgrbDataArray['total-rating-text'] : 'Total rating';?>" type="text">
									</div>
								</div>
								<div class="sg-row">
									<div class="sg-col-12">
										<span class="sgrb-comments-count-options"><?php echo _e('Add review:', 'sgrb');?></span>
									</div>
									<div class="sg-col-12">
										<input class="sgrb-comments-count-to-show sgrb-localization-input" name="add-review-text" value="<?php echo (@$sgrbDataArray['add-review-text']) ? @$sgrbDataArray['add-review-text'] : 'Add your own review';?>" type="text">
									</div>
								</div>
								<div class="sg-row">
									<div class="sg-col-12">
										<span class="sgrb-comments-count-options"><?php echo _e('Edit review:', 'sgrb');?></span>
									</div>
									<div class="sg-col-12">
										<input class="sgrb-comments-count-to-show sgrb-localization-input" name="edit-review-text" value="<?php echo (@$sgrbDataArray['edit-review-text']) ? @$sgrbDataArray['edit-review-text'] : 'Edit your own review';?>" type="text">
									</div>
								</div>
								<div class="sg-row">
									<div class="sg-col-12">
										<span class="sgrb-comments-count-options"><?php echo _e('Name:', 'sgrb');?></span>
									</div>
									<div class="sg-col-12">
										<input class="sgrb-comments-count-to-show sgrb-localization-input" name="name-text" value="<?php echo (@$sgrbDataArray['name-text']) ? @$sgrbDataArray['name-text'] : 'Your name';?>" type="text">
									</div>
								</div>
								<div class="sg-row">
									<div class="sg-col-12">
										<span class="sgrb-comments-count-options"><?php echo _e('Name placeholder:', 'sgrb');?></span>
									</div>
									<div class="sg-col-12">
										<input class="sgrb-comments-count-to-show sgrb-localization-input" name="name-placeholder-text" value="<?php echo (@$sgrbDataArray['name-placeholder-text']) ? @$sgrbDataArray['name-placeholder-text'] : 'name';?>" type="text">
									</div>
								</div>
								<div class="sg-row">
									<div class="sg-col-12">
										<span class="sgrb-comments-count-options"><?php echo _e('Email:', 'sgrb');?></span>
									</div>
									<div class="sg-col-12">
										<input class="sgrb-comments-count-to-show sgrb-localization-input" name="email-text" value="<?php echo (@$sgrbDataArray['email-text']) ? @$sgrbDataArray['email-text'] : 'Email';?>" type="text">
									</div>
								</div>
								<div class="sg-row">
									<div class="sg-col-12">
										<span class="sgrb-comments-count-options"><?php echo _e('Email placeholder:', 'sgrb');?></span>
									</div>
									<div class="sg-col-12">
										<input class="sgrb-comments-count-to-show sgrb-localization-input" name="email-placeholder-text" value="<?php echo (@$sgrbDataArray['email-placeholder-text']) ? @$sgrbDataArray['email-placeholder-text'] : 'email';?>" type="text">
									</div>
								</div>
								<div class="sg-row">
									<div class="sg-col-12">
										<span class="sgrb-comments-count-options"><?php echo _e('Title:', 'sgrb');?></span>
									</div>
									<div class="sg-col-12">
										<input class="sgrb-comments-count-to-show sgrb-localization-input" name="title-text" value="<?php echo (@$sgrbDataArray['title-text']) ? @$sgrbDataArray['title-text'] : 'Title';?>" type="text">
									</div>
								</div>
								<div class="sg-row">
									<div class="sg-col-12">
										<span class="sgrb-comments-count-options"><?php echo _e('Title placeholder:', 'sgrb');?></span>
									</div>
									<div class="sg-col-12">
										<input class="sgrb-comments-count-to-show sgrb-localization-input" name="title-placeholder-text" value="<?php echo (@$sgrbDataArray['title-placeholder-text']) ? @$sgrbDataArray['title-placeholder-text'] : 'title';?>" type="text">
									</div>
								</div>
								<div class="sg-row">
									<div class="sg-col-12">
										<span class="sgrb-comments-count-options"><?php echo _e('Comment:', 'sgrb');?></span>
									</div>
									<div class="sg-col-12">
										<input class="sgrb-comments-count-to-show sgrb-localization-input" name="comment-text" value="<?php echo (@$sgrbDataArray['comment-text']) ? @$sgrbDataArray['comment-text'] : 'Comment';?>" type="text">
									</div>
								</div>
								<div class="sg-row">
									<div class="sg-col-12">
										<span class="sgrb-comments-count-options"><?php echo _e('Com. placeholder:', 'sgrb');?></span>
									</div>
									<div class="sg-col-12">
										<input class="sgrb-comments-count-to-show sgrb-localization-input" name="comment-placeholder-text" value="<?php echo (@$sgrbDataArray['comment-placeholder-text']) ? @$sgrbDataArray['comment-placeholder-text'] : 'Your comment here';?>" type="text">
									</div>
								</div>
								<div class="sg-row">
									<div class="sg-col-12">
										<span class="sgrb-comments-count-options"><?php echo _e('Post button:', 'sgrb');?></span>
									</div>
									<div class="sg-col-12">
										<input class="sgrb-comments-count-to-show sgrb-localization-input" name="post-button-text" value="<?php echo (@$sgrbDataArray['post-button-text']) ? @$sgrbDataArray['post-button-text'] : 'Post Comment';?>" type="text">
									</div>
								</div>
								<?php if (!SGRB_PRO_VERSION) :?>
								<div class="sgrb-captcha-ribbon-wrapper">
									<div class="sgrb-version"><input type="button" class="sgrb-upgrade-button" style="font-size: 17px;" value="PRO" onclick="window.open('<?php echo SGRB_PRO_URL;?>')"></div>
									<div class="sg-row">
										<div class="sg-col-12">
											<span class="sgrb-comments-count-options"><?php echo _e('Captcha regenerate:', 'sgrb');?></span>
										</div>
										<div class="sg-col-12">
											<input class="sgrb-comments-count-to-show sgrb-localization-input" value="Change image" type="text">
										</div>
									</div>
									<div class="sg-row">
										<div class="sg-col-12">
											<span class="sgrb-comments-count-options"><?php echo _e('Not logged in users:', 'sgrb');?></span>
										</div>
										<div class="sg-col-12">
											<input class="sgrb-comments-count-to-show sgrb-localization-input" value="Sorry, to leave a review you need to log in" type="text">
										</div>
									</div>
								</div>
								<?php else :?>
									<div class="sg-row">
										<div class="sg-col-12">
											<span class="sgrb-comments-count-options"><?php echo _e('Captcha regenerate:', 'sgrb');?></span>
										</div>
										<div class="sg-col-12">
											<input class="sgrb-comments-count-to-show sgrb-localization-input" name="captcha-text" value="<?php echo (@$sgrbDataArray['captcha-text']) ? @$sgrbDataArray['captcha-text'] : 'Change image';?>" type="text">
										</div>
									</div>
									<div class="sg-row">
										<div class="sg-col-12">
											<span class="sgrb-comments-count-options"><?php echo _e('Not logged in users:', 'sgrb');?></span>
										</div>
										<div class="sg-col-12">
											<input class="sgrb-comments-count-to-show sgrb-localization-input" name="logged-in-text" value="<?php echo (@$sgrbDataArray['logged-in-text']) ? @$sgrbDataArray['logged-in-text'] : 'Sorry, to leave a review you need to log in';?>" type="text">
										</div>
									</div>
								<?php endif;?>
								<p><b>Empty fields notice texts:</b></p>
								<div class="sg-row">
									<div class="sg-col-12">
										<span class="sgrb-comments-count-options"><?php echo _e('No rated categories:', 'sgrb');?></span>
									</div>
									<div class="sg-col-12">
										<input class="sgrb-comments-count-to-show sgrb-localization-input" name="no-category-text" value="<?php echo (@$sgrbDataArray['no-category-text']) ? @$sgrbDataArray['no-category-text'] : 'Please, rate all suggested categories';?>" type="text">
									</div>
								</div>
								<div class="sg-row">
									<div class="sg-col-12">
										<span class="sgrb-comments-count-options"><?php echo _e('Empty name:', 'sgrb');?></span>
									</div>
									<div class="sg-col-12">
										<input class="sgrb-comments-count-to-show sgrb-localization-input" name="no-name-text" value="<?php echo (@$sgrbDataArray['no-name-text']) ? @$sgrbDataArray['no-name-text'] : 'Name is required';?>" type="text">
									</div>
								</div>
								<div class="sg-row">
									<div class="sg-col-12">
										<span class="sgrb-comments-count-options"><?php echo _e('Empty email:', 'sgrb');?></span>
									</div>
									<div class="sg-col-12">
										<input class="sgrb-comments-count-to-show sgrb-localization-input" name="no-email-text" value="<?php echo (@$sgrbDataArray['no-email-text']) ? @$sgrbDataArray['no-email-text'] : 'Invalid email address';?>" type="text">
									</div>
								</div>
								<div class="sg-row">
									<div class="sg-col-12">
										<span class="sgrb-comments-count-options"><?php echo _e('Empty title:', 'sgrb');?></span>
									</div>
									<div class="sg-col-12">
										<input class="sgrb-comments-count-to-show sgrb-localization-input" name="no-title-text" value="<?php echo (@$sgrbDataArray['no-title-text']) ? @$sgrbDataArray['no-title-text'] : 'Title is required';?>" type="text">
									</div>
								</div>
								<div class="sg-row">
									<div class="sg-col-12">
										<span class="sgrb-comments-count-options"><?php echo _e('Empty comment:', 'sgrb');?></span>
									</div>
									<div class="sg-col-12">
										<input class="sgrb-comments-count-to-show sgrb-localization-input" name="no-comment-text" value="<?php echo (@$sgrbDataArray['no-comment-text']) ? @$sgrbDataArray['no-comment-text'] : 'Comment text is required';?>" type="text">
									</div>
								</div>
								<?php if (!SGRB_PRO_VERSION) :?>
								<div class="sgrb-captcha-ribbon-wrapper">
									<div class="sgrb-version"><input type="button" class="sgrb-upgrade-button" style="font-size: 17px;" value="PRO" onclick="window.open('<?php echo SGRB_PRO_URL;?>')"></div>
									<div class="sg-row">
									<div class="sg-col-12">
										<span class="sgrb-comments-count-options"><?php echo _e('Uncorrect captcha:', 'sgrb');?></span>
									</div>
									<div class="sg-col-12">
										<input class="sgrb-comments-count-to-show sgrb-localization-input" value="Invalid captcha text'" type="text">
									</div>
								</div>
								</div>
								<?php else :?>
								<div class="sg-row">
									<div class="sg-col-12">
										<span class="sgrb-comments-count-options"><?php echo _e('Uncorrect captcha:', 'sgrb');?></span>
									</div>
									<div class="sg-col-12">
										<input class="sgrb-comments-count-to-show sgrb-localization-input" name="no-captcha-text" value="<?php echo (@$sgrbDataArray['no-captcha-text']) ? @$sgrbDataArray['no-captcha-text'] : 'Invalid captcha text';?>" type="text">
									</div>
								</div>
								<?php endif;?>

							</div>
						</div>
					</div>

				</div>

			</div>

		</div>
	</form>
</div>
<input type="hidden" class="sgrb-is-pro" value="<?php echo SGRB_PRO_VERSION;?>">
<div id="sgrb-template" title="Select template">
	<?php
	foreach($allTemplates as $template):
		$isChecked = ($template->getName() == @$sgrbDataArray['template']) ? ' checked' : '';
		$proHtml = '<div class="ribbon-wrapper" style="position:relative;display:block;"><div class="sgrb-ribbon"><div><a target="_blank" href="'.SGRB_PRO_URL.'">PRO</a></div></div></div>';
		if($template->getSgrb_pro_version()==1) $proHtml='';
	?>
	<label class="sgrb-template-label">
		<?php if($template):?>
		<input type="radio" class="sgrb-radio" name="sgrb-template-radio" value="<?php echo $template->getName()?>"<?php echo esc_attr($isChecked);?>>
		<?php endif?>
		<?php echo $proHtml; ?>
		<?php if (!$template->getImg_url()):?>
			<div class="sgrb-custom-template-hilghlighting" style="position:absolute;color:#3F3F3F;margin-left:10px;z-index:9"><b><?=$template->getName()?></b></div>
			<img width="200px" src="<?php echo $sgrb->app_url.'assets/page/img/custom_template.jpeg'; ?>">
		<?php else:?>
			<img class="sgrb-default-template-js" width="200px" src="<?php echo $template->getImg_url(); ?>">
		<?php endif;?>
	</label>
	<?php endforeach; ?>
</div>
