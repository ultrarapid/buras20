				<form class="standard" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
					<div class="div_input">
						<label for="input_product_name"><span>Rubrik:</span></label>
						<input name="data[header]" type="text" id="input_product_header" />
					</div>
					<div class="div_input">
						<label for="input_product_text"><span>Text:</span></label>
             <textarea rows="22" cols="80" name="data[body]" id="input_product_text" class="tinymce"></textarea>
					</div>
					<input name="data[published]" value="1" type="hidden" />									
					<input id="popup_id" type="hidden" value="<?= $popup_id ?>" />
					<input id="popup_msg" type="hidden" value="<?= $popup_msg ?>" />					
					<input value="spara inl&auml;gg" type="submit" class="input_submit" />
				</form>