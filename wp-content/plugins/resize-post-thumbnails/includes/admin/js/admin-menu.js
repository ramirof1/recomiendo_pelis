var resize_post_thumbnails__admin_panel;
( function( $ ) {
	resize_post_thumbnails__admin_panel = {
		init : function() {
			var wpi_title;
			
			var custom_uploader;

			$( '#resize-post-thumbnails-admin-panel-menu > ul' ).accordion( {
				heightStyle: 'content',
				animate: 100,
				activate: function( event, ui ) {
					ui.oldHeader.parent().removeClass( 'open' );
					
					var menu_id = ui.newHeader.attr( 'id' ).replace( 'resize-post-thumbnails-admin-panel-menu-', '' );
					
					$( '#resize-post-thumbnails-admin-panel-content .resize-post-thumbnails-menu-content' ).removeClass( 'open' );
					
					$( '#resize-post-thumbnails-menu-content-' + menu_id ).addClass( 'open' );
				},
				beforeActivate: function( event, ui ) {
					ui.newHeader.parent().addClass( 'open' );
				}
			} );
			
			
			$( '#resize-post-thumbnails-admin-panel-menu .resize-post-thumbnails-submenu' ).click( function() {
				var t = $( this );
				if ( ! t.hasClass( 'submenu_open' ) ) {
				
					t.siblings().removeClass( 'submenu_open' );
					t.addClass().addClass( 'submenu_open' );
					
					var menu_id = t.children( '.accordion-section-title' ).parent().parent().parent().children( '.accordion-section-title' ).attr( 'id' ).replace( 'resize-post-thumbnails-admin-panel-menu-', '' );
					var submenu_id = t.children( '.accordion-section-title' ).attr( 'id' ).replace( 'resize-post-thumbnails-admin-panel-submenu-', '' );
					
					$( '#resize-post-thumbnails-menu-content-' + menu_id + ' .resize-post-thumbnails-submenu-content' ).removeClass( 'submenu_open' );
					$( '#resize-post-thumbnails-submenu-content-' + menu_id + '-' + submenu_id ).addClass( 'submenu_open' );
					
				}
			});

			// Reset button
			$('.resize_post_thumbnails__input_button_reset').live('click', function(){
				var elem_id = $(this).parent().parent().children().children('.code-text').attr('id');
				resize_post_thumbnails__reset_element_by_id( elem_id );
			});
			
			// Select all text button action
			$('.resize_post_thumbnails__input_button_select').live('click', function(){
				$(this).parent().parent().children().children('.code-text').focus().select();
			});

			//on form submit function
			$( '#resize-post-thumbnails-admin-panel > form' ).submit( function() {
				var t = $( this );
				var function_id = t.attr( 'id' );
				var submit_button = $( '#submit-button' );

				if ( ! t.hasClass( 'noclick' ) ) {
				
					submit_button.removeClass( 'button-primary' );
					submit_button.addClass( 'button-disabled' );
				
					//add class noclick for disabling the user to save multiple times that eats a lot of cpu power
					t.addClass( 'noclick' );
					
					var serializedReturn = t.serialize();
					//alert( serializedReturn );
					
					var data = {
						function_id:       function_id,
						action:            'resize_post_thumbnails__admin_save',
						data:              serializedReturn,
						resize_post_thumbnails__nonce:   $( '#resize_post_thumbnails__nonce' ).val()
					};

					$.post( ajaxurl, data, function( response ) {
						if ( response.success == true ) {
							if ( response.data.action == 'refresh' ) {
								location.reload();
							}
						} else if ( response.success == false ) {
							alert( response.data );
						} else {
							alert( "Can't save!" );
						}

					}, 'json' ).fail( function() {
						alert( 'AJAX failed! (No internet connection?)' );
					} ).always( function() {
						t.removeClass( 'noclick' );
						submit_button.removeClass( 'button-disabled' );
						submit_button.addClass( 'button-primary' );
					} );
					
					// $.post( ajaxurl, data, function( response ) {

						// alert( response );

					// } ).fail( function() {
						// alert( response );
					// } ).always( function() {
						// t.removeClass( 'noclick' );
						// submit_button.removeClass( 'button-disabled' );
						// submit_button.addClass( 'button-primary' );
					// } );
				}
				return false;
			});   	
		}
	};

	$( document ).ready( function( $ ) { resize_post_thumbnails__admin_panel.init(); } );
} ) ( jQuery );