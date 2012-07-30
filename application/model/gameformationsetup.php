<?php
class GameFormationSetup extends App_Model
{
	public function __construct($bound = array())
	{
		parent::__construct($bound);
		$this->ordercol = 'game_id';
		$this->order 	= 'ASC';
		$this->usefk 	= true;
		$this->table	= 'bik_gamepositions';
	}
}