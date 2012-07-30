<?php
class UserTeam extends App_Model
{	
	public function __construct($bound = array()) 
	{
		parent::__construct($bound);
		$this->usefk 	= false;
		$this->table	= 'bik_user_teams';
	}
	
	public function GetUserTeamIds($userID)
	{
		$idArray = array();
		$userTeams = $this->GetByUser_id($userID);
		foreach ( $userTeams as $ut ) {
			$idArray[] = $ut['UserTeam']['team_id'];
		}
		return $idArray;
	}

	protected function SetRelations()
	{
		//$this->relations['manyToOne']  = array('player' => array('class' => 'Player', 'fk' => 'player_id'));
		//$this->relations['manyToMany'] = array('playerstats' => array('class' => 'Playerstat', 'fk' => 'player_id', 'joinFk'	=> 'attribute_id', 'joinTable' => 'bik_player_values'));
		$this->relations['recursive'] = false;
		$this->relations['manyToOne'] = array('users' => array('class' => 'User', 'fk' => 'user_id'), 'teams' => array('class' => 'Team', 'fk' => 'team_id'));
	}

}