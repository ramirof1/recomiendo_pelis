function SGRB(){
	'use strict';
	SGRB.tagIndex = 0;
}

function SGReview() {
}

SGReview.prototype.init = function(){
	var that = this;

	SGReview.prototype.isHidden();

	jQuery('.sgrb-save-tables').click(function(){
		that.ajaxSaveFreeTables();
	});

	if (jQuery('#sgrbWooReviewShowTypeProduct').is(':checked')) {
		var productsToLoad = jQuery('input[name=productsToLoad]').val();
		var reviewId = jQuery('input[name=sgrb-id]').val();
		if (!reviewId) {
			reviewId = 0;
		}
		SGReview.ajaxWooProductLoad(0,reviewId,productsToLoad);
	}

	jQuery('#wooReviewShowType').click(function(){
		jQuery('.sgrb-woo-products-wrapper').hide();
		jQuery('.sgrb-woo-products-select-all').hide();
		jQuery('.sgrb-woo-category-wrapper').show();
		jQuery('.sgrb-woo-categories-select-all').show();
	});

	jQuery('#sgrbWooReviewShowTypeProduct').click(function(){
		jQuery('.sgrb-woo-category-wrapper').hide();
		jQuery('.sgrb-woo-categories-select-all').hide();
		jQuery('.sgrb-woo-products-wrapper').show();
		jQuery('.sgrb-woo-products-select-all').show();

		if (jQuery('.sgrb-no-first-click').length < 1) {
			var productsToLoad = jQuery('input[name=productsToLoad]').val();
			var reviewId = jQuery('input[name=sgrb-id]').val();
			if (!reviewId) {
				reviewId = 0;
			}
			SGReview.ajaxWooProductLoad(0,reviewId,productsToLoad);
		}
	});

	jQuery('.sgrb-reviewFakeId').each(function(){
		/* reviewId is real review id, sgrbFakeId is fake but unique review id */
		var reviewFakeId = jQuery(this).val();
		var commentFormWrapper = jQuery('#'+reviewFakeId);
		var skinType = commentFormWrapper.find('.sgrb-rating-type').val();
		SGRateSkin.prototype.prepareFrontSkin(skinType);
		commentFormWrapper.find('#sgrb-review-form-title').click(function(){
			commentFormWrapper.find('.sgrb-show-hide-comment-form').toggle(400);
			SGReview.prototype.ajaxUserRate(false, true, reviewFakeId);
		});
	});

	/* see later, what do this script */
	/*jQuery(document).ajaxComplete(function(){
	 jQuery('.sgrb-common-wrapper').find('.sgrb-each-comment-rate').remove();
	 jQuery('.sgrb-widget-wrapper').find('.sgrb-loading-spinner').hide();
	 var formCommentTextColor = jQuery('.sgrb-rate-text-color');
	 if (formCommentTextColor.length) {
	 var contentTextColor = formCommentTextColor.val();
	 if (contentTextColor) {
	 jQuery('.sgrb-comment-text').attr('style','border-bottom:1px solid '+contentTextColor+'');
	 }
	 }
	 });*/
	SGReview.prototype.checkRateClickedCount();
	SGTemplateHelper.prototype.emptyValueToAdditionalFields();

	jQuery('.sgrb-custom-template-hilghlighting').next().click(function(){
		jQuery('.sgrb-custom-template-hilghlighting').attr('style','position:absolute;color:#3F3F3F;margin-left:10px;z-index:9');
		jQuery(this).prev().attr('style','position:absolute;color:#3F3F3F;margin:-4px;z-index:9');
		jQuery('.sgrb-template-label').find('.sgrb-highlighted').removeClass('sgrb-highlighted');
		jQuery(this).addClass('sgrb-highlighted');
	});

	if (jQuery('.sgrb-custom-template-hilghlighting').length) {
		jQuery('.sgrb-default-template-js').click(function(){
			jQuery('.sgrb-custom-template-hilghlighting').attr('style','position:absolute;color:#3F3F3F;margin-left:10px;z-index:9');
			jQuery('.sgrb-custom-template-hilghlighting').next().removeClass('sgrb-highlighted');
		});
	}

	if (jQuery('.sgrb-template-shadow-style').val()) {
		var shadowStyle = jQuery('.sgrb-template-shadow-style').val();
		jQuery('.sg-template-wrapper').find('.sg-tempo-title').attr('style', shadowStyle);
		jQuery('.sg-template-wrapper').find('.sg-tempo-image ').attr('style', shadowStyle);
		jQuery('.sg-template-wrapper').find('.sg-tempo-title-second ').attr('style', shadowStyle);
		jQuery('.sg-template-wrapper').find('.sg-tempo-title-info ').attr('style', shadowStyle);
		jQuery('.sg-template-wrapper').find('.sg-tempo-context').attr('style', shadowStyle);
	}

	jQuery('.sgrb-field-name').each(function () {
		jQuery(this).on('change keydown keyup', function () {
			if (jQuery(this).val() == '') {
				jQuery(this).addClass('sgrb-input-notice-styles');
			}
			else {
				if (jQuery(this).hasClass('sgrb-input-notice-styles')) {
					jQuery(this).removeClass('sgrb-input-notice-styles');
				}
			}
		});
	});

	var currentFont = jQuery('.sgrb-current-font').val();
	if(currentFont) {
		SGReview.prototype.changeFont(currentFont);
	}

	if (!jQuery('.sgrb-total-rate-title').length) {
		jQuery('.sgrb-total-rate-wrapper').remove();
	}
	jQuery('.sgrb-google-search-checkbox').on('change', function(){
		var googleSearchOn = jQuery(this).prop("checked");
		if (!googleSearchOn) {
			jQuery('.sgrb-hide-google-preview').hide('slow');
			jQuery('.sgrb-google-search-box').attr('style', 'min-height:100px !important;');
		}
		else {
			jQuery('.sgrb-hide-google-preview').show('slow');
			jQuery('.sgrb-google-search-box').removeAttr('style');
		}
	});

	jQuery('.sgrb-email-hide-show-js').on('change', function(){
		var emailNotification = jQuery('.sgrb-email-notification');
		if (jQuery(this).is(':checked')) {
			var adminEmail = jQuery('.sgrb-admin-email').val();
			emailNotification.val(adminEmail);
		}
		else {
			emailNotification.val('');
		}
	});

	jQuery('.sgrb-review-js-update').on('click', function(){
		that.save();
	});

	jQuery('.sgrb-add-field').on('click', function(){
		that.clone();
	});

	jQuery('.sgrb-reset-options').on('click', function(){
		if (confirm('Are you sure?')) {
			jQuery('input[name=skin-color]').val('');
			jQuery('input[name=rate-text-color]').val('');
			jQuery('input[name=total-rate-background-color]').val('');
			jQuery('.wp-color-result').css('background-color','');
		}
	});

	jQuery(function(){
		if(jQuery(".color-picker").length) {
			jQuery(".color-picker").wpColorPicker();
		}
	});

	jQuery('.sgrb-template-selector').on('click', function(){
		var reviewId = jQuery('input[name=sgrb-id]').val();
		var notAllow = false;
		var currentTemplate = jQuery('input[name=sgrb-template]').val();
		/* if it is post review */
		if (reviewId) {
			if (currentTemplate == 'post_review') {
				notAllow = 'Post';
			}
			if (currentTemplate == 'woo_review') {
				notAllow = 'WooCommerce';
			}
			if (notAllow) {
				alert(notAllow+' review cannot be changed to another type');
				return;
			}
		}
		var all = jQuery('#sgrb-template-name').text(),
			container = jQuery('#sgrb-template').dialog({
				width:875,
				height: 600,
				modal: true,
				resizable: false,
				buttons : {
					"Select template": function() {
						var tempName = jQuery('input[name=sgrb-template-radio]:checked').val();
						if (all != tempName) {
							if (confirm('When change the template, you\'ll lose your uploaded images and texts. Continue ?')) {
								if (tempName == 'post_review') {
									jQuery('.sgrb-main-template-wrapper').hide();
									jQuery('.sgrb-template-options-box').hide();
									jQuery('.sgrb-woo-template-wrapper').hide();
									jQuery('.sgrb-post-template-wrapper').show();
									jQuery('.sgrb-template-post-box').attr('style', 'min-height:150px;');
								}
								else if (tempName == 'woo_review') {
									jQuery('.sgrb-main-template-wrapper').hide();
									jQuery('.sgrb-template-options-box').hide();
									jQuery('.sgrb-post-template-wrapper').hide();
									jQuery('.sgrb-woo-template-wrapper').show();
									jQuery('.sgrb-template-post-box').attr('style', 'min-height:150px;');
									jQuery('input[name=wooReviewShowType]').each(function(){
										if (jQuery(this).is(':checked')) {
											var wooReviewShowType = jQuery(this).val();
											if (wooReviewShowType == 'showByProduct') {
												jQuery('.sgrb-woo-category-wrapper').hide();
												jQuery('.sgrb-woo-categories-select-all').hide();
												jQuery('.sgrb-woo-products-wrapper').show();
												jQuery('.sgrb-woo-products-select-all').show();

											}
											else if (wooReviewShowType == 'showByCategory') {
												jQuery('.sgrb-woo-products-wrapper').hide();
												jQuery('.sgrb-woo-products-select-all').hide();
												jQuery('.sgrb-woo-category-wrapper').show();
												jQuery('.sgrb-woo-categories-select-all').show();
											}
										}
									});
								}
								else {
									jQuery('.sgrb-main-template-wrapper').show();
									jQuery('.sgrb-template-options-box').show();
									jQuery('.sgrb-post-template-wrapper').hide();
									jQuery('.sgrb-woo-template-wrapper').hide();
									that.ajaxSelectTemplate(tempName);
								}
								jQuery('input[name=sgrb-template]').val(tempName);
								jQuery('#sgrb-template-name').html(tempName);
								jQuery(this).dialog('destroy');
							}
						}
						else {
							jQuery(this).dialog("close");
						}
					},
					Cancel: function() {
						jQuery(this).dialog("close");
					}
				}
			}),
			scrollTo = jQuery('input[name=sgrb-template-radio]:checked').parent();
		jQuery('input[name=sgrb-template-radio]').each(function(){
			if (jQuery(this).val() == all) {
				jQuery(this).parent().find('input').attr('checked','checked');
				scrollTo = jQuery(this).parent();
			}
		});
		if (scrollTo.length != 0) {
			if(typeof container.offset().top !== 'undefined') {
				container.animate({
					scrollTop: (scrollTo.offset().top - container.offset().top + container.scrollTop()) - 7
					/* Lowered to 7,because label has border and is highlighted (wip) */
				});
			}

		}
		else {
			/* Select template for the first time */
			var defaultTheme = jQuery('#TB_ajaxContent label:first-child');
		}


	});
	var defaultFont = jQuery('.sgrb-main-container .bfh-selectbox-option').text();
	if (defaultFont == '') {
		jQuery('.sgrb-main-container .bfh-selectbox-option').text('Current theme font');
	}
};

SGReview.prototype.deleteTag = function(tagIndex){
	jQuery('#sgrb-tag-index-'+tagIndex).remove();
	jQuery('#sgrb-tag-'+tagIndex).remove();
};

SGReview.prototype.ajaxSaveFreeTables = function(){
	jQuery('.sgrb-review-setting-notice').hide();
	var settingsAction = 'Review_ajaxSaveFreeTables';
	var saveFreeTables = jQuery('input[name=saveFreeTables]').is(':checked');
	if (saveFreeTables) {
		saveFreeTables = 1;
	}
	else {
		saveFreeTables = 0;
	}
	var ajaxHandler = new sgrbRequestHandler(settingsAction, {saveFreeTables:saveFreeTables});
	ajaxHandler.dataType = 'html';
	ajaxHandler.callback = function(response){
		if(response) {
			jQuery('.sgrb-review-setting-notice').show();
			jQuery('.sgrb-review-setting-notice').text('Successfully saved.');
		}
	};
	ajaxHandler.run();
};

SGReview.prototype.ajaxSelectTemplate = function(tempName){
	var changeAction = 'Review_ajaxSelectTemplate';
	var ajaxHandler = new sgrbRequestHandler(changeAction, {template:tempName});
	ajaxHandler.dataType = 'html';
	ajaxHandler.callback = function(response){
		/* If success */
		if(response) {
			jQuery('.sgrb-change-template').empty();
			jQuery('div.sgrb-main-template-wrapper').html(response);
			SGReviewHelper.prototype.uploadImageButton();
			SGReviewHelper.prototype.removeImageButton();
		}
	};
	ajaxHandler.run();

};

SGReview.prototype.save = function(){
	var isEdit = true;
	var sgrbError = false;
	jQuery('.sgrb-updated').remove();
	jQuery('.sgrb-field-name:last').removeClass('sgrb-input-notice-styles');
	var form = jQuery('.sgrb-js-form');
	var font = jQuery('.bfh-selectbox-option').text();
	if (font == 'Current theme font') {
		font = '';
	}
	if(jQuery('.sgrb-title-input').val().replace(/\s/g, "").length <= 0){
		sgrbError = 'Title field is required';
	}
	if (jQuery('.sgrb-one-field').length > 1) {
		jQuery('.sgrb-field-name').each(function() {
			if (jQuery(this).val() == '') {
				sgrbError = 'Empty category fields';
			}
		});
	}
	else if (jQuery('.sgrb-one-field').length == 1 || (jQuery('.sgrb-field-name').first().val() == '')) {
		if (jQuery('.sgrb-field-name').val() == '') {
			sgrbError = 'At least one feature is required';
		}
	}
	if (sgrbError) {
		alert(sgrbError);
		if ((sgrbError == 'At least one feature is required') || (sgrbError == 'Empty feature fields')) {
			jQuery('.sgrb-field-name:last').addClass('sgrb-input-notice-styles');
			jQuery('.sgrb-field-name:last').focus();
		}
		return;
	}
	var products = {};
	if (jQuery('input[name=sgrb-template]').val() == 'woo_review') {
		if (jQuery('#sgrbWooReviewShowTypeProduct').is(':checked')) {
			if (reviewId) {
				var currentProducts = jQuery('.sgrb-all-products-categories').val();
				products = JSON.parse(currentProducts);
			}
			jQuery('.sgrb-woo-product').each(function(){
				if (jQuery(this).is(':checked') && !jQuery(this).attr('disabled')) {
					var eachCategoryId = jQuery(this).val();
					products[eachCategoryId] = 1;
				}
				if (!jQuery(this).is(':checked') && !jQuery(this).attr('disabled')) {
					var eachCategoryId = jQuery(this).val();
					products[eachCategoryId] = 0;
				}
			});
			jQuery('.sgrb-all-products-categories').val(JSON.stringify(products));
		}
		else if (jQuery('#wooReviewShowType').is(':checked')) {
			var id = '';
			jQuery('.sgrb-woo-category').each(function(){
				if (jQuery(this).is(':checked')) {
					var eachCategoryId = jQuery(this).val();
					id += eachCategoryId+',';
					jQuery('.sgrb-all-products-categories').val(id);
				}
			});
		}
	}


	jQuery('.fontSelectbox').val(font);
	var saveAction = 'Review_ajaxSave';
	var ajaxHandler = new sgrbRequestHandler(saveAction, form.serialize());
	ajaxHandler.dataIsObject = false;
	ajaxHandler.dataType = 'html';
	var sgrbSaveUrl = jQuery('.sgrbSaveUrl').val();
	jQuery('.sgrb-loading-spinner').show();
	ajaxHandler.callback = function(response){
		/* If success */
		if(response) {
			jQuery('input[name=sgrb-id]').val(response);
			location.href=sgrbSaveUrl+"&id="+response+'&edit='+isEdit;
		}
		else {
			alert('The review could not be save.');
		}
		jQuery('.sgrb-loading-spinner').hide();

	};
	ajaxHandler.run();
};

SGReview.prototype.clone = function(){
	var oneField = jQuery('.sgrb-one-field');
	var elementsCount = oneField.length,
		elementToClone = oneField.first(),
		elementToAppend = jQuery('.sgrb-field-container'),
		clonedElementId = elementsCount+1,
		clonedElement = elementToClone
		.clone()
		.find("input:text")
		.val("")
		.end()
		.appendTo(elementToAppend)
		.attr('id', 'clone_' + clonedElementId);
	clonedElement
	.find('.sgrb-remove-button')
	.removeAttr('onclick')
	.attr('onclick', "SGReview.remove('clone_"+clonedElementId+"')");
	clonedElement
	.find('.sgrb-fieldId')
	.val('');
	if (jQuery('.sgrb-field-name').length > 1) {
		jQuery('.sgrb-category-empty-warning').hide();
		jQuery('.sgrb-categories-title').attr('style', 'margin-bottom:32px;');
	}
};

SGReview.remove = function(elementId){
	var oneField = jQuery('.sgrb-one-field');
	var elementsCount = oneField.length;
	if (elementsCount <= 1) {
		alert('At least 1 field is needed');
		return;
	}
	if (confirm('Are you sure?')) {
		if (elementId.length > 5) {
			var clone = elementId.slice(0,6);
			if (clone) {
				jQuery('#' + elementId).remove();
				if (jQuery('.sgrb-field-name').length <= 1) {
					jQuery('.sgrb-category-empty-warning').show();
					jQuery('.sgrb-categories-title').removeAttr('style');
				}
				return;
			}
		}

		jQuery('#sgrb_' + elementId).remove();
		SGReview.ajaxDeleteField(elementId);
	}
};

SGReview.ajaxDelete = function(id){
	if (confirm('Are you sure?')) {
		var deleteAction = 'Review_ajaxDelete';
		var ajaxHandler = new sgrbRequestHandler(deleteAction, {id: id});
		ajaxHandler.dataType = 'html';
		ajaxHandler.callback = function(response){
			/* If success */
			location.reload();
		};
		ajaxHandler.run();
	}
};

SGReview.ajaxDeleteField = function(id){
	var deleteAction = 'Review_ajaxDeleteField';
	var ajaxHandler = new sgrbRequestHandler(deleteAction, {id: id});
	ajaxHandler.dataType = 'html';
	ajaxHandler.callback = function(response){
		/* If success */
	};
	ajaxHandler.run();
};

SGReview.ajaxWooProductLoad = function(start,reviewId,perPage){
	var productsToLoad = jQuery('input[name=productsToLoad]').val();
	var allProductsCount = jQuery('input[name=allProductsCount]').val();
	productsToLoad = parseInt(productsToLoad);
	if (!productsToLoad || productsToLoad == 0) {
		alert('Products count to load is not valid (min-max: 1-999)');
		jQuery('input[name=productsToLoad]').val('');
		jQuery('input[name=productsToLoad]').focus();
		return;
	}
	perPage = 500;
	if (jQuery('.sgrb-woo-products-wrapper').hasClass('sgrb-no-first-click')) {
		perPage = productsToLoad;
	}
	jQuery('.sgrb-woo-products-wrapper').show();
	var loading = 'Loading...';
	jQuery('.sgrb-categories-selector').val(loading);
	var productsHtml = '';
	var loadItemsAction = 'Review_ajaxWooProductLoad';
	var ajaxHandler = new sgrbRequestHandler(loadItemsAction, {start:start,reviewId:reviewId,perPage:perPage});
	ajaxHandler.dataType = 'html';
	ajaxHandler.callback = function(response){
		jQuery('.sgrb-load-more-woo').remove();
		var obj = jQuery.parseJSON(response);
		if (jQuery.isEmptyObject(obj)) {
			productsHtml += '<div class="sgrb-each-product-wrapper"><span class="sgrb-woo-product-category-name">No products found</span></div>';
			jQuery('.sgrb-woo-products-wrapper').prepend(productsHtml);
		}
		else {
			var allowCheck = '';
			var disableClass = '';

			for(var i in obj) {
				if (obj[i].matchProdId) {
					disableClass = ' sgrb-disable-woo-products';
					allowCheck = '';
				}
				else {
					disableClass = '';
					allowCheck = 'sgrb-woo-product ';
				}
				productsHtml += '<div class="sgrb-each-product-wrapper"><label for="sgrb-woo-product-'+obj[i].id+'">';
				productsHtml += '<input class="'+allowCheck+obj[i].checkedClass+'" id="sgrb-woo-product-'+obj[i].id+'" type="checkbox" value="'+obj[i].id+'"'+obj[i].checked+obj[i].matchProdId+'>';
				productsHtml += '<span class="sgrb-woo-product-category-name'+disableClass+'">'+obj[i].name+obj[i].matchReview+'</span>';
				productsHtml += '</label></div>';
			}
			start = parseInt(start)+parseInt(productsToLoad);
			if (allProductsCount > start) {
				productsHtml += '<div class="sgrb-load-more-woo"><input onclick="SGReview.ajaxWooProductLoad('+start+','+reviewId+','+productsToLoad+')" class="button-small button sgrb-categories-selector" value="Load more products" type="button"></div>';
			}
			else {
				jQuery('input[name=productsToLoad]').attr('disabled', 'disabled');
				productsHtml += '<div class="sgrb-load-more-woo"><input class="button-small button sgrb-categories-selector" value="No more products" type="button" disabled></div>';
			}
			jQuery('.sgrb-woo-products-wrapper').addClass('sgrb-no-first-click');
			jQuery('.sgrb-woo-products-wrapper').append(productsHtml);
		}

	};
	ajaxHandler.run();
};

SGReview.prototype.ajaxUserRate = function(reviewId, isFirstClick, sgrbFakeId){
	var sgrbMainWrapper = jQuery('#'+sgrbFakeId);
	var categoryCount = 0;
	var clickedCount = 0;
	var skinWrapperToClick = '';
	if (isFirstClick && !sgrbMainWrapper.find('.sgrb-hide-show-wrapper').find('.br-widget').length) {
		jQuery('.sgrb-rate-clicked-count').each(function(){
			jQuery(this).click(function(){
				jQuery(this).data('clicked', true);
			});
			if (jQuery(this).data('clicked')) {
				clickedCount++;
			}
			categoryCount++;
		});
		isFirstClick = false;
		return;
	}
	else if (isFirstClick && sgrbMainWrapper.find('.sgrb-hide-show-wrapper').find('.br-widget').length) {
		jQuery('.sgrb-hide-show-wrapper').find('.br-widget a').each(function(){
			jQuery(this).click(function(){
				jQuery(this).data('clicked', true);
			});
			if (jQuery(this).data('clicked')) {
				clickedCount++;
			}
		});
		sgrbMainWrapper.find('.sgrb-hide-show-wrapper').find('.br-widget').each(function(){
			jQuery(this).click(function(){
				jQuery(this).data('clicked', true);
			});
			categoryCount++;
		});
		isFirstClick = false;
		return;
	}
	var errorInputStyleClass = 'sgrb-form-input-notice-styles';
	sgrbMainWrapper.find('input[name=addTitle]').removeClass(errorInputStyleClass);
	sgrbMainWrapper.find('input[name=addEmail]').removeClass(errorInputStyleClass);
	sgrbMainWrapper.find('input[name=addName]').removeClass(errorInputStyleClass);
	sgrbMainWrapper.find('textarea[name=addComment]').removeClass(errorInputStyleClass);
	sgrbMainWrapper.find('input[name=addTitle]').next().text('');
	sgrbMainWrapper.find('input[name=addEmail]').next().text('');
	sgrbMainWrapper.find('input[name=addName]').next().text('');
	sgrbMainWrapper.find('textarea[name=addComment]').next().text('');
	sgrbMainWrapper.find('.sgrb-notice-rates').hide();
	var error = false,
		captchaError = false,
		requiredEmail = sgrbMainWrapper.find('.sgrb-requiredEmail').val(),
		requiredTitle = sgrbMainWrapper.find('.sgrb-requiredTitle').val(),
		thankText = sgrbMainWrapper.find('.sgrb-thank-text').val(),
		noRateText = sgrbMainWrapper.find('.sgrb-no-rate-text').val(),
		noNameText = sgrbMainWrapper.find('.sgrb-no-name-text').val(),
		noEmailText = sgrbMainWrapper.find('.sgrb-no-email-text').val(),
		noTitleText = sgrbMainWrapper.find('.sgrb-no-title-text').val(),
		noCommentText = sgrbMainWrapper.find('.sgrb-no-comment-text').val(),
		noCaptchaText = sgrbMainWrapper.find('.sgrb-no-captcha-text').val(),
		name = sgrbMainWrapper.find('input[name=addName]').val(),
		email = sgrbMainWrapper.find('input[name=addEmail]').val(),
		title = sgrbMainWrapper.find('input[name=addTitle]').val(),
		comment = sgrbMainWrapper.find('textarea[name=addComment]').val();
	if (sgrbMainWrapper.find('.sgrb-hide-show-wrapper').find('.br-widget').length) {
		skinWrapperToClick = '.br-widget a';
		sgrbMainWrapper.find('.sgrb-hide-show-wrapper').find('.br-widget a').each(function(){
			jQuery(this).click(function(){
				jQuery(this).data('clicked', true);
			});
			if (jQuery(this).data('clicked')) {
				clickedCount++;
			}
		});
		sgrbMainWrapper.find('.sgrb-hide-show-wrapper').find('.br-widget').each(function(){
			jQuery(this).click(function(){
				jQuery(this).data('clicked', true);
			});
			categoryCount++;
		});
		isFirstClick = false;
	}
	else {
		skinWrapperToClick = '.sgrb-rate-each-skin-wrapper';
		sgrbMainWrapper.find('.sgrb-hide-show-wrapper').find('.sgrb-rate-each-skin-wrapper').each(function(){
			jQuery(this).click(function(){
				jQuery(this).data('clicked', true);
			});
			if (jQuery(this).data('clicked')) {
				clickedCount++;
			}
			categoryCount++;
		});
		isFirstClick = false;
	}
	if (sgrbMainWrapper.find('input[name=sgRate]').val() == 0) {
		if ((parseInt(categoryCount) != parseInt(clickedCount)) || (parseInt(categoryCount) > parseInt(clickedCount))) {
			error = noRateText;
			sgrbMainWrapper.find('.sgrb-user-comment-submit').removeAttr('disabled');
			sgrbMainWrapper.find('.sgrb-notice-rates span').show().text(error);
			sgrbMainWrapper.find('.sgrb-notice-rates').show();
		}
	}
	if (sgrbMainWrapper.find('input[name=captchaOn]').val() == 1) {
		var captchaCode = jQuery('#sgrb-captcha-'+reviewId).realperson('getHash');
	}
	else {
		var captchaCode = '';
	}
	/* default form errors */
	var validationString = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	if (sgrbMainWrapper.find('input[name=customForm]').val() == 0) {
		if (requiredEmail && !email) {
			error = noEmailText;
		}
		if (!(validationString.test(email)) && requiredEmail) {
			error = noEmailText;
			sgrbMainWrapper.find('input[name=addEmail]').addClass(errorInputStyleClass);
			sgrbMainWrapper.find('input[name=addEmail]').next().text(error);
		}
		else if (!requiredEmail && email != '' && !(validationString.test(email))) {
			error = 'Invalid email address';
			sgrbMainWrapper.find('input[name=addEmail]').addClass(errorInputStyleClass);
			sgrbMainWrapper.find('input[name=addEmail]').next().text(error);
		}
		if (requiredTitle && !title) {
			error = noTitleText;
			sgrbMainWrapper.find('input[name=addTitle]').addClass(errorInputStyleClass);
			sgrbMainWrapper.find('input[name=addTitle]').next().text(error);
		}
		if (!name) {
			error = noNameText;
			sgrbMainWrapper.find('input[name=addName]').addClass(errorInputStyleClass);
			sgrbMainWrapper.find('input[name=addName]').next().text(error);
		}
		if (!comment) {
			error = noCommentText;
			sgrbMainWrapper.find('textarea[name=addComment]').addClass(errorInputStyleClass);
			sgrbMainWrapper.find('textarea[name=addComment]').next().text(error);
		}
	}
	else {
		/* created form errors */
		sgrbMainWrapper.find('.sgrb-user-form-error').text('');
		var hasInputField = false;
		var hasInput = sgrbMainWrapper.find('input');
		if (hasInput) {
			var hasInputField = true;
		}
		if (sgrbMainWrapper.find('.sgrb-comment-form-asterisk').length || hasInputField) {
			sgrbMainWrapper.find('input').each(function(){
				if (jQuery(this).attr('type') == 'email') {
					var emailText = jQuery(this).val();
					var errorEmailFieldInput = jQuery(this);
					var emailFieldError = jQuery(this).next();
					if (!(validationString.test(emailText)) && emailText != '') {
						error = 'Invalid email address';
						errorEmailFieldInput.addClass(errorInputStyleClass);
						emailFieldError.text(error);
					}
				}
				if (jQuery(this).attr('type') == 'number') {
					var emailText = jQuery(this).val();
					var errorEmailFieldInput = jQuery(this);
					var emailFieldError = jQuery(this).next();
					var validNumber = /^[0-9]+$/;
					if (emailText.match(validNumber) == '' || jQuery.isEmptyObject(emailText.match(validNumber))) {
						error = 'Not a number';
						errorEmailFieldInput.addClass(errorInputStyleClass);
						emailFieldError.text(error);
					}
				}
			});
			sgrbMainWrapper.find('.sgrb-comment-form-asterisk').each(function(){
				var errorFieldName = jQuery(this).prev().text();
				if (errorFieldName == '' || errorFieldName === 'undefined') {
					var errorFieldName = 'Current';
				}
				/* if no label */
				if (errorFieldName == 'Current') {
					var errorFieldInput = jQuery(this).parent().find('input');
					var errorFieldTextarea = jQuery(this).parent().find('textarea');
					var errorFieldInputType = jQuery(this).next().attr('type');
					var fieldError = jQuery(this).parent().find('.sgrb-user-form-error');
				}
				else {
					var errorFieldInput = jQuery(this).next().find('input');
					var errorFieldTextarea = jQuery(this).next().find('textarea');
					var errorFieldInputType = jQuery(this).next().find('input').attr('type');
					var fieldError = jQuery(this).next().find('.sgrb-user-form-error');
				}
				fieldError.text('');
				if (errorFieldInput.length) {
					if (errorFieldInputType == 'text') {
						errorFieldInput.removeClass(errorInputStyleClass);
						if (errorFieldInput.val() == '') {
							error = errorFieldName+' field is required';
							errorFieldInput.addClass(errorInputStyleClass);
							fieldError.text(error);
						}
					}
					if (errorFieldInputType == 'number') {
						var numbers = /^[0-9]+$/;
						errorFieldInput.removeClass(errorInputStyleClass);
						if (errorFieldInput.val().match(numbers) == '' || jQuery.isEmptyObject(errorFieldInput.val().match(numbers))) {
							error = errorFieldName+' field is required';
							errorFieldInput.addClass(errorInputStyleClass);
							fieldError.text(error);
						}
					}
					if (errorFieldInputType == 'email') {
						errorFieldInput.removeClass(errorInputStyleClass);
						if (errorFieldInput.val() == '') {
							error = errorFieldName+' field is required';
							errorFieldInput.addClass(errorInputStyleClass);
							fieldError.text(error);
						}
						else if (!(validationString.test(errorFieldInput.val()))) {
							error = 'Invalid email address';
							errorFieldInput.addClass(errorInputStyleClass);
							fieldError.text(error);
						}
					}
				}
				if (errorFieldTextarea.length) {
					if (errorFieldTextarea.val() == '') {
						error = errorFieldName+' field is required';
						errorFieldTextarea.addClass(errorInputStyleClass);
						fieldError.text(error);
					}
					else {
						fieldError.val('');
						errorFieldTextarea.removeClass(errorInputStyleClass);
					}
				}
			});
		}
	}
	if (error) {
		return;
	}
	sgrbMainWrapper.find('.sgrb-user-comment-submit').attr('disabled','disabled');
	var form = sgrbMainWrapper.parent(),
		cookie = sgrbMainWrapper.find('.sgrb-cookie').val(),
		saveAction = 'Review_ajaxUserRate';
	var ajaxHandler = new sgrbRequestHandler(saveAction, form.serialize()+'&captchaCode='+captchaCode);
	ajaxHandler.dataType = 'html';
	ajaxHandler.dataIsObject = false;
	ajaxHandler.callback = function(response){
		if (response != 0 && !isNaN(response)) {
			if (sgrbMainWrapper.find('.sgrb-total-rate-title').length == 0) {
				sgrbMainWrapper.find('.sgrb-total-rate-wrapper').removeAttr('style');
			}
			sgrbMainWrapper.find('.sgrb-notice-rates').hide(500);
			sgrbMainWrapper.find('.sgrb-hide-show-wrapper').hide(1000);
			sgrbMainWrapper.find('.sgrb-user-comment-wrapper').append('<span>'+thankText+'</span>');
			jQuery.cookie('rater', cookie);
		}
		else if (response == false) {
			captchaError = noCaptchaText;
			sgrbMainWrapper.find('.sgrb-user-comment-submit').removeAttr('disabled');
			sgrbMainWrapper.find('.sgrb-captcha-notice span').show().text(captchaError);
			sgrbMainWrapper.find('.sgrb-captcha-notice').show();
			sgrbMainWrapper.find('input[name=addCaptcha]').addClass(errorInputStyleClass);
		}
	};
	ajaxHandler.run();
};

SGReview.prototype.ajaxCloneReview = function (id) {
	var cloneAction = 'Review_ajaxCloneReview';
	var ajaxHandler = new sgrbRequestHandler(cloneAction, {id: id});
	ajaxHandler.dataType = 'html';
	ajaxHandler.callback = function(response){
		/* If success */
		if (response != null) {
			location.reload();
		}
		else {
			alert('Could not clone this review');
		}
	};
	ajaxHandler.run();
};

/**
 * rateSelectboxHtmlBuilder() get skin style for show preview.
 * @param type is integer
 */
SGReview.prototype.rateSelectboxHtmlBuilder = function (type,span,count) {
	var selectBox = jQuery('.sgrb-select-box-count');
	jQuery('.sgrb-rate-count-span').text(span);
	jQuery('code').text(type);
	selectBox.val(count);
	var selectBoxCount = selectBox.val();
	var htmlRateSelectBox = '';
	for (var i=1;i<=selectBoxCount;i++) {
		htmlRateSelectBox += '<option value="'+i+'">'+i+'</option>';
	}
	jQuery('.sgrb-rate').empty();
	jQuery(htmlRateSelectBox).appendTo('.sgrb-rate');
};

/**
 * isHidden() checked if review rated by current user
 * and hide the comment form
 */
SGReview.prototype.isHidden = function () {
	if (!jQuery('.sgrb-hide-show-wrapper').is(":visible")) {
		/* jQuery('.sgrb-row-category').hide(); */
	}
};

SGReview.prototype.changeFont = function (fontName) {
	var font = fontName.replace(new RegExp(" ",'g'),"");
	var res = font.match(/[A-Z][a-z]+/g);
	var result = '';

	for (var i=0;i<res.length;i++) {
		result += res[i]+' ';
	}
	WebFontConfig = {
		google: { families: [ result.substr(0, result.length-1) ] }
	};
	(function() {
		var wf = document.createElement('script');
		wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
			'://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
		wf.type = 'text/javascript';
		wf.async = 'true';
		var s = document.getElementsByTagName('script')[0];
		s.parentNode.insertBefore(wf, s);

	})();
};

SGReview.prototype.checkRateClickedCount = function() {
	SGReview.prototype.ajaxUserRate(false, true);
};

function arrayUnique(array) {
	var a = array.concat();
	for(var i=0; i<a.length; ++i) {
		for(var j=i+1; j<a.length; ++j) {
			if(a[i] === a[j])
				a.splice(j--, 1);
		}
	}

	return a;
}