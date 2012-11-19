<?php
class Gameevent extends App_Model
{

  /**
   * @var array Names of gibf game events and matching number.
   */
	public $gameEventTypes = array(1 => 'Mål', 2 => 'Utvisning', 3 => 'Time out', 4 => 'Straff');

  /**
   * @var boolean Full logging On/Off
   */
	private $_logging		   = false;

  /**
   * @var integer Local ID of game
   */
	private $_gameID		   = 0;

  /**
   * @var array Active penalties
   */	
	private $_penalties    = array();

  /**
   * @var array All penaltyshots in game
   */		
	private $_penaltyshots = array();

  /**
   * @var array All goals in the game
   */		
	private $_scores			 = array();

  /**
   * @var array Timeouts from the games
   */		
	private $_timeouts		 = array();
	
	public function __construct($bound = array())
	{
		parent::__construct($bound);
		$this->usefk 	= false;
		$this->table	= 'bik_gameevents';		
	}

 /**
	* Sets this dbobjects relations
	*
	* @param  None
	* @throws none
	* @return Void
	*/	
	protected function SetRelations()
	{
		$this->relations['recursive'] = false;					
		$this->relations['manyToOne'] = array('games' => array('class' => 'Game', 'fk' => 'game_id'), 'player' => array('class' => 'Player', 'fk' => 'primaryplayer_id' , 'join' => 'LEFT OUTER'));
	}

 /**
	* Gets made boxplay goals
	*
	* @param  integer Season ID
	* @param  integer Team ID	
	* @param  integer Gameformat ID
	* @param  integer thisteam
	* @throws none
	* @return array With Player id, name and amount of goals
	*/
	public function GetBoxplayGoalsBySeasonTeam($seasonID, $teamID, $gameformatID = 1, $thisteam = 1)
	{
		$playercompare = $thisteam == 0 ? '>' : '<';

		$stmt = 'SELECT count(ge.id) as Team_bpcount FROM bik_gameevents as ge
						INNER JOIN bik_games AS g
						ON g.id = ge.game_id
						WHERE (g.season_id = ? OR 0 = ?)
						AND (g.team_id = ? OR 0 = ?)
						AND (g.gameformat_id = ? OR 0 = ?)
						AND ge.thisteam = ?
						AND ge.eventtype = 1
						AND ge.ourplayers ' . $playercompare . ' ge.theirplayers';
		
		$values = array($seasonID, $seasonID, $teamID, $teamID, $gameformatID, $gameformatID, $thisteam);
		return current($this->GetResult($stmt, $values));
	}

 /**
	* Gets playerdata joined with gameevents
	*
	* @param  integer Local ID of game
	* @throws none
	* @return array with gameevent data
	*/
	public function GetGameEventsWithPlayerNames($gameID)
	{ 
		$this->usefk = true;
		$this->relations['manyToOne'] = array('player1' => array('class' => 'Player', 'fk' => 'primaryplayer_id' , 'join' => 'LEFT OUTER'), 'player2' => array('class' => 'Player', 'fk' => 'secondaryplayer_id', 'alias' => 'player2', 'join' => 'LEFT OUTER'));
		return $this->GetByGame_id($gameID);
	}

 /**
	* Gets made powerplay goals
	*
	* @param  integer Season ID
	* @param  integer Team ID	
	* @param  integer Gameformat ID
	* @param  integer thisteam
	* @throws none
	* @return array With Player id, name and amount of goals
	*/
	public function GetPowerplayGoalsBySeasonTeam($seasonID, $teamID, $gameformatID = 1, $thisteam = 1)
	{
		$playercompare = $thisteam == 0 ? '<' : '>';

		$stmt = 'SELECT count(ge.id) as Team_ppcount FROM bik_gameevents as ge
						INNER JOIN bik_games AS g
						ON g.id = ge.game_id
						WHERE (g.season_id = ? OR 0 = ?)
						AND (g.team_id = ? OR 0 = ?)
						AND (g.gameformat_id = ? OR 0 = ?)
						AND (ge.thisteam = ? OR 0 = ?)
						AND ge.eventtype = 1
						AND ge.ourplayers ' . $playercompare . ' ge.theirplayers';
		
		$values = array($seasonID, $seasonID, $teamID, $teamID, $gameformatID, $gameformatID, $thisteam, $thisteam);
		return current($this->GetResult($stmt, $values));
	}


 /**
	* Gets powerplay opportunities
	*
	* @param  integer Season ID
	* @param  integer Team ID	
	* @param  integer Gameformat ID
	* @param  integer thisteam
	* @throws none
	* @return array With Player id, name and amount of goals
	*/
	public function GetPowerPlayOpportunitiesBySeasonTeam($seasonID, $teamID, $gameformatID = 1, $thisteam = 1)
	{
		$ctrlTeam = $thisteam == 0 ? 0 : 1;
		$oppTeam  = $thisteam == 0 ? 1 : 0;
		$stmt = 'SELECT COUNT(ge.id) AS Team_Penalties, 
							SUM((SELECT count(ge2.id) FROM bik_gameevents as ge2 WHERE ge2.thisteam = ' . $ctrlTeam . ' AND ge.game_id = ge2.game_id AND ge.time = ge2.time AND ge.teamtime = ge2.teamtime) - (SELECT count(ge3.id) FROM bik_gameevents as ge3 WHERE ge3.thisteam = ' . $oppTeam . ' AND ge.game_id = ge3.game_id AND ge.time = ge3.time AND ge.teamtime = ge3.teamtime AND ge.id != ge3.id)) AS Team_Cancelled
						FROM bik_gameevents as ge
						INNER JOIN bik_games as g
						ON g.id = ge.game_id
						WHERE (g.season_id = ? OR 0 = ?)
						AND (g.team_id = ? OR 0 = ?)
						AND (g.gameformat_id = ? OR 0 = ?)
						AND ge.thisteam = ' . $oppTeam . ' 
						AND ge.eventtype = 2 
						AND ge.time < "01:00:00"
						GROUP BY ge.id';

		//$this->displayQuery = true;

		$values = array($seasonID, $seasonID, $teamID, $teamID, $gameformatID, $gameformatID);
		$opp = 0;
		$subtracts = 0;
		$oppArray = $this->GetResult($stmt, $values);
		foreach ( $oppArray as $o ) {
			$opp++;
			$subtracts = $subtracts + $o['Team']['Cancelled'];
		}
		return $opp - $subtracts;
	}

 /**
	* Gets players with most markings as secondaryplayer on goal events
	*
	* @param  integer Season ID
	* @param  integer Team ID	
	* @param  integer Amount of players to fetch	
	* @throws none
	* @return array With Player id, name and amount of goals
	*/
	public function GetTopPasserBySeasonTeam($seasonID, $teamID, $gameformatID = 1, $count = 1)
	{
		$stmt = 'SELECT ge.secondaryplayer_id as Player_id, COUNT(ge.id) as Player_assists, p.firstname as Player_firstname, p.lastname as Player_lastname FROM bik_gameevents as ge
			INNER JOIN bik_players as p
			ON ge.secondaryplayer_id = p.id
			INNER JOIN bik_games as g
			ON ge.game_id = g.id
			WHERE ge.eventtype = 1 
			AND ge.thisteam = 1 
			AND ge.secondaryplayer_id > 0
			AND (g.season_id = ? OR 0 = ?)
			AND (g.team_id = ? OR 0 = ?)
			AND (g.gameformat_id = ? OR 0 = ?)
			GROUP BY secondaryplayer_id
			ORDER BY Player_assists DESC
			LIMIT 0, ?';
		$values = array($seasonID, $seasonID, $teamID, $teamID, $gameformatID, $gameformatID, $count);
		return $this->GetResult($stmt, $values);
	}

 /**
	* Gets players with most minutes in penalty booth penalty events
	*
	* @param  integer Season ID
	* @param  integer Team ID	
	* @param  integer Amount of players to fetch	
	* @throws none
	* @return array With Player id, name and minutes in penaltybox
	*/
	public function GetTopPenaltyBySeasonTeam($seasonID, $teamID, $gameformatID = 1, $count = 1)
	{
		$stmt = 'SELECT ge.secondaryplayer_id as Player_id, SUM(MINUTE(ge.playertime)) as Player_penaltyminutes, p.firstname as Player_firstname, p.lastname as Player_lastname, 
				(SELECT ge2.code AS Player_code
					FROM bik_gameevents AS ge2
					INNER JOIN bik_games AS g2
					ON g2.id = ge2.game_id
					WHERE ge2.primaryplayer_id = ge.primaryplayer_id
					AND ge2.eventtype = 2
					AND (g2.season_id = ? OR 0 = ?)
					AND (g2.team_id = ? OR 0 = ?)
					AND (g2.gameformat_id = ? OR 0 = ?)
					GROUP BY ge2.code
					ORDER BY COUNT( ge2.code ) DESC 
					LIMIT 0 , 1) AS Player_favcode 
			FROM bik_gameevents as ge
			INNER JOIN bik_players as p
			ON ge.primaryplayer_id = p.id
			INNER JOIN bik_games as g
			ON ge.game_id = g.id
			WHERE ge.eventtype = 2 
			AND ge.thisteam = 1 
			AND ge.primaryplayer_id > 0
			AND (g.season_id = ? OR 0 = ?)
			AND (g.team_id = ? OR 0 = ?)
			AND (g.gameformat_id = ? OR 0 = ?)
			GROUP BY primaryplayer_id
			ORDER BY Player_penaltyminutes DESC
			LIMIT 0, ?';
		$values = array($seasonID, $seasonID, $teamID, $teamID, $gameformatID, $gameformatID, $seasonID, $seasonID, $teamID, $teamID, $gameformatID, $gameformatID, $count);
		return $this->GetResult($stmt, $values);
	}


 /**
	* Gets players with most markings as secondaryplayer on goal events
	*
	* @param  integer Season ID
	* @param  integer Team ID	
	* @param  integer Amount of players to fetch	
	* @throws none
	* @return array With Player id, name and amount of goals
	*/
	public function GetTopPlayedGamesBySeasonTeam($seasonID, $teamID, $gameformatID = 1, $count = 1)
	{
		$stmt = 'SELECT gs.player_id as Player_id, COUNT(gs.id) as Player_played, p.firstname as Player_firstname, p.lastname as Player_lastname FROM bik_gamesetups as gs
			INNER JOIN bik_players as p
			ON gs.player_id = p.id
			INNER JOIN bik_games as g
			ON gs.game_id = g.id
			WHERE (g.season_id = ? OR 0 = ?)
			AND (g.team_id = ? OR 0 = ?)
			AND (g.gameformat_id = ? OR 0 = ?)
			GROUP BY gs.player_id
			ORDER BY Player_played DESC
			LIMIT 0, ?';

		//$this->displayQuery = true;

		$values = array($seasonID, $seasonID, $teamID, $teamID, $gameformatID, $gameformatID, $count);
		return $this->GetResult($stmt, $values);
	}

 /**
	* Gets players with most markings as primaryplayer on goal events
	*
	* @param  integer Season ID
	* @param  integer Team ID	
	* @param  integer Amount of players to fetch	
	* @throws none
	* @return array With Player id, name and amount of goals
	*/
	public function GetTopPointsBySeasonTeam($seasonID, $teamID, $gameformatID = 1, $count = 1)
	{

		$stmt = 'SELECT p.id AS Player_id, p.firstname AS Player_firstname, p.lastname AS Player_lastname,
		( SELECT COUNT(ge.id) 
			FROM bik_gameevents AS ge
			INNER JOIN bik_games AS g ON g.id = ge.game_id
			WHERE (p.id = ge.primaryplayer_id OR p.id = ge.secondaryplayer_id )
			AND ge.thisteam = 1 
			AND ge.eventtype = 1
			AND (g.season_id = ? OR 0 = ?)
			AND (g.team_id = ? OR 0 = ?)
			AND (g.gameformat_id = ? OR 0 = ?)
		) AS Player_points FROM bik_players AS p
		WHERE p.id IN 
		( SELECT stp.player_id
			FROM bik_season_team_players AS stp
			WHERE (stp.season_id = ? OR 0 = ?)
			AND (stp.team_id = ? OR 0 = ?))
		ORDER BY Player_points DESC
		LIMIT 0, ?';

		$values = array($seasonID, $seasonID, $teamID, $teamID, $gameformatID, $gameformatID, $seasonID, $seasonID, $teamID, $teamID, $count);
		return $this->GetResult($stmt, $values);

	}


 /**
	* Gets players with most markings as primaryplayer on goal events
	*
	* @param  integer Season ID
	* @param  integer Team ID	
	* @param  integer Amount of players to fetch	
	* @throws none
	* @return array With Player id, name and amount of goals
	*/
	public function GetTopScorerBySeasonTeam($seasonID, $teamID, $gameformatID = 1, $count = 1)
	{
		$stmt = 'SELECT ge.primaryplayer_id as Player_id, COUNT(ge.id) as Player_goals, p.firstname as Player_firstname, p.lastname as Player_lastname FROM bik_gameevents as ge
			INNER JOIN bik_players as p
			ON ge.primaryplayer_id = p.id
			INNER JOIN bik_games as g
			ON ge.game_id = g.id
			WHERE ge.eventtype = 1 
			AND ge.thisteam = 1 
			AND ge.primaryplayer_id > 0
			AND (g.season_id = ? OR 0 = ?)
			AND (g.team_id = ? OR 0 = ?)
			AND (g.gameformat_id = ? OR 0 = ?)
			GROUP BY primaryplayer_id
			ORDER BY Player_goals DESC
			LIMIT 0, ?';
		$values = array($seasonID, $seasonID, $teamID, $teamID, $gameformatID, $gameformatID, $count);
		return $this->GetResult($stmt, $values);
	}


 /**
	* Gets postdata and converts it to fit statistics db and saves
	*
	* @param  integer Local ID of game
	* @throws none
	* @return Void
	*/
	public function SaveGameeventData($gameID)
	{
		$this->_gameID = $gameID;
		$geArr = $this->FillGameArrayFromPostData();
		$geArr = $this->SetPlayersAndCodes($geArr);

		if ( !$this->_logging ) {
			if ( !empty($geArr) ) {
				$this->deleteFields = array('game_id' => $gameID);
				if ( $this->Del() ) {
					foreach ( $geArr as $ge ) {
						$this->Save($ge);
					}
				}	
			}				
		} else {
			print_r($geArr);
		}
		
	}

 /**
	* Calculates how many players is on the field on specific times
	* and sets some codes to specify game events
	*
	* @param  array Array with game event data
	* @throws none
	* @return array Array with corrected data
	*/
	private function SetPlayersAndCodes($geArr)
	{
		$ourActivePenalties = 0;
		$theirActivePenalties = 0;
		$unsetOur = 0;
		$unsetTheir = 0;

		foreach ( $geArr as $k => $post  ) {
			// if a goal was made with a penalty to be deactivated for our team
			if ( $unsetOur == 1 ) {
				foreach ( $this->_penalties as $pk => $p ) {
					if ( $p['thisteam'] == 1 ) {
						// do not deactivate 5 minute penalty unless time is up
						if ( $p['teamtime'] != '00:05:00' || ( $p['teamtime'] == '00:05:00' && strtotime($post['time']) + strtotime('00:00:00') > strtotime($p['time']) + strtotime($p['teamtime']) )  ) {
							unset($this->_penalties[$pk]);
							$unsetOur = 0;
							$ourActivePenalties--;
							if ( $this->_logging ) {
								$geArr[$k]['deductOurFromUnset'] = 1;
							}
						}
						// only deactivate the first penalty				
						break;
					}
				}
			// if a goal was made with a penalty to be deactivated for opponent team
			} else if ( $unsetTheir == 1 ) {
				foreach ( $this->_penalties as $pk => $p ) {
					if ( $p['thisteam'] == 0 ) {
						// do not deactivate 5 minute penalty unless time is up
						if ( $p['teamtime'] != '00:05:00' || ( $p['teamtime'] == '00:05:00' && strtotime($post['time']) + strtotime('00:00:00') > strtotime($p['time']) + strtotime($p['teamtime']) ) ) {
							unset($this->_penalties[$pk]);
							$unsetTheir = 0;
							$theirActivePenalties--;
							if ( $this->_logging ) {
								$geArr[$k]['deductTheirFromUnset'] = 1;
							}
						}
						// only deactivate the first penalty
						break;
					}
				}
			}

			foreach ( $this->_penalties as $pk => $p ) {
				// unsets penalties where the penalty time has passed
				if ( strtotime($p['time']) < strtotime($post['time']) ) {
					if ( strtotime($post['time']) + strtotime('00:00:00') > strtotime($p['time']) + strtotime($p['teamtime']) ) {
						if ( $p['thisteam'] == 1 ) {
							$ourActivePenalties--;
							if ( $this->_logging ) {
								$geArr[$k]['deductOurFromPenalty'] = array_key_exists('deductOurPenalty', $geArr[$k]) ? $geArr[$k]['deductOurFromPenalty']++ : 1;
								$geArr[$k]['ourPenaltyArray'][$pk] = $p;
								$geArr[$k]['ptime'] = strtotime($p['time']);
								$geArr[$k]['postitime'] = strtotime($post['time']);
								$geArr[$k]['teamtime'] = strtotime($p['teamtime']);
								$geArr[$k]['sumGametime'] = strtotime($post['time']) + strtotime('00:00:00');
								$geArr[$k]['sumPenaltytime'] = strtotime($p['time']) + strtotime($p['teamtime']);

							}
						} else {
							$theirActivePenalties--;
							if ( $this->_logging ) {
								$geArr[$k]['deductTheirFromPenalty'] = array_key_exists('deductTheirFromPenalty', $geArr[$k]) ? $geArr[$k]['deductTheirFromPenalty']++ : 1;								
								$geArr[$k]['theirPenaltyArray'][$pk] = $p;
								$geArr[$k]['ptime'] = strtotime($p['time']);
								$geArr[$k]['postitime'] = strtotime($post['time']);
								$geArr[$k]['teamtime'] = strtotime($p['teamtime']);
								$geArr[$k]['sumGametime'] = strtotime($post['time']) + strtotime('00:00:00');
								$geArr[$k]['sumPenaltytime'] = strtotime($p['time']) + strtotime($p['teamtime']);								
							}
						}
						unset($this->_penalties[$pk]);
					}
				}
			}

			// players on field. minimum 3 players.
			$geArr[$k]['ourplayers'] = (5 - $ourActivePenalties >= 3) ? 5 - $ourActivePenalties : 3;
			$geArr[$k]['theirplayers'] = (5 - $theirActivePenalties >= 3) ? 5 - $theirActivePenalties : 3;
			if ( $this->_logging ) {
				$geArr[$k]['theiractivepenalties'] = $theirActivePenalties;
				$geArr[$k]['ouractivepenalties']   = $ourActivePenalties;				
			}

			// goal
			if ( $post['eventtype'] == 1 ) {
				// if the goal was a penaltyshot set goal code to 1
				if ( !empty($this->_penaltyshots) ) {
					foreach ( $this->_penaltyshots as $psk => $ps ) {
						if ( $ps['time'] == $post['time'] ) {
							$geArr[$k]['code'] = 1;
						}
					}
				}
				// if there is a penalty active and the penalty should be deactivated
				if ( ($ourActivePenalties > 0 || $theirActivePenalties > 0) && $theirActivePenalties != $ourActivePenalties ) {
					if ( $post['thisteam'] == 1 && $theirActivePenalties > $ourActivePenalties ) {
						$unsetTheir = 1;
						if ( $this->_logging ) {
							$geArr[$k]['AddUnsetTheirFromGoal'] = 1;							
						}
					} else if ( $post['thisteam'] == 0 && $ourActivePenalties > $theirActivePenalties ) {
						$unsetOur = 1;
						if ( $this->_logging ) {
							$geArr[$k]['AddUnsetOurFromGoal'] = 1;	
						}						
					}
				}
			// penalty
			} else if ( $post['eventtype'] == 2 ) {
				if ( $post['thisteam'] == 1 ) {
					$ourActivePenalties++;
					if ( $this->_logging ) {
						$geArr[$k]['AddOurPenalty'] = 1;						
					}
				} else if ( $post['thisteam'] == 0 ) {
					$theirActivePenalties++;
					if ( $this->_logging ) {
						$geArr[$k]['AddTheirPenalty'] = 1;						
					}
				}
			// penalty shot
			} else if ( $post['eventtype'] == 4 ) {
				// if the penalty resulted in a goal, set code to 1.
				if ( !empty($this->_scores) ) {
					foreach ( $this->_scores as $sk => $s ) {
						if ( $s['time'] == $post['time'] ) {
							$geArr[$k]['code'] = 1;
						}
					}
				}
			}
		}
		return $geArr;	
	}

 /**
	* Fills Game event array. Filters post data based on which events
	* occuring.
	*
	* @param $_POST needs to be set
	* @throws none
	* @return array Array with filtered game event data
	*/
	private function FillGameArrayFromPostData()
	{
		$geArr = array();
		foreach ( $_POST['data'] as $k => $value ) {
			$geArr[$k] 						= $_POST['data'][$k];
			$geArr[$k]['game_id'] = $this->_gameID;
			if ( $_POST['data'][$k]['eventtype'] == 1 ) {
				// goal
				$geArr[$k] = array_merge($geArr[$k], $this->GetScore($k));
			}
			else if ( $_POST['data'][$k]['eventtype'] == 2 ) {
				// penalty
				$geArr[$k] = array_merge($geArr[$k], $this->GetPenalty($k));
			} else if ( $_POST['data'][$k]['eventtype'] == 3 ) {
				// timeout
				$geArr[$k] = array_merge($geArr[$k], $this->GetTimeout($k));
			} else if ( $_POST['data'][$k]['eventtype'] == 4 ) {
				// penalty shot
				$geArr[$k] = array_merge($geArr[$k], $this->GetPenaltyShot($k));
			}
		}
		return $geArr;
	}

 /**
	* Adds specific penalty data to game event array
	*
	* @param  integer Key in $_POST to this game event
	* @throws none
	* @return array Array with this event data
	*/
	private function GetPenalty($key)
	{
		$peArr = array();

		$peArr['teamtime'] 		= '00:0' . $_POST['ctrl'][$key]['teamtime'] . ':00';
		$peArr['playertime']  = '00:' . ( ( strlen($_POST['ctrl'][$key]['playertime']) == 2 ) ? $_POST['ctrl'][$key]['playertime'] : '0' . $_POST['ctrl'][$key]['playertime'] )  . ':00';
		$peArr['thisteam']		= $_POST['data'][$key]['thisteam'];
		$peArr['time']				= $_POST['data'][$key]['time'];

		$primaryKey = 'penalty_player_id';

		if ( $_POST['data'][$key]['thisteam'] == 1 ) {
			if ( array_key_exists($primaryKey, $_POST['ctrl'][$key]) && $_POST['ctrl'][$key][$primaryKey] > 0 ) {
				$peArr['primaryplayer_id'] = $this->Game->Gamesetup->Player->GetLocalIDAndSetGibfID($_POST['ctrl'][$key][$primaryKey], $_POST['ctrl'][$key]['penalty_player_name']);
			}	else {
				$peArr['primaryplayer_id'] = -1;
			}
		}

		$this->_penalties[] = $peArr;

		return $peArr;
	}

 /**
	* Adds specific penalty shot data to game event array
	*
	* @param  integer Key in $_POST to this game event
	* @throws none
	* @return array Array with this event data
	*/
	private function GetPenaltyShot($key)
	{
		$psArr = array();

		$psArr['thisteam']		= $_POST['data'][$key]['thisteam'];
		$psArr['time']				= $_POST['data'][$key]['time'];

		$this->_penaltyshots[] = $psArr;

		return $psArr;
	}

 /**
	* Adds specific goal data to game event array
	*
	* @param  integer Key in $_POST to this game event
	* @throws none
	* @return array Array with this event data
	*/
	private function GetScore($key)
	{
		$scArr = array();

		$primaryKey   = 'primaryplayer_id';
		$secondaryKey = 'secondaryplayer_id';

		if ( $_POST['data'][$key]['thisteam'] == 1 ) {
			if ( array_key_exists($primaryKey, $_POST['ctrl'][$key]) && $_POST['ctrl'][$key][$primaryKey] > 0 ) {
				$scArr[$primaryKey] = $this->Game->Gamesetup->Player->GetLocalIDAndSetGibfID($_POST['ctrl'][$key][$primaryKey], $_POST['ctrl'][$key]['primaryplayer_name']);
			}	else {
				$scArr[$primaryKey] = -1;
			}
			if ( array_key_exists($secondaryKey, $_POST['ctrl'][$key]) && $_POST['ctrl'][$key][$secondaryKey] > 0 ) {
				$scArr[$secondaryKey] = $this->Game->Gamesetup->Player->GetLocalIDAndSetGibfID($_POST['ctrl'][$key][$secondaryKey], $_POST['ctrl'][$key]['secondaryplayer_name']);
			}
			
		}
		$scArr['code'] 		 = 0;
		$scArr['thisteam'] = $_POST['data'][$key]['thisteam'];
		$scArr['time'] 		 = $_POST['data'][$key]['time'];

		$this->_scores[] = $scArr;

		return $scArr;
	}

 /**
	* Adds specific timeout data to game event array
	*
	* @param  integer Key in $_POST to this game event
	* @throws none
	* @return array Array with this event data
	*/
	private function GetTimeout()
	{
		$toArr = array();

		$this->_timeouts[] = $toArr;

		return $toArr;
	}
}