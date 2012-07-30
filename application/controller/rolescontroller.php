<?php
class RolesController extends App_Controller
{
	protected $adminSection = 11;

	public function admin_addsection($roleID, $sectionID)
	{
		$this->IsAllowed();
		$srObject = array('role_id' => $roleID, 'section_id' => $sectionID);
		if ( $this->Role->SectionRole->Save($srObject) ) {
			$this->Redirect(Anchors::Refer('admin_role_edit') . '/' . $roleID);
		}
	}
	
	public function admin_delsection($sectionRoleID)
	{
		$this->IsAllowed();
		$srObject = current($this->Role->SectionRole->GetById($sectionRoleID));
		if ( $srObject['SectionRole']['role_id'] == 1 ) {
			$this->Redirect(Anchors::Refer('logout'));
		}
		if ( $this->Role->SectionRole->Del($srObject['SectionRole']['id']) ) {
			$this->Redirect(Anchors::Refer('admin_role_edit') . '/' . $srObject['SectionRole']['role_id']);
		}
	}
	
  public function admin_add()
	{
		$this->IsAllowed();
		$this->Set('sectionHeader', 'L&auml;gg till roll');
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			if ( $this->Role->Save($_POST['data']) ) {
				$this->Redirect('admin_roles_index');
			}
		}		
		$this->SetContext('admin');
	}
	
	public function admin_edit($id)
	{
		$this->IsAllowed();
		$role = current($this->Role->GetById($id));
		$this->Set('sectionHeader', $role['Role']['name']);		
		$this->Set('role', $role);
		$sectionRoles = $this->Role->SectionRole->GetByRole_id($id);
		$this->Set('section_roles', $sectionRoles);
		$this->Set('section_size', sizeof($sectionRoles));
		$this->Set('sections', $this->Role->SectionRole->Section->GetAll());
		$this->SetContext('admin');
	}
	
	public function admin_index()
	{
		$this->IsAllowed();
		$this->Set('roles', $this->Role->GetAll());
		$this->SetContext('admin');
	}
	
}