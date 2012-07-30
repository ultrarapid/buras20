<?php
class FormationsController extends App_Controller
{	
	public function admin_delete($id)
	{
		$object = current($this->Formation->GetById($id));
		if ( $this->Formation->Del($id) ) {
			$this->Formation->FormationRestriction->deleteFields = array('format_id' => $id);
			if ( $this->Formation->FormationRestriction->Del() ) {
				$this->SetFeedback('text', 'deleted', str_replace('%var1%', $object['Formation']['name'], Message::Load('this_deleted')));
			}
		} else {
			$this->SetFeedback('text', 'error', Message::Load('error_deleting'));
		}
		$this->Redirect(Anchors::Refer('admin_formations') . '/edit');
	}
	
	public function admin_edit($id = 0)
	{
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			if ( $id == 0 ) {
				$this->Formation->returnId = true;
			}
			if ( $returnId = $this->Formation->Save($_POST['data']) ) {
				if ( $id > 0 ) {
					$this->SetFeedback('text', 'saved', Message::Load('formation_changed'));
				} else {
					$id = $returnId;
					$this->SetFeedback('text', 'saved', Message::Load('formation_created'));
				}
				foreach ( $_POST['position'] as $pdata ) {
					if ( !empty($pdata['amount']) ) {
						$pdata['format_id'] = $id;
						$this->Formation->FormationRestriction->Save($pdata);
					} else {
						$this->Formation->FormationRestriction->deleteFields = array('format_id' => $id, 'position_id' => $pdata['position_id']);
						$this->Formation->FormationRestriction->Del();
					}
				}				
			} else {
				$this->SetFeedback('text', 'error', Message::Load('error_saving'));
			}
			$this->Redirect(Anchors::Refer('admin_formations') . '/edit');
		}	
		if ( $id > 0 ) {
			$this->Set('formatdata', current($this->Formation->GetById($id)));
			$this->Set('restrData', $this->Formation->FormationRestriction->GetByFormat_id($id));
		}
		$this->Set('positions', $this->Formation->FormationRestriction->Position->GetAll());
		$this->Set('id', $id);
		$this->Set('formations', $this->Formation->GetAll());		
		$this->SetContext('admin');
	}

}
