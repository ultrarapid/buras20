<img id="<?= $cropDir ?>" class="<?= $cropClass ?>" src="<?= $imgSrc ?>" alt="" />		
				<form id="cropform" class="standard" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
					<input type="hidden" name="side" id="thumb_side" value="<?= $side ?>" />
					<input type="hidden" id="src_h" value="<?= $height ?>" />
					<input type="hidden" id="src_w" value="<?= $width ?>" />
					<input type="hidden" id="new_h" name="new_height" value="<?= $newHeight ?>" />
					<input type="hidden" id="new_w" name="new_width" value="<?= $newWidth ?>" />                  
					<input type="submit" value="spara" />
				</form>
