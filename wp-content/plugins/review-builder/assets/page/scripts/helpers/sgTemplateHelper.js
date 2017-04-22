function SGTemplateHelper() {
};

SGTemplateHelper.prototype.init = function () {
	var that = this;

	that.uploadTemplateImage();
	that.removeImageButton();
};

/**
 * getURLParameter() checked if it is create
 * or edit page;
 * @param params is boolean;
 * @param section to check (e.g. review, comment, template, commentForm);
 */

SGTemplateHelper.prototype.getURLParameter = function (params, section) {
	var sPageURL = window.location.search.substring(1);
	var sURLVariables = sPageURL.split('&');
	for (var i = 0; i < sURLVariables.length; i++) {
		var sParameterName = sURLVariables[i].split('=');
		if (sParameterName[0] == params) {
			return sParameterName[1];
		}
	}
};

SGTemplateHelper.prototype.showSuccessBanner = function (text) {
	jQuery('<div class="updated notice notice-success is-dismissible below-h2">' +
		'<p>'+text+'</p>' +
		'<button type="button" class="notice-dismiss" onclick="jQuery(\'.notice\').remove();"></button></div>').appendTo('.sgrb-top-bar h1');
};

/**
 * ajaxCloseBanner() close the review promotional baner
 */
SGTemplateHelper.prototype.ajaxCloseBanner = function(){
	var deleteAction = 'Review_ajaxCloseBanner';
	var ajaxHandler = new sgrbRequestHandler(deleteAction);
	ajaxHandler.dataType = 'html';
	ajaxHandler.callback = function(response){
		location.reload();
	};
	ajaxHandler.run();
};

/**
 * uploadTemplateImage()
 */
SGTemplateHelper.prototype.uploadTemplateImage = function(){
	jQuery('.sgrb-tepmlate-img-upload').on('click', function(e) {
		var wrapperDiv = jQuery(this).parent().parent(),
			wrap = jQuery(this),
			imgNum = jQuery(this).next('.sgrb-img-num').attr('data-auto-id');
		e.preventDefault();
		var image = wp.media({
			title: 'Upload Image',
			multiple: false
		}).open()
		.on('select', function(e){
			jQuery('.sgrb-template-image-name').show();
			jQuery('.sgrb-tepmlate-img-upload').val('Remove image');
			var uploaded_image = image.state().get('selection').first();
			var image_url = uploaded_image.toJSON().url;
			jQuery('#sgrb-templateimg-url').val(image_url);
			jQuery('.sgrb-image-review').attr('style',"background-image:url("+image_url+");width: 200px;height:200px;background-color:#f7f7f7;margin: 0 auto;");
		});
	});
};

/**
 * removeImageButton()
 */
SGTemplateHelper.prototype.removeImageButton = function(){
	jQuery('span.sgrb-remove-template-img-btn').on('click', function() {
		var uploaded_image = '';
		jQuery(this).parent().parent().parent().attr('style', "background-image:url();width: 200px;height:200px;background-color:#f7f7f7;margin: 0 auto;");
		jQuery(this).parent().parent().find('.sgrb-images').val('');
	});
};

/**
 * insertTag()
 */
SGTemplateHelper.prototype.insertTag = function(tag) {
	tinymce.get('sgrbhtml');
	if (tinyMCE.activeEditor!=null) {
		tinymce.activeEditor.execCommand('mceInsertContent', false, tag);
	}
};

/*
 * additionally set the field's values equal to '' ;
 */
SGTemplateHelper.prototype.emptyValueToAdditionalFields = function () {
	var tempPartWrapper = jQuery('.sgrb-template-part-wrapper');
	var fieldsArray = ['.sg-tempo-title',
		'.sg-tempo-title-second',
		'.sg-tempo-title-info',
		'.sg-tempo-by',
		'.sg-tempo-price',
		'.sg-tempo-shipping',
		'.sg-tempo-subtitle',
		'.sg-tempo-context'
	];

	for (var i=0;i<fieldsArray.length;i++) {
		if (tempPartWrapper.find(fieldsArray[i]).text().replace(/\s/g, "").length <= 0) {
			tempPartWrapper.find(fieldsArray[i]).text('');
		}
	}
};
