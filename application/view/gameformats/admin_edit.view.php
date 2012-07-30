<p><a href="<?= Anchors::Refer('admin_start') ?>">tillbaka</a></p>
<?php	if ( $messageType === 'text' ) : ?>
				<div class="div_text_feedback div_<?= $messageInfo ?>_message">
					<p><?= $messageText ?></p>
				</div>
<?php	endif; ?>
				<form class="form_standard" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
					<div class="input">
						<label for="input_gameformat_name">Namn (ex Serie, Bj&ouml;rnslaget):</label>
						<input name="data[name]" value="<?= ( ( $id > 0 ) ? $gfdata['Gameformat']['name'] : '' ) ?>" type="text" id="input_gameformat_name" />
					</div>                  
					<div class="input">
						<label for="input_gameformat_periods">Perioder:</label>
						<select name="data[periods]" id="input_gameformat_periods">
<?php	for ( $i = 1 ; $i <= 3 ; $i++ ) : ?>
							<option <?= ( ( $id > 0 ) ? ( ( $gfdata['Gameformat']['periods'] == $i ) ? 'selected="selected" ' : '' ) : ( ( $i == $defaultPeriods ) ? 'selected="selected" ' : '' ) ) ?>value="<?= $i ?>"><?= $i ?></option>
<?php	endfor; ?>
						</select>
					</div>
					<div class="input">
						<label for="input_gameformat_time">Periodtid:</label>
						<select name="data[periodtime]" id="input_gameformat_time">
<?php	for ( $j = 1 ; $j <= 20 ; $j++ ) : ?>
							<option <?= ( ( $id > 0 ) ? ( ( $gfdata['Gameformat']['periodtime'] == $j ) ? 'selected="selected" ' : '' ) : ( ( $j == $defaultPeriodTime ) ? 'selected="selected" ' : '' ) ) ?>value="<?= $j ?>"><?= $j ?></option>
<?php	endfor; ?>
						</select>
					</div>
					<div class="input">
						<input <?= ( ( $id > 0 ) ? ( ( $gfdata['Gameformat']['overtime'] == 0 ) ? 'checked="checked" ' : '' ) : ( ( $defaultOvertime == 0 ) ? 'checked="checked" ' : '' ) ) ?>name="data[overtime]" type="radio" value="0">Utan &ouml;vertid</input>
						<input <?= ( ( $id > 0 ) ? ( ( $gfdata['Gameformat']['overtime'] == 1 ) ? 'checked="checked" ' : '' ) : ( ( $defaultOvertime == 1 ) ? 'checked="checked" ' : '' ) ) ?>name="data[overtime]" type="radio" value="1">Med &ouml;vertid</input>
					</div>
					<div>
						<input name="<?= ( ( $id > 0 ) ? 'data[id]' : 'id' ) ?>" type="hidden" value="<?= ( ( $id > 0 ) ? $gfdata['Gameformat']['id'] : 0 ) ?>" />
<?php	if ( $messageType === 'popup' ) : ?>
						<input id="message_info" type="hidden" value="<?= $messageInfo ?>" />
						<input id="message_text" type="hidden" value="<?= $messageText ?>" />
<?php	endif; ?>
						<input value="spara matchformat" type="submit" />
					</div>
				</form>			
				<table class="table_admin_small">
<?php	foreach ( $gameformats as $k => $gameformat ) : ?>
					<tr class="tr_gameformat_row tr_<?= ( ( ($k+1)%2 ) ? 'odd' : 'even' ) ?>">
						<td class="gameformat_name td_info"><?= $gameformat['Gameformat']['name'] ?></td>
						<td class="gameformat_matchtime td_info"><?= $gameformat['Gameformat']['periods'] . ' x ' . $gameformat['Gameformat']['periodtime'] ?></td>
						<td class="game_edit td_edit"><a href="<?= Anchors::Refer('admin_gameformats') . '/edit/' . $gameformat['Gameformat']['id'] ?>">editera</a></td>								
						<td class="game_delete td_delete"><a href="<?= Anchors::Refer('admin_gameformats') . '/delete/' . $gameformat['Gameformat']['id'] ?>">ta bort</a></td>
					</tr>
<?php	endforeach; ?>
<?php	if ( empty($gameformats) ) : ?>
					<tr></tr>
<?php	endif; ?>
				</table>
