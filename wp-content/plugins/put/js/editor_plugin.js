(function() {
	tinymce.create( 'tinymce.plugins.PostUITabsPlugin', {
		init : function(ed, url) {
			ed.addCommand( 'put_add_tab', function() {
				var dialog_args = { 
					selection:ed.selection.getContent() || ''
				};
				ed.windowManager.open({
					file:   url + '/dialog.htm',
					width:  500,
					height: 300,
					inline: 1
				}, dialog_args );
			});
			ed.addCommand( 'put_add_tab_quick', function() {
				ed.focus(); 
				ed.execCommand( 'mceInsertContent', false, '[tab name="'+ed.getLang('put.default_tab_text')+'"]'+ed.selection.getContent()+'[/tab]<br /><br />' );
			});
			ed.addCommand( 'put_end_tabs', function() {
				ed.focus(); 
				ed.execCommand( 'mceInsertContent', false, '[end_tabset]<br /><br />' );
			});
			
			ed.addButton( 'put_addtab', {
				title: ed.getLang('put.addtab_title'),
				cmd:   'put_add_tab',
				image: url + '/addtab.gif'
			});
			ed.addButton( 'put_addtab_quick', {
				title: ed.getLang('put.addtab_quick_title'),
				cmd:   'put_add_tab_quick',
				image: url + '/addtab_quick.gif'
			});
			ed.addButton( 'put_endtabs', {
				title:  ed.getLang('put.endtabs_title'),
				cmd:   'put_end_tabs',
				image: url + '/endtabs.gif'
			});
			ed.foobar = 'foo';
		},
		createControl : function(n, cm) {
			return null;
		},
		getInfo : function() {
			return {
				longname:  'Post UI Tabs - TinyMCE Plugin',
				author:    't31os_',
				authorurl: 'http://wordpress.org/support/profile/t31os_',
				infourl:   'http://wordpress.org/extend/plugins/put/',
				version:   '1.0'
			};
		}
	});
	tinymce.PluginManager.add( 'wordpress_put', tinymce.plugins.PostUITabsPlugin );
})();