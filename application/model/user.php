<?php
class User extends App_Model
{
	public function __construct($bound = array())
	{
		parent::__construct($bound);
		$this->table = 'bik_users';
	}

	public function GetAllowedSections($userID)
	{
		$this->usefk = true;
		
		$conditions = array(0 => array('conditionStatement' => 'bik_sections.admin = 1'));		
		
		$fieldlist = $this->fieldList;
		$this->fieldList = 'DISTINCT(bik_sections.id) AS Section_id';
		$this->relations['manyToManyCondition'] = array('roles' => array('class' => 'UserRole', 'fk' => 'user_id', 'joinTable' => 'bik_user_roles'), 'sectrole' => array('class' => 'SectionRole', 'fk' => 'role_id', 'joinTable' => 'bik_section_roles', 'altTable' => 'bik_user_roles', 'altKey' => 'role_id'), 'section' => array('class' => 'Section', 'fk' => 'id', 'joinTable' => 'bik_sections', 'altTable' => 'bik_section_roles', 'altKey' => 'section_id', 'condition' => $conditions));
		$this->relations['hasOne'] = array();
		//$this->displayQuery = true;
		$sections = $this->GetById($userID);
		//print_r($sections);
		$idArray = array();
		foreach ( $sections as $so ) {
			$idArray[] = $so['Section']['id'];
		}
		$this->fieldList = $fieldlist;
		return $idArray;
		//return implode(', ', $idArray);
	}

	public function GetAllowedTeams($userID)
	{
		$teams = $this->UserTeam->GetByUser_id($userID);
		$idArray = array();
		foreach ( $teams as $to ) {
			$idArray[] = $to['UserTeam']['team_id'];
		}		
		return $idArray;
		//return implode(', ', $idArray);
	}
	
	public function Login($username, $password) {
		$this->conditions = array(0 => array('field' => 'username', 'value' => "'" . $username . "'"), 1 => array('field' => 'password', 'value' => "'" . $password . "'"));
		$user = $this->Get();
		if ( !empty($user) ) {
			$user[0]['User']['password'] = null;
			unset($user[0]['User']['password']);
			return $user[0]['User'];
		} else {
			return array();
		}
	}
	
	protected function SetRelations()
	{
		$this->relations['recursive'] = false;
		$this->relations['oneToMany'] = array('userrole' => array('class' => 'UserRole', 'fk' => 'user_id'), 'userteam' => array('class' => 'UserTeam', 'fk' => 'user_id'));
		$this->relations['hasOne'] = array('player' => array('class' => 'Player', 'fk' => 'player_id'));
	}	
	
}