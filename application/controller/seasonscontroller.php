<?php
class SeasonsController extends App_Controller
{
	protected $args = array();
	protected $messageID = 0;
	protected $messageText = '';
	
	private $firstSeason = 1989;
	private $mainPage = 'live';
	
	public function admin_edit($id = null)
	{ /*
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			$_POST['data']['modified'] = date('Y-m-d H:i:s');
			$this->Post->Save($_POST['data']);
		}
		
		if ( !empty($id) ) {
			$this->Set('post', current($this->Post->GetById($id)));
			$addedScripts = array(0 => 'bbeditor/ed');				
			$this->SetAdminSettings($addedScripts);
		}*/
	}
	
	public function admin_index()
	{		
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			$this->Season->Save($_POST['data']);
		}
		$this->Set('firstSeason', $this->firstSeason);
		//$this->Set('dates', $dates);
		$this->Set('seasons', $this->Season->GetAll());
		//$this->Set('sections', $this->Post->Section->GetAll());
		$addedScripts = array(0 => 'jquery-ui-1.8.13.custom.min', 1 => 'ui/jquery.ui.datepicker-min', 2 => 'ui/jquery.ui.datepicker-sv', 3 => 'local-datepicker');
		$this->Set('layoutStylesheets', array(0 => array('href' => 'smoothness/jquery-ui-1.8.13.custom')));
		$this->SetContext('admin', $addedScripts);		
	}

}