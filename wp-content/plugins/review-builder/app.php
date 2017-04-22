<?php
/**
 * Plugin Name: Review Builder
 * Plugin URI: https://sygnoos.com
 * Description: Review Builder will allow you to add reviews section to your site. Build a reviews section so customers can leave reviews for your products.
 * Version: 2.0.4
 * Author: Sygnoos
 * Author URI: https://www.sygnoos.com
 * Text Domain: sgrb
 * License: GPLv3
 */

if (!defined('WPINC')) {
	die();
}

require_once(dirname(__FILE__).'/com/config/bootstrap.php');
require_once(dirname(__FILE__).'/com/core/SGRB.php');

global $sgrb;

$sgrb = new SGRB();
$sgrb->app_path = realpath(dirname(__FILE__)).'/';
$sgrb->app_url = plugin_dir_url(__FILE__);
$sgrb->run();
