function SGCommentHelper() {
}

SGCommentHelper.prototype.init = function () {
	var that = this;

	jQuery('.sgrb-read-more').on('click', function(){
		that.showHideComment('sgrb-read-more');
	});

	jQuery('.sgrb-hide-read-more').on('click', function(){
		that.showHideComment('sgrb-hide-read-more');
	});

	jQuery('#wpcontent').find('.subsubsub').attr('style','margin-top:66px;position:absolute;');
};
/**
 * generateCommentHtml() generates comment html;
 * @param sgrbFakeId - unique review id;
 * @param obj - response, obj with comments data (name, id, rates...);
 * @param skinHtml - html of selected rate skin type (star, percent, point);
 */
SGCommentHelper.prototype.generateCommentHtml = function (sgrbFakeId, obj, skinHtml, reviewId, next, commentsPerPage, itemsRangeStart) {
	var sgrbMainWrapper = jQuery('#'+sgrbFakeId),
		loadMore = sgrbMainWrapper.find('.sgrb-comment-load');
	var isWidget = sgrbMainWrapper.hasClass('sgrb-widget-wrapper');
	var additionalFieldWrapper = '';
	var noAdditionalFields = false;

	var commentHtml = '';
	var formBackgroundColor = sgrbMainWrapper.find('.sgrb-rate-background-color').val();
	var formTextColor = sgrbMainWrapper.find('.sgrb-rate-text-color').val();
	if (!formBackgroundColor) {
		formBackgroundColor = '#fbfbfb';
	}
	if (!formTextColor) {
		formTextColor = '#4c4c4c';
	}
	if (jQuery.isEmptyObject(obj)) {
		sgrbMainWrapper.find('.sgrb-loading-spinner').hide();
		loadMore.attr({
			'disabled':'disabled',
			'style' : 'cursor:default;color:#c1c1c1;vertical-align: text-top;pointer-events: none;'
		}).text('no more comments');
		return;
	}
	else {
		loadMore.removeAttr('disabled style');
		loadMore.attr('onclick','SGReviewHelper.prototype.ajaxLazyLoading(1,'+next+','+commentsPerPage+','+reviewId+','+sgrbFakeId+')');
	}
	for(var i in obj) {
		if (!sgrbMainWrapper.find('input[name=customForm]').val()) {
			/* if default comment form selected */
			commentHtml += this.prepareDefaultHtml(sgrbFakeId, obj[i], skinHtml, formBackgroundColor, formTextColor);

			if (typeof count === 'undefined' || count == '') {
				count = obj[i].count;
			}
			continue;
		}
		/* else create custom form comment html
		* prepare comment(object) data;
		* */
		var labelName = '',
		 commentId = '',
		 commentDate = '',
		 asComment = '',
		 asTitle = '',
		 asName = '',
		 allowToShow = '',
		 fieldValue = '',
		 isTextarea = '',
		 count = '',
		 rates = '',
		comment = '';

		comment = this.prepareCommentData(obj[i]);
		labelName = comment.key;/* additional fields */
		commentId = comment.id;
		commentDate = comment.date;
		allowToShow = comment.show;
		asName = comment.name;
		asComment = comment.comment;
		asTitle = comment.title;
		fieldValue = comment.additional.val;
		isTextarea = comment.additional.isTextarea;
		count = comment.count;
		rates = comment.rates;

		/* check if additioanl field exists */
		if (!jQuery.isEmptyObject(labelName)) {
			additionalFieldWrapper = '<div class="sg-row">' +
										'<div class="sg-col-12">' +
											'<div class="sgrb-comment-wrapper">' +
												'<ul class="sgrb-additioanl-item-list">';
			for (var k = 0; k < labelName.length; k++) {
				if (allowToShow[k]) {
					if (!jQuery.isEmptyObject(labelName)) {
						if (labelName[k] != '' || typeof labelName[k] === 'undefined') {
							labelName[k] = labelName[k] + ':';
						}
					}
					if (isTextarea[k] && fieldValue[k].length >= 200) {
						additionalFieldWrapper += '<li>' +
													'<input class="sgrb-full-comment" type="hidden" value="' + fieldValue[k] + '"><b>' + labelName[k] + ' </b> ' +
													'<span class="sgrb-comment-text-js sgrb-comment-max-height">';
						additionalFieldWrapper += fieldValue[k].substring(0, 200) + ' ... <a onclick="SGCommentHelper.prototype.showHideComment(' + sgrbFakeId + ', ' + commentId + ', \'sgrb-read-more\')" href="javascript:void(0)" class="sgrb-read-more">show all&#9660</a>' +
													'</span>' +
												'</li>';
					}
					else {
						additionalFieldWrapper += '<li>' +
													'<span><b>' + labelName[k] + ' </b>' + fieldValue[k] + '</span>' +
												'</li>';
					}
				}
				else {
					noAdditionalFields = true;
				}
			}
			additionalFieldWrapper +=   '</ul>' +
									'</div>' +
								'</div>' +
							'</div>';
		}
		commentHtml += '<div class="sgrb-approved-comments-wrapper sgrb-comment-' + commentId + '" style="background-color:' + formBackgroundColor + ';color:' + formTextColor + '">';

		/* if widget review add rate skin html */
		if (isWidget) {
			commentHtml += '<input type="hidden" class="sgrb-each-comment-avg-widget" value="' + comment.rates + '">';
			commentHtml += '<div class="sg-row">' +
								'<div class="sg-col-12">' +
									'<div class="sgrb-comment-wrapper sgrb-each-comment-rate">' + skinHtml + '</div>' +
								'</div>' +
							'</div>';
		}

		/* add title section (header) */
		if (asTitle != '' && typeof asTitle !== 'undefined') {
			commentHtml += '<div class="sg-row">' +
								'<div class="sg-col-12">' +
									'<div class="sgrb-comment-wrapper">' +
										'<span style="width:100%;"><i><b>' + asTitle + ' </i></b></span>' +
									'</div>' +
								'</div>' +
							'</div>';
		}

		/* add list with additioanla items */
		commentHtml += additionalFieldWrapper;

		/* if form has asComment field */
		if (asComment != '' && typeof asComment !== 'undefined') {
			commentHtml += '<div class="sg-row">' +
								'<div class="sg-col-12">' +
									'<div class="sgrb-comment-wrapper">';
			/* if widget review make comment section length smaller (80 chars) */
			if (asComment.length >= 80 && isWidget) {
				commentHtml += '<input class="sgrb-full-comment" type="hidden" value="' + asComment + '">';
				commentHtml += '<span class="sgrb-comment-text-js sgrb-comment-max-height">'+ asComment.substring(0, 80) + ' ... <a onclick="SGCommentHelper.prototype.showHideComment(' + sgrbFakeId + ',' + commentId + ', \'sgrb-read-more\')" href="javascript:void(0)" class="sgrb-read-more">show all&#9660</a></span>';
			}
			else if (asComment.length >= 200 && !isWidget) {
				/* else set comment section length to default (200 chars) */
				commentHtml += '<input class="sgrb-full-comment" type="hidden" value="' + asComment + '">';
				commentHtml += '<span class="sgrb-comment-text-js sgrb-comment-max-height">' + asComment.substring(0, 200) + ' ... <a onclick="SGCommentHelper.prototype.showHideComment(' + sgrbFakeId + ',' + commentId + ', \'sgrb-read-more\')" href="javascript:void(0)" class="sgrb-read-more">show all&#9660</a></span>';
			}
			else {
				/* if short comment, don't cut */
				commentHtml += '<span class="sgrb-comment-text">' + asComment + '</span>';
			}
			commentHtml += '</div>' +
						'</div>' +
					'</div>';
		}
		/* if form has asName field (footer) */
		if (asName != '' && typeof asName !== 'undefined') {
			if (asName.length >= 100) {
				var addWidth = 'style="width:95%;"';
			}
			else {
				var addWidth = '';
			}
			commentHtml += '<div class="sg-row">' +
								'<div class="sg-col-12">' +
									'<span class="sgrb-name-title-text" ' + addWidth + '><b>' + commentDate + '</b> <i> , comment by </i><b>&nbsp;' + asName + '</b> </span>' +
								'</div>' +
							'</div>';
		}
		else {
			commentHtml += '<div class="sg-row"><div class="sg-col-12"><span class="sgrb-name-title-text"><b>' + commentDate + '</b></span></div></div>';
		}
		commentHtml += '</div>';
	}
	this.displayCommentHtml(sgrbFakeId, commentHtml, noAdditionalFields, count);
};

/**
 * displayCommentHtml() display the result of generated comment html;
 * @param reviewId - review id;
 * @param commentHtml - comments html to append to wrapper;
 * @param noAdditionalFields - boolean,checks in comment html has no additional fields;
 * @param commentsCount - integer, count of all comments for current review;
 */
SGCommentHelper.prototype.displayCommentHtml = function (reviewId, commentHtml, noAdditionalFields, commentsCount) {
	var wrapper = jQuery('#'+reviewId);
	var loadMore = wrapper.find('.sgrb-comment-load');
	if (wrapper.length > 0) {
		wrapper.find('.sgrb-approved-comments-to-show').append(commentHtml);
	}
	if (noAdditionalFields) {
		wrapper.find('.sgrb-additioanl-item-list').parent().remove();
	}
	wrapper.find('.sgrb-approved-comments-wrapper').each(function(){
		if (jQuery(this).length) {
			var countOfLoadedComments = wrapper.find('.sgrb-approved-comments-wrapper').length;
			if (countOfLoadedComments && commentsCount && countOfLoadedComments == commentsCount) {
				loadMore.attr({
					'disabled':'disabled',
					'style' : 'cursor:default;color:#c1c1c1;vertical-align: text-top;pointer-events: none;'
				}).text('no more comments');
			}
		}
	});
	wrapper.find('.sgrb-approved-comments-to-show').addClass('sgrb-load-it');
	wrapper.find('.sgrb-loading-spinner').hide();
	wrapper.find('.sgrb-comment-load').show();
};

/**
 * prepareCommentData() prepare the comment object values then return;
 * @param comment - object of comment with values;
 */
SGCommentHelper.prototype.prepareCommentData = function (comment) {
	if (typeof comment.key === 'undefined' && comment.key == '' && !jQuery.isEmptyObject(comment.key)) {
		comment.key = '';
	}
	if (typeof comment.id === 'undefined' && comment.id == '') {
		comment.id = '';
	}
	if (typeof comment.date === 'undefined' && comment.date == '') {
		comment.date = '';
	}
	if (typeof comment.show === 'undefined' && comment.show == '') {
		comment.show = '';
	}
	if (typeof comment.name === 'undefined' && comment.name == '') {
		comment.name = '';
	}
	if (typeof comment.comment === 'undefined' && comment.comment == '') {
		comment.comment = '';
	}
	if (typeof comment.title === 'undefined' && comment.title == '') {
		comment.title = '';
	}
	if (typeof comment.count === 'undefined' && comment.count == '') {
		comment.count = '';
	}
	if (typeof comment.rates === 'undefined' && comment.rates == '') {
		comment.rates = '';
	}

	if (typeof comment.additional !== 'undefined' && comment.additional != '' && !jQuery.isEmptyObject(comment.additional)) {
		if (typeof comment.additional.val !== 'undefined' && comment.additional.val != '' && !jQuery.isEmptyObject(comment.additional.val)) {
			comment.additional.val = comment.additional.val;
		}
		else {
			comment.additional.val = '';
		}

		if (typeof comment.additional.isTextarea !== 'undefined' && comment.additional.isTextarea != '' && !jQuery.isEmptyObject(comment.additional.isTextarea)) {
			comment.additional.isTextarea = comment.additional.isTextarea;
		}
		else {
			comment.additional.isTextarea = '';
		}
	}
	else {
		comment.additional = '';
	}
	return comment;
};

/**
 * prepareDefaultHtml() prepare comment html
 * for default comment form;
 * @param sgrbFakeId - current review unique id;
 * @param comment - comment object with values;
 * @param skinHtml - rate skin html;
 * @param formBackgroundColor - from background color;
 * @param formTextColor - from text color;
 */
SGCommentHelper.prototype.prepareDefaultHtml = function (sgrbFakeId, comment, skinHtml, formBackgroundColor, formTextColor) {
	var sgrbMainWrapper = jQuery('#'+ sgrbFakeId);
	var isWidget = sgrbMainWrapper.hasClass('sgrb-widget-wrapper');
	var commentHtml = '';
	commentHtml += '<div id="" class="sgrb-approved-comments-wrapper sgrb-comment-' + comment.id + '" style="background-color:' + formBackgroundColor + ';color:' + formTextColor + '">';
	/* if widget review add rate skin html */
	if (isWidget) {
		commentHtml += '<input type="hidden" class="sgrb-each-comment-avg-widget" value="' + comment.rates + '">';
		commentHtml += '<div class="sg-row">' +
							'<div class="sg-col-12">' +
								'<div class="sgrb-comment-wrapper sgrb-each-comment-rate">' + skinHtml + '</div>' +
							'</div>' +
						'</div>';
	}
	/* add title section (header) */
	commentHtml += '<div class="sg-row">' +
						'<div class="sg-col-12">' +
							'<div class="sgrb-comment-wrapper">' +
								'<span style="width:100%;">' +
									'<i><b>' + comment.title + ' </i></b>' +
								'</span>' +
							'</div>' +
						'</div>' +
					'</div>';
	/* add comment section (content) */
	commentHtml += '<div class="sg-row">' +
						'<div class="sg-col-12">' +
							'<div class="sgrb-comment-wrapper">';
							/* if widget review make comment section length smaller (80 chars) */
							if (comment.comment.length >= 80 && isWidget) {
								commentHtml += '<input class="sgrb-full-comment" type="hidden" value="' + comment.comment + '">';
								commentHtml += '<span class="sgrb-comment-text-js sgrb-comment-max-height">'+
													comment.comment.substring(0, 80) +
													' ... <a onclick="SGCommentHelper.prototype.showHideComment(' + sgrbFakeId + ',' + comment.id + ', \'sgrb-read-more\')" href="javascript:void(0)" class="sgrb-read-more">show all&#9660</a>' +
												'</span>';
							}
							else if (comment.comment.length >= 200 && !isWidget) {
								/* else set comment section length to default (200 chars) */
								commentHtml += '<input class="sgrb-full-comment" type="hidden" value="' + comment.comment + '">';
								commentHtml += '<span class="sgrb-comment-text-js sgrb-comment-max-height">'+
													comment.comment.substring(0, 200) +
													' ... <a onclick="SGCommentHelper.prototype.showHideComment(' + sgrbFakeId + ',' + comment.id + ', \'sgrb-read-more\')" href="javascript:void(0)" class="sgrb-read-more">show all&#9660</a>' +
												'</span>';
							}
							else {
								/* if short comment, don't cut */
								commentHtml += '<span class="sgrb-comment-text">' + comment.comment + '</span>';
							}
	/* if user don't type his/her name, show as Guest (footer) */
	if (!comment.name) {
		var name = 'Guest';
		var addWidth = '';
	}
	else {
		var name = comment.name;
		if (name.length >= 100) {
			var addWidth = 'style="width:95%;"';
		}
		else {
			var addWidth = '';
		}
	}

	commentHtml += '</div>' +
				'</div>' +
			'</div>' +
			'<div class="sg-row">' +
				'<div class="sg-col-12">' +
					'<span class="sgrb-name-title-text" ' + addWidth + '><b>' + comment.date + '</b> <i> , comment by </i><b>&nbsp;' + name + '</b> </span>' +
				'</div>' +
			'</div>' +
		'</div>';

	return commentHtml;
};


/**
 * showHideComment() show more or less comment;
 * @param reviewId - review id;
 * @param commentId - comment id;
 * @param className - class, which shows is less or more;
 */
SGCommentHelper.prototype.showHideComment = function (reviewId,commentId,className) {
	var currentComment = jQuery('#'+reviewId).find('.sgrb-comment-'+commentId);

	if (currentComment.parentsUntil('.sgrb-user-rate-js-form').hasClass('sgrb-widget-wrapper')) {
		var cutTextSize = 80;
	}
	else {
		var cutTextSize = 200;
	}
	if (className == 'sgrb-read-more') {
		var fullText = currentComment.find('.sgrb-read-more')
		.parent()
		.parent()
		.find('.sgrb-full-comment')
		.val();
		currentComment.find('.sgrb-read-more')
		.parent()
		.parent()
		.find('.sgrb-comment-text-js')
		.empty()
		.removeClass('sgrb-comment-max-height')
		.html(''+fullText+' <a onclick="SGCommentHelper.prototype.showHideComment('+reviewId+', '+commentId+',\'sgrb-hide-read-more\')" href="javascript:void(0)" class="sgrb-hide-read-more">hide&#9650</a>');
	}

	if (className == 'sgrb-hide-read-more') {
		var fullText = currentComment.find('.sgrb-hide-read-more').parent().parent().find('.sgrb-full-comment').val();
		var cuttedText = fullText.substr(0, cutTextSize);
		currentComment.find('.sgrb-hide-read-more').parent().parent().find('.sgrb-comment-text-js').empty().addClass('sgrb-comment-max-height').html(cuttedText+' ... <a onclick="SGCommentHelper.prototype.showHideComment('+reviewId+', '+commentId+',\'sgrb-read-more\')" href="javascript:void(0)" class="sgrb-read-more">show all&#9660</a>');
	}
};

SGCommentHelper.prototype.lazyLoadingLoadMoreButton = function () {

};