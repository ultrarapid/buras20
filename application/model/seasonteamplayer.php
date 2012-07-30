<?php
class Seasonteamplayer extends App_Model
{
	public function __construct($bound = array())
	{
		parent::__construct($bound);
		$this->usefk 	= false;
		$this->table	= 'bik_season_team_players';		
	}
	
	protected function SetRelations()
	{
		$this->relations['recursive'] = false;
		$this->relations['manyToOne'] = array('players' => array('class' => 'Player', 'fk' => 'player_id', 'orderby' => 1), 'seasons' => array('class' => 'Season', 'fk' => 'season_id'), 'teams' => array('class' => 'Team', 'fk' => 'team_id'));
	}
	
	public function AddPlayer($seasonID, $teamID, $playerID)
	{
		$pA = array();
		$pA['season_id'] = $seasonID;
		$pA['team_id'] 	 = $teamID;
		$pA['player_id'] = $playerID;
		if ( $this->Save($pA) ) {
			return true;
		} else {
			return false;
		}
	}
	
	public function FilterPlayers()
	{
		$nameArray = array();
		if ( isset($_SESSION['filterstp']['firstname']) ) {
			if ( !empty($_SESSION['filterstp']['firstname']) ) {
				$nameArray[] = array('field' => 'firstname', 'value' => "'%" . $_SESSION['filterstp']['firstname'] . "%'", 'separator' => 'LIKE');
			}
		}
		if ( isset($_SESSION['filterstp']['lastname']) ) {
			if ( !empty($_SESSION['filterstp']['lastname']) ) {
				$nameArray[] = array('field' => 'lastname', 'value' => "'%" . $_SESSION['filterstp']['lastname'] . "%'", 'separator' => 'LIKE');
			}
		}
		if ( isset($_SESSION['filterstp']['value']) ) {
			if ( !empty($_SESSION['filterstp']['value']) ) {
				$conditions = array(0 => array('field' => 'playerstat_id', 'value' => $_SESSION['filterstp']['playerstat_id']), 1 => array('field' => 'value', 'value' => "'" . $_SESSION['filterstp']['value'] . "'", 'separator' => 'LIKE'));
				$this->Player->usefk = true;
				$this->Player->relations['manyToManyCondition'] = array(0 => array('joinTable' => $this->Player->PlayerStatValue->table, 'fk' => 'player_id', 'condition' => $conditions));
			}
		}
		$this->Player->conditions = $nameArray;
		return $this->Player->Get();	
	}

	public function GetPlayerIds($stps)
	{
		$ids = array();
		foreach ( $stps as $stp) {
			$ids[] = $stp['Seasonteamplayer']['player_id'];
		}
		return $ids;
	}

	public function GetSeasonTeamPlayers($seasonID, $teamID)
	{
		$conditions = $this->conditions;
		$this->recursive = true;
		$this->usefk = true;
		$this->manyToOne = array('players' => array('class' => 'Player', 'fk' => 'player_id', 'orderby' => 1));			
		$this->conditions = array(0 => array('field' => 'season_id', 'value' => $seasonID), 1 => array('field' => 'team_id', 'value' => $teamID));
		$stps = $this->Get();
		$this->conditions = $conditions;
		return $stps;
	}
	
	public function SaveTeam($seasonID, $teamID)
	{
		$pArray = array();
		$pArray['season_id'] = $seasonID;
		$pArray['team_id'] = $teamID;
		$complete = true;
		foreach ( $_POST['pdata'] as $pdata ) {
			if ( isset($pArray['id']) ) {
				unset($pArray['id']);
			}
			if ( isset($pdata['control']) ) {
				$pArray['player_id'] = $pdata['player_id'];
				if ( isset($pdata['id']) ) {
					$pArray['id'] = $pdata['id'];
				}
				if ( !$this->Save($pArray) ) {
					$complete = false;
				}
			} else {
				if ( array_key_exists('id', $pdata) ) {
					if ( !$this->Del($pdata['id']) ) {
						$complete = false;
					}							
				} 
			}
		}
		return $complete;
	}
	

}