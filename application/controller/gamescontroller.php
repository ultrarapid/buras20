<?php
class GamesController extends App_Controller
{
	protected $dbTextFields = array('location', 'opponent', 'pregame', 'postgame');

	public function testgameinfo()
	{
			$gibf = new GibfService();
			print_r($gibf->GetGameEvents('http://www.innebandy.se/Templates/IDA/MatchInfo.aspx?PageID=106388&MatchID=617927'));
	}
	
	
	public function admin_add($teamID = 0)
	{
		if ( $teamID == 0 ) {
			$this->teamSection = -1;
		} else {
			$this->teamSection = $teamID;
		}
		$this->IsAllowed();
		$this->Set('team_id', $teamID);
		$this->Set('max_game_goals', $this->Game->maxGameGoals);		
		$this->Set('max_period_goals', $this->Game->maxPeriodGoals);
		$this->Set('defaultFormat', $this->Game->defaultGameFormat);		
		$this->Set('games', $this->Game->GetByTeam_id($this->teamSection));
		$this->Set('gameformats', $this->Game->Gameformat->GetAll());
		$this->Set('seasons', $this->Game->Season->GetAll());
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
			$gibf = new GibfService();
			$this->Set('gibf_games', $gibf->GetScheduleInSeason($getUrl, $setSeason));
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
	
	public function admin_index($teamID)
	{
		$this->teamSection = $teamID;
		$this->IsAllowed();
		
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			$_POST['data']['team_id'] = $teamID;
			$_POST['data']['gamedate'] = $_POST['format']['gamedate'] . ' ' . $_POST['format']['gametime'];		
			if ( $this->Game->Save($_POST['data']) ) {
					$this->SetFeedback('text', 'saved', Message::Load('game_saved'));
					$this->Redirect($_SERVER['REQUEST_URI']);	
			} else {
				$this->SetFeedback('text', 'error', Message::Load('error_saving'));
				$this->PrintFeedback();
			}
		}				
		
		$this->Set('defaultFormat', $this->Game->defaultGameFormat);
		$this->Set('max_game_goals', $this->Game->maxGameGoals);		
		$this->Set('max_period_goals', $this->Game->maxPeriodGoals);		
		$this->Set('games', $this->Game->GetByTeam_id($teamID));
		$this->Set('gameformats', $this->Game->Gameformat->GetAll());
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
		$thisSeason = $setSeason == '' || $setSeason == 'alla' ? ( $setSeason == '' ? $activeSeason : false )  : $this->Game->Season->GetSeasonByYears(substr($setSeason, 0, 4), substr($setSeason, 5, 4)) ;
		$thisSeasonID = $thisSeason ? $thisSeason['Season']['id'] : 0;

		if ( $gameSlug == '' ) {		
			//$seasonID = $season['Season']['id'];
			//$this->Set('games', $this->Game->GetFullGame($season['Season']['id'], $teamID));
			$this->Set('games', $this->Game->GetSeasonGames($teamID, substr($setSeason, 0, 4), $gameType));
			$this->Set('gameDetails', 0);
		} else {
			$game = $this->Game->GetGameFiltered(substr($setSeason, 0, 4), $gameType, $gameSlug);
			$this->Set('games', current($game));
			$this->Set('gameDetails', 1);			
		}
		$section = PublicWrapper::GetSection();
		$this->Set('activeSection', current($section->GetByTarget('games/index/' . $teamID)));		
		$this->Set('activeSeason', $activeSeason);
		$this->Set('thisSeasonID', $thisSeasonID);
		//$this->Set('setSeason', $season);
		$this->Set('seasons', $this->Game->Season->GetPastSeasons());
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