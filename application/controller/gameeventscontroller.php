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
				if ( $_POST['form_id'] == 1 ) {
					$this->Redirect(Anchors::Refer('admin_gameevents') . '/edit/' . $_POST['gsdata']['game_id']);
				}
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
			$statProxy = new StatsServiceProxy();
			$this->Set('penaltyCodes', $statProxy->GetPenaltyCodes());
			$codes = array_flip($this->Gameevent->gameEventTypes);
			$this->Gameevent->Game->conditions = array(0 => array('field' => 'season_id', 'value' => $game['Game']['season_id']), 1 => array('field' => 'team_id', 'value' => $game['Game']['team_id']), 2 => array('field' => 'gameformat_id', 'value' => 1));			
			$this->Set('games', $this->Gameevent->Game->GetAll());
			$this->Set('game_id', $gameID);
			if ( $getIbf > 0 ) {
				if ( !empty($game['Game']['ibfid']) ) {
					$this->Set('gibf_events', $statProxy->GetGameEventsByGameId($game['Game']['ibfid']));
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