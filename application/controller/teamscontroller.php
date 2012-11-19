<?php
class TeamsController extends App_Controller
{
	public function admin_delete($id)
	{
		$object = current($this->Team->GetById($id));
		if ( $this->Team->Del($id) ) {
			$this->SetFeedback('text', 'deleted', str_replace('%var1%', $object['Team']['name'], Message::Load('this_deleted')));
		} else {
			$this->SetFeedback('text', 'error', Message::Load('error_deleting'));
		}
		$this->Redirect(Anchors::Refer('admin_teams') . '/edit');
	}
	
	public function admin_edit($id = 0)
	{
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			if ( $this->Team->Save($_POST['data']) ) {
				if ( $id > 0 ) {
					$this->SetFeedback('text', 'saved', Message::Load('team_changed'));
				} else {
					$this->SetFeedback('text', 'saved', Message::Load('team_created'));
				}				
				$this->Redirect(Anchors::Refer('admin_teams') . '/edit');
			} else {
				$this->SetFeedback('text', 'error', Message::Load('error_saving'));
			}
		}	
		if ( $id > 0 ) {
			$this->Set('teamdata', current($this->Team->GetById($id)));
		}
		$this->Set('id', $id);
		$this->Set('teams', $this->Team->GetAll());		
		$this->SetContext('admin');
	}
	
	public function admin_index($id = 0)
	{
		$this->teamSection = $id;
		$this->IsAllowed();
	  $this->Set('teamID', $id);
		$this->Set('team', current($this->Team->GetById($id)));
		$this->SetContext('admin');
	}
	
	public function index($id = 0, $setSeason = null)
	{
		$_toplistAmount = 3;
		$activeSeason = $this->Team->Seasonteamplayer->Season->GetActiveSeason();
		$season = empty($setSeason) ? $activeSeason : $this->Team->Seasonteamplayer->Season->GetSeasonByYears(substr($setSeason, 0, 4), substr($setSeason, 5, 4));

		$season = empty($season) ? $activeSeason : $season;
		$seasonID = $season['Season']['id'];
		//$seasonID = 1;
		$table = $this->Team->Seasonteamtable->GetTable($id, $seasonID);
		$this->Set('table', $table);

		$scriptArray = array();

		if ( !empty($table) ) {			

			$this->Set('colors', array('#087543', '#d5d5d5', '#999'));
			$this->Set('stats', $this->Team->Game->GetStatsBySeasonTeam($seasonID, $id));
			$this->Set('toppoints', $this->Team->Game->Gameevent->GetTopPointsBySeasonTeam($seasonID, $id, 1, $_toplistAmount));	

			$this->Set('topplayedgames', $this->Team->Game->Gameevent->GetTopPlayedGamesBySeasonTeam($seasonID, $id, 1, $_toplistAmount));

			$this->Set('topscorer', $this->Team->Game->Gameevent->GetTopScorerBySeasonTeam($seasonID, $id, 1, $_toplistAmount));
			$this->Set('toppasser', $this->Team->Game->Gameevent->GetTopPasserBySeasonTeam($seasonID, $id, 1, $_toplistAmount));

			$this->Set('toppenalty', $this->Team->Game->Gameevent->GetTopPenaltyBySeasonTeam($seasonID, $id, 1, $_toplistAmount));

			$this->Set('boxplaygoals', $this->Team->Game->Gameevent->GetBoxplayGoalsBySeasonTeam($seasonID, $id, 1, 1));
			$this->Set('powerplaygoalslost', $this->Team->Game->Gameevent->GetBoxplayGoalsBySeasonTeam($seasonID, $id, 1, 0));			
			$this->Set('powerplaygoals', $this->Team->Game->Gameevent->GetPowerplayGoalsBySeasonTeam($seasonID, $id, 1, 1));

			$this->Set('ppopportunities', $this->Team->Game->Gameevent->GetPowerPlayOpportunitiesBySeasonTeam($seasonID, $id, 1, 1));

			$this->Set('opowerplaygoals', $this->Team->Game->Gameevent->GetPowerplayGoalsBySeasonTeam($seasonID, $id, 1, 0));

			$this->Set('oppopportunities', $this->Team->Game->Gameevent->GetPowerPlayOpportunitiesBySeasonTeam($seasonID, $id, 1, 0));			

			$this->Set('thisteam', $this->Team->Game->thisTeam);
			
			$statProxy = new StatsServiceProxy();
			$this->Set('penaltyCodes', $statProxy->GetPenaltyCodes());
			$scriptArray[] = 'chart';
		}
		$section = PublicWrapper::GetSection();
		$activeSection = current($section->GetByTarget('teams/index/' . $id));
		
		$this->Set('layoutTitle', $activeSection['Section']['header'] . 'laget - innebandy i siffror');
		$this->Set('activeSection', $activeSection);
		$this->Set('activeSeason', $activeSeason);
		$this->Set('setSeason', $season);
		$this->Set('seasons', $this->Team->Seasonteamplayer->Season->GetPastSeasons());

		$this->SetContext('public', $scriptArray);
	}

}
