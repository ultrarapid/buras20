<?php
class GamesController extends App_Controller
{
	protected $dbTextFields = array('location', 'opponent', 'pregame', 'postgame');

	public function testgameinfo()
	{
		//echo phpinfo();
	}
	
	
	public function admin_add($teamID = 0)
	{
		if ( $teamID == 0 ) {
			$this->teamSection = -1;
		} else {
			$this->teamSection = $teamID;
		}
		$this->IsAllowed();

		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			$slugString = $_POST['data']['homegame'] == 1 ? Formatter::CreateSlug($this->Game->thisTeam) . '---' . Formatter::CreateSlug($_POST['data']['opponent']) : Formatter::CreateSlug($_POST['data']['opponent']) . '---' . Formatter::CreateSlug($this->Game->thisTeam);
			$_POST['data']['slug'] = $slugString . '-' . Formatter::CreateDateSlug($_POST['format']['gamedate']);			

			$_POST['data']['gamedate'] = $_POST['format']['gamedate'] . ' ' . $_POST['format']['gametime'];

			$this->Game->returnId = true;
			
			$this->GetResultsByHomegame($_POST['data']['homegame']);
			
			//print_r($_POST['data']);

				
			if ( $returnID = $this->Game->Save($_POST['data']) ) {
				$this->SetFeedback('text', 'saved', Message::Load('game_added'));
				$this->Redirect(Anchors::Refer('admin_games_edit') . '/' . $returnID);
			} else {
				$this->SetFeedback('text', 'error', Message::Load('error_saving'));
				$this->PrintFeedback();
			}
		}

		$this->Set('team_id', $teamID);
		$this->Set('max_game_goals', $this->Game->maxGameGoals);		
		$this->Set('max_period_goals', $this->Game->maxPeriodGoals);
		$this->Set('defaultFormat', $this->Game->defaultGameFormat);		
		$this->Set('games', $this->Game->GetByTeam_id($this->teamSection));
		$this->Set('gameformats', $this->Game->Gameformat->GetAll());
		$this->Set('seasons', $this->Game->Season->GetAll());
		$this->Set('active_season', $this->Game->Season->GetActiveSeason());
		$this->Set('allteams', $this->Game->Team->GetAll());
	
		$addedScripts = array(0 => 'jquery-ui-1.8.13.custom.min', 1 => 'ui/jquery.ui.datepicker-min', 2 => 'ui/jquery.ui.datepicker-sv', 3 => 'jquery-ui-timepicker-addon', 4 => 'bbeditor/ed', 5 => 'local-datepicker', 6 => 'admin-edit-game');
		$this->Set('layoutStylesheets', array(0 => array('href' => 'smoothness/jquery-ui-1.8.13.custom')));
		
		$this->SetContext('admin', $addedScripts);		
			
	}
	
	public function admin_delete($id)
	{
		$gObject = current($this->Game->GetById($id));
		if ( !empty($gObject) ) {
			$this->teamSection = $gObject['Game']['team_id'];
			$this->IsAllowed();
			$this->Game->FullDelete($id);			
		}		
	}
	
	public function admin_edit($id = 0)
	{
		$gObject = array();
		if ( $id > 0 ) {
			$gObject = current($this->Game->GetById($id));
			if ( !empty($gObject) ) {
				$this->teamSection = $gObject['Game']['team_id'];
				$this->IsAllowed();
			}	
		}
		
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			$_POST['data']['gamedate'] = $_POST['format']['gamedate'] . ' ' . $_POST['format']['gametime'];
			if ( $id == 0 ) {
				$this->Game->returnId = true;
			}
			
			$this->GetResultsByHomegame($_POST['data']['homegame']);
				
			if ( $returnID = $this->Game->Save($_POST['data']) ) {
				if ( $id > 0 ) {
					$this->SetFeedback('text', 'saved', Message::Load('game_saved'));
					$this->Redirect($_SERVER['REQUEST_URI']);
				} else {
					$this->SetFeedback('text', 'saved', Message::Load('game_added'));
					$this->Redirect(Anchors::Refer('admin_games_edit') . '/' . $returnID);
				}				
			} else {
				$this->SetFeedback('text', 'error', Message::Load('error_saving'));
				$this->PrintFeedback();
				$gObject['Game'] = $_POST['data'];
				if ( !array_key_exists('id', $gObject['Game']) ) {
					$id = 1;
				}
			}
		}		
		
		if ( $id == 0 ) {
		  $this->Set('defaultFormat', $this->Game->defaultGameFormat);
		}
		
		$this->Set('gamedata', $gObject);
		
		$this->Set('id', $id);
		$this->Set('max_game_goals', $this->Game->maxGameGoals);		
		$this->Set('max_period_goals', $this->Game->maxPeriodGoals);
		
		$this->Set('games', $this->Game->GetByTeam_id($this->teamSection));
		$this->Set('gameformats', $this->Game->Gameformat->GetAll());
		$this->Set('seasons', $this->Game->Season->GetAll());
		$this->Set('allteams', $this->Game->Team->GetAll());
	
		$addedScripts = array(0 => 'jquery-ui-1.8.13.custom.min', 1 => 'ui/jquery.ui.datepicker-min', 2 => 'ui/jquery.ui.datepicker-sv', 3 => 'jquery-ui-timepicker-addon', 4 => 'bbeditor/ed', 5 => 'local-datepicker', 6 => 'admin-edit-game');
		$this->Set('layoutStylesheets', array(0 => array('href' => 'smoothness/jquery-ui-1.8.13.custom')));
		
		$this->SetContext('admin', $addedScripts);
	}
	
	public function admin_getschedule($teamID)
	{
		$this->teamSection = $teamID;
		$this->IsAllowed();
		
		$getUrl = '';
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			if ( $_POST['form_id'] == 2 ) {
				$getUrl = $_POST['getUrl'];
				
				if (  array_key_exists('control', $_POST)) {
					foreach ( $_POST['control'] as $k => $value ) {
						$_POST['data'][$k]['team_id'] = $teamID;
						$_POST['data'][$k]['season_id'] = $_POST['season_id'];
						$_POST['data'][$k]['gameformat_id'] = 1;
						$this->Game->Save($_POST['data'][$k]);
					}
				}

			} else {
				$getUrl = $_POST['post_url'];
			}			
			$seasonID = $_POST['season_id'];
			$setSeason = current($this->Game->Season->GetById($seasonID));
			$statProxy = new StatsServiceProxy();
			$this->Set('gibf_games', $statProxy->GetScheduleInSeason($getUrl, $setSeason));
			$this->Set('getUrl', $getUrl);			
			$this->Set('games', $this->Game->GetBySeasonTeam($seasonID, $teamID));
			$this->Set('set_season', $setSeason);
		} else {
			$this->Set('set_season', $this->Game->Season->GetActiveSeason());
		}
		
		$this->Set('thisTeam', $this->Game->thisTeam);
		$this->Set('seasons', $this->Game->Season->GetAll());
		
		$addedScripts = array(0 => 'admin-get-schedule');
		//$this->_encodeContent = true;
		$this->SetContext('admin', $addedScripts);
		
	}
	
	public function admin_index($teamID, $seasonID = 0)
	{
		$this->teamSection = $teamID;
		$this->IsAllowed();

		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			$this->Redirect(Anchors::Refer('admin_games') . '/index/' . $teamID . '/' . $_POST['gdata']['season_id']);
		}
		
		if ( $seasonID == 0 ) {
			$season = $this->Game->Season->GetActiveSeason();
			$seasonID = $season['Season']['id'];
		}

		$this->Set('defaultFormat', $this->Game->defaultGameFormat);
		$this->Set('max_game_goals', $this->Game->maxGameGoals);		
		$this->Set('max_period_goals', $this->Game->maxPeriodGoals);		
		$this->Set('games', $this->Game->GetBySeasonTeam($seasonID, $teamID));
		$this->Set('gameformats', $this->Game->Gameformat->GetAll());
		$this->Set('season_id', $seasonID);
		$this->Set('seasons', $this->Game->Season->GetAll());	
		$this->Set('team_id', $teamID);

		$addedScripts = array(0 => 'jquery-ui-1.8.13.custom.min', 1 => 'ui/jquery.ui.datepicker-min', 2 => 'ui/jquery.ui.datepicker-sv', 3 => 'jquery-ui-timepicker-addon', 4 => 'bbeditor/ed', 5 => 'local-datepicker', 6 => 'admin-edit-game');
		$this->Set('layoutStylesheets', array(0 => array('href' => 'smoothness/jquery-ui-1.8.13.custom')));
		
		$this->SetContext('admin', $addedScripts);
		
	}
	
	public function gameinfo()
	{
		$this->SetContext('public');
	}
	
	public function index($teamID = 0, $setSeason = '', $gameType = 'seriematch', $gameSlug = '')
	{
		$activeSeason = $this->Game->Season->GetActiveSeason();

		$displaySeason = $this->Game->Season->GetSeasonBySeasonYears($setSeason);
		//print_r($thisSeasonID);

		if ( $gameSlug == '' ) {		
			$this->Set('games', $this->Game->GetSeasonGamesByTeamIdSeasonIdGameType($teamID, $displaySeason['Season']['id'], $gameType));
			$this->Set('gameDetails', 0);
		} else {
			$game = current($this->Game->GetGameFiltered(substr($setSeason, 0, 4), $gameType, $gameSlug));
			$this->Set('games', $game);
			$this->Set('gameDetails', 1);
			$seasonTeam = $this->Game->Team->Seasonteamtable->GetTableBySeasonTeam($displaySeason['Season']['id'], $teamID);
			$statProxy = new StatsServiceProxy();
			$this->Set('penaltyCodes', $statProxy->GetPenaltyCodes());			
			$this->Set('thisTeam', $this->Game->thisTeam);
			$this->Set('gameevent_types', $this->Game->Gameevent->gameEventTypes);
			$this->Set('gameevents', $this->Game->Gameevent->GetGameEventsWithPlayerNames($game['Game']['id']));
			$this->Set('layoutTitle', ( $game['Game']['homegame'] == 1 ? $this->Game->thisTeam . ' - ' . $game['Game']['opponent'] : $game['Game']['opponent'] . ' - ' . $this->Game->thisTeam ) . ' - ' . $setSeason . ', ' . $seasonTeam['Seasonteamtable']['division'] . ', Göteborg Innebandy');
		}
		$section = PublicWrapper::GetSection();
		$this->Set('activeSection', current($section->GetByTarget('games/index/' . $teamID)));		
		$this->Set('activeSeason', $this->Game->Season->GetActiveSeason());
		$this->Set('activeGameType', $gameType);
		$this->Set('displaySeason', $displaySeason);
		//$this->Set('setSeason', $season);
		$this->Set('seasons', $this->Game->Season->GetPastSeasons());
		$this->Set('gameformats', $this->Game->Gameformat->GetAll());
		//$this->Set('layoutTitle', 'Matchtest');
		$this->SetContext('public');
	}
	
	private function GetResultsByHomegame($homegame = 1)
	{
		if ( $homegame == 1 ) {
			
			$_POST['data']['ourscore']  = $_POST['rawdata']['homescore'];
			$_POST['data']['ourfirst']  = $_POST['rawdata']['homefirst'];
			$_POST['data']['oursecond'] = $_POST['rawdata']['homesecond'];
			$_POST['data']['ourthird']  = $_POST['rawdata']['homethird'];
			
			$_POST['data']['theirscore']  = $_POST['rawdata']['awayscore'];
			$_POST['data']['theirfirst']  = $_POST['rawdata']['awayfirst'];
			$_POST['data']['theirsecond'] = $_POST['rawdata']['awaysecond'];
			$_POST['data']['theirthird']  = $_POST['rawdata']['awaythird'];
			
		} else if ( $homegame == 0 ) {
			
			$_POST['data']['ourscore']  = $_POST['rawdata']['awayscore'];
			$_POST['data']['ourfirst']  = $_POST['rawdata']['awayfirst'];
			$_POST['data']['oursecond'] = $_POST['rawdata']['awaysecond'];
			$_POST['data']['ourthird']  = $_POST['rawdata']['awaythird'];	
			
			$_POST['data']['theirscore']  = $_POST['rawdata']['homescore'];
			$_POST['data']['theirfirst']  = $_POST['rawdata']['homefirst'];
			$_POST['data']['theirsecond'] = $_POST['rawdata']['homesecond'];
			$_POST['data']['theirthird']  = $_POST['rawdata']['homethird'];
								
		}
		
	}

}