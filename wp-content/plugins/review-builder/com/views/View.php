<?php

abstract class SGRB_View
{
	protected $layouts = array();
	protected $params = array();

	public function setParams($params)
	{
		$this->params = $params;
	}

	public function setLayouts($layouts)
	{
		$this->layouts = $layouts;
	}

	public function configureLayouts($mainLayout)
	{
		return array($mainLayout);
	}

	public function globalParams()
	{
		return array();
	}

	public function buildBody()
	{
		$content = '';

		ob_start();

		//pass variables to the main layout
		foreach ($this->params as $key=>$val) {
			$$key = $val;
		}

		global $sgrb;

		foreach ($this->layouts as $layout) {
			include($sgrb->layoutPath($layout));
		}

		$content .= ob_get_contents();
		ob_end_clean();

		return $content;
	}

	public function prepareView($layout, $params=array())
	{
		$this->setLayouts($this->configureLayouts($layout));
		$this->setParams(array_merge($this->globalParams(), $params));
	}

	public static function render($layout, $params=array())
	{

	}

	public function output()
	{
		echo $this->buildBody();
	}
}
