<?php
class PostsController extends App_Controller
{
	private $_paging = 10;
	protected $args = array();
	protected $messageID = 0;
	protected $messageText = '';
	
	private $mainPage = 'live';
	
	public function admin_add($id)
	{
		$this->adminSection = $id;
		$this->IsAllowed();
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			$_POST['data']['created'] = date('Y-m-d H:i:s');
			$_POST['data']['modified'] = NULL;
			$_POST['data']['url'] = $this->Post->CreateUniqueUrl($_POST['data']['header']);
			$_POST['data']['section_id'] = $id;
			$_POST['data']['user_id'] = $_SESSION['User']['id'];
			$this->Post->Save($_POST['data']);
		}
		$section = current($this->Post->Section->GetById($id));
		$this->Set('sectionHeader', 'L&auml;gg till (' . $section['Section']['name'] .')');
		$this->Set('adminCustomSubMenu', array(0 => array('class' => 'btn-list', 'href' => '/admin/posts/index/' . $id, 'text' => 'Lista', 'title' => 'Lista')));
		$this->SetContext('admin');
	}
	
	public function admin_delete($id)
	{
     $post = current($this->Post->GetById($id));
		 if ( !empty($post) ) {
			 $this->adminSection = $post['Post']['section_id'];
			 $this->IsAllowed();
			 $this->Post->Del($id);
			 $this->Redirect(Anchors::Refer('admin_posts_index') . '/' . $this->adminSection);
		 }
	}
	
	public function admin_edit($id = 0)
	{
		if ( $id > 0 ) {
			$post = current($this->Post->GetById($id));
			$this->adminSection = $post['Post']['section_id'];
			$this->IsAllowed();
			if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
				$_POST['data']['modified'] = date('Y-m-d H:i:s');
  			if ( $this->Post->Save($_POST['data']) ) {
					$this->Redirect(Anchors::Refer('admin_posts_edit') . '/' . $id);
				}
			} else {
				$this->Set('post', $post);
			}

			$section = current($this->Post->Section->GetById($post['Post']['section_id']));
			$this->Set('sectionHeader', 'Editera (' . $section['Section']['name'] .')');
			$this->SetContext('admin');
			
			$this->Set('adminCustomSubMenu', array(0 => array('class' => 'btn-add', 'href' => '/admin/posts/add/' . $post['Post']['section_id'], 'text' => 'L&auml;gg till', 'title' => 'L&auml;gg till'), 1 => array('class' => 'btn-list', 'href' => '/admin/posts/index/' . $post['Post']['section_id'], 'text' => 'Lista', 'title' => 'Lista')));

		}
	}

	public function admin_converter()
	{
		//ALTER TABLE bik_posts CONVERT TO CHARACTER SET utf8 COLLATE utf8_swedish_ci;
		$allposts = $this->Post->GetAll();
		foreach ( $allposts as $post ) {
			$post['Post']['body'] = utf8_encode($post['Post']['body']);
			$post['Post']['header'] = utf8_encode($post['Post']['header']);
			$this->Post->Save($post['Post']);
		}

	}
	
	public function admin_index($id = 0)
	{
		$this->adminSection = $id;
		$this->IsAllowed();

		$this->Set('posts', $this->Post->GetBySection_id($id));
		//$this->Set('sections', $this->Post->Section->GetAll());
  	$this->Set('adminCustomSubMenu', array(0 => array('class' => 'btn-list', 'href' => '/admin/posts/index/' . $id, 'text' => 'Lista')));
		$this->SetContext('admin');
	}

	public function getposts($postid, $lastid)
	{
		$section = PublicWrapper::GetSection();
		$sect = current($section->GetById($postid));
		$this->Set('section', $sect);
		$this->Post->conditions = array(0 => array('field' => 'section_id', 'value' => '?'), 1 => array('field' => 'id', 'separator' => '<', 'value' => '?'), 2 => array('field' => 'published', 'value' => 1)); 
		$this->Post->limit = array('start' => 0, 'end' => $this->_paging);
		$this->Set('posts', $this->Post->GetWithValues(array($postid, $lastid)));
		$this->Set('paging', $this->_paging);
	}

	public function home()
	{
		$this->Set('removeHeader', 1);
		$attributes = array();
		$attributes[] = array('name' => 'type', 'value' => 'text/css');		
		$attributes[] = array('name' => 'href', 'value' => Anchors::Refer('javascript_folder') . '/nivo/nivo-slider/nsm.css');
		$attributes[] = array('name' => 'rel', 'value' => 'stylesheet');
		$attributes[] = array('name' => 'media', 'value' => 'screen');

		$game = new Game();

		$this->Set('previousMensGame', $game->GetPreviousGame(1));
		$this->Set('previousWomensGame', $game->GetPreviousGame(2));
		$this->Set('latestPosts', $this->Post->GetLatestPosts(1, 3));

		$this->Set('layoutMetaDescription', 'Burås innebandyklubb är en anrik förening i hjärtat av Göteborg. Vi har ett damlag i division 2 och ett herrlag i division 3. Spela innebandy med oss.');

		$this->Set('layoutMetaKeywords', 'Burås, Innebandyklubb, Innebandy, Göteborg');

		$this->SetContext('public', array(0 => 'nivo/nivo-slider/jquery.nivo.slider.pack', 1 => 'loader'), array(), array(0 => array('ElementName' => 'link', 'Attributes' => $attributes)));
	}

	public function index($sectionID = 1, $year = '', $month = '', $post = '')
	{
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
		  if ( $_POST['fix'] == 'hej' || $_POST['fix'] == '"hej"') {
				$comment = array();
				$comment = $_POST['data'];
				$comment['created'] = date('Y-m-d H:i:s');
				if ( $this->Post->Comment->Save($comment) ) {
					$this->Redirect($_SERVER['REQUEST_URI']);
				}
			} else {
				$this->Redirect($_SERVER['REQUEST_URI']);
			}
		}
		$section = PublicWrapper::GetSection();
		$activeSection = current($section->GetById($sectionID));		
		if ( !empty($post) ) {
			$posts = $this->Post->GetByUrl($post);
			$this->Set('posts', $posts);
			$this->Set('singlepost', true);
			$this->Set('layoutTitle', $posts[0]['Post']['header'] . ' - Innebandy' . strtolower($activeSection['Section']['name']));
		} else {
			$this->Post->limit = array('start' => 0, 'end' => $this->_paging);
			$this->Post->conditions = array(0 => array('field' => 'section_id', 'value' => $sectionID), 1 => array('field' => 'published', 'value' => 1)); 
			$this->Set('posts', $this->Post->Get());
			$this->Set('singlepost', false);
			$this->Set('amount', $this->Post->CountConditional());			
			//$this->SetContext('public', array(0 => 'more-news'));	
		}
		if ( $sectionID == 3 ) {
			$game = new Game();
			$this->Set('upcomingGames', $game->GetUpcomingGames());
			$this->Set('latestNews', $this->Post->GetLatestPosts(1));
			/*
			$this->Set('gameMale', $game->GetNextGame(1));
			$this->Set('gameFemale', $game->GetNextGame(2));
			7*/
		}
		
		
		$this->args = func_get_args();		
		//$this->GetPage();
		if ( empty($post) ) {
			$this->SetContext('public', array(0 => 'more-news'));
		} else {
			$this->SetContext('public');	
		}
		
	}
	
	public function news()
	{
		$this->Set('posts', $this->Posts->GetBySection(1));
		$this->SetPublicSettings();
	}

}
