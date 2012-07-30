<?php
class Playerstat extends App_Model
{	
	public $publicSymbol	 = '@';
	public $internalSymbol	 = '+';
	public $restrictedSymbol = '!';
	
	public function __construct($bound = array()) 
	{
		parent::__construct($bound);
		$this->ordercol = 'ordernr';
		$this->orderdbcol = 'ordernr';		
		$this->order 	= 'ASC';
		$this->usefk 	= false;
		$this->table	= 'bik_playerstats';
	}

	protected function SetRelations()
	{
		$this->relations['recursive'] = false;
		$this->relations['oneToMany'] = array('playerstatrestrictions' => array('class' => 'PlayerstatRestriction', 'fk' => 'playerstat_id'), 'playerstatvalues' => array('class' => 'PlayerStatValue', 'fk' => 'playerstat_id'));
	}
	
	public function GetStatus()
	{
		return array(1 => 'Webbsida', 2 => 'Medlemmar', 3 => 'Styrelse');
	}
	
	public function GetStatusSymbols()
	{
		return array(1 => $this->publicSymbol, 2 => $this->internalSymbol, 3 => $this->restrictedSymbol);
	}
	
}