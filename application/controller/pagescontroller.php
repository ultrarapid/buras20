<?php
class PagesController extends App_Controller
{
	protected $messageID = 0;
	protected $messageText = '';
	
	public function admin_crop() {
		
		
		$_maxWidth = 0;
		$_maxHeight = 0;
		$_newHeight = 0;
		$_newWidth = 0;
		$_side = 0;
		$_img = '';
		$_height = 0;
		$_width = 0;
		
			
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			$selected_x = $_POST['x'];
			$selected_y = $_POST['y'];
			$selected_h = $_POST['h'];
			$selected_w = $_POST['w'];
			if (intval($_POST['side']) > 0) {
				$_newWidth = $_POST['side'];
				$_newHeight = $_POST['side'];			
			} else {
				$_newWidth = $selected_w;
				$_newHeight = $selected_h;
				$_maxWidth = $_POST['new_width'];
				$_maxHeight = $_POST['new_height'];
			}
	
	
			$srcPath = $_SESSION['img']['path'];
			$tsSrcPath = $_SESSION['img']['srcpath'];
			$_height = $_SESSION['img']['height'];
			$_width = $_SESSION['img']['width'];
			$newName = $_SESSION['img']['newname'];
			$returnUrl = $_SESSION['img']['url'];
				
			$image = new Image();
			//print_r($_POST);
			//print_r($_SESSION);
			
			$file = $image->ResizeThumbnailImage($newName, $tsSrcPath, $_newWidth, $_newHeight, $selected_x, $selected_y, $selected_w, $selected_h, $_maxWidth, $_maxHeight);
	
			if (!empty($file) && $_SESSION['img']['delete'] == 1) {
				unlink($tsSrcPath);	
			}
			$_SESSION['filename'] = $file;
			unset($_SESSION['img']);
			header('location: ' .$returnUrl);
			exit;
			
			
		} else {
			$this->Set('imgSrc', $_SESSION['img']['path']);
			$this->Set('height', $_SESSION['img']['height']);
			$this->Set('width', $_SESSION['img']['width']);
			$this->Set('side', $_SESSION['img']['side']);
	
			if ( $_SESSION['img']['side'] > 0)  {
				$this->Set('cropDir', 'cropbox_thumb_ar1');
				$this->Set('cropClass', 'thumb');
				$this->Set('newHeight', 0);
				$this->Set('newWidth', 0);
			} else {
				$this->Set('cropDir', 'cropbox');
				$this->Set('cropClass', 'croppy');
				$this->Set('newHeight', $_SESSION['img']['new_height']);
				$this->Set('newWidth', $_SESSION['img']['new_width']);
			}	
		}
		
		$addedScripts = array(0 => 'jquery.Jcrop.min', 1 => 'crop');
		$this->Set('layoutStylesheets', array(0 => array('href' => 'jquery.Jcrop')));
		
		$this->SetContext('admin', $addedScripts);
		
	}
	
	
	public function admin_index()
	{
		
	}
}