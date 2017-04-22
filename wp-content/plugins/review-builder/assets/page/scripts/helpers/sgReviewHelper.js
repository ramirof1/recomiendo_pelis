function SGReviewHelper(){
}

SGReviewHelper.prototype.init = function () {
	var that = this;

	that.uploadImageButton();
	that.removeImageButton();
	that.captchaReCall();

	jQuery('.sgrb-tagcloud-link').click(function(){
		jQuery('.sgrb-tags-cloud').toggle();
	});

	jQuery('.sgrb-select-all-products').click(function(){
		jQuery('.sgrb-woo-product').prop('checked', this.checked);
	});

	jQuery('.sgrb-select-all-categories').click(function(){
		jQuery('.sgrb-selected-categories').prop('checked', this.checked);
	});

	/* add tags */
	jQuery('.sgrb-tagadd').click(function(){
		that.setTags(false);
	});

};

/*
 * setTags()
 */

SGReviewHelper.prototype.setTags = function (hasTags) {
	var newTag = jQuery('.sgrb-newtag').val();
	if (hasTags) {
		newTag = hasTags;
	}
	var tagsArray = [];
	jQuery('.sgrb-new-tag-text').each(function(){
		var tagsString = jQuery(this).text();
		if (tagsString) {
			tagsArray.push(tagsString);
		}
	});
	if (newTag.replace(/\s/g, "").length <= 0) {
		jQuery('.sgrb-newtag').val('');
		jQuery('.sgrb-newtag').focus();
		return;
	}
	if (newTag != '') {
		var hasComma = newTag.search(',');
		if (hasComma > 0) {
			newTag = newTag.split(',');
			var array3 = arrayUnique(newTag.concat(tagsArray));
			jQuery('.tagchecklist').empty();
			array3.sort();
			for (var i=0;i<array3.length;i++) {
				tagsArray.push(array3[i]);
				var tagHtml = '<span id="sgrb-tag-index-'+SGRB.tagIndex+'"><a href="javascript:void(0)" id="post_tag-check-num-'+SGRB.tagIndex+'" onclick="SGReview.prototype.deleteTag('+SGRB.tagIndex+')" class="ntdelbutton" tabindex="'+SGRB.tagIndex+'"></a></span><span id="sgrb-tag-'+SGRB.tagIndex+'" class="sgrb-new-tag-text">'+array3[i]+'</span> <input type="hidden" value="'+array3[i]+'" name="tagsArray[]">';
				jQuery('.tagchecklist').append(tagHtml);
				jQuery('.sgrb-newtag').val('');
				jQuery('.sgrb-newtag').focus();
				SGRB.tagIndex = parseInt(SGRB.tagIndex+1);
			}
		}
		else {
			newTag = jQuery.makeArray(newTag);
			var array3 = arrayUnique(newTag.concat(tagsArray));
			jQuery('.tagchecklist').empty();
			array3.sort();
			for (var i=0;i<array3.length;i++) {
				var tagHtml = '<span id="sgrb-tag-index-'+SGRB.tagIndex+'"><a href="javascript:void(0)" id="post_tag-check-num-'+SGRB.tagIndex+'" onclick="SGReview.prototype.deleteTag('+SGRB.tagIndex+')" class="ntdelbutton" tabindex="'+SGRB.tagIndex+'"></a></span><span id="sgrb-tag-'+SGRB.tagIndex+'" class="sgrb-new-tag-text">'+array3[i]+'</span> <input type="hidden" value="'+array3[i]+'" name="tagsArray[]">';
				tagsArray.push(newTag);
				jQuery('.tagchecklist').append(tagHtml);
				jQuery('.sgrb-newtag').val('');
				jQuery('.sgrb-newtag').focus();
				SGRB.tagIndex = parseInt(SGRB.tagIndex+1);
			}
		}
	}
	else {
		jQuery('.sgrb-newtag').val('');
		jQuery('.sgrb-newtag').focus();
	}
};

SGReviewHelper.prototype.uploadImageButton = function(){
	jQuery('span.sgrb-upload-btn').on('click', function(e) {
		var wrapperDiv = jQuery(this).parent().parent(),
			wrap = jQuery(this),
			imgNum = jQuery(this).next('.sgrb-img-num').attr('data-auto-id');
		e.preventDefault();
		var image = wp.media({
			title: 'Upload Image',
			multiple: false
		}).open()
		.on('select', function(e){
			var uploaded_image = image.state().get('selection').first();
			var image_url = uploaded_image.toJSON().url;
			jQuery('#sgrb_image_url_'+imgNum).val(image_url);
			jQuery(wrap).addClass('sgrb-image-review-plus');
			jQuery(wrapperDiv).addClass('sgrb-image-review');
			jQuery(wrapperDiv).parent().attr('style',"background-image:url("+image_url+")");
			jQuery(wrapperDiv).parent().parent().attr('style',"border:none;");
		});
	});
};

SGReviewHelper.prototype.removeImageButton = function(){
	jQuery('span.sgrb-remove-img-btn').on('click', function() {
		jQuery(this).parent().parent().parent().attr('style', "background-image:url()");
		jQuery(this).parent().parent().find('.sgrb-images').val('');
		jQuery(this).parent().parent().parent().parent().attr('style',"border: 2px dashed #ccc;border-radius: 10px;");
	});
};


SGReviewHelper.prototype.captchaReCall = function () {
	jQuery('.sgrb-reviewFakeId').each(function(){
		/* reviewId is real review id, sgrbFakeId is fake but unique review id */
		var reviewId = jQuery(this).next().val();
		var sgrbFakeId = jQuery(this).val();
		var sgrbMainWrapper = jQuery('#'+sgrbFakeId);
		if (sgrbMainWrapper.find('.sgrb-captchaOn').val() == 1) {
			var captchaRegenerate = sgrbMainWrapper.find('.sgrb-captcha-text').val();
			sgrbMainWrapper.find('#sgrb-captcha-'+reviewId).realperson({
				regenerate: captchaRegenerate
			});
		}
		if (sgrbMainWrapper.find('.sgrb-approved-comments-to-show').length) {
			var commentsPerPage = parseInt(sgrbMainWrapper.find('.sgrb-comments-count').val());
			SGReviewHelper.prototype.ajaxLazyLoading(1, 0, commentsPerPage, reviewId, sgrbFakeId);
		}
	});
};

SGReviewHelper.prototype.ajaxLazyLoading = function(page, itemsRangeStart, perPage, reviewId, sgrbFakeId){
	var count = 0;
	var sgrbMainWrapper = jQuery('#'+sgrbFakeId);
	if (sgrbMainWrapper.find('.sgrb-load-it').length != '') {
		perPage = parseInt(sgrbMainWrapper.find('.sgrb-comments-count-load').val());
	}
	var postId = '';
	var commentsPerPage = perPage,
		pageCount = sgrbMainWrapper.find('.sgrb-page-count').val(),
		postId = sgrbMainWrapper.find('.sgrb-post-id').val(),
		loadMore = sgrbMainWrapper.find('.sgrb-comment-load'),
		arr = parseInt(sgrbMainWrapper.find('.sgrb-current-page').text());

	var review = sgrbMainWrapper.find('.sgrb-reviewId').val();
	var skinType = sgrbMainWrapper.find('.sgrb-rating-type').val();
	var jPageAction = 'Review_ajaxLazyLoading';
	var ajaxHandler = new sgrbRequestHandler(jPageAction, {review:review,page:page,itemsRangeStart:itemsRangeStart,perPage:perPage,postId:postId});
	ajaxHandler.dataType = 'html';
	sgrbMainWrapper.find('.sgrb-loading-spinner').show();
	sgrbMainWrapper.find('.sgrb-comment-load').hide();
	ajaxHandler.callback = function(response){
		var obj = jQuery.parseJSON(response);
		var skinHtml = SGRateSkin.prototype.prepareSkinHtml(skinType);
		var next = parseInt(itemsRangeStart+commentsPerPage);
		SGCommentHelper.prototype.generateCommentHtml(sgrbFakeId, obj, skinHtml, reviewId, next, commentsPerPage, itemsRangeStart);
		/*var next = parseInt(itemsRangeStart+commentsPerPage);*/

		/*if (jQuery.isEmptyObject(obj)) {
			sgrbMainWrapper.find('.sgrb-loading-spinner').hide();
			loadMore.attr({
				'disabled':'disabled',
				'style' : 'cursor:default;color:#c1c1c1;vertical-align: text-top;pointer-events: none;'
			}).text('no more comments');
			return;
		}
		else {
			var commentsCount = obj[0].count;
			loadMore.removeAttr('disabled style');
			loadMore.attr('onclick','SGReviewHelper.prototype.ajaxLazyLoading(1,'+next+','+commentsPerPage+','+reviewId+','+sgrbFakeId+')');
		}*/

		if (!sgrbMainWrapper.find('.sgrb-row-category').is(':visible') || jQuery('.sgrb-widget-wrapper')) {
			sgrbMainWrapper.find('.sgrb-widget-wrapper .sgrb-comment-load').remove();
		}

		//var skinHtml = SGRateSkin.prototype.prepareSkinHtml(skinType);
		//SGCommentHelper.prototype.generateCommentHtml(sgrbFakeId, obj, skinHtml);
		/* show widget skin after skinHtml loads */
		SGReviewHelper.prototype.prepareWidget(sgrbFakeId, skinType);
	};
	ajaxHandler.run();
};

/**
 * prepareWidget() - prepare the widget skin;
 * @param reviewId - unique review id;
 * @param skinType - skin type integer (star=1, percent=2, point=3);
 */
SGReviewHelper.prototype.prepareWidget = function (reviewId, skinType) {
	var wrapper = jQuery('#'+reviewId);
	if (wrapper.hasClass('sgrb-widget-wrapper')) {
		if (skinType == SGRateSkin.prototype.sgrbRateTypeStar) {
			SGRateSkin.prototype.displayWidgetStarSkin(wrapper);
		}
		if (skinType == SGRateSkin.prototype.sgrbRateTypePercent) {
			SGRateSkin.prototype.displayWidgetPercentSkin(wrapper);
		}
		if (skinType == SGRateSkin.prototype.sgrbRateTypePoint) {
			SGRateSkin.prototype.displayWidgetPointSkin(wrapper);
		}
	}
};