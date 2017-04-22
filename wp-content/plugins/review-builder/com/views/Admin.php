<?php

global $sgrb;
$sgrb->includeView('View');

class SGRB_AdminView extends SGRB_View
{
	public function configureLayouts($mainLayout)
	{
		return array($mainLayout);
	}

	public static function render($layout, $params=array())
	{
		$view = new self();
		$view->prepareView($layout, $params);
		$view->output();
	}
}
