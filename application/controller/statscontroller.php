<?php
class StatsController extends App_Controller
{	
	public function admin_delete($id)
	{
		$deleteObject = current($this->Stat->GetById($id));
		$removingOrder = $deleteObject['Stat']['ordernumber'];
		if ( $this->Stat->Del($id) ) {
			if ( $this->Decreaser($removingOrder) ) {
				header('location: /bik/admin/stats/index');
				exit;
			}
		}
	}
	
	public function admin_index($id = 0)
	{
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			if ( isset($_POST['firstsave']['id']) ) {
				$_POST['data']['ordernumber'] = count($this->Stat->GetAll());
			}
			if ( $this->Stat->Save($_POST['data']) ) {
				header('location: /bik/admin/stats/index');
				exit;
			}
		}
		if ( $id > 0 ) {
			$this->Set('stat', current($this->Stat->GetById($id)));
		}
		$this->Set('id', $id);
		$this->Set('allStats', $this->Stat->GetAll());
		$this->SetContext('admin');
	}
	
	private function Decreaser($orderNumber)
	{
		$allStats = $this->Stat->GetAll();
		foreach ( $allStats as $stat ) {
			if ( $stat['Stat']['ordernumber'] > $orderNumber ) {
				$stat['Stat']['ordernumber']--;
				$this->Stat->Save($stat['Stat']);
			}
		}
		return true;
	}
	
}