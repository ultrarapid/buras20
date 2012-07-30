<?php
class Controller 
{
	protected $_controller;
	protected $_action;
	protected $_view;

	public function __construct($controller, $action) 
	{
		$model = ucfirst(rtrim($controller, 's'));
		
		$this->_action		= $action;
		$this->_controller	= $controller;
		if ( $model != 'Page' ) {
			$this->$model		= new $model;
		}
		$this->_view = new View($controller, $action);
	}
	
	public function __destruct() 
	{
		echo $this->_view->Render();
	}
	
	public function PreExecute()
	{
		
	}
		
	public function Set($name, $value) 
	{
		$this->_view->Set($name, $value);
	}
		
}