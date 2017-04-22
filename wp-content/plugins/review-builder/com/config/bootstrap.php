<?php
if (!class_exists('SGRB_Helper')) {
	require_once(dirname(__FILE__).'/SGRB_Helper.php');
	$sgrbHelper = new SGRB_Helper();
}
else {
	wp_die('You have already installed the plugin');
}
require_once(dirname(__FILE__).'/config.php');
require_once(dirname(__FILE__).'/autoload.php');
