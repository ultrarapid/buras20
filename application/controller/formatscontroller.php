<?php
class FormationsController extends App_Controller
{
	public function admin_delete($id)
	{
		$object = current($this->Formation->GetById($id));
		if ( $this->Formation->Del($id) ) {
			$this->SetFeedback('text', 'deleted', str_replace('%var1%', $object['Formation']['name'], Message::Load('this_deleted')));
		} else {
			$this->SetFeedback('text', 'error', Message::Load('error_deleting'));
		}
		$this->Redirect(Anchors::Refer('admin_formations') . '/edit');
	}
	
	public function admin_edit($id = 0)
	{
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			if ( $this->Formation->Save($_POST['data']) ) {
				if ( $id > 0 ) {
					$this->SetFeedback('text', 'saved', Message::Load('formation_changed'));
				} else {
					$this->SetFeedback('text', 'saved', Message::Load('formation_created'));
				}				
				$this->Redirect(Anchors::Refer('admin_formations') . '/edit');
			} else {
				$this->SetFeedback('text', 'error', Message::Load('error_saving'));
			}
		}	
		if ( $id > 0 ) {
			$this->Set('formatdata', current($this->Formation->GetById($id)));
		}
		$this->Set('id', $id);
		$this->Set('formations', $this->Formation->GetAll());		
		$this->SetContext('admin');
	}

}
