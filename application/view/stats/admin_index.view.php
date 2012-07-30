<p><a href="<?= Anchors::Refer('admin_start') ?>">tillbaka</a></p>
<?php	if ( $messageType === 'text' ) : ?>
				<div class="div_text_feedback div_<?= $messageInfo ?>_message">
					<p><?= $messageText ?></p>
				</div>
<?php	endif; ?>
				<form class="form_standard" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
					<div class="input">
						<label for="input_stats_name">Namn (ex Damer A, Herrar B):</label>
						<input name="data[name]" value="<?= ( ( $id > 0 ) ? $stat['Stat']['name'] : '' ) ?>" type="text" id="input_stats_name" />
					</div>
					<div class="input">
						<label for="input_stats_status">Status (0, 1, 2):</label>
						<input name="data[statusfield]" value="<?= ( ( $id > 0 ) ? $stat['Stat']['statusfield'] : '' ) ?>" type="text" id="input_stats_status" />
					</div>
					<div class="input">
						<label for="input_stats_order">Order (0, 1, 2):</label>
						<input name="data[ordernumber]" value="<?= ( ( $id > 0 ) ? $stat['Stat']['ordernumber'] : '' ) ?>" type="text" id="input_stats_order" />
					</div> 				              
					<div>
						<input name="<?= ( ( $id > 0 ) ? 'data[id]' : 'firstsave[id]' ) ?>" type="hidden" value="<?= ( ( $id > 0 ) ? $stat['Stat']['id'] : 1 ) ?>" />
<?php	if ( $messageType === 'popup' ) : ?>
						<input id="message_info" type="hidden" value="<?= $messageInfo ?>" />
						<input id="message_text" type="hidden" value="<?= $messageText ?>" />
<?php	endif; ?>						
						<input value="spara lag" type="submit" />
					</div>
				</form>
				<form class="standard" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
					<table class="table_admin_small">
<?php	foreach ( $allStats as $k => $thisStat ) : ?>
						<tr class="tr_team_row tr_<?= ( ( ($k+1)%2 ) ? 'odd' : 'even' ) ?>">
							<td class="team_name td_info"><?= $thisStat['Stat']['name'] ?></td>
                            <td class="team_edit td_edit"><a href="/bik/admin/stats/index/<?= $thisStat['Stat']['id'] ?>">editera</a></td>
                            <td class="team_edit td_edit"><a href="/bik/admin/stats/moveup/<?= $thisStat['Stat']['id'] ?>">-- upp --</a></td>
                            <td class="team_edit td_edit"><a href="/bik/admin/stats/movedown/<?= $thisStat['Stat']['id'] ?>">-- ner --</a></td>
							<td class="team_delete td_delete"><a href="/bik/admin/stats/delete/<?= $thisStat['Stat']['id'] ?>">ta bort</a></td>
							<td class="team_delete td_delete"><?= $thisStat['Stat']['ordernumber'] ?></td>
						</tr>
<?php	endforeach; ?>
<?php	if ( empty($teams) ) : ?>
						<tr></tr>
<?php	endif; ?>
					</table>			
				</form>
