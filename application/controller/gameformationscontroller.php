<?php
class GameformationsController extends App_Controller 
{
	public function admin_add($gameID, $gfID, $posID, $playerID = 0)
	{
		if ( $playerID > 0 ) {
			$gpObj = array('gameformation_id' => $gfID, 'position_id' => $posID, 'player_id' => $playerID);
			if ( $this->Gameformation->GamePosition->Save($gpObj) ) {
				$this->Redirect(Anchors::Refer('admin_gameformations') . '/edit/' . $gameID);
			} else {
				$this->Redirect(Anchors::Refer('admin_gameformations') . '/edit/' . $gameID);
			}
		} else if ( $playerID == 0 ) {
			$this->Set('gameplayers', $this->GetAvailablePlayers($gameID, $gfID));
			$this->Set('game_id', $gameID);
			$this->Set('gf_id', $gfID);
			$this->Set('pos_id', $posID);
		}
		$this->SetContext('admin');
	}
	
	public function admin_delete($gameID, $gposID)
	{
		if ( $this->Gameformation->GamePosition->Del($gposID) ) {
			$this->Redirect(Anchors::Refer('admin_gameformations') . '/edit/' . $gameID);
		}
	}
	
	public function admin_edit($gameID)
	{
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			$form = array();
			$form['game_id'] = $gameID;
			$form['formation_id'] = $_POST['data']['formation_id'];
			$this->Gameformation->conditions = array(0 => array('field' => 'game_id', 'value' => $gameID));
			$form['orderNr'] = $this->Gameformation->MaxOrder() + 1;
			if ( $this->Gameformation->Save($form) ) {
				$this->Redirect(Anchors::Refer('admin_gameformations') . '/edit/' . $gameID);
			}
		}
		$this->SetFormations($gameID);
		$this->Set('game_id', $gameID);
		$this->SetContext('admin');
	}
	
	private function GetAvailablePlayers($gameID, $gfID)
	{
		$naPlayers = $this->Gameformation->GamePosition->GetByGameformation_id($gfID);
		$naIDs = '0';
		foreach ( $naPlayers as $k => $p ) {
			//if ( $k > 0 ) {
				$naIDs .= ', ';
			//}
			$naIDs .= $p['GamePosition']['player_id'];
		}
		$this->Gameformation->Game->Gamesetup->conditions = array(0 => array('field' => 'player_id', 'separator' => 'NOT IN', 'value' => '(' . $naIDs . ')'), 1 => array('field' => 'game_id', 'value' => $gameID));
		$this->Gameformation->Game->Gamesetup->displayQuery = true;
		return $this->Gameformation->Game->Gamesetup->GetFullPlayers($gameID);
		
	}
	
	private function SetFormations($gameID)
	{
		$gFormations = $this->Gameformation->GetWithFormation($gameID);
		$gPositions = array();
		$fRules = array();
		if ( !empty($gFormations) ) {
			$gfIDs = '';
			$fIDs = '';
			foreach ( $gFormations as $k => $f ) {
				if ( $k > 0 ) {
					$fIDs .= ', ';
					$gfIDs .= ', ';
				}
				$fIDs .= $f['Gameformation']['formation_id'];
				$gfIDs .= $f['Gameformation']['id'];
			}
			$this->Gameformation->Formation->conditions = array(0 => array('field' => 'id', 'separator' => 'NOT IN', 'value' => '(' . $fIDs . ')'));
			$this->Gameformation->GamePosition->conditions = array(0 => array('field' => 'gameformation_id', 'separator' => 'IN', 'value' => '(' . $gfIDs . ')'));
			$this->Gameformation->Formation->FormationRestriction->conditions = array(0 => array('field' => 'format_id', 'separator' => 'IN', 'value' => '(' . $fIDs . ')'));
			$gPositions = $this->Gameformation->GamePosition->Get();
			$fRules = $this->Gameformation->Formation->FormationRestriction->GetWithPositions();
		}
		$this->Set('gameFormations', $gFormations);
		$this->Set('gamePositions', $gPositions);
		$this->Set('formationRules', $fRules);
		$this->Set('availableFormations', $this->Gameformation->Formation->GetAll());		
	}
}