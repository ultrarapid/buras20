			<p><a href="<?= Anchors::Refer('admin_start') ?>">start</a></p>            
			<p><a href="<?= Anchors::Refer('admin_sections_index') ?>">tillbaka</a></p>
				<form class="standard" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
					<div class="input">
						<label for="input_product_name">name:</label>
						<input name="data[name]" type="text" id="input_product_name" value="<?= ( ( $set ) ? $sectionen['Section']['name'] : '' ) ?>" />
					</div>
					<div class="input">
						<label for="input_product_adminname">adminname:</label>
						<input name="data[adminname]" type="text" id="input_product_adminname" value="<?= ( ( $set ) ? $sectionen['Section']['adminname'] : '' ) ?>" />
					</div>
					<div class="input">
						<label for="input_product_header">header:</label>
						<input name="data[header]" type="text" id="input_product_header" value="<?= ( ( $set ) ? $sectionen['Section']['header'] : '' ) ?>" />
					</div>					
					<?php /*
					<p><?= ( ( $set ) ? $section['Section']['url'] : '' ) ?></p>
					<div class="input">
						<label for="input_product_text">Text:</label>
                        <textarea rows="12" cols="40" name="data[body]" id="input_product_text" class="ed"><?= ( ( $set ) ? $section['Section']['body'] : '' ) ?></textarea>
					</div>
					<div class="input">
						<label for="input_section_visible">Synlig:</label>
						<select name="data[visible]" id="input_section_visible">
							<option value="1"<?= ( ( $section['Section']['visible'] == 1 ? ' selected="selected"' : '' ) ) ?>>Synlig</option>
							<option value="0"<?= ( ( $section['Section']['visible'] == 0 ? ' selected="selected"' : '' ) ) ?>>Dold</option>
						</select>
					</div>
					<div class="input">
						<label for="input_section_postsection">Sida med inl&auml;gg (nyheter, blogg osv) eller sida med fast inneh&aring;ll:</label>
						<select name="data[postsection]" id="input_section_visible">
							<option value="2"<?= ( ( $section['Section']['postsection'] == 2 ? ' selected="selected"' : '' ) ) ?>>Inl&auml;gg</option>
							<option value="1"<?= ( ( $section['Section']['postsection'] == 1 ? ' selected="selected"' : '' ) ) ?>>Fast inneh&aring;ll</option>
							<option value="0"<?= ( ( $section['Section']['postsection'] == 0 ? ' selected="selected"' : '' ) ) ?>>&Ouml;vrig koppling</option>
						</select>
					</div>					
<?php	if ( !$set || $section['Section']['postsection'] == 1 ) : ?>
					<div class="input">
						<label for="input_section_comment_enable">Kommentarer av inl&auml;gg m&ouml;jliga</label>
						<input type="checkbox" name="data[comment_enabled]" value="1" <?= ( ( $section['Section']['comment_enabled'] == 1 ) ? 'checked="checked" ' : '' )?>/>
					</div>

<?php	endif; ?>
*/ ?>
<?php	if ( $set ) : ?>
					<input name="data[id]" value="<?= $sectionen['Section']['id'] ?>" type="hidden" />
<?php	endif; ?>					
					<input id="popup_id" type="hidden" value="<?= $popup_id ?>" />
					<input id="popup_msg" type="hidden" value="<?= $popup_msg ?>" />					
					<input value="spara inl&auml;gg" type="submit" />
				</form>