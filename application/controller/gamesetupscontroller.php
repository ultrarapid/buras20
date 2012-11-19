<?php
class GamesetupsController extends App_Controller
{
	public function admin_add($gameID, $playerID)
	{
		$game = current($this->Gamesetup->Game->GetById($gameID));
		$this->teamSection = $game['Game']['team_id'];
		$this->IsAllowed();
		$this->Gamesetup->AddPlayer($gameID, $playerID);
		$this->Redirect(Anchors::Refer('admin_gamesetups') . '/edit/' . $gameID);	
	}
	
	public function admin_delete($gameID, $playerID, $id)
	{
		$game = current($this->Gamesetup->Game->GetById($gameID));
		$this->teamSection = $game['Game']['team_id'];
		$this->IsAllowed();
		$this->Gamesetup->Del($id);
		$this->Redirect(Anchors::Refer('admin_gamesetups') . '/edit/' . $gameID);
	}

	public function admin_edit($gameID = 0, $getIbf = 0)
	{
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			$this->Redirect(Anchors::Refer('admin_gamesetups') . '/edit/' . $_POST['gsdata']['game_id']);
		}
		
		if ( $gameID > 0 ) {
			$game = current($this->Gamesetup->Game->GetById($gameID));
			$this->teamSection = $game['Game']['team_id'];
			$this->IsAllowed();
			$gps = $this->Gamesetup->GetFullPlayers($gameID);
			
			$this->Set('ids', $this->Gamesetup->GetPlayerIds($gps));
			$this->Set('gameplayers', $gps);
			$this->Set('seasonplayers', $this->Gamesetup->GetSeasonPlayers($gameID));
			$this->Gamesetup->Game->conditions = array(0 => array('field' => 'season_id', 'value' => $game['Game']['season_id']), 1 => array('field' => 'team_id', 'value' => $game['Game']['team_id']), 2 => array('field' => 'gameformat_id', 'value' => 1));
			if ( $getIbf == 1 ) {
				$statProxy = new StatsServiceProxy();
				if ( !empty($game['Game']['ibfid']) ) {
					$playersInGame = $statProxy->GetPlayersByGameID($game['Game']['ibfid']);
					if ( !empty($playersInGame) ) {
						$this->Gamesetup->DeleteByGame_id($gameID);
						foreach ( $playersInGame as $k => $p ) {
							$playerID = $this->Gamesetup->Player->GetLocalIDAndSetGibfID($p['id'], $p['name']);
							$this->Gamesetup->AddPlayer($gameID, $playerID);
						}
					}
				}	
				$this->Redirect(Anchors::Refer('admin_gamesetups') . '/edit/' . $gameID);			
			}
		}
		$this->Set('games', $this->Gamesetup->Game->GetAll());
		$this->Set('game_id', $gameID);
		$this->SetContext('admin');
	}	
	
}