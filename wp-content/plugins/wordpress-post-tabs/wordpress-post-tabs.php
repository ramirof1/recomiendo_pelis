<?php
/*
Plugin Name: WordPress Post Tabs
Plugin URI: http://tabbervilla.com/wordpress-post-tabs/
Description: WordPress Post Tabs will help you to easily display your WordPress Post or Page sections in structured tabs, so that if you are writing some review post, you can add distinct tabs representing each section of the review like overview, specifications, performance, final rating and so on. Watch Live Demo at <a href="http://tabbervilla.com/wordpress-post-tabs/">Plugin Page</a>.
Version: 1.6.2
Author: Internet Techies
Author URI: http://www.clickonf5.org
WordPress version supported: 3.5 and above
*/

/*  Copyright 2010-2016  Tejaswini Deshpande  (email : tedeshpa@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
if ( ! defined( 'WPTS_PRO_ACTIVE' ) ):
if ( ! defined( 'WPTS_PLUGIN_BASENAME' ) )
	define( 'WPTS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define("WPTS_VER","1.6.2",false);
define('WPTS_URLPATH', trailingslashit( WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) ) );
global $wpts,$wpts_db_version;
$wpts_db_version='1.6.2'; //current version of WordPress Post Tabs database 
$wpts = get_option('wpts_options');
function wpts_url( $path = '' ) {
	global $wp_version;
	if ( version_compare( $wp_version, '2.8', '<' ) ) { // Using WordPress 2.7
		$folder = dirname( plugin_basename( __FILE__ ) );
		if ( '.' != $folder )
			$path = path_join( ltrim( $folder, '/' ), $path );

		return plugins_url( $path );
	}
	return plugins_url( $path, __FILE__ );
}
//on activation, your WordPress Post Tabs options will be populated. Here a single option is used which is actually an array of multiple options
function activate_wpts() {
	global $wpts_db_version;
	$default_tab_settings = get_wpts_default_settings();
	$installed_ver = get_site_option( 'wpts_db_version' );
	if( $installed_ver != $wpts_db_version ) {
		$wpts_opts1 = get_option('wpts_options');
		$speed=(isset($wpts_opts1['speed'])?$wpts_opts1['speed']:'');
		if(isset($wpts_opts1) and $speed=='1'){
			$pages=$wpts_opts1['pages'];
			$posts=$wpts_opts1['posts'];
			if(empty($pages) or !isset($pages)) {
			  $wpts_opts1['pages']='0';
			}
			if(empty($posts) or !isset($posts)) {
			  $wpts_opts1['posts']='0';
			}
		}
		$wpts_opts2 = $default_tab_settings;
		if ($wpts_opts1) {
			$wpts = $wpts_opts1 + $wpts_opts2;
			update_option('wpts_options',$wpts);
		}
		else {
			$wpts_opts1 = array();	
			$wpts = $wpts_opts1 + $wpts_opts2;
			add_option('wpts_options',$wpts);		
		}
		update_site_option( 'wpts_db_version', $wpts_db_version );
	}
}

register_activation_hook( __FILE__, 'activate_wpts' );

/* Added for auto update - start */
function wpts_update_db_check() {
    global $wpts_db_version;
    if (get_site_option('wpts_db_version') != $wpts_db_version) {
        activate_wpts();
    }
}
add_action('plugins_loaded', 'wpts_update_db_check');
/* Added for auto update - end */

require_once (dirname (__FILE__) . '/tinymce/tinymce.php');

function wpts_wp_init() {
    global $post,$wpts;
	$wpts = wpts_populate_settings( $wpts );
	if(is_singular() or $wpts['enable_everywhere'] == '1') { 
		$enablewpts = get_post_meta($post->ID, 'enablewpts', true);
		if(isset($wpts['posts']))$wpposts=$wpts['posts'];
		else $wpposts='';
		if(isset($wpts['pages']))$wppages=$wpts['pages'];
		else $wppages='';
		// check whether cookie is set for last active tab
		if(isset($wpts['enablecookie'])) $enablecookie = $wpts['enablecookie'];
		else $enablecookie = '0';
		if( (is_page() and ((!empty($enablewpts) and $enablewpts=='1') or  $wppages != '0'  ) ) 
			or (is_single() and ((!empty($enablewpts) and $enablewpts=='1') or $wpposts != '0'  ) ) or $wpts['enable_everywhere'] == '1' ) 
		{
			$css="css/styles/".$wpts['stylesheet'].'/style.css';
			wp_enqueue_style( 'wpts_ui_css', wpts_url( $css ),false, WPTS_VER, 'all'); 
			if(isset($wpts['jquerynoload']) and $wpts['jquerynoload']=='1') {
			    wp_deregister_script( 'jquery' );
				wp_enqueue_script('jquery-ui-tabs', false, array('jquery-ui-core'), WPTS_VER, true);
			}
			else{
				wp_enqueue_script('jquery-ui-tabs', false, array('jquery','jquery-ui-core'), WPTS_VER, true);
			}
			if($enablecookie == '1') wp_enqueue_script('jquery-cookie', wpts_plugin_url( 'js/jquery.cookie.js' ), array('jquery'), WPTS_VER, true);
			// JS added			
			wp_enqueue_script('jquery-posttabs', wpts_plugin_url( 'js/jquery.posttabs.js' ), array('jquery'), WPTS_VER, true);
			global $wpts_count,$wpts_tab_count,$wpts_content,$wpts_prev_post;
			$wpts_count=0;
			$wpts_tab_count=0;
			$wpts_prev_post='';
			$wpts_content=array();
		}
	}
}
add_action( 'wp', 'wpts_wp_init' );

function wpts_edit_custom_box(){
	global $post;
	echo '<input type="hidden" name="enablewpts_noncename" id="enablewpts_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />'; 	?>
	<?php
			$enablewpts = get_post_meta($post->ID,'enablewpts',true);
			if($enablewpts=="1"){
				$checked = ' checked="checked" ';
			}else{
				$checked = '';
			}
	?>
		<p><input type="checkbox" id="enablewpts" name="enablewpts" value="1" <?php echo $checked;?> />&nbsp;<label for="enablewpts"><strong>Enable WP Post Tabs Feature</strong></label></p>
	<?php
}
/* Prints the edit form for pre-WordPress 2.5 post/page */
function wpts_old_custom_box() {

  echo '<div class="dbx-b-ox-wrapper">' . "\n";
  echo '<fieldset id="myplugin_fieldsetid" class="dbx-box">' . "\n";
  echo '<div class="dbx-h-andle-wrapper"><h3 class="dbx-handle">' . 
        __( 'WP Post Tabs', 'wordpress-post-tabs' ) . "</h3></div>";   
   
  echo '<div class="dbx-c-ontent-wrapper"><div class="dbx-content">';

  // output editing form

  wpts_edit_custom_box();

  // end wrapper

  echo "</div></div></fieldset></div>\n";
}
function wpts_add_custom_box() {
	add_meta_box( 'wpts_box1', __( 'Post Tabs' ), 'wpts_edit_custom_box', 'post', 'side','high' );
	//add_meta_box( $id,   $title,     $callback,   $page, $context, $priority ); 
	add_meta_box( 'wpts_box2', __( 'Page Tabs' ), 'wpts_edit_custom_box', 'page', 'advanced' );
}
/* Use the admin_menu action to define the custom boxes */
add_action('admin_menu', 'wpts_add_custom_box');

function wpts_savepost(){
	global $post;
	if(isset($post))$post_id = $post->ID;
	else $post_id = '';
	// verify this came from the our screen and with proper authorization,
	  // because save_post can be triggered at other times
	if(isset($_POST['enablewpts_noncename'])){	  
		if ( !wp_verify_nonce( $_POST['enablewpts_noncename'], plugin_basename(__FILE__) )) {
			return $post_id;
		}	
	}
	else{
		return $post_id;		
	}
	
	  // verify if this is an auto save routine. If it is our form has not been submitted, so we dont want
	  // to do anything
	  if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
		return $post_id;
	  // Check permissions
	  if ( 'page' == $_POST['post_type'] ) {
		if ( !current_user_can( 'edit_page', $post_id ) )
		  return $post_id;
	  } else {
		if ( !current_user_can( 'edit_post', $post_id ) )
		  return $post_id;
	  }
	  // OK, we're authenticated: we need to find and save the data
	$data =  ( isset ( $_POST['enablewpts'] ) and $_POST['enablewpts'] == "1") ? "1" : "0";
	update_post_meta($post_id, 'enablewpts', $data);
	return $data;
}
add_action('save_post', 'wpts_savepost');

function wpts_tab_shortcode($atts,$content) {
	extract(shortcode_atts(array(
		'name' => 'Tab Name',
		'link' => '',
		'active' => '',
	), $atts));
	
    global $wpts;
	global $wpts_content,$wpts_tab_count,$wpts_count;
	$wpts_content[$wpts_tab_count]['name'] = $name;
	$wpts_content[$wpts_tab_count]['link'] = $link;
	$wpts_content[$wpts_tab_count]['selected'] = $active;	
	$wpts_content[$wpts_tab_count]['content'] = do_shortcode($content);
    $wpts_tab_count = $wpts_tab_count+1;
		
	if(is_feed()){
	  $return = '<h4>'.$name.'</h4>'.$content;
	  return $return;
	}
    return null;
}
add_shortcode( ( isset( $wpts['tab_code'] ) ? $wpts['tab_code'] : 'wptab' ) , 'wpts_tab_shortcode');
function wpts_end_shortcode($atts) {
	if ( is_feed() ) {
		return null;
	}
	global $wpts, $post;
	global $wpts_content,$wpts_tab_count,$wpts_count,$wpts_prev_post;
	$data_selected = '';
	$post_id = $post->ID;
	
	$wpts = wpts_populate_settings( $wpts );
	
	if ( $wpts_prev_post != $post_id ) { $wpts_count=0; }

	if(is_singular() or $wpts['enable_everywhere'] == '1') {
		if($wpts_tab_count!=0 and isset($wpts_tab_count)) {
			$tab_content = '<ul>';
			$tab_i=0;
			for($i=0;$i<$wpts_tab_count;$i++) {
				//Get Page URL //Fixed issue on https servers
				$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
				$pageurl=$protocol . $_SERVER['HTTP_HOST']  . $_SERVER['REQUEST_URI'];
				//$pageurl="http://" . $_SERVER['HTTP_HOST']  . $_SERVER['REQUEST_URI'];
				
				$link = $wpts_content[$i]['link'];
				if ($wpts['showtitle'] == '1') {
					$linktitle = 'title="'.$wpts_content[$i]['name'].'"';
				}
				else {
					$linktitle = '';
				}
				if ($wpts['linktarget'] == '1') {
					$linktarget = 'target="_blank"';
				}
				else {
					$linktarget = '';
				}
				//name specific links
				if($wpts['prettylinks']=='1'){				
					$linkhref=preg_replace('/<[^>]*>/', '', $wpts_content[$i]['name']);				
					$linkhref=preg_replace('/\W/', '-', $linkhref);
					if(isset($wpts_content['id']) and is_array($wpts_content['id'])){if(in_array($linkhref,$wpts_content['id']))$linkhref.=$wpts_count;}
				}
				else{
					$linkhref='tabs-'.$post_id.'-'.$wpts_count.'-'.$i;
				}
				
				if(!empty($link)) {
					$tab_content = $tab_content.'<span class="wpts_ext"><a href="'.$wpts_content[$i]['link'] .'" '.$linktitle .$linktarget. '>'.$wpts_content[$i]['name'].'</a></span>';}
				else {
					$tab_content = $tab_content.'<li><a href="'.$pageurl.'#'.$linkhref.'" '.$linktitle .$linktarget.'>'.$wpts_content[$i]['name'].'</a></li>';
					//Selected tab by default
					$selected=$wpts_content[$i]['selected'];
					if($selected=='1')$data_selected=$tab_i;
					$tab_i++;
				}
			  }
			 $tab_content = $tab_content.'</ul><div class="wpts_cl"></div>';
			
			$tab_html='';
			for($i=0;$i<$wpts_tab_count;$i++) {
				$link_html = $wpts_content[$i]['link'];
				
				//name specific links
				if($wpts['prettylinks']=='1'){	
					$linkhref=preg_replace('/<[^>]*>/', '', $wpts_content[$i]['name']);				
					$linkhref=preg_replace('/\W/', '-', $linkhref);
					if(isset($wpts_content['id']) and is_array($wpts_content['id'])){
						if(in_array($linkhref,$wpts_content['id']))$linkhref.=$wpts_count;
						else $wpts_content['id'][]=$linkhref;
					}				
					else $wpts_content['id'][]=$linkhref;
				}
				else {
					$linkhref='tabs-'.$post_id.'-'.$wpts_count.'-'.$i;
				}
				
				if(!empty($link_html)) {
					$tab_html.=''; 
				}
				else {
					$tab_html.='<div id="'.$linkhref.'"><p>'.$wpts_content[$i]['content'].'</p></div>';
				}
				$tab_html=preg_replace("/<p[^>]*>[\s|&nbsp;]*<\/p>/", '', $tab_html);
			}
			$tab_content = $tab_content.$tab_html;
		}
		$tab_content = '<div id="tabs_'.$post_id.'_'.$wpts_count.'">'.$tab_content.'<div class="wpts_cl"></div><div class="wpts_cr"></div></div>';
		
		$wpts_count = $wpts_count+1;
		$wpts_tab_count = 0;
		
		$script = '';

		global $post;
		$wpts_stylesheet = $wpts['stylesheet'];
		$post_id = $post->ID;
		$enablewpts = get_post_meta($post->ID, 'enablewpts', true);
		if( (!empty($enablewpts) and $enablewpts=='1') or $wpts['posts'] != '0'  ) 	{  
			if($wpts['disable_fouc']=='1'){
				$hide='jQuery("html").addClass("wpts-hide");
				jQuery(document).ready(function(){ jQuery(".wpts-hide .wordpress-post-tabs").css({"display" : "block"}); });';}
			else {$hide='';}
			$script = $script.'<script type="text/javascript">
			'.$hide.'
			jQuery(function() {';
			if($wpts_count and $wpts_count!=0){ 
				$i = $wpts_count-1;
				// Cookies option removed from WPTS ver 1.4
				/*if(!isset($wpts['disable_cookies']) or $wpts['disable_cookies']!='1'){ 
				   $cookie = '{ cookie: { expires: 30 } }';
				} 
				else{
				   $cookie = '';
				}	*/
				$tab_name='tabs_'.$post_id.'_'.$i;
				$fade='0';
				if(isset($wpts['fade']) and $wpts['fade']=='1'){ 
					$fade='1';
				}
				$sel = '';
				if( $data_selected != "" ) $sel = $data_selected;
				$reload = '0';
				if(isset($wpts['reload']) and $wpts['reload']=='1') $reload = '1';
				$nav = '0';
				if(isset($wpts['nav']) and $wpts['nav']=='1') $nav = '1';
				$next='';$prev='';
				if(isset($wpts['next_text'])) $next = $wpts['next_text'];
				if(isset($wpts['prev_text'])) $prev = $wpts['prev_text'];
				$enablecookie = '';
				if(isset($wpts['enablecookie'])) $enablecookie = $wpts['enablecookie'];
				$script=$script.'jQuery( "#'.$tab_name.'" ).posttabs({ tabname : "'.$tab_name.'",
					fade 	: "'.$fade.'",
					active	: "'.$sel.'",
					reload	: "'.$reload.'",
					nav	: "'.$nav.'",
					nexttext: "'.$next.'",
					prevtext: "'.$prev.'",
					enablecookie:"'.$enablecookie.'"
				});';
			}
			$script=apply_filters('wpts_tabjs',$script,'$'.$tab_name,$wpts_stylesheet);
			$script = $script.'})';
			$script = $script.'</script> ';
			$line_breaks = array("\r\n", "\n", "\r");
			$script = str_replace($line_breaks, "", $script);
		}
		
		$wpts_prev_post = $post_id;
		
		$support_link='<div style="text-align:right;"><a style="color:#aaa;font-size:9px" href="http://tabbervilla.com/wordpress-post-tabs/" title="WordPress Post Tabs by TabberVilla" target="_blank">WP Post Tabs</a></div>';
		
		if($wpts['support']=='1'){
		  return '<div class="wordpress-post-tabs tabs_'.$post_id.'_'.( $wpts_count - 1 ).'_wrap wordpress-post-tabs-skin-'.$wpts['stylesheet'].'"">'.$tab_content.$support_link.'</div>'.$script;
		}
		else {
		  return '<div class="wordpress-post-tabs tabs_'.$post_id.'_'.( $wpts_count - 1 ).'_wrap wordpress-post-tabs-skin-'.$wpts['stylesheet'].'"">'.$tab_content.'</div>'.$script;
		}
	}
	else {
		return null;
	}
}
add_shortcode( ( isset( $wpts['tab_end_code'] ) ? $wpts['tab_end_code'] : 'end_wptabset' ), 'wpts_end_shortcode');

//Code to add settings page link to the main plugins page on admin
function wpts_admin_url( $query = array() ) {
	global $plugin_page;

	if ( ! isset( $query['page'] ) )
		$query['page'] = $plugin_page;

	$path = 'admin.php';

	if ( $query = build_query( $query ) )
		$path .= '?' . $query;

	$url = admin_url( $path );

	return esc_url_raw( $url );
}

add_filter( 'plugin_action_links', 'wpts_plugin_action_links', 10, 2 );

function wpts_plugin_action_links( $links, $file ) {
	if ( $file != WPTS_PLUGIN_BASENAME )
		return $links;

	$url = wpts_admin_url( array( 'page' => 'wordpress-post-tabs.php' ) );

	$settings_link = '<a href="' . esc_attr( $url ) . '">'
		. esc_html( __( 'Settings') ) . '</a>';

	array_unshift( $links, $settings_link );

	return $links;
}

// function for adding settings page to wp-admin
function wpts_settings() {
    // Add a new submenu under Options:
    add_options_page('WP Post Tabs', 'WP Post Tabs', 'manage_options', basename(__FILE__), 'wpts_settings_page');
}

function wpts_admin_head() {?>
<?php }
add_action('admin_head', 'wpts_admin_head');

//Function to add custom style on settings page - version 1.4
function wpts_custom_css() {
	global $wpts;
	$css=$wpts['css'];
	if($css and !empty($css)){
		if( ( is_admin() and isset($_GET['page']) and 'wordpress-post-tabs.php' == $_GET['page']) or !is_admin() ){	?>
			<script type="text/javascript">jQuery(document).ready(function() { jQuery("head").append("<style type=\"text/css\"><?php echo $css;?></style>"); }) </script>
<?php 	}
	}
}
add_action('wp_footer', 'wpts_custom_css');
add_action('admin_footer', 'wpts_custom_css');

function wpts_plugin_url( $path = '' ) {
	return plugins_url( $path, __FILE__ );
}

function wpts_admin_scripts() {
  if ( is_admin() ){ // admin actions
  // Settings page only
	if ( isset($_GET['page']) && 'wordpress-post-tabs.php' == $_GET['page'] ) {
	wp_enqueue_script('jquery', false, false, false, false);
	wp_enqueue_script( 'wpts_admin_js', wpts_plugin_url( 'js/admin.js' ),	array('jquery'), WPTS_VER, false);
	wp_enqueue_style( 'wpts_admin_css', wpts_plugin_url( 'css/admin.css' ),
		false, WPTS_VER, 'all');
	}
  }
}

add_action( 'admin_init', 'wpts_admin_scripts' );

function wpts_qtag_enqueue_scripts() {
global $wpts;			
	wp_localize_script('wptsqtag', 'wptsadminL10n', array(
			'tab' => ( isset( $wpts['tab_code'] ) ? $wpts['tab_code'] : 'wptab' ),
			'end' => ( isset( $wpts['tab_end_code'] ) ? $wpts['tab_end_code'] : 'end_wptabset' )
		));
}

// This function displays the page content for the Iframe Embed For YouTube Options submenu
function wpts_settings_page() {
?>
<div class="wrap">

<div style="width:65%;margin-top: 15px;margin-bottom: 20px;">
	<div style="float:right;"><strong style="color:#ccc;font-size:9px;">powered by</strong> <a style="margin-left:5px;" href="http://tabbervilla.com/" target="_blank" rel="nofollow"><img src="<?php echo wpts_plugin_url('images/tabbervilla.png');?>" width="120"/></a> </div>
	<h2 style="font-size:26px;">WordPress Post Tabs</h2>
</div>

<form  method="post" action="options.php" id="wpts_form">
<div id="poststuff" class="metabox-holder has-right-sidebar"> 

<div  class="left_panel" id="wpts_form">
<?php
settings_fields('wpts-group');
$wpts = get_option('wpts_options');
$wpts = wpts_populate_settings( $wpts );
?>

<div class="postbox">
<h3 class="hndle"><?php _e('Basic Settings','wpts'); ?></h2>

<div>
<table class="form-table">

<tr valign="top" class="row">
<th scope="row"><label for="wpts_options[stylesheet]"><?php _e('Skin/Style','wpts'); ?></label></th>  
<td><select name="wpts_options[stylesheet]" id="wpts_stylesheet" >

<?php 
$directory = WP_PLUGIN_DIR.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)).'/css/styles/';
if ($handle = opendir($directory)) {
    while (false !== ($file = readdir($handle))) { 
     if($file != '.' and $file != '..') { ?>
      <option value="<?php echo $file;?>" <?php if ($wpts['stylesheet'] == $file){ echo "selected";}?> ><?php echo str_replace("_"," ",$file);?></option>
 <?php  } }
    closedir($handle);
}
?>
</select>
</td>
</tr>

<tr valign="top" class="row even"> 
<th scope="row"><label for="wpts_options[fade]"><?php _e('\'Fade\' effect','wpts'); ?></label></th> 
<td><input name="wpts_options[fade]" type="checkbox" id="wpts_options_fade" value="1" <?php checked("1", $wpts['fade']); ?> /></td> 
</tr>  

<tr valign="top" class="row"> 
<th scope="row"><label for="wpts_options[enable_everywhere]"><?php _e('Enable tabs Sitewide','wpts'); ?></label></th> 
<td><input name="wpts_options[enable_everywhere]" type="checkbox" id="wpts_options_enable_everywhere" value="1" <?php checked("1", $wpts['enable_everywhere']); ?> />
<span class="moreInfo">
	&nbsp; <span class="trigger"> ? </span>
	<div class="tooltip">
	<?php _e('Enable tabs on archives, index and all other templates','wpts'); ?>
	</div>
</span>
</td> 
</tr> 

<tr valign="top" class="row even"> 
<th scope="row"><label for="wpts_options[enablecookie]"><?php _e('Enable Cookie for last active tab','wpts'); ?></label></th> 
<td><input name="wpts_options[enablecookie]" type="checkbox" id="wpts_options_enablecookie" value="1" <?php checked("1", $wpts['enablecookie']); ?> />
<span class="moreInfo">
	&nbsp; <span class="trigger"> ? </span>
	<div class="tooltip">
	<?php _e('If checked last active tab will open by default','wpts'); ?>

	</div>
</span>
</td> 
</tr>

<tr valign="top" class="row"> 
<th scope="row"><label for="wpts_options[prettylinks]"><?php _e('Enable prettylinks for tab #','wpts'); ?></label></th> 
<td><input name="wpts_options[prettylinks]" type="checkbox" id="wpts_options_prettylinks" value="1" <?php checked("1", $wpts['prettylinks']); ?> />
<span class="moreInfo">
	&nbsp; <span class="trigger"> ? </span>
	<div class="tooltip">
	<?php _e('If checked the tab names will be used as tab # urls instead of #tab-112-...','wpts'); ?>
	</div>
</span>
</td> 
</tr>

<tr valign="top" class="row even"> 
<th scope="row"><label for="wpts_options[nav]"><?php _e('Navigation','wpts'); ?></label></th> 
<td><input name="wpts_options[nav]" type="checkbox" id="wpts_options_nav" value="1" <?php checked("1", $wpts['nav']); ?> />
<span class="moreInfo">
	&nbsp; <span class="trigger"> ? </span>
	<div class="tooltip">
	<?php _e('Enable Navigation Links','wpts'); ?>
	</div>
</span>
</td> 
</tr> 

<tr valign="top" class="row">
<th scope="row"><label for="wpts_options[next_text]"><?php _e('\'Next\' navigation text','wpts'); ?></label></th>
<td><input type="text" name="wpts_options[next_text]" id="wpts_options_next_text" class="regular-text code" value="<?php echo $wpts['next_text']; ?>" />
</td>
</tr>

<tr valign="top" class="row even">
<th scope="row"><label for="wpts_options[prev_text]"><?php _e('\'Prev\' navigation text','wpts'); ?></label></th>
<td><input type="text" name="wpts_options[prev_text]" id="wpts_options_prev_text" class="regular-text code" value="<?php echo $wpts['prev_text']; ?>" /></td>
</tr> 

<tr valign="top" class="row"> 
<th scope="row"><label for="wpts_options[linktarget]"><?php _e('Open tab links in New window','wpts'); ?> </label></th> 
<td><input name="wpts_options[linktarget]" type="checkbox" id="wpts_options_linktarget" value="1" <?php checked("1", $wpts['linktarget']); ?> />
<span class="moreInfo">
	&nbsp; <span class="trigger"> ? </span>
	<div class="tooltip">
	<?php _e('This checkbox should be checked when you are linking the tab to an External Link and would want to open that link in a new window.','wpts'); ?>
	</div>
</span>
</td> 
</tr>

<tr valign="top" class="row even"> 
<th scope="row"><label for="wpts_options[reload]"><?php _e('Reload on click','wpts'); ?></label></th> 
<td><input name="wpts_options[reload]" type="checkbox" id="wpts_options_reload" value="1"  <?php checked("1", $wpts['reload']); ?> />
<span class="moreInfo">
	&nbsp; <span class="trigger"> ? </span>
	<div class="tooltip">
	<?php _e('This may increase your pageviews.','wpts'); ?>
	</div>
</span>
</td> 
</tr> 

<tr valign="top" class="row"> 
<th scope="row"><label for="wpts_options[showtitle]"><?php _e('Title Attribute for tab links','wpts'); ?></label></th> 
<td><input name="wpts_options[showtitle]" type="checkbox" id="wpts_options_showtitle" value="1" <?php checked("1", $wpts['showtitle']); ?> />
<span class="moreInfo">
	&nbsp; <span class="trigger"> ? </span>
	<div class="tooltip">
	<?php _e('If you want title attribute to be displayed when the user hovers on Tab Title.','wpts'); ?>
	</div>
</span>
</td> 
</tr>

<tr valign="top" class="row even"> 
<th scope="row"><label for="wpts_options[disable_fouc]"><?php _e('Disable FOUC','wpts'); ?></label></th> 
<td><input name="wpts_options[disable_fouc]" type="checkbox" id="wpts_options_disable_fouc" value="1" <?php checked("1", $wpts['disable_fouc']); ?> />
<span class="moreInfo">
	&nbsp; <span class="trigger"> ? </span>
	<div class="tooltip">
	<?php _e('If you disable FOUC i.e. Flash Of Unstyled Content, tabs may not be displayed on the browser on which Javascript is disabled.','wpts'); ?>
	</div>
</span>
</td> 
</tr> 

<tr valign="top" class="row"> 
<th scope="row"><label for="wpts_options[jquerynoload]"><?php _e('Disable \'jquery\'','wpts'); ?></label></th> 
<td><input name="wpts_options[jquerynoload]" type="checkbox" id="wpts_options_jquerynoload" value="1" <?php checked("1", $wpts['jquerynoload']); ?> />
<span class="moreInfo">
	&nbsp; <span class="trigger"> ? </span>
	<div class="tooltip">
	<?php _e('In case jQuery.js is added by hardcoding in active theme or plugin. This will avoid js conflict','wpts'); ?>
	</div>
</span>
</td> 
</tr> 
</table> 
<p class="submit" style="padding-left:10px;">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>
</div>
</div>


<div class="postbox">
<h3 class="hndle" style="font-size: 18px;"><?php _e('Advanced Options','wpts'); ?></h2>
 
<h3 style="background: #CCC; color: #222;margin: 20px 0;"><?php _e('Disable Plugin Resources','wpts'); ?> </h3> 

<div style="padding:10px">
<small><?php _e('This will help you avoid loading the plugin files (js,css) on all pages and posts. You would get an option (custom checkbox) on edit post/page panel to individually load resources on selected posts and pages only. If the below checkboxes are not checked, the plugin files will load on every page/post of your wordpress site.','wpts'); ?></small> 
 
<table class="form-table"> 
<tr valign="top"> 
<th scope="row"><label for="wpts_options[posts]"><?php _e('Disable resources on all Posts','wpts'); ?></label></th> 
<td><input name="wpts_options[posts]" type="checkbox" id="wpts_options_posts" value="0" <?php checked("0", $wpts['posts']); ?> />
<span class="moreInfo">
	&nbsp; <span class="trigger"> ? </span>
	<div class="tooltip">
	<?php _e('You would get a custom box on your edit post panel to enable tabs on that particular post.','wpts'); ?>
	</div>
</span>
</td> 
</tr> 
 
<tr valign="top"> 
<th scope="row"><label for="wpts_options[pages]"><?php _e('Disable resources on all Pages','wpts'); ?></label></th> 
<td><input name="wpts_options[pages]" type="checkbox" id="wpts_options_pages" value="0" <?php checked("0", $wpts['pages']); ?>  />
<span class="moreInfo">
	&nbsp; <span class="trigger"> ? </span>
	<div class="tooltip">
	<?php _e('You would get a custom box on your edit page panel to enable tabs on that particular page.','wpts'); ?>
	</div>
</span>
</td> 
</tr>  
</table> 
</div> 
<h3 style="background: #CCC; color: #222;margin: 20px 0;"><?php _e('Custom Shortcodes','wpts'); ?></h3> 
<div style="padding:10px">
<small><?php _e('The default shortcodes are [wptab] for adding a tab and [end_wptabset] to end particular set/group of tabs. Do not use spaces in the custom shortcodes. To check how to insert the tabs in your post/page, please refer the','wpts'); ?> <a href="http://guides.tabbervilla.com/wordpress-post-tabs/"><?php _e('plugin guide','wpts'); ?></a>.</small> 
<p style="color:#F04A4F"><?php _e('IMPORTANT: While changing these values to  new values, you would need to check if you have used old shortcode values in any of the posts.','wpts'); ?></p> 
 
<table class="form-table"> 
 
<tr valign="top"> 
<th scope="row"><label for="wpts_options[tab_code]"><?php _e('Replace [wptab] shortcode with','wpts'); ?></label></th> 
<td>[<input type="text" name="wpts_options[tab_code]" id="wpts_options_tab_code" value="<?php echo $wpts['tab_code']; ?>" />]<small> &nbsp; &nbsp; <?php _e('(For example, you can enter: mytabs)','wpts'); ?></small></td> 
</tr> 
<tr valign="top"> 
<th scope="row"><label for="wpts_options[tab_end_code]"><?php _e('Replace [end_wptabset] shortcode with','wpts'); ?></label></th> 
<td>[<input type="text" name="wpts_options[tab_end_code]" id="wpts_options_tab_end_code" value="<?php echo $wpts['tab_end_code']; ?>" />]<small> &nbsp; &nbsp; <?php _e('(For example, you can enter: end_mytabs)','wpts'); ?></small></td> 
</tr> 

</table>
</div>


<h3 style="background: #CCC; color: #222;margin: 20px 0;"><?php _e('Miscellaneous','wpts'); ?> </h3> 
<div style="padding:10px">
<table class="form-table"> 

<tr valign="top">
<th scope="row"><?php _e('Custom Styles','wpts'); ?></th>
<td><textarea name="wpts_options[css]"  rows="5" cols="32" class="regular-text code"><?php echo $wpts['css']; ?></textarea>
<span class="moreInfo">
	&nbsp; <span class="trigger"> ? </span>
	<div class="tooltip">
	<?php _e('custom css styles that you would want to be applied to the tab  elements.','wpts'); ?>
	</div>
</span>
</td>
</tr>
<?php do_action('wptspro_misc_settings');?>
 
<tr valign="top"> 
<th scope="row"><label for="wpts_options[support]"><?php _e('Promote WordPress Post Tabs WP Plugin','wpts'); ?></label></th> 
<td><input name="wpts_options[support]" type="checkbox" id="wpts_options_support" value="1"  <?php checked("1", $wpts['support']); ?> /> </td> 
</tr> 
 
</table> 

<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>
</div>
</div>
<!--- <a href="http://slidervilla.com/" target="_blank" rel="nofollow"><img src="<?php echo wpts_plugin_url('images/slidervilla-728.jpg');?>" width="100%"/></a> -->

<div style="clear:both;"></div>

</div>

<div class="right_panel"> 

			<div class="postbox"> 
			  <h3 class="hndle"><span><?php _e('About this Plugin:','wpts'); ?></span></h3> 
			  <div class="inside">
			  
			  
			  <div class="right_panel_link">
                <ul>
                <li><a href="http://tabbervilla.com/wordpress-post-tabs/" title="<?php _e('WordPress Post Tabs Homepage','wpts'); ?>" ><?php _e('Plugin Homepage','wpts'); ?></a></li>
                <li><a href="http://tejaswinideshpande.com/" title="<?php _e('WordPress Post Tabs Author Page','wpts'); ?>" ><?php _e('About the Author','wpts'); ?></a></li>
				<li><a href="http://tabbervilla.com" title="<?php _e('Visit TabberVilla','wpts'); ?>
" ><?php _e('Plugin Parent Site','wpts'); ?></a></li>
                <li><a href="http://www.clickonf5.org/go/donate-wp-plugins/" title="<?php _e('Donate if you liked the plugin and support in enhancing WordPress Post Tabs and creating new plugins','wpts'); ?>" ><?php _e('Donate with Paypal','wpts'); ?></a></li>
                </ul> 
			  </div>	
			  
			  <div class="right_panel_img">
				<a style="margin-top:20px;float:right;" href="http://www.clickonf5.org/go/donate-wp-plugins/" target="_blank" rel="nofollow"><img src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" width="100%"/></a>
			  </div>
			  <div class="clear"></div>
				
              </div> 
			</div> 

			<div class="clear"></div>
			

				<div style="margin:0 auto 15px auto;">
							<a href="http://tabbervilla.com/wordpress-post-tabs-pro" title="WordPress Post Tabs PRO plugin" target="_blank"><img src="<?php echo wpts_plugin_url('images/tabbervilla-ad-300x250.jpg');?>" alt="WordPress Post Tabs PRO plugin" width="100%" /></a>
				</div>


          
</div> <!--end of poststuff -->
</form>

</div> <!--end of float wrap -->

<?php	
}
// Hook for adding admin menus
if ( is_admin() ){ // admin actions
  add_action('admin_menu', 'wpts_settings');
  add_action( 'admin_init', 'register_wpts_settings' ); 
} 
function register_wpts_settings() { // whitelist options
  register_setting( 'wpts-group', 'wpts_options' );
}

function get_wpts_default_settings() {
	$default_tab_settings=array('speed' => '1',
	                   'transition' => '',
					   'pages' => '1',
					   'posts' => '1',
					   'stylesheet' => 'default',
					   'reload' => '0',
					   'tab_code' => 'wptab',
					   'tab_end_code' => 'end_wptabset',
					   'support' => '0', 
					   'fade' => '0', 
					   'jquerynoload' => '0',
					   'disable_cookies'=>'0',
					   'showtitle' =>'0',
					   'linktarget' =>'0',
					   'nav'=>'0',
					   'next_text'=>'Next &#187;',
					   'prev_text'=>'&#171; Prev',
					   'enable_everywhere'=>'0',
					   'disable_fouc'=>'0',
					   'prettylinks'=>'0',
					   'enablecookie' => '0',
					   'css'=>''
					   );
	return $default_tab_settings;
}
function wpts_populate_settings( $wpts ) {
	$default_tab_settings = get_wpts_default_settings();
	foreach( $default_tab_settings as $key => $value ){
		if( !isset( $wpts[$key] ) ) $wpts[$key] = '';
	}
	return $wpts;
}

else:
		add_action( 'admin_init', 'wpts_deactivate' );

        function wpts_deactivate() {
            deactivate_plugins( plugin_basename( __FILE__ ) );
        }
endif;
?>
