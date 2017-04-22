function SGRateSkin() {
}

SGRateSkin.prototype.sgrbRateTypeStar = 1;
SGRateSkin.prototype.sgrbRateTypePercent = 2;
SGRateSkin.prototype.sgrbRateTypePoint = 3;

SGRateSkin.prototype.init = function () {
	var that = this;
	var currentPreviewType = jQuery('.sgrb-rate-type:checked').val();
	that.preview(currentPreviewType);

	jQuery('.sgrb-rate-type').on('change', function(){
		var value = jQuery(this).val();
		that.changeType(value);
		that.preview(value);
	});
};

/**
 * prepareSkinHtml() prepares and return current skin html;
 * @param type - integer, current review rate skin type;
 */
SGRateSkin.prototype.prepareSkinHtml = function (type) {
	var skinHtml = false;
	if (type == this.sgrbRateTypeStar) {
		skinHtml = '<div class="sgrb-each-rateYo"></div>';
	}
	if (type == this.sgrbRateTypePercent) {
		skinHtml = '<div class="circles-slider"></div>';
	}
	if (type == this.sgrbRateTypePoint) {
		skinHtml = '<select class="sgrb-point">'+
			'<option value="1">1</option>'+
			'<option value="2">2</option>'+
			'<option value="3">3</option>'+
			'<option value="4">4</option>'+
			'<option value="5">5</option>'+
			'<option value="6">6</option>'+
			'<option value="7">7</option>'+
			'<option value="8">8</option>'+
			'<option value="9">9</option>'+
			'<option value="10">10</option>'+
			'</select>';
	}
	if (skinHtml) {
		return skinHtml;
	}
};


/**
 * preview() get skin style for show preview.
 * @param type is integer
 */
SGRateSkin.prototype.preview = function (type) {
	var selectedType = '.sgrb-preview-'+type,
		skinColor = jQuery('.sgrb-skin-color'),
		skinStylePreview = jQuery('.sgrb-skin-style-preview');

	if (selectedType == '.sgrb-preview-1') {
		skinColor.show(200);
		skinStylePreview
		.empty()
		.html('<div></div>')
		.find('div')
		.attr('class','')
		.addClass('rateYoPreview');
		skinStylePreview.find('.sgrb-point').hide();
		skinStylePreview.removeClass('sgrb-skin-style-preview-percent sgrb-skin-style-preview-point');
		jQuery('.rateYoPreview').rateYo({
			rating: "3",
			fullStar: true,
			starWidth: "40px"
		});
	}
	else if (selectedType == '.sgrb-preview-2') {
		skinColor.show(200);
		skinStylePreview.empty().html('<div class="sgrb-percent-preview"><div></div></div>');
		skinStylePreview.removeClass('sgrb-skin-style-preview-point').addClass('sgrb-skin-style-preview-percent');
		jQuery('.sgrb-percent-preview').find('div').attr('class','').addClass('circles-slider');
		skinStylePreview.find('.sgrb-point').hide();
		jQuery(".circles-slider").slider({
			max:100,
			value: 40
		}).slider("pips", {
			rest: false,
			labels:100
		}).slider("float", {
		});
	}
	else if (selectedType == '.sgrb-preview-3') {
		skinColor.hide(200);
		skinStylePreview.empty();
		skinStylePreview.removeClass('sgrb-skin-style-preview-percent').addClass('sgrb-skin-style-preview-point');
		skinStylePreview.html('<select class="sgrb-point"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option></select>');

		jQuery('.sgrb-point').barrating({
			theme : 'bars-1to10'
		});
		jQuery('.sgrb-point').barrating('set',5);
		jQuery('.br-theme-bars-1to10 .br-widget a').attr("style", "height:23px !important;width:18px !important;");
		jQuery(".br-current-rating").attr("style", 'display:none;');
	}
};

/**
 * changeType() get skin style and set it as default.
 * @param type is integer
 */
SGRateSkin.prototype.changeType = function (type) {
	var span = '';
	var count = 0;
	if (type == this.sgrbRateTypeStar) {
		span = ' Rate (1-5) : ';
		type = 'star';
		count = 5;
	}
	else if (type == this.sgrbRateTypePercent) {
		span = ' Rate (1-100) : ';
		type = 'percent';
		count = 100;
	}
	else if (type == this.sgrbRateTypePoint) {
		span = ' Rate (1-10) : ';
		type = 'point';
		count = 10;
	}
	SGReview.prototype.rateSelectboxHtmlBuilder(type,span,count);
};

/**
 * prepareFrontSkin()
 */
SGRateSkin.prototype.prepareFrontSkin = function (type) {
	jQuery('.sgrb-reviewFakeId').each(function(){
		/* reviewId is real review id, sgrbFakeId is fake but unique review id */
		var sgrbFakeId = jQuery(this).val();
		var mainWrapper = jQuery('#'+sgrbFakeId);

		var type = mainWrapper.find('.sgrb-rating-type').val(),
			isRated = true;

		if (type == SGRateSkin.prototype.sgrbRateTypeStar) {
			SGRateSkin.prototype.displayStarSkin(sgrbFakeId);
		}
		else if (type == SGRateSkin.prototype.sgrbRateTypePercent) {
			SGRateSkin.prototype.displayPercentSkin(sgrbFakeId);
		}
		else if (type == SGRateSkin.prototype.sgrbRateTypePoint) {
			SGRateSkin.prototype.displayPointSkin(sgrbFakeId);
		}
	});
};

/**
 * displayStarSkin()
 */
SGRateSkin.prototype.displayStarSkin = function (sgrbFakeId) {
	var mainWrapper = jQuery('#'+sgrbFakeId),
		type = mainWrapper.find('.sgrb-rating-type').val(),
		isRated = true,
		mainFinalRate = mainWrapper.find('.sgrb-final-rate');
	if (mainWrapper.find('.sgrb-show-tooltip span').text().match('^0')) {
		isRated = false;
	}
	if (mainWrapper.length > 0) {
		var rateTextColor = mainWrapper.find('.sgrb-rate-text-color').val();
		if (rateTextColor) {
			mainFinalRate.css('color',rateTextColor);
		}
		var skinColor = mainWrapper.find('.sgrb-skin-color').val();
		if (!skinColor) {
			skinColor = '#F39C12';
		}
		var eachCategoryTotal = '';
		/* show total (if rates) */
		if (isRated && mainWrapper.find('.sgrb-tooltip-text').text() != 'no rates') {
			mainWrapper.find('.sgrb-rate-each-skin-wrapper').each(function(){
				eachCategoryTotal = jQuery(this).find('.sgrb-each-category-total').val();
				if (eachCategoryTotal) {
					jQuery(this).find('.rateYoTotal').rateYo({
						ratedFill: skinColor,
						rating: eachCategoryTotal,
						fullStar : true,
						readOnly: true
					});
				}
			});
		}
		/* show total for the first time (if no rates) */
		if (mainWrapper.find('.sgrb-tooltip-text').text().match('^no rates')) {
			mainWrapper.find('.sgrb-rate-each-skin-wrapper').each(function(){
				jQuery(this).find('.rateYoTotal').rateYo({
					ratedFill: skinColor,
					readOnly: true
				});
			});
		}
		/* edit rate (review) */
		if (mainWrapper.find('input[name=sgRate]').val() != 0) {
			mainWrapper.find('input[name=sgRate]').each(function(){
				var sgRate = jQuery(this).val();
				jQuery(this).parent().parent().find('.sgrb-each-rate-skin').val(sgRate);
				jQuery(this).next().rateYo({
					rating:sgRate,
					ratedFill: skinColor,
					fullStar: true,
					maxValue: 5,
					onChange: function (rating, rateYoInstance) {
						jQuery(this).next().text(rating);
						var res = jQuery(this).parent().find(".sgrb-counter").text();
						jQuery(this).parent().parent().find('.sgrb-each-rate-skin').val(res);
					}
				});
			});
		}
		/* add new rate (review) */
		if (mainWrapper.find('input[name=sgRate]').val() == 0) {
			mainWrapper.find(".rateYo").rateYo({
				starWidth: "30px",
				ratedFill: skinColor,
				fullStar: true,
				maxValue: 5,
				onChange: function (rating, rateYoInstance) {
					jQuery(this).next().text(rating);
					mainWrapper.find('.sgrb-each-rate-skin').each(function(){
						var res = jQuery(this).parent().find(".sgrb-counter").text();
						jQuery(this).parent().find('.sgrb-each-rate-skin').val(res);
					});
				}
			});
		}

		jQuery('.rateYoAll').attr('style', 'margin-top: 110px; margin-left:30px;position:absolute');
		jQuery('.sgrb-counter').attr('style', 'display:none');
		jQuery('.sgrb-allCount').attr('style', 'display:none');

		jQuery('.sgrb-user-comment-submit').on('click', function(){
			jQuery(".rateYo").rateYo({readOnly:true});
		});
	}
};

/**
 * displayPercentSkin()
 */
SGRateSkin.prototype.displayPercentSkin = function (sgrbFakeId) {
	var mainWrapper = jQuery('#'+sgrbFakeId),
		type = mainWrapper.find('.sgrb-rating-type').val(),
		isRated = true,
		value = 1;
	if (mainWrapper.find('.sgrb-show-tooltip span').text().match('^0')) {
		isRated = false;
	}

	if (mainWrapper.find('.sgrb-common-wrapper')) {
		//if (isRated) {
			mainWrapper.find('.sgrb-each-percent-skin-wrapper').each(function(){
				value = jQuery(this).find('.sgrb-each-category-total').val();
				jQuery(this).find('.circles-slider').slider({
					max:100,
					value: value
				}).slider("pips", {
					rest: false,
					labels:100
				}).slider("float", {
				});
			});
			mainWrapper.find('.circles-slider').attr('style','pointer-events:none;float:right !important;');
			mainWrapper.find('.circles-slider .ui-slider-handle').addClass('ui-state-hover ui-state-focus');
		//}
		if (!mainWrapper.find('input[name=sgRate]').val()) {
			mainWrapper.find(".sgrb-circle-total").slider({
				max:100,
				value: value,
				change: function(event, ui) {
					jQuery(this).parent().parent().find('.sgrb-each-rate-skin').val(ui.value);
				}
			}).slider("pips", {
				rest: false,
				labels:100
			}).slider("float", {
			});
			mainWrapper.find('.sgrb-circle-total').attr('style','float:right !important;');
		}
		else {
			jQuery('input[name=sgRate]').each(function(){
				var sgRate = jQuery(this).val();
				jQuery(this).prev().slider({
					max:100,
					value: sgRate,
					change: function(event, ui) {
						jQuery(this).parent().parent().find('.sgrb-each-rate-skin').val(ui.value);
					}
				}).slider("pips", {
					rest: false,
					labels:100
				}).slider("float", {
				});
				mainWrapper.find('.sgrb-circle-total').attr('style','float:right !important;');
			});
		}
	}
};

/**
 * displayWidgetStarSkin()
 */
SGRateSkin.prototype.displayWidgetStarSkin = function (wrapper) {
	wrapper.find('.sgrb-approved-comments-wrapper').each(function(){
		value = jQuery(this).find('.sgrb-each-comment-avg-widget').val();

		jQuery(this).find('.sgrb-each-rateYo').rateYo({
			rating: value,
			readOnly: true
		});
	});
};

/**
 * displayWidgetPercentSkin()
 */
SGRateSkin.prototype.displayWidgetPercentSkin = function (wrapper) {
	wrapper.find('.sgrb-each-comment-rate').attr('style', 'padding:0 !important;min-height:30px;');
	wrapper.find('.sgrb-approved-comments-wrapper').each(function () {
		value = jQuery(this).find('.sgrb-each-comment-avg-widget').val();

		jQuery(this).find('.circles-slider').slider({
			max: 100,
			value: value
		}).slider("pips", {
			rest: false,
			labels: 100
		}).slider("float", {});
	});
	wrapper.find('.circles-slider').attr('style', 'pointer-events:none;margin: 40px 30px 0 27px !important;width: 78% !important;clear: right !important;');
	wrapper.find('.circles-slider .ui-slider-handle').addClass('ui-state-hover ui-state-focus');
};

/**
 * displayPointSkin()
 */
SGRateSkin.prototype.displayPointSkin = function (sgrbFakeId) {
	var mainWrapper = jQuery('#'+sgrbFakeId),
		type = mainWrapper.find('.sgrb-rating-type').val(),
		isRated = true,
		mainFinalRate = mainWrapper.find('.sgrb-final-rate');
	if (mainWrapper.find('.sgrb-show-tooltip span').text().match('^0')) {
		isRated = false;
	}
	var point = mainWrapper.find('.sgrb-point');
	var pointEditable = mainWrapper.find('.sgrb-point-user-edit');
	mainFinalRate.parent().css('margin','30px 15px 30px 0');
	if (mainWrapper.hasClass('sgrb-widget-wrapper')) {

	}
	if (!isRated) {
		mainWrapper.find('.sgrb-rate-each-skin-wrapper').each(function(){
			jQuery(this).find('.sgrb-point').barrating({
				theme : 'bars-1to10',
				readonly: true
			});
		});
		point.barrating('show');
	}
	if (isRated) {
		mainWrapper.find('.sgrb-rate-each-skin-wrapper').each(function(){
			var pointValue = jQuery(this).find('.sgrb-each-category-total').val();
			pointValue = Math.round(pointValue);
			jQuery(this).find('.sgrb-point').barrating({
				theme : 'bars-1to10',
				readonly: true
			});
			jQuery(this).find('.sgrb-point').barrating('set',pointValue);
		});
		point.barrating('show');
	}
	if (mainWrapper.find('input[name=sgRate]').val()) {
		mainWrapper.find('.sgrb-rate-each-skin-wrapper').each(function(){
			var sgRate = jQuery(this).find('.sgrb-each-category-total').val();
			pointEditable.barrating({
				theme : 'bars-1to10',
				onSelect: function (value, text, event) {
					this.$widget.parent().parent().parent().find('.sgrb-each-rate-skin').val(value);
					mainFinalRate.text(value);
					mainFinalRate.attr('style','margin:8px 0 0 30px;color: rgb(237, 184, 103); display: inline-block;width:70px;height:70px;position:relative;font-size:4em;text-align:center');
				}
			});
			jQuery(this).find('.sgrb-point-user-edit').barrating('set', sgRate);
		});
	}
	point.barrating('show');
	mainWrapper.find('.br-current-rating').attr('style','display:none');
	mainWrapper.find(".br-wrapper").attr("style", 'display:inline-block;float:right;height:28px;');
	mainWrapper.find('.sgrb-each-rate-skin').each(function(){
		point.parent().find('a').attr("style", 'width:9px;box-shadow:none;border:1px solid #dbe867;');
		pointEditable.parent().find('a').attr("style", 'width:9px;box-shadow:none;border:1px solid #dbe867;');
	});
	mainWrapper.find('.sgrb-user-comment-submit').on('click', function(){
		point.barrating('readonly',true);
	});
};

/**
 * displayWidgetPointSkin
 */
SGRateSkin.prototype.displayWidgetPointSkin = function (wrapper) {
	wrapper.find('.sgrb-each-comment-rate').attr('style', 'padding:0 !important;min-height:30px;');
	wrapper.find('.sgrb-approved-comments-wrapper').each(function () {
		var value = jQuery(this).find('.sgrb-each-comment-avg-widget').val();

		jQuery(this).find('.sgrb-point').barrating({
			theme: 'bars-1to10',
			readonly: true
		});
		jQuery(this).find('.sgrb-point').barrating('set', value);
		wrapper.find(".br-wrapper").attr('style', 'margin-top: 2px !important;');
		wrapper.find('.sgrb-point').parent().find('a').attr("style", 'width:8%;box-shadow:none;border:1px solid #dbe867;');
		wrapper.find('.br-current-rating').attr('style', 'height:27px !important;line-height:1.5 !important;padding-right: 0 !important');
	});
};