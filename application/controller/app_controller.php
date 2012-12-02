<?php
class App_Controller extends Controller
{
	protected $adminSection = 0;
	protected $teamSection  = 0;
	protected $messageInfo  = '';
	protected $messageText  = '';
	protected $messageType  = 0;
	protected $dbTextFields = array();

	public function admin_converter()
	{
		$model = ucfirst(rtrim($this->_controller, 's'));
		//print_r($model);

		$this->$model->ExecuteConvertionSQL();

		$all = $this->$model->GetAll();
		foreach ( $all as $one ) {
			foreach ( $this->dbTextFields as $field  ) {
				$one[$model][$field] = utf8_encode($one[$model][$field]);	
			}
			$this->$model->Save($one[$model]);
		}
		
	}

	public function PreExecute()
	{
		$this->PrintFeedback();
	}
	
	protected function ExecuteDelete()
	{
		$sessionName  = $this->_controller . '-' . $this->_action . '-del';
		if ( isset($_SESSION[$sessionName]) ) {
			if ( $_SESSION[$sessionName]['confirmed'] == 1 ) {
				$this->DeleteMessage($_SESSION[$sessionName]['id']);
			} else if ( $_SESSION[$sessionName]['confirmed'] == 2 ) {
				$this->DeleteCommands($_SESSION[$sessionName]['id']);
				$_SESSION[$sessionName] = null;
			}
		}		
	}

	protected function FormKeyEmpty()
	{
		$keys = func_get_args();
		foreach ( $keys as $key ) {
			if ( !array_key_exists($key, $_POST['data']) ) {
				$_POST['data'][$key] = 0;
			}
		}
	}
		
	protected function IsAdmin()
	{
		$this->SessionStart();
    //print_r($_SESSION);
		if ( !array_key_exists('User', $_SESSION) ) {
			$this->Redirect(Anchors::Refer('logout') . '?ake');
			//print_r('ake');exit;
		} else {
			if ( empty($_SESSION['User']) ) {
				$this->Redirect(Anchors::Refer('logout') . '?emp');
				//print_r('emp');exit;
			}		
		}
	}
	
	/*
	method: 'OR' user needs to have access to one id in array
	        'AND' user needs to have access to all ids in array
	
	*/
	protected function IsAllowed()
	{
		if ( $this->adminSection == 0 && $this->teamSection == 0 ) {
			$this->Redirect(Anchors::Refer('logout') . '?ad,ts0');
			//print_r('ad,ts0');exit;
		}
		$deny = true;
		$this->SessionStart();

		foreach ( $_SESSION['User']['allowed'] as $aid ) {
			if ( $aid == $this->adminSection ) {
				$deny = false;
				break;
			}
		}
		foreach ( $_SESSION['User']['teams'] as $tid ) {
			if ( $tid == $this->teamSection ) {
				$deny = false;
				break;
			}
		}		
		if ( $deny ) {
			$this->Redirect(Anchors::Refer('logout') . '?all');
			//print_r('all');exit;
		}
	}
	
	protected function PrintFeedback()
	{
		$this->SessionStart();
		if ( isset($_SESSION['feedback']) ) {
			$this->messageType = $_SESSION['feedback']['type'];
			$this->messageInfo = $_SESSION['feedback']['info'];
			$this->messageText = $_SESSION['feedback']['text'];
			$_SESSION['feedback'] = array();
			unset($_SESSION['feedback']);
		}		
	}
	
	protected function Redirect($url = NULL)
	{
		if ( !empty($url) ) {
			header('location: ' . $url);
			exit;
		} else {
			header('location: ' . Anchors::Refer('force'));
			exit;			
		}
	}
	
	protected function RedirectDelete()
	{
		$deleteActive = false;
		$sessionName  = $this->_controller . '-' . $this->_action . '-del';
		$path = $this->args;
		foreach ( $path as $k => $p ) {
			if ( $deleteActive ) {
				if ( is_numeric($p) ) {
					$_SESSION[$sessionName]['id'] = $p;
					$_SESSION[$sessionName]['confirmed'] = 1;
					$deleteActive = false;
					header('location: ' . substr($_SERVER['REQUEST_URI'], 0, (strpos($_SERVER['REQUEST_URI'], '/delete'))));
					exit;			
				}
			}			
			if ( substr($p, 0, 6) == 'delete' ) {
				$deleteActive = true;
			}
			if ( substr($p, 0, 7) == 'confirm' ) {
				$_SESSION[$sessionName]['confirmed'] = 2;
				header('location: ' . substr($_SERVER['REQUEST_URI'], 0, (strpos($_SERVER['REQUEST_URI'], '/confirm'))));
				exit;				
			}
			if ( substr($p, 0, 4) == 'undo' ) {
				$_SESSION[$sessionName] = null;
				header('location: ' . substr($_SERVER['REQUEST_URI'], 0, (strpos($_SERVER['REQUEST_URI'], '/undo'))));
				exit;					
			}
		}
	}
	
	protected function SessionReset()
	{
		$this->SessionStart();
		$_SESSION = array();
		session_destroy();
	}
	
	protected function SessionStart()
	{
		if ( !isset($_SESSION) ) {
			session_start();
		}
	}
	
	protected function SetContext($context = 'public', $addedJavascripts = array(), $addedHeaderElements = array(), $addedBodyElements = array())
	{
		if ( $context == 'admin' ) {
			$this->SettingsAdmin();							
		} else if ( $context == 'public' ) {
			$this->SettingsPublic();
		} else if ( $context == 'login' ) {
			$this->SettingsLogin();	
		}

		$this->Set('messageInfo', $this->messageInfo);
		$this->Set('messageText', $this->messageText);			
		$this->Set('messageType', $this->messageType);			
		$this->Set('layout', $context);
		$javascripts = array();
		if ( !empty($addedJavascripts) ) {			
			foreach ( $addedJavascripts as $js ) {
				$javascripts[] = $js;
			}
		}
		if ( !empty($addedHeaderElements) ) {			
			foreach ( $addedHeaderElements as $ah ) {
				$headerelements[] = $ah;
			}
		}
		if ( !empty($addedBodyElements) ) {			
			foreach ( $addedBodyElements as $ab ) {
				$bodyelements[] = $ab;
			}
		}
		$this->Set('layoutJavascripts', $javascripts);
		$this->Set('layoutHeaderElements', $addedHeaderElements);
		$this->Set('layoutBodyElements', $addedBodyElements);		
	}
	
	protected function SetFeedback($type = 'text', $info = 'common', $text = '')
	{
		/* 
		 *  text = 1, popup = 2, text + mail = 3, popup + mail = 4
		 *  common  = 10, saved = 20, error = 30
		 */
		$this->SessionStart();
		if ( $type == 'textmail' || $type == 'popupmail' ) {
			// mailsender code here
			$type = rtrim($type, 'mail');
		}
		$_SESSION['feedback']['type'] = $type;
		$_SESSION['feedback']['info'] = $info;
		$_SESSION['feedback']['text'] = $text;
	}

	private function SettingsAdmin()
	{
		$this->IsAdmin();	
		$this->Set('layoutHeader', 'Burås Administratör');		
		$section = new Section();
		$this->SessionStart();
		$this->Set('adminMenu', $section->GetAdminMenu($_SESSION['User']['allowed'], -2));
		//print_r($_SERVER['REQUEST_URI']);
		
		$activeUrl = str_replace(Anchors::Refer('admin') . '/', '', $_SERVER['REQUEST_URI']);
		//print_r($activeUrl);
		//$section->displayQuery = true;
		$resetConditions = $section->conditions;
		$conditions = $resetConditions;
		$section->conditions = array(0 => array('field' => 'target', 'separator' => 'LIKE', 'value' => '"' . $activeUrl . '"'));
		$sect = current($section->Get());
		$menuParent = ( ( $sect['Section']['parent_id'] == 0 ) ? $sect['Section']['id'] : $sect['Section']['parent_id'] );
		$section->conditions = $resetConditions;
		//print_r($sect);
		$this->Set('section', $sect);
		$subMenu = $section->GetAdminMenu($_SESSION['User']['allowed'], $menuParent);
		if ( empty($subMenu) ) {
			if ( $sect['Section']['id'] == 18 ) {
				//print_r('print_18 section');
				$conditions[] = array('field' => 'postsection', 'value' => 1);
				$section->conditions = $conditions;
				//$section->displayQuery = true;
				$subMenu = $section->GetAdminMenu($_SESSION['User']['allowed'], -1);
				$section->conditions = $resetConditions;				
			} else {
				$urlArr = explode('/', $activeUrl);
				//print_r($urlArr);
				
				if ( isset($urlArr[2]) ) {
					if ( $urlArr[1] == 'edit' ) {
						$conditions[] = array('field' => 'controller', 'value' => '"' . $urlArr[0] . '"');
						$conditions[] = array('field' => 'params', 'value' => '"' . $urlArr[2] . '"');
						$section->conditions = $conditions;
						//$section->displayQuery = true;
						$parentSection = current($section->GetAdminMenu($_SESSION['User']['allowed'], -1));
						$thisMainID = $parentSection['Section']['id'];
						if ( $parentSection['Section']['parent_id'] != 0 ) {
							$thisMainID = $parentSection['Section']['parent_id'];
						}
						$section->conditions = $resetConditions;
						$subMenu = $section->GetAdminMenu($_SESSION['User']['allowed'], $thisMainID);	
					}
				}
			}
		}		
		$this->Set('adminSubMenu', $subMenu);		
	}

	private function SettingsLogin()
	{
		$this->Set('layoutHeader', 'Logga in');
	}

	private function SettingsPublic()
	{
		$section = PublicWrapper::GetSection();
		$startpage = $section->GetByStartpage(1);
		$active_id = $startpage[0]['Section']['id'];

		$parent_id = DEFAULT_PARENT;
					
		if ( defined('SECTION_PARENT') && defined('SECTION_ACTIVE') ) {
			$parent_id = SECTION_PARENT;
			$active_id = SECTION_ACTIVE;
		}
		$active_main = $active_id;
		$active_sub  = $active_id;

		if ( $parent_id > 0 ) {
			$active_main = $parent_id;
		}
		if ( $parent_id > 0 || $section->HasSubMenu($active_id) ) {
			$this->Set('layoutSubMenu', $section->GetMenu($active_main));
		}
		$game = new Game();
		$event = new Event();
		$this->Set('nextMensGame', $game->GetNextGame(1));
		$this->Set('nextWomensGame', $game->GetNextGame(2));
		$this->Set('nextEvent', $event->GetNextPublicEvent());
		$this->Set('active_main', $active_main);
		$this->Set('active_sub', $active_sub);
		$sect = current($section->GetById($active_id));
		$this->Set('section', $sect);
		$this->Set('layoutMenu', $section->GetMenu());		
	}

}