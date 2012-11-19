<?php
class SeasonteamtablesController extends App_Controller
{

	protected $dbTextFields = array('url', 'division');

	public function admin_table($teamID, $seasonID = 0)
	{
		$this->teamSection = $teamID;
		$this->IsAllowed();
		
		if ( $seasonID == 0 ) {
			$season = $this->Seasonteamtable->Season->GetActiveSeason();
			$seasonID = $season['Season']['id'];
		}

		$seasonteamtable = $this->Seasonteamtable->GetTableBySeasonTeam($seasonID, $teamID);
		
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			if ( $_POST['form_id'] == 1 ) {
				$this->Redirect(Anchors::Refer('admin_seasonteamtables') . '/table/' . $teamID . '/' . $_POST['gdata']['season_id']);
			} else if ( $_POST['form_id'] == 2 ) {
				$_POST['data']['season_id'] = $seasonID;
				$_POST['data']['team_id'] = $teamID;
				if ( !empty($seasonteamtable) ) {
					$_POST['data']['id'] = $seasonteamtable['Seasonteamtable']['id'];
				}
				
				if ( $this->Seasonteamtable->Save($_POST['data']) ) {
					$this->Redirect(Anchors::Refer('admin_seasonteamtables') . '/table/' . $teamID . '/' . $seasonID);
				}
				//print_r($_POST['data']);
			}
		}
		$this->Set('season_id', $seasonID);
		$this->Set('seasonteamtable', $seasonteamtable);
		$this->Set('seasons', $this->Seasonteamtable->Season->GetAll());

		$this->SetContext('admin');
	}

	public function admin_x($teamID, $setSeason = '') {
		$section = PublicWrapper::GetSection();
		$activeSection = current($section->GetByTarget('seasonteamtables/table/' . $teamID));

		$activeSeason  = $this->Seasonteamtable->Season->GetActiveSeason();
		$thisSeason    = $setSeason == '' ? $activeSeason : $this->Seasonteamtable->Season->GetSeasonByYears(substr($setSeason, 0, 4), substr($setSeason, 5, 4)) ;
		$thisSeasonID  = $thisSeason['Season']['id'];
		$thisSeasonUrl = substr($thisSeason['Season']['startdate'], 0, 4) . '-' . substr($thisSeason['Season']['enddate'], 0, 4);
		$this->Set('activeSection', $activeSection);		
		$this->Set('activeSeason', $activeSeason);
		$this->Set('thisSeasonID', $thisSeasonID);
		$this->Set('thisSeasonUrl', $thisSeasonUrl);
		$this->Set('seasons', $this->Seasonteamtable->Season->GetPastSeasons());
		$this->Set('tableRows', $this->Seasonteamtable->GetTable($teamID, $thisSeasonID));
		$this->SetContext('public');			
	}

	public function table($teamID, $setSeason = '')
	{
		$section = PublicWrapper::GetSection();
		$activeSection = current($section->GetByTarget('seasonteamtables/table/' . $teamID));

		$activeSeason  = $this->Seasonteamtable->Season->GetActiveSeason();
		$thisSeason    = $setSeason == '' ? $activeSeason : $this->Seasonteamtable->Season->GetSeasonByYears(substr($setSeason, 0, 4), substr($setSeason, 5, 4)) ;
		$thisSeasonID  = $thisSeason['Season']['id'];
		$thisSeasonUrl = substr($thisSeason['Season']['startdate'], 0, 4) . '-' . substr($thisSeason['Season']['enddate'], 0, 4);
		$this->Set('activeSection', $activeSection);		
		$this->Set('activeSeason', $activeSeason);
		$this->Set('thisSeasonID', $thisSeasonID);
		$this->Set('thisSeasonUrl', $thisSeasonUrl);
		$this->Set('seasons', $this->Seasonteamtable->Season->GetPastSeasons());
		$this->Set('tableRows', $this->Seasonteamtable->GetTable($teamID, $thisSeasonID));
		$this->SetContext('public');		
	}
}