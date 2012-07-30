<p><a href="<?= Anchors::Refer('admin_start') ?>">tillbaka</a></p>
<?php	if ( $messageType === 'text' ) : ?>
				<div class="div_text_feedback div_<?= $messageInfo ?>_message">
					<p><?= $messageText ?></p>
				</div>
<?php	endif; ?>
				<form class="form_standard" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
					<div class="input">
						<label for="input_formation_name">Namn (ex F&ouml;rsta femman, Andra femman, Powerplay #1):</label>
						<input name="data[name]" value="<?= ( ( $id > 0 ) ? $formatdata['Formation']['name'] : '' ) ?>" type="text" id="input_formation_name" />
					</div>
<?php	foreach ( $positions as $j => $pos ) : ?>
					<div class="div_input">
						<label for="input_position_<?= $pos['Position']['id'] ?>">Antal (<?= $pos['Position']['name']?>) i formation</label>					
<?php		$counter = '';
			if ( $id > 0 && isset($restrData) ) :
				if ( !empty($restrData) ) :
					foreach ( $restrData as $rd ) :
						if ( $rd['FormationRestriction']['position_id'] == $pos['Position']['id'] ) :
							$counter['amount'] = $rd['FormationRestriction']['amount'];
							$counter['id']	   = $rd['FormationRestriction']['id'];
						endif; 
					endforeach;
				endif;
			endif;
			if ( !empty($counter) ) : ?>
						<input name="position[<?= $j ?>][amount]" value="<?= $counter['amount'] ?>" type="text" id="input_position_<?= $pos['Position']['id'] ?>" />
						<input name="position[<?= $j ?>][id]" value="<?= $counter['id'] ?>" type="hidden" />
<?php		else : ?>
						<input name="position[<?= $j ?>][amount]" value="" type="text" id="input_position_<?= $pos['Position']['id'] ?>" />
<?php		endif; ?>
						<input type="hidden" name="position[<?= $j ?>][position_id]" value="<?= $pos['Position']['id'] ?>" />
					</div>
<?php	endforeach; ?>
					<div>
						<input name="<?= ( ( $id > 0 ) ? 'data[id]' : 'id' ) ?>" type="hidden" value="<?= ( ( $id > 0 ) ? $formatdata['Formation']['id'] : 0 ) ?>" />
<?php	if ( $messageType === 'popup' ) : ?>
						<input id="message_info" type="hidden" value="<?= $messageInfo ?>" />
						<input id="message_text" type="hidden" value="<?= $messageText ?>" />
<?php	endif; ?>						
						<input value="spara formation" type="submit" />
					</div>
				</form>
				<form class="standard" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
					<table class="table_admin_small">
<?php	foreach ( $formations as $k => $formation ) : ?>
						<tr class="tr_formation_row tr_<?= ( ( ($k+1)%2 ) ? 'odd' : 'even' ) ?>">
							<td class="formation_name td_info"><?= $formation['Formation']['name'] ?></td>
                            <td class="formation_edit td_edit"><a href="<?= Anchors::Refer('admin_formations'). '/edit/' . $formation['Formation']['id'] ?>">editera</a></td>								
							<td class="formation_delete td_delete"><a href="<?= Anchors::Refer('admin_formations') . '/delete/' . $formation['Formation']['id'] ?>">ta bort</a></td>
						</tr>
<?php	endforeach; ?>
<?php	if ( empty($formations) ) : ?>
						<tr></tr>
<?php	endif; ?>
					</table>			
				</form>
