<?php
class GameformatsController extends App_Controller
{

	protected $dbTextFields = array('name');

	public function admin_edit($id = 0)
	{
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			if ( $this->Gameformat->Save($_POST['data']) ) {
				if ( $id > 0 ) {
					$this->SetFeedback('text', 'saved', str_replace('%var1%', $_POST['data']['name'], Message::Load('this_updated')));
				} else {
					$this->SetFeedback('text', 'saved', str_replace('%var1%', $_POST['data']['name'], Message::Load('this_added')));
				}				
				$this->Redirect(Anchors::Refer('admin_gameformats') . '/edit');
			} else {
				$this->SetFeedback('text', 'error', Message::Load('error_saving'));
			}
		}
		if ( $id > 0 ) {
			$this->Set('gfdata', current($this->Gameformat->GetById($id)));
		}		
		$this->Set('id', $id);
		$this->Set('defaultPeriods', $this->Gameformat->defaultPeriods);
		$this->Set('defaultPeriodTime', $this->Gameformat->defaultPeriodTime);
		$this->Set('defaultOvertime', $this->Gameformat->defaultOvertime);				
				
		$this->Set('gameformats', $this->Gameformat->GetAll());		
		$this->SetContext('admin');
	}

}