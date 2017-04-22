jQuery(document).ready(function($){

	$( '#tabs-1' ).tabs({ 
		show: 800, 
		disabled: [2] 
	});
	
	$('select[name="post-ui-tabs_settings[skin]"]').on({
		change: on_ui_style_change,
		keyup:  on_ui_style_change
	});
	
	function on_ui_style_change(e) {
		
		// If there is not a skin on this index bail
		if( 'undefined' == typeof( put_skins[this.selectedIndex] ) )
			return false;
			
		// Support for using arrow keys
		if( 'keypress' == e.type && ( e.keyCode < 37 || e.keyCode > 40 ) )
			return false;
		
		var styletag = $('link#jquery-ui-tabs-css');
		
		if( 'undefined' == typeof( styletag ) )
			return false;
		
		var style = put_dir + '/' + put_skins[this.selectedIndex] + '/' + put_file;
		
		if( styletag.attr('href') !== style )
			styletag.attr('href', style );
	}
});