<?php
class UserRole extends App_Model
{	
	public function __construct($bound = array()) 
	{
		parent::__construct($bound);
		$this->usefk 	= false;
		$this->table	= 'bik_user_roles';
	}
	
	public function GetUserRoleIds($userID)
	{
		$idArray = array();
		$userRoles = $this->GetByUser_id($userID);
		foreach ( $userRoles as $ur ) {
			$idArray[] = $ur['UserRole']['role_id'];
		}
		return $idArray;
	}

	protected function SetRelations()
	{
		//$this->relations['manyToOne']  = array('player' => array('class' => 'Player', 'fk' => 'player_id'));
		//$this->relations['manyToMany'] = array('playerstats' => array('class' => 'Playerstat', 'fk' => 'player_id', 'joinFk'	=> 'attribute_id', 'joinTable' => 'bik_player_values'));
		$this->relations['recursive'] = false;
		$this->relations['manyToOne'] = array('users' => array('class' => 'User', 'fk' => 'user_id'), 'roles' => array('class' => 'Role', 'fk' => 'role_id'));
	}

}