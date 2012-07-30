<?php
class Position extends App_Model
{
	public function __construct($bound = array())
	{
		parent::__construct($bound);
		$this->usefk 	= false;
		$this->table	= 'bik_positions';		
	}
	
	protected function SetRelations()
	{
		
	}
}