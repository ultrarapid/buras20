<?php
class Team extends App_Model
{
	public function __construct($bound = array())
	{
		parent::__construct($bound);
		$this->ordercol = 'name';
		$this->order 	= 'ASC';
		$this->usefk 	= true;
		$this->table	= 'bik_teams';
	}
	
	protected function SetRelations()
	{
		$this->relations['oneToMany'] = array(
											'game' => array('class' => 'Game', 'fk' => 'team_id'),
											'seasonteamplayer' => array('class' => 'Seasonteamplayer', 'fk' => 'team_id'),
											'seasonteamtable' => array('class' => 'Seasonteamtable'));
		//$this->relations['oneToMany']  = array('playersetting' => array('class' => 'Playersetting', 'fk' => 'game_id'));											
		
	}
	
	public function GetTeams()
	{
		return "Teams";
	}
	
}