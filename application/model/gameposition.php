<?php
class GamePosition extends App_Model
{
	public function __construct($bound = array())
	{
		parent::__construct($bound);
		$this->usefk 	= true;
		$this->table	= 'bik_gamepositions';
	}
	
	protected function SetRelations()
	{
		$this->relations['manyToOne'] = array('player' => array('class' => 'Player', 'fk' => 'player_id'));	
	}

	public function DeleteByGameformation_id($gameformationID)
	{
		$this->deleteFields = array('gameformation_id' => $gameformationID);
		if ( $this->Del() ) {
		  return true;
		} else {
			return false;
		}
	}
	
	
	
}