<?php
class Role extends App_Model
{
	public function __construct($bound = array())
	{
		parent::__construct($bound);
		$this->usefk 	= true;
		$this->table	= 'bik_roles';
	}
	
	public function GetPublicRoles()
	{
		$conditions = $this->conditions;
		$this->conditions = array(0 => array('field' => 'id', 'separator' => '!=', 'value' => '1'));
		$publicRoles = $this->Get();
		$this->conditions = $conditions;
		return $publicRoles;
	}
	
	protected function SetRelations()
	{
		$this->relations['recursive'] = false;
		$this->relations['oneToMany'] = array('userrole' => array('class' => 'UserRole', 'fk' => 'role_id'), 'sectionrole' => array('class' => 'SectionRole', 'fk' => 'role_id'));
	}
	
}