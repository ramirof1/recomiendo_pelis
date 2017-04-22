function SGMainHelper() {
}

SGMainHelper.prototype.init = function () {
	var that = this;

	jQuery('.sg-banner-close-js').click(function(){
		that.ajaxCloseBanner();
	});

	if (that.getURLParameter('edit')) {
		if (jQuery('.sgrb-review-js-update').length) {
			that.showSuccessBanner('Review updated');
		}
		if (jQuery('.sgrb-comment-js-update').length) {
			that.showSuccessBanner('Comment updated');
		}
		if (jQuery('.sgrb-template-js-update').length) {
			that.showSuccessBanner('Template updated');
		}
		if (jQuery('.sgrb-comment-form-js-update').length) {
			that.showSuccessBanner('Form updated');
		}
	}

	that.showHideTooltip();
	jQuery('#wpcontent').find('.subsubsub').attr('style','margin-top:66px;position:absolute;');
};

/**
 * getURLParameter() checked if it is create
 * or edit page;
 * @param params is boolean;
 */
SGMainHelper.prototype.getURLParameter = function (params) {
	var sPageURL = window.location.search.substring(1);
	var sURLVariables = sPageURL.split('&');
	for (var i = 0; i < sURLVariables.length; i++) {
		var sParameterName = sURLVariables[i].split('=');
		if (sParameterName[0] == params) {
			return sParameterName[1];
		}
	}
};

SGMainHelper.prototype.showSuccessBanner = function (text) {
	jQuery('<div class="updated notice notice-success is-dismissible below-h2">' +
		'<p>'+text+'</p>' +
		'<button type="button" class="notice-dismiss" onclick="jQuery(\'.notice\').remove();"></button></div>').appendTo('.sgrb-top-bar h1');
};

/**
 * ajaxCloseBanner() close the review promotional baner
 */
SGMainHelper.prototype.ajaxCloseBanner = function(){
	var deleteAction = 'Review_ajaxCloseBanner';
	var ajaxHandler = new sgrbRequestHandler(deleteAction);
	ajaxHandler.dataType = 'html';
	ajaxHandler.callback = function(response){
		location.reload();
	};
	ajaxHandler.run();
};

SGMainHelper.prototype.showHideTooltip = function () {
	if (jQuery('.sgrb-show-tooltip').length) {
		var totalRateCout = jQuery('.sgrb-show-tooltip');
		var sgrbTooltip = jQuery('.sgrb-tooltip');

		totalRateCout.on('mouseenter', function(){
			sgrbTooltip.show(100);
		});
		totalRateCout.on('mouseleave', function(){
			sgrbTooltip.hide(100);
		});
	}
	if (jQuery('.sgrb-widget-wrapper').find('.sgrb-show-tooltip-widget').length) {
		var totalRateCountWidget = jQuery('.sgrb-show-tooltip-widget');
		var sgrbTooltipWidget = jQuery('.sgrb-tooltip-widget');

		totalRateCountWidget.on('mouseenter', function(){
			sgrbTooltipWidget.show();
		});
		totalRateCountWidget.on('mouseleave', function(){
			sgrbTooltipWidget.hide();
		});
	}
};
