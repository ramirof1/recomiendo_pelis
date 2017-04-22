function init() {
	tinyMCEPopup.resizeToInnerSize();
}


function insertWPTSLink() {
	
	var tagtext = '';
		var comma = ",";
		var tabString = document.getElementById('tabname').value;
		if (tabString != '' ) {
			var tabArray = tabString.split(",");
			for( var i = 0, len = tabArray.length; i < len; i++ ) {
				tabArray[i] = tabArray[i].replace(/^\s\s*/, '').replace(/\s\s*$/, '');
				tagtext = tagtext + "[" + wptsadminL10n.tab + " name='" + tabArray[i] + "']" + "Content for the tab " + tabArray[i] + "[/" + wptsadminL10n.tab + "]" + "<br /><br />";
			} 
			tagtext = tagtext + "[" + wptsadminL10n.end + "]";
		}
		else
			tinyMCEPopup.close();
	
	if(window.tinyMCE) {
		//TODO: For QTranslate we should use here 'qtrans_textarea_content' instead 'content'
		if (typeof window.tinyMCE.execInstanceCommand != 'undefined') {
			window.tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, tagtext);
        }
		else {
			if (typeof window.tinyMCE.execCommand != 'undefined') {
				window.tinyMCE.get('content').execCommand('mceInsertContent', false, tagtext);
			}
        }
		//window.tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, tagtext);
		//Peforms a clean up of the current editor HTML. 
		//tinyMCEPopup.editor.execCommand('mceCleanup');
		//Repaints the editor. Sometimes the browser has graphic glitches. 
		tinyMCEPopup.editor.execCommand('mceRepaint');
		tinyMCEPopup.close();
	}
	return;
}
