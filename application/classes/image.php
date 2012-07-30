<?php
class Image {
	
	public $file_name		    = null;
	public $file_extensions = array('jpg', 'jpeg');
	public $max_height		  = null;	
	public $max_width		    = null;
	public $max_filesize	  = null;
	public $path_large		  = null;
	public $path_temp		    = null;
	public $path_thumb		  = null;
	public $thumb_height	  = null;
	public $thumb_width	    = null;
	
	public function __construct() {
	}
	
	public function GetHeight($image) {
		$sizes = getimagesize($image);
		$height = $sizes[1];
		return $height;
	}

	public function GetWidth($image) {
		$sizes = getimagesize($image);
		$width = $sizes[0];
		return $width;
	}
	
	public function ResizeImage($image,$width,$height,$scale) {
		$newImageWidth = ceil($width * $scale);
		$newImageHeight = ceil($height * $scale);
		$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
		if (false !== ($source = @imagecreatefromjpeg($image))) {
			imagecopyresampled($newImage,$source,0,0,0,0,$newImageWidth,$newImageHeight,$width,$height);
			imagejpeg($newImage,$image,90);
			chmod($image, 0777);
			return $image;		
		} else {
			return false;
		}
				
	}
	
	public function ResizeThumbnailImage($new_name, $src, $new_w, $new_h, $x, $y, $w, $h, $max_w = 0, $max_h = 0){
		if ($max_w > 0 && $max_h > 0) {
			if ($new_w > $max_w) {
				$scale = $max_w/$new_w;
				$new_w = $new_w * $scale;
				$new_h = $new_h * $scale;
			}
			if ($new_h > $max_h) {
				$scale = $max_h/$new_h;
				$new_h = $new_h * $scale;
				$new_w = $new_w * $scale;
			}
		}
		$newImage = imagecreatetruecolor($new_w, $new_h);
		$source = imagecreatefromjpeg($src);
		imagecopyresampled($newImage,$source,0,0,$x,$y,$new_w,$new_h,$w,$h);
		imagejpeg($newImage,$new_name,90);
		chmod($new_name, 0777);
		return $new_name;
	}

	public function CheckExtension($ext) {
		if (in_array($ext, $this->file_extensions)) {
			return true;
		} else {
			return false;
		}
	}

	public function CheckSize($size) {
		if ($size <= $this->max_filesize) {
			return true;
		} else {
			return false;
		}
	}
	
	public function CreateLarge() {
		if (!empty($this->path_large) && !empty($this->path_temp) && !empty($this->file_name) && !empty($this->max_height) && !empty($this->max_width)) {
			if ( !file_exists($this->path_large) ) {
				mkdir($this->path_large, 0777);				
			}
			$newfile = $this->path_large.$this->file_name;
			move_uploaded_file($this->path_temp, $newfile);
			chmod($newfile, 0777);
			$width = $this->GetWidth($newfile);
			$height = $this->GetHeight($newfile);
			//Scale the image if it is greater than the width set above
			if ($width > $this->max_width || $height > $this->max_height){
				if ($width > $this->max_width) {
					$scale = $this->max_width/$width;
					$height = $height*$scale;					
				}
				if ($height > $this->max_height) {
					$scale = $this->max_height/$height;
				}
				if (!$uploaded = $this->ResizeImage($newfile,$width,$height,$scale)) {
					unlink($newfile);
				}
				return $uploaded;
			} else {
				$scale = 1;
				if (!$uploaded = $this->ResizeImage($newfile,$width,$height,$scale)) {
					unlink($newfile);
				}
				return $uploaded;
			}			
		} else {
			return false;
		}
/*		
		move_uploaded_file($userfile_tmp, $large_image_location);
		chmod ($large_image_location, 0777);

		$width = getWidth($large_image_location);
		$height = getHeight($large_image_location);
		//Scale the image if it is greater than the width set above
		if ($width > $max_width || $height > $max_height){
			if ($width > $max_width) {
				$scale = $max_width/$width;
				$height = $height*$scale;					
			}
			if ($height > $max_height) {
				$scale = $max_height/$height;
			}
			$uploaded = resizeImage($large_image_location,$width,$height,$scale);
		} else {
			$scale = 1;
			$uploaded = resizeImage($large_image_location,$width,$height,$scale);
		}
		//Delete the thumbnail file so the user can create a new one
		if (file_exists($thumb_image_location)) {
			unlink($thumb_image_location);
		}*/
	}

}