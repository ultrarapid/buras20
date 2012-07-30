<?php
class Player extends App_Model
{	
	public function __construct($bound = array()) 
	{
		parent::__construct($bound);
		$this->ordercol = 'firstname lastname';
		$this->order 	= 'ASC';
		$this->usefk 	= true;
		$this->table	= 'bik_players';
	}

	protected function SetRelations()
	{
		$this->relations['recursive'] = false;
		$this->relations['manyToMany'] = array('playerstat' => array('class' => 'Playerstat', 'fk' => 'player_id', 'joinFk' => 'playerstat_id', 'joinTable' => 'bik_player_stat_values'));
		
		$this->relations['oneToMany'] = array('playerstatvalues' => 
											array('class' => 'PlayerStatValue', 'fk' => 'playerstat_id'),
											'seasonteamplayers' => 
											array('class' => 'Seasonteamplayer', 'fk' => 'player_id'));			
	}

	public function GetPlayerByFirstnameAndLastname($firstname, $lastname)
	{
		$conditionArray = array();
		$conditions = $this->conditions;

		$conditionArray[] = array('field' => 'firstname', 'value' => "'%" . $firstname . "%'", 'separator' => 'LIKE');

		$conditionArray[] = array('field' => 'lastname', 'value' => "'%" . $lastname . "%'", 'separator' => 'LIKE');
		$this->conditions = $conditionArray;
		$player = $this->Get();
		$this->conditions = $conditions;
		return $player;
	}
	
	public function GetLocalIDAndSetGibfID($gibfID, $fullname)
	{
		//$player = array();
		$player = $this->GetByIbfplayerid($gibfID);
		if ( empty($player) ) {
			$player = $this->GetPlayerByFirstnameAndLastname(substr($fullname, 0, strpos($fullname, ' ')), substr($fullname, strpos($fullname, ' ') + 1));
			if ( !empty($player) ) {
				//$this->Game->Gamesetup->Player->SetIbfID($)
				$playerWithGibfID = array();
				$playerWithGibfID['id'] = $player[0]['Player']['id'];
				$playerWithGibfID['ibfplayerid'] = $gibfID;
				$this->Save($playerWithGibfID);
			}
		}
		return $player[0]['Player']['id'];
	}

	public function GetPlayersWithContacts($teamID = 0, $seasonID = 0, $statsList = array())
	{
		if ( is_numeric($teamID) && is_numeric($seasonID) ) 
		{
			$this->ClearRelations();	
			$statsList = array(0 => 82, 1 => 83);	
			$conditionStatement = $this->PlayerStatValue->table . '.playerstat_id IN (' . implode(', ' , $statsList) . ')';	
			if ( $teamID > 0 ) {
				$conditionStatement .=  ' AND ' . $this->Seasonteamplayer->table . '.team_id = ' . $teamID;
			}			
			if ( $seasonID > 0 ) {
				$conditionStatement .=  ' AND ' . $this->Seasonteamplayer->table . '.season_id = ' . $seasonID;
			}			
			$mtmConditions = array(0 => array('conditionStatement' => $conditionStatement));
			$this->relations['manyToManyCondition'] = array(0 => array('joinTable' => $this->PlayerStatValue->table, 'fk' => 'player_id', 'condition' => $mtmConditions));
			if ( $seasonID > 0 || $teamID > 0 ) {
				$this->relations['manyToManyCondition'][] = array('joinTable' => $this->Seasonteamplayer->table, 'fk' => 'player_id');
			}
			$this->relations['oneToMany'] = array('playerstatvalues' => array('class' => 'PlayerStatValue', 'fk' => 'player_id'));
					
			/*
			$this->displayQuery = true;
			$this->PlayerStatValue->displayQuery = true;
			$this->Seasonteamplayer->displayQuery = true;
			*/
							
			$this->usefk = true;
			$this->recursive = true;
			$this->distinct = true;
			return $this->Get();
		}
	}
	
	public function GetPlayersInSeasonTeam($seasonID, $teamID)
	{
		//$conditions = $this->conditions;
		$this->recursive = false;
		$this->usefk = true;
		$mtmConditions = array(0 => array('field' => 'season_id', 'value' => $seasonID), 1 => array('field' => 'team_id', 'value' => $teamID));		
		$this->relations['manyToManyCondition'] = array(0 => array('joinTable' => $this->Seasonteamplayer->table, 'fk' => 'player_id', 'condition' => $mtmConditions));		
		
		
		
		
		
		//$this->manyToOne = array('players' => array('class' => 'Player', 'fk' => 'player_id', 'orderby' => 1));			
		//$this->conditions = array(0 => array('field' => 'season_id', 'value' => $seasonID), 1 => array('field' => 'team_id', 'value' => $teamID));
		$stps = $this->Get();
		$this->relations['manyToManyCondition'] = array();
		return $stps;
	}

}