<?php
class PositionsController extends App_Controller
{

	protected $dbTextFields = array('name');
	
	public function admin_delete($id)
	{
		$object = current($this->Position->GetById($id));
		if ( $this->Position->Del($id) ) {
			$this->SetFeedback('text', 'deleted', str_replace('%var1%', $object['Position']['name'], Message::Load('this_deleted')));
		} else {
			$this->SetFeedback('text', 'error', Message::Load('error_deleting'));
		}
		$this->Redirect(Anchors::Refer('admin_positions') . '/edit');
	}
	
	public function admin_edit($id = 0)
	{
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			if ( $this->Position->Save($_POST['data']) ) {
				if ( $id > 0 ) {
					$this->SetFeedback('text', 'saved', Message::Load('position_changed'));
				} else {
					$this->SetFeedback('text', 'saved', Message::Load('position_created'));
				}				
				$this->Redirect(Anchors::Refer('admin_positions') . '/edit');
			} else {
				$this->SetFeedback('text', 'error', Message::Load('error_saving'));
			}
		}	
		if ( $id > 0 ) {
			$this->Set('positiondata', current($this->Position->GetById($id)));
		}
		$this->Set('id', $id);
		$this->Set('positions', $this->Position->GetAll());
		$this->SetContext('admin');
	}
}