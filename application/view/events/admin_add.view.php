<?php	if ( $messageType === 'text' ) : ?>
				<div class="div_text_feedback div_<?= $messageInfo ?>_message">
					<p><?= $messageText ?></p>
				</div>
<?php	endif; ?>
				<form class="standard" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
					<div class="div_input">
						<label for="input_event_name"><span>Rubrik:</span></label>
						<input name="data[header]" type="text" id="input_event_name" />
					</div>
					<div class="div_input">
						<label for="input_event_location"><span>Plats:</span></label>
						<input name="data[location]" type="text" id="input_event_location" />
					</div>					
					<div class="div_input">
						<label for="input_event_description"><span>Beskrivning:</span></label>
             <textarea rows="22" cols="80" name="data[description]" id="input_event_description" class="tinymce"></textarea>
					</div>
					<div class="div_input">
						<label for="input_event_date"><span>Datum:</span></label>
						<input name="raw[date]" class="input_date" type="text" id="input_event_date" />
					</div>
					<div class="div_input">
						<label for="input_event_time"><span>Tid:</span></label>
						<input name="raw[time]" class="input_time" type="text" id="input_event_time" />
					</div>
					<div class="div_select">
						<label for="input_event_status"><span>Tid:</span></label>
						<select name="data[status]" id="input_event_status">
							<option value="0">Dold</option>
							<option value="1">Intern</option>
							<option value="2">Publik</option>
						</select>
					</div>				
					<input value="spara händelse" type="submit" class="input_submit" />
				</form>