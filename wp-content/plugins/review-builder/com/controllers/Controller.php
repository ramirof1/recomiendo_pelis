<?php

abstract class SGRB_Controller
{
	protected $controller = '';
	protected $action = '';

	public function __construct()
	{

	}

	public function setController($controller)
	{
		$this->controller = $controller;
	}

	public function setAction($action)
	{
		$this->action = $action;
	}

	public function dispatch($param1, $param2)
	{
		$action = $this->action;
		if ($param1 || $param2) {
			return $this->$action($param1, $param2);
		}
		else {
            return $this->$action();
		}
	}
}
