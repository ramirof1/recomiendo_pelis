<?php
class SGRB_Helper {

	public function __construct()
	{
		$this->isFreePluginActive();
		$this->checkPhpVersion();
	}

	private function isFreePluginActive()
	{
		$isActive = class_exists('SGRB');
		// if class exists = Review builder has already activated
		if ($isActive) {
			if (SGRB_PRO_VERSION) {
				wp_die('You have already installed the plugin');
			}
			else {
				wp_die('Please, deactivate the FREE version of our plugin before upgrading to PRO');
			}
		}
	}

	private function checkPhpVersion()
	{
		if (version_compare(PHP_VERSION, '5.3', '<')) {
			wp_die('Review Builder plugin requires PHP version >= 5.3 version required. You server using PHP version = '.PHP_VERSION);
		}
	}
}
