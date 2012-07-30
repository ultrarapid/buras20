<?php
class SectionsController extends App_Controller
{
	protected $dbTextFields = array('name', 'adminname', 'header', 'body', 'url', 'target', 'adminUrl');
	
	public function admin_edit($id = 0)
	{
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			if ( !array_key_exists('comment_enabled', $_POST['data']) ) {
				$_POST['data']['comment_enabled'] = 0;
			}
			$this->Section->Save($_POST['data']);
		}
		if ( $id != 0 ) {
			$this->Set('set', 1);
			$section = current($this->Section->GetById($id));
			$this->Set('section', $section);
		} else {
			$this->Set('set', 0);
			$this->Set('section', array('Section' => array('visible' => 1, 'postsection' => 0, 'comment_enabled' => 0)));
		}
		$addedScripts = array(0 => 'bbeditor/ed');				
		$this->SetContext('admin', $addedScripts);
	}
	
	public function admin_index($id = 0)
	{
		if ( $id == 0 ) {
			$this->IsAdmin();
			//print_r($_SESSION);
			$this->SetContext('admin');
		} else {
			$this->adminSection = $id;
			$this->IsAllowed();
			if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
				$this->SectionSave();
			}
			//$sett = current($this->Section->GetById($id));
			//$this->Set('section', $sett);
			//print_r($sett);
			//$this->Set('section', current($this->Section->GetById($id)));		
		}
		$this->Set('settings', $this->Section->GetSectionSettings());
		$this->SetContext('admin');	
	}
	
	private function StartpageReset()
	{
		$startpage = $this->Section->GetByStartpage(1);
		foreach ( $startpage as $so ) {
			$arr = array();
			$arr['id'] = $so['Section']['id'];
			$arr['startpage'] = 0;
			$this->Section->Save($arr);
		}
	}
	
	private function SectionSave()
	{
		// if checkboxes is not checked set value to zero
		$this->FormKeyEmpty('visible', 'comment_enabled');
		if ( array_key_exists('startpage', $_POST['data']) ) {
			$this->StartpageReset();
		}
		$this->Section->Save($_POST['data']);		
	}
	
}