<?php
class PlayersController extends App_Controller
{	
	protected $adminSection = 16;
	protected $dbTextFields = array('firstname', 'lastname');
	
	public function admin_add()
	{
		$this->IsAllowed();
		$id = 0;
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {			
			$this->Player->returnId = true;
			$slug = Formatter::CreateSlug($_POST['data']['firstname'] . ' ' . $_POST['data']['lastname']);
			$slugTestPlayer = $this->Player->GetBySlug($slug);
			if ( !empty($slugTestPlayer) ) {
				$i = 0;
				while ( !empty($slugTestPlayer) ) {
					$slugTestPlayer = array();
					$i++;
					$loopSlug = $slug . '-' . $i;
					$slugTestPlayer = $this->Player->GetBySlug($loopSlug);
				}
				$slug .= '-' . $i;
			}
			$_POST['data']['slug'] = $slug;
			if ( $returnId = $this->Player->Save($_POST['data']) ) {
				$this->SetFeedback('text', 'saved', str_replace('%var1%', $_POST['data']['firstname'] . ' ' . $_POST['data']['lastname'],Message::Load('this_added')));
				$id = $returnId;
				foreach ( $_POST['psvdata'] as $psvdata ) {
					if ( !empty($psvdata['value']) ) {
						$psvdata['player_id'] = $id;
						//print_r($psvdata);
						$this->Player->PlayerStatValue->Save($psvdata);
					}
				}
				$this->PrepareImage($id);
				$this->Redirect(Anchors::Refer('admin_players') . '/edit/' . $id);
			} else {
				$this->SetFeedback('text', 'error', Message::Load('error_saving'));
			}
		}
		$this->Set('restrictiondata', $this->Player->Playerstat->PlayerstatRestriction->GetAll());
		$this->Set('playerstats', $this->Player->Playerstat->GetAll());
		$this->Set('id', $id);
		$this->Set('players', $this->Player->GetAll());	
		$this->SetContext('admin');
	}
	
	public function admin_contacts($teamID = 0, $seasonID = -1)
	{
		$this->IsAllowed();
		
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			$this->Redirect('/admin/players/contacts/' . $_POST['team_id'] . '/' . $_POST['season_id']);
		}

		if ( $seasonID == -1 ) {
			$activeSeason = $this->Player->Seasonteamplayer->Season->GetActiveSeason();
			$seasonID = $activeSeason['Season']['id'];
		}		

		$this->Set('sectionHeader', 'Kontaktlista');
  	$this->Set('teams', $this->Player->Seasonteamplayer->Team->GetAll());
  	$this->Set('seasons', $this->Player->Seasonteamplayer->Season->GetAll());		
		$this->Set('team_id', $teamID);
		$this->Set('season_id', $seasonID);

		$this->Set('players', $this->Player->GetPlayersWithContacts($teamID, $seasonID));

		$this->SetContext('admin');
	}
	
	public function admin_delete($id = 0)
	{
		/*
		$this->IsAllowed();
		
		$pObject = current($this->Player->GetById($id));
		if ( !empty($pObject) ) {
			$this->adminSection = $gObject['Game']['team_id'];

			$this->Game->FullDelete($id);
		}	*/
	}
	
	public function admin_deleteimage($id, $imgID = 0) {
		$this->IsAllowed();
		$player = current($this->Player->GetById($id));
  	$path = ROOT . DS . 'htdocs' . DS . 'bilder' . DS . 'spelarbilder' . DS;
		$error = 1;
		if ( unlink($path . $player['Player']['imgsrc'] . '_tn.jpg') ) {
			if ( unlink($path . $player['Player']['imgsrc'] . '.jpg') ) {
				$updatedPlayer = array();
				$updatedPlayer['id'] = $id;
				$updatedPlayer['imgsrc'] = '';
				if ( $this->Player->Save($updatedPlayer) ) {
					$error = 0;
					$this->SetFeedback('text', 'saved', Message::Load('image_deleted'));
				}
			}
		}
		if ( $error == 1 ) {
			$this->SetFeedback('text', 'error', Message::Load('image_delete_error'));
		}
		$this->Redirect($_SERVER['HTTP_REFERER']);
//					$_SESSION['img']['newname'] = ROOT . DS . 'htdocs' . DS . 'bilder' . DS . 'spelarbilder' . DS . $imgName . '_tn.'		
		
	}
	
	public function admin_edit($id = 0, $teamID = 0)
	{
		$this->SaveFromSession($id);
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {			
			if ( $id == 0 ) {
				$this->Player->returnId = true;
			}
			if ( $returnId = $this->Player->Save($_POST['data']) ) {
				if ( $id > 0 ) {
					$this->SetFeedback('text', 'saved', str_replace('%var1%', $_POST['data']['firstname'] . ' ' . $_POST['data']['lastname'],Message::Load('this_updated')));
				} else {
					$this->SetFeedback('text', 'saved', str_replace('%var1%', $_POST['data']['firstname'] . ' ' . $_POST['data']['lastname'],Message::Load('this_added')));
					$id = $returnId;
				}
				foreach ( $_POST['psvdata'] as $psvdata ) {
					if ( !empty($psvdata['value']) ) {
						$psvdata['player_id'] = $id;
						//print_r($psvdata);
						$this->Player->PlayerStatValue->Save($psvdata);
					} else {
						$this->Player->PlayerStatValue->conditions = array(0 => array('field' => 'playerstat_id', 'value' => $psvdata['playerstat_id']), 1 => array('field' => 'player_id', 'value' => $id));
						$psv = current($this->Player->PlayerStatValue->Get());
						if ( !empty($psv) ) {
							$this->Player->PlayerStatValue->Del($psv['PlayerStatValue']['id']);
						}
					}
				}
				$this->PrepareImage();
				//$this->Redirect(Anchors::Refer('admin_players') . '/index');
			} else {
				$this->SetFeedback('text', 'error', Message::Load('error_saving'));
			}
			$this->Redirect($_SERVER['REQUEST_URI']);
		}
		if ( $id > 0 ) {
			$this->Set('pdata', current($this->Player->GetById($id)));
			$this->Set('psvdata', $this->Player->PlayerStatValue->GetByPlayer_id($id));
		}
		$this->Set('restrictiondata', $this->Player->Playerstat->PlayerstatRestriction->GetAll());
		$this->Set('playerstats', $this->Player->Playerstat->GetAll());
		$this->Set('id', $id);
		if ( $teamID > 0 ) {
			$season = $this->Player->Seasonteamplayer->Season->GetActiveSeason();
			$this->Set('players', $this->Player->GetPlayersInSeasonTeam($season['Season']['id'], $teamID));			
		} else {
			$this->Set('players', $this->Player->GetAll());	
		}

		$this->SetContext('admin');		
	}
	
	public function admin_index($teamID = 0)
	{
		/*
		$this->SaveFromSession($id);
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {			
			if ( $id == 0 ) {
				$this->Player->returnId = true;
			}
			if ( $returnId = $this->Player->Save($_POST['data']) ) {
				if ( $id > 0 ) {
					$this->SetFeedback('text', 'saved', str_replace('%var1%', $_POST['data']['firstname'] . ' ' . $_POST['data']['lastname'],Message::Load('this_updated')));
				} else {
					$this->SetFeedback('text', 'saved', str_replace('%var1%', $_POST['data']['firstname'] . ' ' . $_POST['data']['lastname'],Message::Load('this_added')));
					$id = $returnId;
				}
				foreach ( $_POST['psvdata'] as $psvdata ) {
					if ( !empty($psvdata['value']) ) {
						$psvdata['player_id'] = $id;
						//print_r($psvdata);
						$this->Player->PlayerStatValue->Save($psvdata);
					}
				}
				$this->PrepareImage($id);
				//$this->Redirect(Anchors::Refer('admin_players') . '/index');
			} else {
				$this->SetFeedback('text', 'error', Message::Load('error_saving'));
			}
		}
		if ( $id > 0 ) {
			$this->Set('pdata', current($this->Player->GetById($id)));
			$this->Set('psvdata', $this->Player->PlayerStatValue->GetByPlayer_id($id));
		}
		$this->Set('restrictiondata', $this->Player->Playerstat->PlayerstatRestriction->GetAll());
		$this->Set('playerstats', $this->Player->Playerstat->GetAll());
		$this->Set('id', $id);
		*/
		
		if ( $teamID == 0 ) {
			$this->IsAllowed();
			$this->Set('players', $this->Player->GetAll());
		} else {
			$season = $this->Player->Seasonteamplayer->Season->GetActiveSeason();
			$this->Set('players', $this->Player->GetPlayersInSeasonTeam($season['Season']['id'], $teamID));

		}
		$this->Set('team_id', $teamID);					
		
		
		
		//$this->Set('players', $this->Player->GetAll());//this->Player->GetAll());	
		$this->SetContext('admin');
	}
	
	public function admin_mysettings()
	{
		$this->SessionStart();
		$id = $_SESSION['User']['player_id'];
		$this->SaveFromSession($id);
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {			
			if ( $this->Player->Save($_POST['data']) ) {
				$this->SetFeedback('text', 'saved', str_replace('%var1%', $_POST['data']['firstname'] . ' ' . $_POST['data']['lastname'],Message::Load('this_updated')));
				foreach ( $_POST['psvdata'] as $psvdata ) {
					if ( !empty($psvdata['value']) ) {
						$psvdata['player_id'] = $id;
						//print_r($psvdata);
						$this->Player->PlayerStatValue->Save($psvdata);
					}
				}
				$this->PrepareImage();
				//$this->Redirect(Anchors::Refer('admin_players') . '/index');
			} else {
				$this->SetFeedback('text', 'error', Message::Load('error_saving'));
			}
		}
		if ( $id > 0 ) {
			$this->Set('pdata', current($this->Player->GetById($id)));
			$this->Set('psvdata', $this->Player->PlayerStatValue->GetByPlayer_id($id));
		}
		$this->Set('restrictiondata', $this->Player->Playerstat->PlayerstatRestriction->GetAll());
		$this->Set('playerstats', $this->Player->Playerstat->GetAll());
		$this->Set('id', $id);	
		
		$this->SetContext('admin');
	}
	
	public function index($teamID = 0, $setSeason = '', $playerSlug = '')
	{
		$section = PublicWrapper::GetSection();
		$activeSection = current($section->GetByTarget('players/index/' . $teamID));
		
		if ( !$this->IsSeason($setSeason) ) {
			$this->RedirectFromOldPlayerSite($setSeason, $activeSection['Section']['url']);					
		}

		$activeSeason  = $this->Player->Seasonteamplayer->Season->GetActiveSeason();
		$thisSeason    = $setSeason == '' || $setSeason == 'alla' ? ( $setSeason == '' ? $activeSeason : false )  : $this->Player->Seasonteamplayer->Season->GetSeasonByYears(substr($setSeason, 0, 4), substr($setSeason, 5, 4)) ;
		$thisSeasonID  = $thisSeason ? $thisSeason['Season']['id'] : 0;
		$thisSeasonUrl = $thisSeasonID ? substr($thisSeason['Season']['startdate'], 0, 4) . '-' . substr($thisSeason['Season']['enddate'], 0, 4) : 'alla';


		if ( empty($playerSlug) ) {
			//$season = $this->Player->Seasonteamplayer->Season->GetActiveSeason();
			$this->Set('players', $this->Player->GetPlayersInSeasonTeam($thisSeasonID, $teamID));
			$this->Set('playerdetails', 0);
		} else {
			$player = $this->Player->GetBySlug($playerSlug);
			$this->Set('players', $player);
			$this->Set('playerstatsvalues', $this->Player->PlayerStatValue->GetByPlayer_id($player[0]['Player']['id']));
			//$this->Player->Playerstat->displayQuery = true;
			$this->Set('playerstats', $this->Player->Playerstat->GetByStatus(1));
			$this->Set('playerdetails', 1);
		}
		$this->Set('activeSection', $activeSection);		
		$this->Set('activeSeason', $activeSeason);
		$this->Set('thisSeasonID', $thisSeasonID);
		$this->Set('thisSeasonUrl', $thisSeasonUrl);
		$this->Set('seasons', $this->Player->Seasonteamplayer->Season->GetPastSeasons());
		$this->SetContext('public');
	}
	
	public function playerdetails($playerSlug = '')
	{
		$player = $this->Player->GetBySlug($playerSlug);
	}

	private function IsSeason($setSeason)
	{
		return $setSeason == '' || $setSeason == 'alla' || is_numeric(substr($setSeason, 0, 4));
	}

	private function PrepareImage($id = 0)
	{
		if ( !empty($_FILES['playerimg']['name']) ) {
				$image = new Image();
				$image->max_filesize 	= 2148576;
				$image->max_height   	= 2000;
				$image->max_width    	= 2000;
				$image->path_large   	= ROOT . DS . 'htdocs' . DS . 'bilder' . DS . 'spelarbilder' . DS;
				$image->path_thumb  	= ROOT . DS . 'htdocs' . DS . 'bilder' . DS . 'spelarbilder' . DS;
				$image->path_temp 	  	= $_FILES['playerimg']['tmp_name'];			

				$dotPos = strripos($_FILES['playerimg']['name'], '.');	
						
				$image->file_name = Formatter::CreateSlug(basename(substr($_FILES['playerimg']['name'], 0, $dotPos))) . '.jpg';
				$userfileSize = $_FILES['playerimg']['size'];			
				$fileExt = strtolower(substr(basename($_FILES['playerimg']['name']), $dotPos + 1));
				if ( !$returnval = $image->CreateLarge() ) {
					$this->messageText = "Korrupt bildfil. ";
					$this->messageID = 2;
				} else {
					//$imgWithExt = substr($returnval, strripos($returnval,'/')+1);
					$imgWithExt = substr($returnval, strripos($returnval,'/')+1);
					if ( strripos($returnval, '\\') && strripos($returnval, '\\') > strripos($returnval, '/') ) {
						$imgWithExt = substr($returnval, strripos($returnval, '\\') + 1);
					}
					//$imgName = substr($returnval, strripos($returnval,'/')+1);
					$imgName = substr($imgWithExt, 0, strripos($imgWithExt, '.'));
					
					$_SESSION['img']['width'] 	= $image->GetWidth($image->path_large . $imgWithExt);
					$_SESSION['img']['height'] 	= $image->GetHeight($image->path_large . $imgWithExt);
					$_SESSION['img']['side'] 	= 50;					
					$_SESSION['img']['delete'] 	= 0;
					$_SESSION['img']['path'] 	= '/bilder/spelarbilder/' . $imgWithExt;
					$_SESSION['img']['srcpath'] = ROOT . DS . 'htdocs' . DS . 'bilder' . DS . 'spelarbilder' . DS . $imgWithExt;			
					$_SESSION['img']['newname'] = ROOT . DS . 'htdocs' . DS . 'bilder' . DS . 'spelarbilder' . DS . $imgName . '_tn.' . $fileExt;				
					$_SESSION['img']['url'] 	= ( ( $id == 0 ) ? $_SERVER['REQUEST_URI'] : '/admin/players/edit/' . $id );//$_SERVER['REQUEST_URI'];
					
					$_SESSION['description'] = $_POST['data']['description'];
					
					header('location: /admin/pages/crop');
					exit;
				}			
			
		}
	}
	
	private function RedirectFromOldPlayerSite($string, $thisUrl)
	{
		$player = current($this->Player->GetBySlug($string));
		if ( !empty($player) ) {
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: http://" . $_SERVER['SERVER_NAME'] . Anchors::Refer('base') . "/" . $thisUrl . "/2011-2012/" . $player['Player']['slug'] . ".html");
			exit;
		} else {
			header("Location: " . Anchors::Refer('base') . "/" . $thisUrl . ".html");
			exit;
		}
	}

	private function SaveFromSession($id) 
	{
		if ( isset($_SESSION['filename']) ) {
			if ( !empty($_SESSION['filename']) ) {
				//$image['src'] = substr(substr($_SESSION['filename'], strripos($_SESSION['filename'], '/')+1), 0, stripos(substr($_SESSION['filename'], strripos($_SESSION['filename'], '/')+1), '.'));
				$imgName = substr($_SESSION['filename'], strripos($_SESSION['filename'],'/')+1);
				if ( strripos($_SESSION['filename'], '\\') && strripos($_SESSION['filename'], '\\') > strripos($_SESSION['filename'], '/') ) {
					$imgName = substr($_SESSION['filename'], strripos($_SESSION['filename'], '\\') + 1);
				}


				$file = substr($imgName, 0, stripos($imgName, '.'));
				$player['imgsrc'] = substr($file, 0, strripos($file, '_tn'));
				$player['id'] = $id;
				if ( $this->Player->Save($player) ) {
					
				}
								
				//print_r(substr($_SESSION['filename'], strripos($_SESSION['filename'],'/')+1, ((stripos($_SESSION['filename'], '_tn.')) - (strripos($_SESSION['filename'],'/')+1))));
				//unlink($_SESSION['filename']);
			}
			unset($_SESSION['filename']);
			unset($_SESSION['description']);
		}
	}
	
	
	

}