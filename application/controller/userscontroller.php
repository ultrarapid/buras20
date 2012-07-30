<?php
class UsersController extends App_Controller
{
	protected $adminSection = 9;
	
	public function admin_add()
	{
		$this->IsAllowed();
		$this->Set('sectionHeader', 'L&auml;gg till anv&auml;ndare');
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			$_POST['data']['password'] = hash('sha256', $_POST['cdata']['pw']);
			if ( $_POST['cdata']['player'] != 0 ) {
				$ctrlUser = $this->User->GetByPlayer_id($_POST['cdata']['player']);
				if ( empty($ctrlUser) ) {
					$_POST['data']['player_id'] = $_POST['cdata']['player'];
				}
			}
			$this->User->Save($_POST['data']);
		}
		$this->Set('players', $this->User->Player->GetAll());
		$this->SetContext('admin');
	}
	
	public function admin_addrole($userID, $roleID)
	{
		$this->IsAllowed();
		if ( $roleID == 1 ) {
			$this->Redirect(Anchors::Refer('logout'));
		}
		$urObject = array('user_id' => $userID, 'role_id' => $roleID);
		if ( $this->User->UserRole->Save($urObject) ) {
			$this->Redirect(Anchors::Refer('admin_user_editrole') . '/' . $userID);
		}
	}

	public function admin_addteam($userID, $teamID)
	{
		$this->IsAllowed();
		$utObject = array('user_id' => $userID, 'team_id' => $teamID);
		if ( $this->User->UserTeam->Save($utObject) ) {
			$this->Redirect(Anchors::Refer('admin_user_editrole') . '/' . $userID);
		}
	}
	
	public function admin_delrole($userRoleID)
	{
		$this->IsAllowed();
		$urObject = current($this->User->UserRole->GetById($userRoleID));
		if ( $urObject['UserRole']['role_id'] == 1 ) {
			$this->Redirect(Anchors::Refer('logout'));
		}
		if ( $this->User->UserRole->Del($urObject['UserRole']['id']) ) {
			$this->Redirect(Anchors::Refer('admin_user_editrole') . '/' . $urObject['UserRole']['user_id']);
		}
	}

	public function admin_delteam($userTeamID)
	{
		$this->IsAllowed();
		$utObject = current($this->User->UserTeam->GetById($userTeamID));
		if ( $this->User->UserTeam->Del($utObject['UserTeam']['id']) ) {
			$this->Redirect(Anchors::Refer('admin_user_editrole') . '/' . $utObject['UserTeam']['user_id']);
		}
	}
	
	public function admin_edit($id = 0)
	{
		$this->IsAdmin();
		// flytta denna till spelar edit
		/*
		$user = current($this->User->GetByPlayer_id($id));
		
		if ( !empty($user) && $user['User']['id'] )
		if ( $id != $_SESSION['User']['id'] ) {
			$season = $this->User->Player->Seasonteamplayer->Season->GetActiveSeason();
			$seasonID = $season['Season']['id'];
			
			$conditions = $this->User->Player->Seasonteamplayer->conditions;
			$this->User->Player->Seasonteamplayer->conditions = array(0 => array('field' => 'season_id', 'value' => '$seasonID'), 1 => array('field' => 'player_id', 'value' => $id));
      $stps = $this->User->Player->Seasonteamplayer->Get();
			$this->User->Player->Seasonteamplayer->conditions = $conditions;
			$deny = true;
			foreach ( $_SESSION['User']['teams'] as $tid ) {
				foreach ( $stps as $stp ) {
					if ( $tid == $stp['SeasonTeamPlayer']['team_id'] ) {
						$deny = false;
						break 2;
					}
				}
			}
			if ( $deny ) {
				$this->Redirect(Anchors::Refer('logout'));
			}				
		}
		$this->SetContext('admin');
		*/
		
	}
	
	public function admin_editrole($userID)
	{
		$this->IsAllowed();
		$this->Set('user', current($this->User->GetById($userID)));
		$this->Set('roles', $this->User->UserRole->Role->GetPublicRoles());
		$this->Set('teams', $this->User->UserTeam->Team->GetAll());
		$userRoles = $this->User->UserRole->GetByUser_id($userID);
		$userTeams = $this->User->UserTeam->GetByUser_id($userID);		
		$this->Set('userRoles', $userRoles);
		$this->Set('userTeams', $userTeams);		
		$this->Set('roleSize', sizeof($userRoles));
		$this->Set('teamSize', sizeof($userTeams));		
		$this->Set('id', $userID);
		$this->SetContext('admin');
	}
	
	public function admin_index()
	{
		$this->IsAllowed();
		$this->Set('users', $this->User->GetAll());
		$this->SetContext('admin');
	}
	
	public function create()
	{
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			$user['username'] = $_POST['data']['user'];
			$user['password'] = hash('sha256', $_POST['data']['pw']);
			$user['allow'] = 1;
			$this->User->Save($user);
		}
	}
	
	public function info()
	{
		//phpinfo();
	}
	
	public function login()
	{
  	$this->SessionStart();
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			$_SESSION['User'] = $this->User->Login($_POST['data']['user'], hash('sha256', $_POST['data']['pw']));
			if ( !empty($_SESSION['User']) ) {
				$_SESSION['User']['allowed'] = $this->User->GetAllowedSections($_SESSION['User']['id']);
				$_SESSION['User']['teams'] = $this->User->GetAllowedTeams($_SESSION['User']['id']);				
				$this->Redirect(Anchors::Refer('admin_sections_index'));
			}
		}
		$this->SetContext('login');
	}
	
	public function logout()
	{
		$this->SessionReset();
		$this->Redirect(Anchors::Refer('login'));
	}
	
}