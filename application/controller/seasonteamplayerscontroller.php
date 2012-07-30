<?php
class SeasonteamplayersController extends App_Controller
{
	public function admin_add($seasonID, $teamID, $playerID)
	{
		$this->Seasonteamplayer->AddPlayer($seasonID, $teamID, $playerID);
		$this->Redirect(Anchors::Refer('admin_seasonteamplayers') . '/edit/' . $teamID . '/' . $seasonID);	
	}
	
	public function admin_delete($seasonID, $teamID, $id)
	{
		$this->Seasonteamplayer->Del($id);
		$this->Redirect(Anchors::Refer('admin_seasonteamplayers') . '/edit/' . $teamID . '/' . $seasonID);		
	}
	
	public function admin_edit($teamID = 0, $seasonID = 0)
	{
		$this->teamSection = $teamID;
		$this->IsAllowed();
		if ( $seasonID == 0 ) {
		  $season = $this->Seasonteamplayer->Season->GetActiveSeason();
			$seasonID = $season['Season']['id'];
		}
		
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			if ( $seasonID > 0 && $teamID > 0 ) {
				if ( isset($_POST['Filter']) ) {
					$this->SetFilterSession();
				} else if ( isset($_POST['Byt']) ) {
					$this->ClearFilterSession();
				}	
			}
			$this->Redirect(Anchors::Refer('admin_seasonteamplayers') . '/edit/' . $_POST['stpdata']['team_id'] . '/' . $_POST['stpdata']['season_id']);
		}

		if ( $seasonID > 0 && $teamID > 0 )	{
			if ( $this->RenderFilterSession() ) {
				$this->Set('filterplayers', $this->Seasonteamplayer->FilterPlayers());
			} else {
				$this->Set('filterplayers', $this->Seasonteamplayer->Player->GetAll());
			}
			$stps = $this->Seasonteamplayer->GetSeasonTeamPlayers($seasonID, $teamID);
			$this->Set('players', $stps);			
			$this->Set('ids', $this->Seasonteamplayer->GetPlayerIds($stps));
			$this->Set('playerstats', $this->Seasonteamplayer->Player->Playerstat->GetAll());			
		} else {
			$this->ClearFilterSession();
		}
		$this->Set('seasonid', $seasonID);
		$team = current($this->Seasonteamplayer->Team->GetById($teamID));
		$this->Set('team', $team);
		$this->Set('team_id', $team['Team']['id']);
		$this->Set('seasons', $this->Seasonteamplayer->Season->GetAll());
		$this->SetContext('admin');		
	}
	
	public function admin_index($teamID = 0, $seasonID = 0)
	{
		$this->teamSection = $teamID;
		$this->IsAllowed();
		if ( $seasonID == 0 ) {
		  $season = $this->Seasonteamplayer->Season->GetActiveSeason();
			$seasonID = $season['Season']['id'];
		}
		
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			if ( $seasonID > 0 && $teamID > 0 ) {
				if ( isset($_POST['Filter']) ) {
					$this->SetFilterSession();
				} else if ( isset($_POST['Byt']) ) {
					$this->ClearFilterSession();
				}	
			}
			$this->Redirect(Anchors::Refer('admin_seasonteamplayers') . '/index/' . $_POST['stpdata']['team_id'] . '/' . $_POST['stpdata']['season_id']);
		}

		if ( $seasonID > 0 && $teamID > 0 )	{
			if ( $this->RenderFilterSession() ) {
				$this->Set('filterplayers', $this->Seasonteamplayer->FilterPlayers());
			} else {
				$this->Set('filterplayers', $this->Seasonteamplayer->Player->GetAll());
			}
			$stps = $this->Seasonteamplayer->GetSeasonTeamPlayers($seasonID, $teamID);
			$this->Set('players', $stps);			
			$this->Set('ids', $this->Seasonteamplayer->GetPlayerIds($stps));
			$this->Set('playerstats', $this->Seasonteamplayer->Player->Playerstat->GetAll());			
		} else {
			$this->ClearFilterSession();
		}
		$this->Set('seasonid', $seasonID);
		$team = current($this->Seasonteamplayer->Team->GetById($teamID));
		$this->Set('team', $team);
		$this->Set('team_id', $team['Team']['id']);
		$this->Set('seasons', $this->Seasonteamplayer->Season->GetAll());
		$this->SetContext('admin');		
	}
	
	/*
	public function admin_index($teamID, $seasonID = 0)
	{
		$this->teamSection = $teamID;
		$this->IsAllowed();
		if ( $seasonID == 0 ) {
		  $season = $this->Seasonteamplayer->Season->GetActiveSeason();
			$seasonID = $season['Season']['id'];
		}
		$this->Set('players', $this->Seasonteamplayer->GetSeasonTeamPlayers($seasonID, $teamID));
		$this->SetContext('admin');
	}
	*/
	private function ClearFilterSession()
	{
		$this->SessionStart();
		if ( isset($_SESSION['filterstp']) ) {
			$_SESSION['filterstp'] = array();
			unset($_SESSION['filterstp']);
		}
	}
	
	private function RenderFilterSession()
	{
		$this->SessionStart();
		$filter = false;
		if ( isset($_SESSION['filterstp']['firstname']) ) {
			if ( !empty($_SESSION['filterstp']['firstname']) ) {
				$this->Set('filterFirstname', $_SESSION['filterstp']['firstname']);
				$filter = true;
			}
		}
		if ( isset($_SESSION['filterstp']['lastname']) ) {
			if ( !empty($_SESSION['filterstp']['lastname']) ) {
				$this->Set('filterLastname', $_SESSION['filterstp']['lastname']);
				$filter = true;
			}
		}
		if ( isset($_SESSION['filterstp']['value']) ) {
			if ( !empty($_SESSION['filterstp']['value']) ) {
				$this->Set('filterStatvalue', $_SESSION['filterstp']['value']);
				$this->Set('filterStat_id', $_SESSION['filterstp']['playerstat_id']);
				$filter = true;
			}
		}
		return $filter;	
	}
	
	private function SetFilterSession()
	{
		$this->ClearFilterSession();
		if ( !empty($_POST['search']['firstname']) ) {
			$_SESSION['filterstp']['firstname'] = $_POST['search']['firstname'];
		}
		if ( !empty($_POST['search']['lastname']) ) {
			$_SESSION['filterstp']['lastname'] = $_POST['search']['lastname'];
		}
		if ( !empty($_POST['search']['value']) ) {
			if ( $_POST['search']['playerstat_id'] > 0 ) {
				$_SESSION['filterstp']['value'] = $_POST['search']['value'];
				$_SESSION['filterstp']['playerstat_id'] = $_POST['search']['playerstat_id'];
			}
		}
		
	}
}