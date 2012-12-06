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
	
	public function manualadd()
	{
		$arr = array();
		$arr[0]['id'] = 27;
		$arr[0]['title'] = 'Kom och träna med Burås herrlag';
		$arr[1]['id'] = 28;
		$arr[1]['title'] = 'Spela innebandy i Göteborg med Burås damlag';
		$arr[2]['id'] = 1;
		$arr[2]['title'] = 'Innebandynyheter - Burås Göteborg';
		$arr[3]['id'] = 15;
		$arr[3]['title'] = 'Burås herrtrupp';
		$arr[4]['id'] = 14;
		$arr[4]['title'] = 'Damspelare - Burås Innebandy';
		$arr[5]['id'] = 22;
		$arr[5]['title'] = 'Burås Önskelista';
		$arr[6]['id'] = 6;
		$arr[6]['title'] = 'Matcher - Herr - Göteborg div3';
		$arr[7]['id'] = 12;
		$arr[7]['title'] = 'Göteborg div2 - Damernas matcher';
		$arr[8]['id'] = 19;
		$arr[8]['title'] = 'Göteborg div3 - Tabell - Burås innebandy';
		$arr[9]['id'] = 20;
		$arr[9]['title'] = 'Tabell - Göteborg div2 - Burås innebandy';		
		foreach ( $arr as $a ) {
			$this->Section->Save($a);
		}
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