<?php	if ( $messageType === 'text' ) : ?>
				<div class="div_text_feedback div_<?= $messageInfo ?>_message">
					<p><?= $messageText ?></p>
				</div>
<?php	endif; ?>
				<form class="standard" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
					<div class="div_input">
						<label for="input_product_name"><span>Rubrik:</span></label>
						<input name="data[header]" type="text" id="input_product_header" value="<?= $post['Post']['header'] ?>" />
					</div>
					<div class="div_input">
						<label for="input_product_text"><span>Text:</span></label>
             <textarea rows="22" cols="80" name="data[body]" id="input_product_text" class="tinymce"><?= $post['Post']['body'] ?></textarea>
					</div>
					<input name="data[published]" value="<?= $post['Post']['published'] ?>" type="hidden" />
          <input name="data[id]" type="hidden" value="<?= $post['Post']['id'] ?>" />
					<input value="spara inl&auml;gg" type="submit" class="input_submit" />
				</form>
