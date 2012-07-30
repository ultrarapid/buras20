<?php
class PlayerstatsController extends App_Controller
{	

	protected $dbTextFields = array('name');

	public function admin_add()
	{
		$id = 0;
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['playerdata']['name']) ) {
			$_POST['playerdata']['ordernr'] = $this->Playerstat->MaxOrder() + 1;
			$this->Playerstat->returnId = true;
			if ( $returnId = $this->Playerstat->Save($_POST['playerdata']) ) {
				$id = $returnId;
				$this->SetFeedback('text', 'saved', str_replace('%var1%', $_POST['playerdata']['name'], Message::Load('this_saved')));
			}				
			foreach ( $_POST['restrictions'] as $rdata ) {
				if ( !empty($rdata['value']) ) {
					$rdata['playerstat_id'] = $id;
					$this->Playerstat->PlayerstatRestriction->Save($rdata);
				} else {
					$this->Playerstat->PlayerstatRestriction->deleteFields = array('playerstat_id' => $id, 'restriction' => $rdata['restriction']);
					$this->Playerstat->PlayerstatRestriction->Del();
				}
			}
			$this->Redirect(Anchors::Refer('admin_playerstats') . '/edit/' . $id);
		} else {
			$this->SetFeedback('text', 'error', Message::Load('error_saving'));
		}
		$this->Set('visibilities', $this->Playerstat->GetStatus());
		$this->Set('restrictions', $this->Playerstat->PlayerstatRestriction->GetRestrictions());
		$this->Set('id', $id);
		$this->Set('playerstats', $this->Playerstat->GetAll());	
		$this->Set('statussymbols', $this->Playerstat->GetStatusSymbols());
		$this->Set('minOrder', $this->Playerstat->MinOrder());
		$this->Set('maxOrder', $this->Playerstat->MaxOrder());
		$this->Set('sectionHeader', 'L&auml;gg till egenskap');
		$this->Set('adminCustomSubMenu', array(0 => array('class' => 'btn-list', 'href' => '/admin/playerstats/index/', 'text' => 'Lista', 'title' => 'Lista')));			
		$this->SetContext('admin');
	}	
	


	public function admin_delete($id = 0)
	{
		if ( $this->Playerstat->Del($id) ) {
			$this->Playerstat->PlayerstatRestriction->deleteFields = array('playerstat_id' => $id);
			if ( $this->Playerstat->PlayerstatRestriction->Del() ) {
				$this->Playerstat->PlayerStatValue->deleteFields = array('playerstat_id' => $id);
				if ( $this->Playerstat->PlayerStatValue->Del() ) {
					$this->SetFeedback('text', 'deleted', Message::Load('playerstat_deleted'));
				}
			}
		} else {
			$this->SetFeedback('text', 'error', Message::Load('error_deleting'));
		}
		$this->Redirect(Anchors::Refer('admin_playerstats') . '/index/');
	}
	
	public function admin_edit($id = 0)
	{
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['playerdata']['name']) ) {
			if ( $id == 0 ) {
				$_POST['playerdata']['ordernr'] = $this->Playerstat->MaxOrder() + 1;
				$this->Playerstat->returnId = true;
			}
			if ( $returnId = $this->Playerstat->Save($_POST['playerdata']) ) {
				if ( $id > 0 ) {
					$this->SetFeedback('text', 'saved', str_replace('%var1%', $_POST['playerdata']['name'], Message::Load('this_updated')));
				} else {
					$id = $returnId;
					$this->SetFeedback('text', 'saved', str_replace('%var1%', $_POST['playerdata']['name'], Message::Load('this_saved')));
				}
				
				foreach ( $_POST['restrictions'] as $rdata ) {
					if ( !empty($rdata['value']) ) {
						$rdata['playerstat_id'] = $id;
						$this->Playerstat->PlayerstatRestriction->Save($rdata);
					} else {
						$this->Playerstat->PlayerstatRestriction->deleteFields = array('playerstat_id' => $id, 'restriction' => $rdata['restriction']);
						$this->Playerstat->PlayerstatRestriction->Del();
					}
				}
				$this->Redirect(Anchors::Refer('admin_playerstats') . '/edit');
			} else {
				$this->SetFeedback('text', 'error', Message::Load('error_saving'));
			}
		}
		if ( $id > 0 ) {
			$this->Set('psdata', current($this->Playerstat->GetById($id)));
			$this->Set('psrdata', $this->Playerstat->PlayerstatRestriction->GetByPlayerstat_id($id));
		}
		$this->Set('visibilities', $this->Playerstat->GetStatus());
		$this->Set('restrictions', $this->Playerstat->PlayerstatRestriction->GetRestrictions());
		$this->Set('id', $id);
		$this->Set('playerstats', $this->Playerstat->GetAll());	
		$this->Set('statussymbols', $this->Playerstat->GetStatusSymbols());
		$this->Set('minOrder', $this->Playerstat->MinOrder());
		$this->Set('maxOrder', $this->Playerstat->MaxOrder());
		
		$this->Set('sectionHeader', 'Editera egenskap');
		$this->Set('adminCustomSubMenu', array(0 => array('class' => 'btn-add', 'href' => '/admin/playerstats/add/', 'text' => 'L&auml;gg till', 'title' => 'L&auml;gg till'), 1 => array('class' => 'btn-list', 'href' => '/admin/playerstats/index/', 'text' => 'Lista', 'title' => 'Lista')));		
		$this->SetContext('admin');
		
	}	
	
	public function admin_index()
	{
		$this->Set('visibilities', $this->Playerstat->GetStatus());
		$this->Set('restrictions', $this->Playerstat->PlayerstatRestriction->GetRestrictions());
		$this->Set('playerstats', $this->Playerstat->GetAll());	
		$this->Set('statussymbols', $this->Playerstat->GetStatusSymbols());
		$this->Set('minOrder', $this->Playerstat->MinOrder());
		$this->Set('maxOrder', $this->Playerstat->MaxOrder());
		$this->Set('sectionHeader', 'Spelarinfo');
		$this->Set('adminCustomSubMenu', array(0 => array('class' => 'btn-add', 'href' => '/admin/playerstats/add/', 'text' => 'L&auml;gg till', 'title' => 'L&auml;gg till'), 1 => array('class' => 'btn-list', 'href' => '/admin/playerstats/index/', 'text' => 'Lista', 'title' => 'Lista')));		
		$this->SetContext('admin');			
		$this->SetContext('admin');
	}
	
	public function admin_movedown($ordernr)
	{
		if ( $this->Playerstat->MoveDown($ordernr) ) {
			$this->Redirect(Anchors::Refer('admin_playerstats') . '/edit');
		}
	}
	
	public function admin_moveup($ordernr)
	{
		if ( $this->Playerstat->MoveUp($ordernr) ) {
			$this->Redirect(Anchors::Refer('admin_playerstats') . '/edit');
		}
	}
	

}
