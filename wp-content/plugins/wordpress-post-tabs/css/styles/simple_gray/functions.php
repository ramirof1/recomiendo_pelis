<?php function wpts_pro_wp_init_simple_gray($atts=array(),$data=array()) { 
    global $post,$wpts;
	if(is_singular() or $wpts['enable_everywhere'] == '1') { 
		$enablewpts = get_post_meta($post->ID, 'enablewpts', true);
		if( (is_page() and ((!empty($enablewpts) and $enablewpts=='1') or  $wpts['pages'] != '0'  ) ) 
			or (is_single() and ((!empty($enablewpts) and $enablewpts=='1') or $wpts['posts'] != '0'  ) ) or $wpts['enable_everywhere'] == '1' ) 
		{
			//Effects JS
			if(is_array($atts)) extract($atts,EXTR_PREFIX_ALL,'in');
			if(empty($in_effect)) $in_effect=$wpts['fade'];
			//Backward compatibility with lite version for Fade effect
			if($in_effect=='1')$in_effect='fade';
			if(empty($in_auto)) $in_auto='0';
			$effects_handle='';
			if(!empty($in_effect) ) {
				if( $in_effect!='0' and $in_effect!='1' and $in_effect!='2' and $in_effect!='3'){
					$effects_handle='jquery-effects-'.$in_effect;
				}
			}
			//Load CSS and JS files
			$wpts_stylesheet='simple_gray';
			$css='skins/'.$wpts_stylesheet.'/style.css';
			$js_folder='skins/'.$wpts_stylesheet.'/js';
			wp_enqueue_style( 'wpts_ui_css_simple_gray', wpts_pro_plugin_url( $css ),false, WPTSPRO_VER, 'all'); 
			if(isset($wpts['jquerynoload']) and $wpts['jquerynoload']=='1') {
			    wp_deregister_script( 'jquery' );
				wp_enqueue_script('jquery-ui-tabs', false, array('jquery-ui-core'), WPTSPRO_VER, true);
				wp_enqueue_script('jquery-effects-core', false, array('jquery-ui-core'), WPTSPRO_VER, true);
				if( $in_auto == '1' ) wp_enqueue_script('jquery-ui-tabs-rotate', wpts_pro_plugin_url( 'core/js/jquery-ui-tabs-rotate.js' ), array('jquery-ui-tabs'), WPTSPRO_VER, true);
			}
			else{
				wp_enqueue_script('jquery-ui-tabs', false, array('jquery','jquery-ui-core'), WPTSPRO_VER, true);
				wp_enqueue_script('jquery-effects-core', false, array('jquery','jquery-ui-core'), WPTSPRO_VER, true);
				if( $in_auto == '1' ) wp_enqueue_script('jquery-ui-tabs-rotate', wpts_pro_plugin_url( 'core/js/jquery-ui-tabs-rotate.js' ), array('jquery-ui-tabs'), WPTSPRO_VER, true);
			}
			if(!empty($effects_handle)) wp_enqueue_script($effects_handle, false, array('jquery-effects-core'), WPTSPRO_VER, true);
			//action hook
			do_action('wptspro_skin_init',$atts,$data,$wpts_stylesheet);
		}
	}
}
function return_wpts_tab_script_simple_gray( $wpts_count=0,$atts=array(),$data=array() ){
	global $post,$wpts,$wpts_style;
	$wpts_stylesheet='simple_gray';
	$post_id = $post->ID;
	$enablewpts = get_post_meta($post->ID, 'enablewpts', true);
	$script='';
	if( (!empty($enablewpts) and $enablewpts=='1') or $wpts['posts'] != '0'  ) 	{  
		//Set Parameters
		if(is_array($atts)) extract($atts,EXTR_PREFIX_ALL,'in');
		if(empty($in_onhover))	$in_onhover=$wpts['onhover'];
		if(empty($in_effect)) $in_effect=$wpts['fade'];
		//Backward compatibility with lite version for Fade effect
		if($in_effect=='1')$in_effect='fade';
		if(empty($in_effectduration)) $in_effectduration='800';
		if(empty($in_easing)) $in_easing='linear';
		if(empty($in_prevnext)) $in_prevnext=$wpts['nav'];
		if(empty($in_prevtext)) $in_prevtext=$wpts['prev_text'];
		if(empty($in_nexttext)) $in_nexttext=$wpts['next_text'];
		if(empty($in_location)) $in_location=$wpts['location'];
		if(empty($in_reload)) $in_reload=$wpts['reload'];
		if(empty($in_loadhash)) $in_loadhash=$wpts['taburl'];
		if(empty($in_showhash)) $in_showhash=$wpts['showurl'];
		if(empty($in_auto)) $in_auto='0';		
		if(empty($in_timer)) $in_timer='4';
		$script = $script.'<script type="text/javascript">';
		if($wpts['disable_fouc']=='1')	$script = $script.'jQuery("html").addClass("wpts-hide");';
		$script = $script.'jQuery(document).ready(function() {';
		if($wpts_count and $wpts_count!=0){ 
			$i = $wpts_count-1;
			$tab_options_arr=array();
			$tab_name='tabs_'.$post_id.'_'.$i;
			if($wpts['disable_fouc']=='1'){
				$hide='jQuery(".wpts-hide .'.$tab_name.'_wrap").css({"display" : "block"});';}
			else {$hide='';}
			//Tab transition on mouseover or click
			if($in_onhover=='1') $tab_options_arr[]='event: "mouseover"';
			//Set transition
			if(!empty($in_effect) ) {
				if( $in_effect=='1' )	$tab_options_arr[] =  'fx: { opacity: "toggle", duration: '.$in_effectduration.'}';	
				if( $in_effect=='2' )	$tab_options_arr[] =  'show: { effect: "slideDown", duration: '.$in_effectduration.', easing: "'.$in_easing.'"}';
				if( $in_effect!='0' and $in_effect!='1' and $in_effect!='2' and $in_effect!='3'){
					$tab_options_arr[] =  'show: { effect: "'.$in_effect.'", duration: '.$in_effectduration.', easing: "'.$in_easing.'"}';
				}
			}
			$tab_options=implode(',',$tab_options_arr);
			//Tabs Location
			if( $in_location=='top' ) $script = $script.$hide.'var $'.$tab_name.' = jQuery("#tabs_'.$post_id.'_'.$i.'").tabs({'.$tab_options.'}).addClass( "ui-tabs-horizontal-top ui-helper-clearfix" );';
			elseif( $in_location=='left' ) $script = $script.$hide.'var $'.$tab_name.' = jQuery("#tabs_'.$post_id.'_'.$i.'").tabs({'.$tab_options.'}).addClass( "ui-tabs-vertical-left ui-helper-clearfix" );jQuery( "#tabs_'.$post_id.'_'.$i.' li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );';
			elseif( $in_location=='right' ) $script = $script.$hide.'var $'.$tab_name.' = jQuery("#tabs_'.$post_id.'_'.$i.'").tabs({'.$tab_options.'}).addClass( "ui-tabs-vertical-right ui-helper-clearfix" );jQuery( "#tabs_'.$post_id.'_'.$i.' li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-right" );';
			elseif( $in_location=='bottom' ) $script = $script.$hide.'var $'.$tab_name.' = jQuery("#tabs_'.$post_id.'_'.$i.'").tabs({'.$tab_options.'}).addClass( "ui-tabs-horizontal-bottom ui-helper-clearfix" );jQuery( "#tabs_'.$post_id.'_'.$i.' li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-bottom" );';
			else $script = $script.$hide.'var $'.$tab_name.' = jQuery("#tabs_'.$post_id.'_'.$i.'").tabs({'.$tab_options.'});';
			//Autorotate tabs
			if( $in_auto == '1' ) $script.='$'.$tab_name.'.tabs("rotate", '. ($in_timer * 1000 ).', true);';
			//Set default tab on load
			$sel_script='';
			if( isset($data['selected']) ) $sel_script = '$'.$tab_name.'.tabs("option", "active", '. $data['selected'] .');';
			
			$tabtop='';
			if(!empty($wpts['tabtop']) and $wpts['tabtop']=='1')
				$tabtop='jQuery("body,html").animate({"scrollTop":   jQuery("#"+(ui.newPanel).attr("id")).offset().top	}, 1000);';
				
			$gettab='';$settab='';
			//Reload on click
			if (!empty($in_reload) and $in_reload=='1')  {
				$gettab=$gettab.'$'.$tab_name.'.on("tabsbeforeactivate", function(event, ui){event.preventDefault();window.location.hash = "#"+ui.newPanel.attr("id");location.reload();});';
			}
			//Linkable tabs
			if( (!empty($in_loadhash) and $in_loadhash=='1') or (!empty($in_reload) and $in_reload=='1') ){	
				$gettab=$gettab.'var anchor=jQuery(document).attr("location").hash;
						if(anchor){
							var index = jQuery("#'.$tab_name.' > div.ui-tabs-panel").index(jQuery(anchor));
							if(index != -1)	$'.$tab_name.'.tabs("option", "active", index);
						} else {
							'.$sel_script.'
						}';
			}
			else{
				$script = $script.$sel_script;
			}
			//Show tab # url
			if( !empty($in_showhash) and $in_showhash=='1'  ){
				$settab='window.location.hash = "#"+ui.newPanel.attr("id");';
			}
			//In Page Links
			$gettab=$gettab.'jQuery("a.wptslink").click(function() {
			var a_href=jQuery(this).attr("href");
			if(a_href){
				var index = jQuery("#'.$tab_name.' > div.ui-tabs-panel").index(jQuery(a_href));
				$'.$tab_name.'.tabs("option", "active", index); 
			}
			});';
			
			$script=$script.$gettab.'$'.$tab_name.'.on("tabsactivate", function(event, ui){
					'.$tabtop.$settab.'	
					var gmap=ui.newPanel.find("iframe.gmap");
					if(typeof gmap != "undefined" && typeof gmap.data("src") != "undefined" ) gmap.attr("src",gmap.data("src"));
				});';
			if(!empty($in_prevnext) and $in_prevnext=='1') {
			   $script = $script.'var wpts_j=0;
				jQuery("#tabs_'.$post_id.'_'.$i.' > .ui-tabs-panel").each(function(wpts_j){
					var totalSize = jQuery("#tabs_'.$post_id.'_'.$i.' > .ui-tabs-panel").size();
					if (wpts_j < (totalSize - 1)) { 
						var wpts_next = wpts_j + 1;
						jQuery(this).append("<a href=\'#\' id=\'wpts-next-'.$post_id.'_'.$i.'\' class=\'wpts-next-tab wpts-mover\' rel=\'" + wpts_next + "\'>'.$in_nexttext.'</a>");}	
					if (wpts_j >= 1) { 
						var wpts_prev = wpts_j - 1;
						jQuery(this).append("<a href=\'#\' id=\'wpts-prev-'.$post_id.'_'.$i.'\' class=\'wpts-prev-tab wpts-mover\' rel=\'" + wpts_prev + "\'>'.$in_prevtext.'</a>");
					} 
				});
				jQuery("#wpts-next-'.$post_id.'_'.$i.', #wpts-prev-'.$post_id.'_'.$i.'").click(function() { $'.$tab_name.'.tabs("option", "active", ( jQuery(this).attr("rel") ) ); return false; });';
		 	}

						
/************************************************************************/
/*---------------------------- STYLE EDITOR ----------------------------*/
			if(isset($wpts_style[$wpts_stylesheet]) and is_array($wpts_style[$wpts_stylesheet])){
				extract($wpts_style[$wpts_stylesheet],EXTR_PREFIX_ALL,'style');
				if((!empty($style_bg) and $style_bg!="#") or (!empty($style_active_bg) and $style_active_bg!="#") or (!empty($style_color) and $style_color!="#") or (!empty($style_active_color) and $style_active_color!="#") or (!empty($style_hover_bg) and $style_hover_bg!="#") or (!empty($style_hover_color) and $style_hover_color!="#"))	{		
					$script .='jQuery("head").append("<style type=\"text/css\">';
					if(!empty($style_bg) and $style_bg!="#") $script .= '#'.$tab_name.' > ul > li.ui-state-default {background: '.$style_bg.';}'; else {if(!empty($style_active_bg) and $style_active_bg!="#"){$script .= '#'.$tab_name.' > ul > li.ui-state-default {background: #eeeeee;}';}}	
					if(!empty($style_active_bg) and $style_active_bg!="#") $script .= '#'.$tab_name.' > ul > li.ui-state-active { background: '.$style_active_bg.';}'; else {if(!empty($style_bg) and $style_bg!="#"){$script .= '#'.$tab_name.' > ul > li.ui-state-active { background: #fff;}';}}	
					if(!empty($style_hover_bg) and $style_hover_bg!="#") $script .= '#'.$tab_name.' > ul > li.ui-state-hover { background: '.$style_hover_bg.';}';
					if(!empty($style_color) and $style_color!="#") $script .= '#'.$tab_name.' > ul > li.ui-state-default .ui-tabs-anchor{ color: '.$style_color.';}'; else {if(!empty($style_active_color) and $style_active_color!="#"){$script .= '#'.$tab_name.' > ul > li.ui-state-default .ui-tabs-anchor{ color: #666666;}'; } }
					if(!empty($style_active_color) and $style_active_color!="#") $script .= '#'.$tab_name.' > ul > li.ui-state-active .ui-tabs-anchor{ color:'.$style_active_color.';}'; else {if(!empty($style_color) and $style_color!="#"){$script .= '#'.$tab_name.' > ul > li.ui-state-active .ui-tabs-anchor{ color: #4c4c4c;}';}}
					if(!empty($style_hover_color) and $style_hover_color!="#") $script .= '#'.$tab_name.' > ul > li.ui-state-hover a { color: '.$style_hover_color.'!important;}'; else {$script .= '#'.$tab_name.' > ul > li.ui-state-hover a { color: #4c4c4c !important;}';}				
					$script .='</style>");';
				} // if empty check end 		
			}
/*------------------------- END STYLE EDITOR -------------------------*/
			$script=apply_filters('wptspro_tabjs',$script,'$'.$tab_name,$wpts_stylesheet);
		 }
		$script = $script.'})';
		//filter hook
		$script = apply_filters('wptspro_skin_scripts',$script,$atts,$data,$wpts_count,$wpts_stylesheet);
		$script = $script.'</script> ';
	}
	return $script;
}
?>
