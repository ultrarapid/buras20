<?php
class PlayerStatValue extends App_Model
{	
	public function __construct($bound = array()) 
	{
		parent::__construct($bound);
		$this->usefk 	= true;
		$this->table	= 'bik_player_stat_values';
	}

	protected function SetRelations()
	{
		//$this->relations['manyToOne']  = array('player' => array('class' => 'Player', 'fk' => 'player_id'));
		//$this->relations['manyToMany'] = array('playerstats' => array('class' => 'Playerstat', 'fk' => 'player_id', 'joinFk'	=> 'attribute_id', 'joinTable' => 'bik_player_values'));		
	}
	
	

}