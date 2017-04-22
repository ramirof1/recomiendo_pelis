function SGForm(){
}

SGForm.prototype.inputTypeText = 1;
SGForm.prototype.inputTypeEmail = 2;
SGForm.prototype.inputTypeNumber = 3;
/*SGRB.prototype.inputTypePassword = 4;*/
SGForm.prototype.inputTypeTextarea = 5;

SGForm.prototype.init = function() {
	var that = this;

	jQuery('.sgrb-attributes-input').attr('disabled', 'disabled');
	jQuery('input[name=isRequired]').attr('disabled', 'disabled');
	jQuery('input[name=isHidden]').attr('disabled', 'disabled');

	jQuery('.sgrb-comment-form-js-update').click(function(){
		that.save();
	});

	jQuery('.sgrb-form-textarea').each(function(){
		var shortcode = jQuery(this).val();
		var asTitle = false;
		var asComment = false;
		var asUsername = false;
		if (shortcode.match('as=title') != null) {
			asTitle = true;
		}
		if (shortcode.match('as=comment') != null) {
			asComment = true;
		}
		if (shortcode.match('as=username') != null) {
			asUsername = true;
		}
		jQuery('.sgrb-attributes-input').each(function(){
			var asValue = jQuery(this).val();
			if (asTitle && asValue == 'title') {
				jQuery(this).parent().parent().parent().hide();
			}
			if (asComment && asValue == 'comment') {
				jQuery(this).parent().parent().parent().hide();
			}
			if (asUsername && asValue == 'username') {
				jQuery(this).parent().parent().parent().hide();
			}
		});
	});

	jQuery('.sgrb-insert-js').click(function(){
		that.insertFieldShortcode();
	});
	jQuery('#sgrb-text-is-required').change(function(){
		var required = ' required ';
		var shortcodeToEdit = jQuery('.sgrb-created-text-tag').val();
		if (jQuery(this).is(':checked')) {
			if (!shortcodeToEdit) {
				return;
			}
			var position = shortcodeToEdit.indexOf(']');
			var requiredAttr = [shortcodeToEdit.slice(0, position), required, shortcodeToEdit.slice(position)].join('');
			jQuery('.sgrb-created-text-tag').text(requiredAttr);
		}
		else {
			shortcodeToEdit = shortcodeToEdit.replace(required, "");
			jQuery('.sgrb-created-text-tag').text(shortcodeToEdit);
		}
	});
	jQuery('#sgrb-for-admin').change(function(){
		var isHidden = ' hidden ';
		var shortcodeToEdit = jQuery('.sgrb-created-text-tag').val();
		if (jQuery(this).is(':checked')) {
			if (!shortcodeToEdit) {
				return;
			}
			var position = shortcodeToEdit.indexOf(']');
			var showOnFrontAttr = [shortcodeToEdit.slice(0, position), isHidden, shortcodeToEdit.slice(position)].join('');
			jQuery('.sgrb-created-text-tag').text(showOnFrontAttr);
		}
		else {
			shortcodeToEdit = shortcodeToEdit.replace(isHidden, "");
			jQuery('.sgrb-created-text-tag').text(shortcodeToEdit);
		}
	});
};

SGForm.prototype.save = function() {
	var isEdit = false;
	var sgrbError = false;
	var form = jQuery('.sgrb-js-form');
	if(jQuery('.sgrb-title-input').val().replace(/\s/g, "").length <= 0){
		sgrbError = 'Title field is required';
	}
	if (sgrbError) {
		alert(sgrbError);
		return;
	}
	var saveAction = 'CommentForm_ajaxSave';
	var ajaxHandler = new sgrbRequestHandler(saveAction, form.serialize());
	ajaxHandler.dataIsObject = false;
	ajaxHandler.dataType = 'html';
	jQuery('.sgrb-loading-spinner').show();
	var sgrbSaveUrl = jQuery('input[name=sgrbSaveUrl]').val();
	ajaxHandler.callback = function(response){
		//If success
		if(response) {
			isEdit = true;
			/* Response is comment id */
			jQuery('input[name=sgrb-form-id]').val(response);
			location.href=sgrbSaveUrl+"&id="+response+'&edit='+isEdit;
			jQuery('.sgrb-loading-spinner').hide();
		}
	};
	ajaxHandler.run();
};

SGForm.prototype.deleteField = function(field) {
	var index = 1;
	var deletedField = '';
	if (confirm('Are you sure?')) {
		deletedField = jQuery('#sgrb-field-'+field).find('.sgrb-form-textarea').val();
		if (deletedField.match('as=title') != null) {
			jQuery('#sgrb-as-title').show();
		}
		if (deletedField.match('as=comment') != null) {
			jQuery('#sgrb-as-comment').show();
		}
		if (deletedField.match('as=username') != null) {
			jQuery('#sgrb-as-username').show();
		}
		jQuery('.sgrb-form-row').each(function(){
			jQuery(this).removeAttr('id');
			jQuery(this).attr('id', 'sgrb-field-'+index);
			jQuery(this).find('.sgrb-delete-form-field-js').removeAttr('onclick');
			jQuery(this).find('.sgrb-delete-form-field-js').attr('onclick', 'SGForm.prototype.deleteField('+index+')');
			jQuery('#sgrb-field-'+field).remove();
			index++;
		});
	}
};

SGForm.prototype.ajaxDelete = function(id) {
	if (id == 1) {
		alert('Could not delete this form.');
		return;
	}
	if (confirm('Are you sure?')) {
		var deleteAction = 'CommentForm_ajaxDelete';
		var ajaxHandler = new sgrbRequestHandler(deleteAction, {id: id});
		ajaxHandler.dataType = 'html';
		ajaxHandler.callback = function(response){
			/* If success */
			location.reload();
		};
		ajaxHandler.run();
	}
};

SGForm.prototype.insertFieldShortcode = function() {
	var index = 1;
	jQuery('.sgrb-form-minus-icon').each(function(){
		index++;
	});
	jQuery('.sgrb-attributes-input').each(function(){
		var inputName = jQuery(this).attr('name');
		SGForm.prototype.applyInputValue(inputName);
	});
	var createdFormTag = jQuery('.sgrb-created-text-tag').text();
	/* use as */
	jQuery('.sgrb-attributes-input').each(function(){
		if (jQuery(this).attr('name') == 'inputUseAs') {
			if (jQuery(this).is(':checked')) {
				var useAs = jQuery(this).val();
				if (useAs != 'additional') {
					jQuery(this).parent().parent().parent().hide();
				}
			}
		}
		else {
			jQuery(this).val('');
		}
		if (jQuery(this).val() == 'additional') {
			jQuery(this).attr('checked', 'checked');
		}
	});
	/* use as */
	var sgrbAppUrl = jQuery('input[name=sgrbAppUrl]').val();
	if (createdFormTag) {
		var textareaHtml = '<div id="sgrb-field-'+index+'" class="sg-row sgrb-form-row">'+
			'<div class="sg-col-11">'+
			'<textarea rows="2" class="sgrb-form-textarea" name="mainCreatedFormHtml[]">'+createdFormTag+'</textarea>'+
			'</div>'+
			'<div class="sg-col-1 sgrb-minus-icon-wrapper">'+
			'<img onclick="SGForm.prototype.deleteField('+index+');" class="sgrb-form-minus-icon sgrb-delete-form-field-js" src="'+sgrbAppUrl+'assets/page/img/remove_image.png">'+
			'</div>'+
			'</div>';
		jQuery('.sgrb-form-textareas-wrapper .howto').remove();
		jQuery('.sgrb-form-textareas-wrapper').append(textareaHtml);
		jQuery('#sgrb-text-is-required').removeAttr('checked');
		jQuery('#sgrb-for-admin').removeAttr('checked');
	}
	jQuery('.sgrb-attributes-input').attr('disabled', 'disabled');
	jQuery('input[name=isRequired]').attr('disabled', 'disabled');
	jQuery('input[name=isHidden]').attr('disabled', 'disabled');
	jQuery('.sgrb-insert-form-tag').each(function(){
		jQuery(this).removeAttr('style');
	});
	jQuery('.sgrb-created-text-tag').text('');
	jQuery('input[name=inputUseAsHidden]').val('');
};

SGForm.prototype.applyInputValue = function(inputName) {
	var shortcodeToEdit = jQuery('.sgrb-created-text-tag').val();
	var valueToApply = jQuery('input[name='+inputName+']').val();
	var valueHidden = jQuery('input[name='+inputName+'Hidden]').val();
	if (valueToApply == '' || shortcodeToEdit == '') {
		return;
	}
	jQuery('input[name='+inputName+'Hidden]').val(valueToApply);
	/* attributes */
	if (inputName == 'textLabel') {
		valueToApply = valueToApply.replace(/ /g, '-');
		if (valueHidden != '') {
			shortcodeToEdit = shortcodeToEdit.replace('label='+valueHidden+'', 'label='+valueToApply);
		}
		else {
			shortcodeToEdit = shortcodeToEdit.replace("label=", 'label='+valueToApply);
		}

		jQuery('.sgrb-created-text-tag').text(shortcodeToEdit);
	}
	if (inputName == 'textPlaceholder') {
		valueToApply = valueToApply.replace(/ /g, '-');
		if (valueHidden != '') {
			shortcodeToEdit = shortcodeToEdit.replace('placeholder='+valueHidden+'', 'placeholder='+valueToApply);
		}
		else {
			shortcodeToEdit = shortcodeToEdit.replace("placeholder=", 'placeholder='+valueToApply);
		}
		jQuery('.sgrb-created-text-tag').text(shortcodeToEdit);
	}
	if (inputName == 'textId') {
		if (valueHidden != '') {
			shortcodeToEdit = shortcodeToEdit.replace('id='+valueHidden+'', 'id='+valueToApply);
		}
		else {
			shortcodeToEdit = shortcodeToEdit.replace("id=", 'id='+valueToApply);
		}
		jQuery('.sgrb-created-text-tag').text(shortcodeToEdit);
	}
	if (inputName == 'textClass') {
		if (valueHidden != '') {
			shortcodeToEdit = shortcodeToEdit.replace('class='+valueHidden+'', 'class='+valueToApply);
		}
		else {
			shortcodeToEdit = shortcodeToEdit.replace("class=", 'class='+valueToApply);
		}
		jQuery('.sgrb-created-text-tag').text(shortcodeToEdit);
	}
	if (inputName == 'textStyle') {
		if (valueHidden != '') {
			shortcodeToEdit = shortcodeToEdit.replace('style='+valueHidden+'', 'style='+valueToApply);
		}
		else {
			shortcodeToEdit = shortcodeToEdit.replace("style=", 'style='+valueToApply);
		}
		jQuery('.sgrb-created-text-tag').text(shortcodeToEdit);
	}
	jQuery('input[name='+inputName+'Hidden]').val('');
};

SGForm.prototype.selectUseAs = function(valueToApply) {
	var shortcodeToEdit = jQuery('.sgrb-created-text-tag').val();
	var valueHidden = jQuery('input[name=inputUseAsHidden]').val();
	if (valueHidden != '') {
		shortcodeToEdit = shortcodeToEdit.replace('as='+valueHidden+'', 'as='+valueToApply);
	}
	else {
		shortcodeToEdit = shortcodeToEdit.replace("as=", 'as='+valueToApply);
	}
	jQuery('input[name=inputUseAsHidden]').val(valueToApply);
	jQuery('.sgrb-created-text-tag').text(shortcodeToEdit);
};

SGForm.prototype.showFieldOptions = function(inputType) {
	var success = false;
	if (inputType == this.inputTypeText) {
		var activeButton = 'text';
		var insertedHtml = '[sgrb_text id= class= style= placeholder= label= as=]';
	}
	if (inputType == this.inputTypeEmail) {
		var activeButton = 'email';
		var insertedHtml = '[sgrb_email id= class= style= placeholder= label= as=]';
	}
	if (inputType == this.inputTypeNumber) {
		var activeButton = 'number';
		var insertedHtml = '[sgrb_number id= class= style= placeholder= label= as=]';
	}
	if (inputType == this.inputTypeTextarea) {
		var activeButton = 'text area';
		var insertedHtml = '[sgrb_textarea id= class= style= placeholder= label= as=]';
	}
	jQuery('.sgrb-insert-form-tag').each(function(){
		jQuery(this).removeAttr('style');
		if (jQuery(this).val() == activeButton) {
			jQuery(this).attr('style', 'background-color: #ccc;');
			jQuery('.sgrb-insert-js').removeAttr('disabled');
		}
		jQuery('.sgrb-attributes-input').removeAttr('disabled');
		jQuery('input[name=isRequired]').removeAttr('disabled');
		jQuery('input[name=isHidden]').removeAttr('disabled');
	});
	jQuery('.sgrb-created-text-tag').html(insertedHtml);
};