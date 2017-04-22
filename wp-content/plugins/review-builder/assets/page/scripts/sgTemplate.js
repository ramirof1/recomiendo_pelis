function SGTemplate(){
}

SGTemplate.prototype.init = function() {
	var that = this;

	jQuery('.sgrb-template-js-update').on('click', function(){
		that.save();
	});
	jQuery('.sgrb-template-image-name').hide();

	jQuery('.sgrb-preview-eye').each(function(){
		jQuery(this).hover(
			function(){
				jQuery(this).find('.sgrb-template-icon-preview').attr('id','sgrb-template-display-style');
			},
			function(){
				jQuery(this).find('.sgrb-template-icon-preview').removeAttr('id');
			}
		);
	});
};

SGTemplate.ajaxDeleteTemplate = function(id) {
	if (confirm('Are you sure?')) {
		var deleteAction = 'TemplateDesign_ajaxDeleteTemplate';
		var ajaxHandler = new sgrbRequestHandler(deleteAction, {id: id});
		ajaxHandler.dataType = 'html';
		ajaxHandler.callback = function(response){
			/* If success */
			if (response) {
				location.reload();
			}
			else {
				alert('Can not delete.This template attached to Your reviews.');
				return;
			}
		};
		ajaxHandler.run();
	}
};

SGTemplate.prototype.save = function() {
	var isEdit = false;
	var sgrbError = false;
	var editor = tinymce.get('sgrbhtml');
	if (editor) {
		editor.save();
	}
	var form = jQuery('.sgrb-template-js-form');
	if(jQuery('.sgrb-title-input').val().replace(/\s/g, "").length <= 0){
		sgrbError = 'Title field is required';
	}
	if (sgrbError) {
		alert(sgrbError);
		return;
	}
	var saveAction = 'TemplateDesign_ajaxSave';
	var ajaxHandler = new sgrbRequestHandler(saveAction, form.serialize());
	ajaxHandler.dataIsObject = false;
	ajaxHandler.dataType = 'html';
	jQuery('.sgrb-loading-spinner').show();
	var sgrbSaveUrl = jQuery('input[name=sgrbSaveUrl]').val();
	ajaxHandler.callback = function(response){
		/* If success*/
		if(response) {
			jQuery('.sgrb-loading-spinner').hide();
			isEdit = true;
			/* Response is template id */
			jQuery('input[name=sgrbTemplateId]').val(response);
			location.href=sgrbSaveUrl+"&id="+response+'&edit='+isEdit;
		}
		else {
			alert('Template with this title already exists');
			jQuery('.sgrb-loading-spinner').hide();
			return;
		}
	};
	ajaxHandler.run();
};

