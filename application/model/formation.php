<?php
class Formation extends App_Model
{
	public function __construct($bound = array())
	{
		parent::__construct($bound);
		$this->usefk 	= false;
		$this->table	= 'bik_formations';
	}
	
	protected function SetRelations()
	{
		$this->relations['oneToMany'] = array('formationrestrictions' => array('class' => 'FormationRestriction', 'fk' => 'format_id'));
		//$this->relations['oneToMany']  = array('playersetting' => array('class' => 'Playersetting', 'fk' => 'game_id'));											
		
	}
	
}