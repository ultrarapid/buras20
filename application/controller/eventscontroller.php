<?php
class EventsController extends App_Controller
{

	protected $adminSection = 26;

	public function admin_add()
	{
		$this->IsAllowed();

		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			$_POST['data']['eventdate'] = $_POST['raw']['date'] . ' ' . $_POST['raw']['time'];
			$_POST['data']['slug'] = Formatter::CreateSlug($_POST['data']['header']);
			$this->Event->Save($_POST['data']);
		}
	
		$addedScripts = array(0 => 'jquery-ui-1.8.13.custom.min', 1 => 'ui/jquery.ui.datepicker-min', 2 => 'ui/jquery.ui.datepicker-sv', 3 => 'jquery-ui-timepicker-addon', 4 => 'bbeditor/ed', 5 => 'local-datepicker', 6 => 'admin-edit-game');
		$this->Set('layoutStylesheets', array(0 => array('href' => 'smoothness/jquery-ui-1.8.13.custom')));
		
		$this->SetContext('admin', $addedScripts);
	}

	public function admin_delete($eventID)
	{
		$this->IsAllowed();
		$this->Event->Del($eventID);
		$this->Redirect(Anchors::Refer('admin_events') . '/index');
	}

	public function admin_edit($eventID)
	{
		$this->IsAllowed();

		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			$_POST['data']['eventdate'] = $_POST['raw']['date'] . ' ' . $_POST['raw']['time'];
			$this->Event->Save($_POST['data']);
		}

		$this->Set('event', current($this->Event->GetById($eventID)));

		$addedScripts = array(0 => 'jquery-ui-1.8.13.custom.min', 1 => 'ui/jquery.ui.datepicker-min', 2 => 'ui/jquery.ui.datepicker-sv', 3 => 'jquery-ui-timepicker-addon', 4 => 'bbeditor/ed', 5 => 'local-datepicker', 6 => 'admin-edit-game');
		$this->Set('layoutStylesheets', array(0 => array('href' => 'smoothness/jquery-ui-1.8.13.custom')));

		$this->SetContext('admin', $addedScripts);
	}

	public function admin_index()
	{
		$this->IsAllowed();
		$this->Set('events', $this->Event->GetAll());
		
		$this->SetContext('admin');
	}

	public function index($eventSlug = '')
	{
		$singleEvent = 0;
		if ( $eventSlug != '' ) {
			$singleEvent = 1;
			$this->Set('events', current($this->Event->GetBySlug($eventSlug)));
		} else {
			$this->Set('events', $this->Event->Get());
		}
		$this->Set('singleEvent', $singleEvent);		
	}

}