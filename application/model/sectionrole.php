<?php
class SectionRole extends App_Model
{	
	public function __construct($bound = array()) 
	{
		parent::__construct($bound);
		$this->usefk 	= false;
		$this->table	= 'bik_section_roles';
	}

	protected function SetRelations()
	{
		$this->relations['manyToOne']  = array('section' => array('class' => 'Section', 'fk' => 'section_id'), 'roles' => array('class' => 'Role', 'fk' => 'role_id'));
		//$this->relations['manyToMany'] = array('playerstats' => array('class' => 'Playerstat', 'fk' => 'player_id', 'joinFk'	=> 'attribute_id', 'joinTable' => 'bik_player_values'));
	}

}