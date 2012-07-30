<?php
class Gameevent extends App_Model
{

  /**
   * @var array Names of gibf game events and matching number.
   */
	public $gameEventTypes = array(1 => 'Mål', 2 => 'Utvisning', 3 => 'Time out', 4 => 'Straff');

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
	* Gets players with most markings as secondaryplayer on goal events
	*
	* @param  integer Season ID
	* @param  integer Team ID	
	* @param  integer Amount of players to fetch	
	* @throws none
	* @return array With Player id, name and amount of goals
	*/
	public function GetTopPasserBySeasonTeam($seasonID, $teamID, $count = 1)
	{
		$stmt = 'SELECT ge.secondaryplayer_id as Player_id, COUNT(ge.id) as Player_assists, p.firstname as Player_firstname, p.lastname as Player_lastname FROM bik_gameevents as ge
			INNER JOIN bik_players as p
			ON ge.secondaryplayer_id = p.id
			INNER JOIN bik_games as g
			ON ge.game_id = g.id
			WHERE ge.eventtype = 1 
			AND ge.thisteam = 1 
			AND ge.secondaryplayer_id > 0
			AND g.season_id = ?
			AND g.team_id = ?
			GROUP BY secondaryplayer_id
			ORDER BY Player_assists DESC
			LIMIT 0, ?';
		$values = array($seasonID, $teamID, $count);
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
	public function GetTopPenaltyBySeasonTeam($seasonID, $teamID, $count = 1)
	{
		$stmt = 'SELECT ge.secondaryplayer_id as Player_id, SUM(MINUTE(ge.playertime)) as Player_penaltyminutes, p.firstname as Player_firstname, p.lastname as Player_lastname, 
				(SELECT ge2.code AS Player_code
					FROM bik_gameevents AS ge2
					WHERE ge2.primaryplayer_id = ge.primaryplayer_id
					AND ge2.eventtype =2
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
			AND g.season_id = ?
			AND g.team_id = ?
			GROUP BY primaryplayer_id
			ORDER BY Player_penaltyminutes DESC
			LIMIT 0, ?';
		$values = array($seasonID, $teamID, $count);
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
	public function GetTopScorerBySeasonTeam($seasonID, $teamID, $count = 1)
	{
		$stmt = 'SELECT ge.primaryplayer_id as Player_id, COUNT(ge.id) as Player_goals, p.firstname as Player_firstname, p.lastname as Player_lastname FROM bik_gameevents as ge
			INNER JOIN bik_players as p
			ON ge.primaryplayer_id = p.id
			INNER JOIN bik_games as g
			ON ge.game_id = g.id
			WHERE ge.eventtype = 1 
			AND ge.thisteam = 1 
			AND ge.primaryplayer_id > 0
			AND g.season_id = ?
			AND g.team_id = ?
			GROUP BY primaryplayer_id
			ORDER BY Player_goals DESC
			LIMIT 0, ?';
		$values = array($seasonID, $teamID, $count);
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
		if ( !empty($geArr) ) {
			$this->deleteFields = array('game_id' => $gameID);
			if ( $this->Del() ) {
				foreach ( $geArr as $ge ) {
					$this->Save($ge);
				}
			}	
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
						if ( $p['teamtime'] != '00:05:00' || ( $p['teamtime'] == '00:05:00' && strtotime($post['time']) + strtotime('00:00:00') > strtotime($p['time']) + strtotime($p['teamtime']) ) ) {
							unset($this->_penalties[$pk]);	
							$unsetOur = 0;
							$ourActivePenalties--;
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
						} else {
							$theirActivePenalties--;
						}
						unset($this->_penalties[$pk]);
					}
				}
			}

			// players on field. minimum 3 players.
			$geArr[$k]['ourplayers'] = (5 - $ourActivePenalties >= 3) ? 5 - $ourActivePenalties : 3;
			$geArr[$k]['theirplayers'] = (5 - $theirActivePenalties >= 3) ? 5 - $theirActivePenalties : 3;


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
					} else if ( $post['thisteam'] == 0 && $ourActivePenalties > $theirActivePenalties ) {
						$unsetOur = 1;			
					}
				}
			// penalty
			} else if ( $post['eventtype'] == 2 ) {
				if ( $post['thisteam'] == 1 ) {
					$ourActivePenalties++;
				} else if ( $post['thisteam'] == 0 ) {
					$theirActivePenalties++;
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