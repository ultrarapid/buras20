<?php
class Gamesetup extends App_Model
{
	public function __construct($bound = array())
	{
		parent::__construct($bound);
		$this->usefk 	= false;
		$this->table	= 'bik_gamesetups';		
	}
	
	protected function SetRelations()
	{
		$this->relations['recursive'] = false;					
		$this->relations['manyToOne'] = array('games' => array('class' => 'Game', 'fk' => 'game_id'), 'players' => array('class' => 'Player', 'fk' => 'player_id', 'orderby' => 1));
	}

	
	public function AddPlayer($gameID, $playerID)
	{
		$pA = array();
		$pA['game_id'] = $gameID;
		$pA['player_id'] = $playerID;
		if ( $this->Save($pA) ) {
			return true;
		} else {
			return false;
		}
	}
	
	public function DeleteByGame_id($gameID)
	{
		$this->deleteFields = array('game_id' => $gameID);
		if ( $this->Del() ) {
		  return true;
		} else {
			return false;
		}
	}
	
	public function GetFullPlayers($gameID)
	{
		$this->recursive = true;
		$this->usefk = true;
		$mto = $this->relations['manyToOne'];
		$this->relations['manyToOne'] = array('players' => array('class' => 'Player', 'fk' => 'player_id', 'orderby' => 1));
		return $this->GetByGame_id($gameID);
	}


	public function GetPlayerIds($gps)
	{
		$ids = array();
		foreach ( $gps as $gp) {
			$ids[] = $gp['Gamesetup']['player_id'];
		}
		return $ids;
	}

	public function GetSeasonID($gameID)
	{
		$this->Game->usefk = false;
		$game = current($this->Game->GetById($gameID));
		return $game['Game']['season_id'];		
	}
	
	public function GetSeasonPlayers($gameID)
	{
		$this->Game->usefk = false;
		$game = current($this->Game->GetById($gameID));
		return $this->Player->GetPlayersInSeasonTeam($game['Game']['season_id'], $game['Game']['team_id']);
	}
	
	public function GetTeamID($gameID)
	{
		$this->Game->usefk = false;
		$game = current($this->Game->GetById($gameID));
		return $game['Game']['team_id'];
	}
}