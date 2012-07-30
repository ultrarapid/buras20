<?php
class FormationRestriction extends App_Model
{	
	public function __construct($bound = array()) 
	{
		parent::__construct($bound);
		$this->ordercol = 'format_id position_id';
		$this->usefk 	= false;
		$this->table	= 'bik_formation_restrictions';
	}

	protected function SetRelations()
	{
		$this->relations['manyToOne'] = array('formations' => array('class' => 'Formation', 'fk' => 'format_id'), 'positions' => array('class' => 'Position', 'fk' => 'position_id'));
	}
	
	public function GetWithPositions()
	{
		$this->ClearRelations();
		$this->relations['manyToOne'] = array('positions' => array('class' => 'Position', 'fk' => 'position_id'));
		$usefk = $this->usefk;
		$this->usefk = true;
		$fObj = $this->Get();
		$this->usefk = $usefk;
		$this->SetRelations();
		return $fObj;		
	}

}