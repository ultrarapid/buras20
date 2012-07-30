<?php
class Guestpost extends App_Model
{	
	public function __construct($bound = array()) 
	{
		parent::__construct($bound);
		//$this->displayQuery = true;
		$this->ordercol = 'created';
		$this->order 	= 'DESC';
		$this->usefk 	= true;
		$this->table	= 'bik_guestbook';
	}

	protected function SetRelations()
	{
		$this->relations['recursive'] = false;
		$this->relations['manyToOne'] = array('user' => array('class' => 'User', 'fk' => 'user_id', 'join' => 'LEFT'));	
	}
}