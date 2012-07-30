<p><a href="<?= Anchors::Refer('admin_start') ?>">tillbaka</a></p>
<?php	if ( $messageType === 'text' ) : ?>
				<div class="div_text_feedback div_<?= $messageInfo ?>_message">
					<p><?= $messageText ?></p>
				</div>
<?php	endif; ?>
				<form class="form_standard" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
					<div class="input">
						<label for="input_team_name">Namn (ex Damer A, Herrar B):</label>
						<input name="data[name]" value="<?= ( ( $id > 0 ) ? $teamdata['Team']['name'] : '' ) ?>" type="text" id="input_team_name" />
					</div>                  
					<div>
						<input name="<?= ( ( $id > 0 ) ? 'data[id]' : 'id' ) ?>" type="hidden" value="<?= ( ( $id > 0 ) ? $teamdata['Team']['id'] : 0 ) ?>" />
<?php	if ( $messageType === 'popup' ) : ?>
						<input id="message_info" type="hidden" value="<?= $messageInfo ?>" />
						<input id="message_text" type="hidden" value="<?= $messageText ?>" />
<?php	endif; ?>						
						<input value="spara lag" type="submit" />
					</div>
				</form>
				<form class="standard" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
					<table class="table_admin_small">
<?php	foreach ( $teams as $k => $team ) : ?>
						<tr class="tr_team_row tr_<?= ( ( ($k+1)%2 ) ? 'odd' : 'even' ) ?>">
							<td class="team_name td_info"><?= $team['Team']['name'] ?></td>
              <td class="team_edit td_edit"><a href="<?= Anchors::Refer('admin_teams'). '/edit/' . $team['Team']['id'] ?>">editera</a></td>								
							<td class="team_delete td_delete"><a href="<?= Anchors::Refer('admin_teams') . '/delete/' . $team['Team']['id'] ?>">ta bort</a></td>
						</tr>
<?php	endforeach; ?>
<?php	if ( empty($teams) ) : ?>
						<tr></tr>
<?php	endif; ?>
					</table>			
				</form>
