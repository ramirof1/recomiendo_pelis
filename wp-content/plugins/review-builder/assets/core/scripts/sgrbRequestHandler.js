function sgrbRequestHandler(action, data, params){
	this.action = action;
	this.url = '';
	this.data = data;
	this.callback = '';
	this.type = 'POST';
	this.dataType = 'JSON';
	this.dataIsObject = true;
	this.params = params;
};
if(typeof ajaxurl === 'undefined') {
	ajaxurl = sgrb_ajaxurl;
	this.url = ajaxurl;
}
sgrbRequestHandler.prototype.prepareData = function() {
	this.url = ajaxurl;

	if(typeof this.data === 'undefined') {
		if (this.dataIsObject === true) this.data = {};
		else this.data = '';
	}

	var action = 'sgrb_'+this.action;
	if (this.dataIsObject === true) {
		//if formdata

		if(this.data instanceof FormData){

			this.data.append('action', action);
		}
		else {
			this.data['action'] = action;
		}
	}
	else {
		if (this.data !== '') this.data += '&';
		this.data += 'action='+action;
	}
}

sgrbRequestHandler.prototype.run = function(){
	var that = this;
	that.prepareData();
	var settings = {
		url: that.url,
		data: that.data,
		type: that.type,
		dataType: that.dataType,
		success: function(response){
			if(jQuery.isFunction(that.callback)){
				that.callback(response, false);
			}
		},
		error: function (e, textStatus) {
			if(jQuery.isFunction(that.callback)){
				that.callback(false, textStatus);
			}
		}
	};

	if (typeof this.params === 'undefined') this.params = {};

	jQuery.extend(settings, this.params);

	return jQuery.ajax(settings);
};
