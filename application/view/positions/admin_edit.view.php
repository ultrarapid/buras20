<p><a href="<?= Anchors::Refer('admin_start') ?>">tillbaka</a></p>
<?php	if ( $messageType === 'text' ) : ?>
				<div class="div_text_feedback div_<?= $messageInfo ?>_message">
					<p><?= $messageText ?></p>
				</div>
<?php	endif; ?>
				<form class="form_standard" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
					<div class="input">
						<label for="input_pos_name">Namn (ex Back, Center):</label>
						<input name="data[name]" value="<?= ( ( $id > 0 ) ? $positiondata['Position']['name'] : '' ) ?>" type="text" id="input_team_name" />
					</div>                  
					<div>
						<input name="<?= ( ( $id > 0 ) ? 'data[id]' : 'id' ) ?>" type="hidden" value="<?= ( ( $id > 0 ) ? $positiondata['Position']['id'] : 0 ) ?>" />
<?php	if ( $messageType === 'popup' ) : ?>
						<input id="message_info" type="hidden" value="<?= $messageInfo ?>" />
						<input id="message_text" type="hidden" value="<?= $messageText ?>" />
<?php	endif; ?>						
						<input value="spara position" type="submit" />
					</div>
				</form>
				<form class="standard" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
					<table class="table_admin_small">
<?php	foreach ( $positions as $k => $pos ) : ?>
						<tr class="tr_pos_row tr_<?= ( ( ($k+1)%2 ) ? 'odd' : 'even' ) ?>">
							<td class="pos_name td_info"><?= $pos['Position']['name'] ?></td>
                            <td class="pos_edit td_edit"><a href="<?= Anchors::Refer('admin_positions'). '/edit/' . $pos['Position']['id'] ?>">editera</a></td>								
							<td class="pos_delete td_delete"><a href="<?= Anchors::Refer('admin_positions') . '/delete/' . $pos['Position']['id'] ?>">ta bort</a></td>
						</tr>
<?php	endforeach; ?>
<?php	if ( empty($positions) ) : ?>
						<tr></tr>
<?php	endif; ?>
					</table>			
				</form>
