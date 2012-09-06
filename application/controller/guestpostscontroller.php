<?php
class GuestpostsController extends App_Controller 
{
	private $paging = 20;
	protected $adminSection = 21;
	protected $dbTextFields = array('name', 'email', 'url', 'team', 'body');
		
	public function admin_delete($id)
	{
		$this->IsAllowed();
		$guestPost['id'] = $id;
		$guestPost['visible'] = 0;
  	if ( $this->Guestpost->Save($guestPost) ) {
			$this->SetFeedback('text', 'deleted', Message::Load('guestpost_deleted'));
		} else {
			$this->SetFeedback('text', 'error', Message::Load('error_deleting'));
		}
		$this->Redirect(Anchors::Refer('admin_guestposts_index'));
	}
	
	public function admin_index()
	{
		$this->IsAllowed();
		$this->Set('gposts', $this->Guestpost->GetByVisible(1));
		$this->SetContext('admin');
	}
	
	public function getposts($lastID)
	{
		$this->Guestpost->conditions = array(0 => array('field' => 'id', 'separator' => '<', 'value' => $lastID), 1 => array('field' => 'visible', 'value' => 1)); 
		$this->Guestpost->limit = array('start' => 0, 'end' => $this->paging);
		//$this->Guestpost->displayQuery = true;
		//$gbposts = $this->Guestpost->GetAll();
		$this->Set('gbposts', $this->Guestpost->GetAll());
		$this->Set('paging', $this->paging);
	}
	
	public function index()
	{
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			if ( $this->IsNotSpam() ) {
				if ( $this->IsFilled() )	{
  				$gbObject = $this->FillObject();
					if ( $this->Guestpost->Save($gbObject) ) {
						$this->Redirect($_SERVER['REQUEST_URI']);
					}
				}
				else {
				}
			}
		}
		$this->Guestpost->conditions = array(0 => array('field' => 'visible', 'value' => 1)); 		
		$this->Guestpost->limit = array('start' => 0, 'end' => $this->paging);
		$this->Set('amount', $this->Guestpost->CountConditional());
		$this->Set('gbposts', $this->Guestpost->GetByVisible(1));
		$this->SetContext('public', array(0 => 'more-posts'));
	}
	
	public function insert()
	{
		$gbObject = array();
		$gbObject['created'] = date('Y-m-d H:i:s');
		$gbObject['name']    = 'Sten Sture Den Yngre';
		$gbObject['email']   = 'dannehojj@hotmail.com';
		$gbObject['url']     = 'www.burasik.com';
		$gbObject['team']    = 'Bur&aring;s IK';
		$gbObject['body']    = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean sapien felis, consectetur nec sagittis eget, interdum non eros. Pellentesque placerat, nulla in viverra condimentum, lorem lacus dapibus risus, id tempus lacus velit egestas dolor. In sed ligula ut dolor pretium aliquam. Morbi tellus eros, viverra non pretium eget, aliquam ut arcu. Ut ultrices tincidunt dolor, sit amet suscipit mauris aliquam ac. Nullam accumsan blandit nibh id sollicitudin. Nulla facilisi. Aliquam faucibus, eros sed faucibus pretium, risus urna sagittis mauris, eu consectetur turpis lectus at lectus. Fusce nulla augue, egestas in volutpat sit amet, porttitor pretium sem. Nam ac augue lorem. In a leo id massa scelerisque congue a ac magna. Integer lobortis dapibus malesuada.

Etiam tempor feugiat erat vel cursus. Nunc ut tristique turpis. Ut non dolor arcu. Phasellus at pharetra enim. Nullam ultrices quam id massa pellentesque lobortis. Donec id nibh risus, eget dapibus ipsum. Nam vel metus posuere justo sollicitudin commodo.

Curabitur at odio vel turpis congue porta. Sed facilisis velit in neque hendrerit a vehicula justo rutrum. Sed porta justo fringilla turpis tempus mollis. Phasellus at pharetra lacus. Mauris elit mi, feugiat ac ornare sit amet, auctor ut libero. In interdum ullamcorper lorem, et sagittis odio ultrices quis. Praesent sed vulputate nulla. Donec non dui diam. Nunc vitae leo vitae erat commodo blandit ut in urna. Nam eget dolor elementum nulla gravida viverra. Nunc euismod justo vel erat dapibus egestas. Nulla faucibus mattis justo, ut vehicula orci ullamcorper eget. Suspendisse eu augue non ligula lobortis commodo. Duis arcu odio, mollis congue viverra vitae, rhoncus a velit. Aliquam erat volutpat. In lorem dolor, porttitor non tincidunt eget, tristique vitae mauris.

Maecenas eleifend ligula at mi iaculis porta. Morbi dignissim pretium varius. Integer mattis sapien a metus egestas gravida. Aliquam ac mauris turpis, quis dictum diam. Nulla gravida, sapien non molestie pretium, magna purus tempor lacus, a tempus arcu purus quis nisi. Suspendisse molestie pretium tellus, ut sagittis risus dapibus at. Maecenas ut sapien vitae nisl ullamcorper ultrices. Aenean orci nibh, sagittis non lacinia vitae, feugiat id nibh. Nunc vehicula magna nec ligula posuere eleifend. Nunc a tellus sapien, at dictum augue. Nullam ut erat risus. In ac venenatis massa. Integer arcu lacus, pretium id commodo a, laoreet id purus. Suspendisse eu libero quis erat rhoncus varius.

Morbi pharetra vulputate eros, in sagittis tellus commodo quis. Phasellus sit amet condimentum nibh. Donec non libero urna, id laoreet elit. Nullam tristique tincidunt orci imperdiet congue. Nullam id elementum nulla. In hac habitasse platea dictumst. Quisque blandit magna vitae quam rutrum dapibus.';
		$gbObject['user_id'] = 0;
		for ( $i = 0; $i < 130; $i++ ) {
			$gbObject['created'] = date('Y-m-d H:i:s');
			//$this->Guestpost->Save($gbObject);
		}
	}
	
	private function FillObject()
	{
		$gbObject = array();
		$gbObject['created'] = date('Y-m-d H:i:s');
		$gbObject['name']    = $_POST['data']['name'];
		$gbObject['email']   = $_POST['data']['email'];
		$gbObject['url']     = $_POST['data']['url'];
		$gbObject['team']    = $_POST['data']['team'];
		$gbObject['body']    = $_POST['data']['text2'];
		$gbObject['user_id'] = 0;
		return $gbObject;
	}
	
	private function IsFilled()
	{
		$unFilled = 0;
		$isFilled = false;
		$inputs = $this->ValidateInputs();
		foreach ( $inputs as $input ) {
			//print_r($_POST['data'][$input]);
			if ( strlen($_POST['data'][$input]) == 0 ) {
				$unFilled++;
				break;
			}
		}
		if ( $unFilled == 0 ) {
			$isFilled = true;
		}
		return $isFilled;		
		
	}
	
	private function IsNotSpam() 
	{
		$spamDetections = 0;
		$isClean = false;
		$spanInputs = $this->ForbiddenInputs();
		foreach ( $spanInputs as $input ) {
			//print_r($_POST['data'][$input]);
			if ( strlen($_POST['data'][$input]) > 0 ) {
				$spamDetections++;
				break;
			}
		}
		if ( $spamDetections == 0 ) {
			$isClean = true;
		}
		return $isClean;
	}
	
	private function ForbiddenInputs()
	{
		return array('game', 'text1', 'text3');
	}
	
	private function ValidateInputs()
	{
		return array('name', 'text2');
	}
	
	
}