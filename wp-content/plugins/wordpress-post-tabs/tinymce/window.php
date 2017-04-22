<?php

// look up for the path
require_once( dirname( dirname(__FILE__) ) . '/wpts-config.php');
// check for rights
if ( !current_user_can('edit_pages') && !current_user_can('edit_posts') ) 
	wp_die(__("You are not allowed to be here"));

global $wpdb,$wpts;

?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>WordPress Post Tabs</title>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo get_option('blog_charset'); ?>" />
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/utils/mctabs.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>
	<script type="text/javascript">	var wptsadminL10n={'tab':'<?php echo $wpts['tab_code']; ?>','end':'<?php echo $wpts['tab_end_code']; ?>'};</script>
	<script language="javascript" type="text/javascript" src="<?php echo WPTS_URLPATH; ?>tinymce/tinymce.js"></script> 
	<base target="_self" />
</head>
<body id="link" onLoad="tinyMCEPopup.executeOnLoad('init();');document.body.style.display='';document.getElementById('tabname').focus();" style="display: none">
<!-- <form onsubmit="insertLink();return false;" action="#"> -->
	<form name="WPTS" action="#">
	
		<br />
		<table border="0" cellpadding="4" cellspacing="0">
         <tr>
            <td nowrap="nowrap"><label for="tabname">Enter Tab Names</label></td>
            <td><input type="text" id="tabname" name="tabname" value="" size="50" /></td>
          </tr>
		  <tr>
			<td></td>
            <td nowrap="nowrap"><label for="ex">e.g. Overview, Features, Test, Results</label></td>
            
          </tr>
        </table>

	<div class="mceActionPanel">
		<div style="float: left">
			<input type="button" id="cancel" name="cancel" value="Cancel" onClick="tinyMCEPopup.close();" />
		</div>

		<div style="float: right">
			<input type="submit" id="insert" name="insert" value="Insert" onClick="insertWPTSLink();" />
		</div>
	</div>
</form>
</body>
</html>
