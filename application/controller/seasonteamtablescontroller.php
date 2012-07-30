<?php
class SeasonteamtablesController extends App_Controller
{
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