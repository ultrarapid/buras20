<?php
class GameeventsController extends App_Controller
{
	public function admin_edit($gameID = 0, $getIbf = 0)
	{
		if ( $gameID > 0 ) {
			$game = current($this->Gameevent->Game->GetById($gameID));
			$this->teamSection = $game['Game']['team_id'];
			$this->IsAllowed();
			if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
				if ( $_POST['form_id'] == 2 ) {
					if (array_key_exists('data', $_POST)) {
						$this->Gameevent->SaveGameeventData($gameID);
					}
				}
			} 
			$this->Set('game', $game);
			$this->Set('gameevents', $this->Gameevent->GetGameEventsWithPlayerNames($gameID));
			$this->Set('gameevent_types', $this->Gameevent->gameEventTypes);
			$this->Set('gameevent_types_flipped', array_flip($this->Gameevent->gameEventTypes));
			$this->Set('thisTeam', $this->Gameevent->Game->thisTeam);
			$gibfService  = new GibfService();
			$this->Set('penaltyCodes', $gibfService->GetPenaltyCodes());
			$codes = array_flip($this->Gameevent->gameEventTypes);
			if ( $getIbf > 0 ) {
				$gibfService = new GibfService();
				if ( !empty($game['Game']['ibfid']) ) {
					$this->Set('gibf_events', $gibfService->GetGameEventsByGameId($game['Game']['ibfid']));
				}
			}	
		}
		$this->SetContext('admin');
	}

	public function admin_index()
	{
		$this->Gameevent->GetGameEventsWithPlayerNames2(6);
	}
	
}