<?php
class Game extends App_Model
{
	public $defaultGameFormat = 1;
	public $maxGameGoals      = 30;
	public $maxPeriodGoals    = 15;
	public $thisTeam          = 'Burås IK';
		
	public function __construct($bound = array()) 
	{
		parent::__construct($bound);
		$this->ordercol = 'gamedate';
		$this->order 	= 'ASC';
		$this->usefk 	= false;
		$this->table	= 'bik_games';
	}

	protected function SetRelations()
	{
		$this->relations['oneToMany']  = array('gamesetup' => array('class' => 'Gamesetup', 'fk' => 'game_id'), 'gameformation' => array('class' => 'Gameformation', 'fk' => 'game_id' ), 'gameevent' => array('class' => 'Gameevent', 'fk' => 'game_id'));
		$this->relations['manyToOne'] = array(
											'team' => array('class' => 'Team', 'fk' => 'team_id'),
											'season' => array('class' => 'Season', 'fk' => 'season_id'),
											'gameformat' => array('class' => 'Gameformat', 'fk' => 'gameformat_id'));
		
		
	}
	
	public function FullDelete($id)
	{
		$this->Gameformation->usefk = false;
		$gameformations = $this->Gameformation->GetByGame_id($id);
		
		foreach ( $gameformations as $g ) {
			$this->Gameformation->GamePosition->DeleteByGameformation_id($id);
			$this->Gameformation->Del($g['Gameformation']['id']);
		}
		$this->Gamesetup->DeleteByGame_id($id);
		$this->Del($id);
	}
	
	public function GetFullGame($seasonID, $teamID)
	{
		$usefk = $this->usefk;
		$recursive = $this->recursive;
		$this->usefk = true;
		$this->recursive = true;
		$games = $this->GetBySeasonTeam($seasonID, $teamID);
		$this->usefk = $usefk;
		$this->recursive = $recursive;
		return $games;
	}
	
	public function GetGameFiltered($seasonStart, $gameType, $gameSlug)
	{
		$season = current($this->Season->GetByStartdate($seasonStart . '-07-01'));
		$gameformat = current($this->Gameformat->GetBySlug($gameType));
		$conditions = $this->conditions;
		$this->conditions = array(0 => array('field' => 'season_id', 'value' => $season['Season']['id']), 
															1 => array('field' => 'gameformat_id', 'value' => $gameformat['Gameformat']['id']),
															2 => array('field' => 'slug', 'value' => "'" . $gameSlug . "'"));
		$game = $this->Get();
		$this->conditions = $conditions;
		return $game;		
	}
	
	public function GetBySeasonTeam($seasonID, $teamID)
	{
		$conditions = $this->conditions;
		$this->conditions = array(0 => array('field' => 'season_id', 'value' => $seasonID), 
															1 => array('field' => 'team_id', 'value' => $teamID));
		$games = $this->Get();
		$this->conditions = $conditions;
		return $games;	
	}
	
	public function GetNextGame($teamID)
	{
		$conditions = $this->conditions;
		$limit = $this->limit;
		$order = $this->order;		
		$this->limit = array('start' => 0, 'end' => 1);
		$this->order = 'ASC';
		$date = date('Y-m-d');
		$today = strtotime($date);
		$this->conditions = array(0 => array('field' => 'team_id', 'value' => $teamID), 
															1 => array('field' => 'gamedate', 'separator' => '>', 'value' => "'" . date('Y-m-d', $today) . "'"));
		$game = current($this->Get());
		$this->conditions = $conditions;
		$this->order = $order;
		$this->limit = $limit;
		return $game;
	}
	
	public function GetPreviousGame($teamID)
	{
		$usefk = $this->usefk;
		$conditions = $this->conditions;
		$limit = $this->limit;
		$order = $this->order;
		$this->limit = array('start' => 0, 'end' => 1);
		$date = date('Y-m-d');
		$today = strtotime($date);
		$this->usefk = true;
		$this->conditions = array(0 => array('field' => 'team_id', 'value' => $teamID), 
															1 => array('field' => 'gamedate', 'separator' => '<', 'value' => "'" . date('Y-m-d', $today) . "'"));
		$game = current($this->Get());
		$this->conditions = $conditions;
		$this->order = $order;
		$this->limit = $limit;
		$this->usefk = $usefk;	
		return $game;
	}

	public function GetSeasonGames($teamID, $seasonStart = 'alla', $gameType = 'alla')
	{
		$usefk 										= $this->usefk;
		$this->usefk 							= true;
		$recursive 								= $this->recursive;
		$this->recursive 					= true;
		$conditionFunc						= $this->conditionFunction;
		$this->conditionFunction 	= true;
		
		//print_r($seasonStart);

		$season   	= $seasonStart != 'alla' ? current($this->Season->GetByStartdate($seasonStart . '-07-01')) : 0;
		$seasonID 	= $season == 'alla' ? 0 : $season['Season']['id'];

		$gameFormat = $gameType != 'alla' ? current($this->Gameformat->GetBySlug($gameType)) : 0;

		$gformatID 	= !$gameFormat ? 0 : $gameFormat['Gameformat']['id'];

		//print_r($gformatID);

		$this->conditions = array(0 => array('stmt' => $this->table . '.team_id = ? AND (' . $this->table . '.season_id = ? Or 0 = ?) AND (' . $this->table . '.gameformat_id = ? OR 0 = ?)'));

		//$this->displayQuery = true;
		$values 									= array($teamID, $seasonID, $seasonID, $gformatID, $gformatID);
		//print_r($values);
		$games 										= $this->GetWithValues($values);

		$this->usefk 							= $usefk;
		$this->recursive 					= $recursive;
		$this->conditionFunction 	= $conditionFunc;
		return $games;
	}

	public function GetSeasonGamesByTeamIdSeasonIdGameType($teamID, $seasonID, $gameType = 'alla')
	{
		$usefk 										= $this->usefk;
		$this->usefk 							= true;
		$recursive 								= $this->recursive;
		$this->recursive 					= true;
		$conditionFunc						= $this->conditionFunction;
		$this->conditionFunction 	= true;
		
		$gameFormat = $gameType != 'alla' ? current($this->Gameformat->GetBySlug($gameType)) : 0;

		$gformatID 	= !$gameFormat ? 0 : $gameFormat['Gameformat']['id'];

		//print_r($gformatID);

		$this->conditions = array(0 => array('stmt' => $this->table . '.team_id = ? AND (' . $this->table . '.season_id = ? Or 0 = ?) AND (' . $this->table . '.gameformat_id = ? OR 0 = ?)'));

		//$this->displayQuery = true;
		$values 									= array($teamID, $seasonID, $seasonID, $gformatID, $gformatID);
		//print_r($values);
		$games 										= $this->GetWithValues($values);

		$this->usefk 							= $usefk;
		$this->recursive 					= $recursive;
		$this->conditionFunction 	= $conditionFunc;
		return $games;
	}

	
	public function GetStatsBySeasonTeam($seasonID, $teamID)
	{
		$stmt = 'SELECT 
							(SELECT COUNT(g.id) FROM bik_games as g
								WHERE g.ourscore > g.theirscore
								AND season_id = ? AND team_id = ? AND publish = 1 AND gameformat_id = 1) as Game_win, 
							(SELECT COUNT(g.id) FROM bik_games as g
								WHERE g.ourscore = g.theirscore
								AND season_id = ? AND team_id = ? AND publish = 1 AND gameformat_id = 1) as Game_draw, 
							(SELECT COUNT(g.id) FROM bik_games as g
								WHERE g.ourscore < g.theirscore
								AND season_id = ? AND team_id = ? AND publish = 1 AND gameformat_id = 1) as Game_loss';
		$values = array($seasonID, $teamID, $seasonID, $teamID, $seasonID, $teamID);
		return $this->GetResult($stmt, $values);
	}

	public function GetUpcomingGames($teamID = 0, $numberOfGames = 4)
	{
		$this->usefk = true;
		$conditions = $this->conditions;
		$limit = $this->limit;
		$order = $this->order;
		$this->limit = array('start' => 0, 'end' => $numberOfGames);
		$this->order = 'ASC';
		$date = date('Y-m-d H:i:s');
		$oneDayAgo = strtotime('-1 day', strtotime($date));
		if ( $teamID > 0 ) {
			$this->conditions = array(0 => array('field' => 'team_id', 'value' => $teamID), 
															1 => array('field' => 'gamedate', 'separator' => '>', 'value' => "'" . date('Y-m-d H:i:s', $oneDayAgo) . "'"));
		} else if ( $teamID == 0 ) {
			$this->conditions = array(0 => array('field' => 'gamedate', 'separator' => '>', 'value' => "'" . date('Y-m-d H:i:s', $oneDayAgo) . "'"));			
		}
		$game = $this->Get();
		$this->conditions = $conditions;
		$this->order = $order;
		$this->limit = $limit;
		$this->usefk = false;
		return $game;
	}

}