<?php
class Gameformat extends App_Model
{
	public $defaultPeriods		= 3;
	public $defaultPeriodTime	= 20;
	public $defaultOvertime		= 0;
	
	public function __construct($bound = array())
	{
		parent::__construct($bound);
		$this->ordercol = 'name';
		$this->order 	= 'ASC';
		$this->usefk 	= true;
		$this->table	= 'bik_gameformats';
	}
	
	protected function SetRelations()
	{
		$this->relations['oneToMany'] = array(
											'game' => array('class' => 'Game', 'fk' => 'gameformat_id'));
	
		//$this->relations['oneToMany']  = array('playersetting' => array('class' => 'Playersetting', 'fk' => 'game_id'));											
		
	}
	
}