<?php
class Gameformation extends App_Model
{
	public function __construct($bound = array())
	{
		parent::__construct($bound);
		$this->orderdbcol = 'orderNr';
		$this->usefk 	= true;
		$this->table	= 'bik_gameformations';		
	}
	
	protected function SetRelations()
	{
		$this->relations['oneToMany'] = array('gameposition' => array('class' => 'GamePosition', 'fk' => 'gameformation_id'));
		$this->relations['manyToOne'] = array('game' => array('class' => 'Game', 'fk' => 'game_id'), 'formation' => array('class' => 'Formation', 'fk' => 'formation_id'));	
	}
	
	public function DeleteByGame_id($gameID)
	{
		$this->deleteFields = array('game_id' => $gameID);
		if ( $this->Del() ) {
		  return true;
		} else {
			return false;
		}
	}
	
	public function GetWithFormation($gameID)
	{
		$this->ClearRelations();
		$this->relations['manyToOne'] = array('formation' => array('class' => 'Formation', 'fk' => 'formation_id'));			
		$formations = $this->GetByGame_id($gameID);
		$this->SetRelations();
		return $formations;
	}
	
}