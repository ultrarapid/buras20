<?php
class PlayerstatRestriction extends App_Model
{	
	public function __construct($bound = array()) 
	{
		parent::__construct($bound);
		$this->usefk 	= true;
		$this->table	= 'bik_playerstat_restrictions';
	}

	protected function SetRelations()
	{
		//$this->relations['manyToOne']  = array('playerstat' => array('class' => 'Playerstat', 'fk' => 'playerstat_id'));
		//$this->relations['manyToMany'] = array('playerstats' => array('class' => 'Playerstat', 'fk' => 'player_id', 'joinFk'	=> 'attribute_id', 'joinTable' => 'bik_player_values'));		
	}
	
	public function GetRestrictions()
	{
		return array(0 => 'Minimumv&auml;rde', 1 => 'Maximumv&auml;rde', 2 => 'Best&auml;mda v&auml;rde');
	}

}