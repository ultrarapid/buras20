<?php	if ( $messageType === 'text' ) : ?>
				<div class="div_text_feedback div_<?= $messageInfo ?>_message">
					<p><?= $messageText ?></p>
				</div>
<?php	endif; ?>
				<table class="table_admin_small">
<?php	foreach ( $playerstats as $i => $playerstat ) : ?>
					<tr class="tr_playerstat_row tr_<?= ( ( ($i+1)%2 ) ? 'odd' : 'even' ) ?>">
						<td class="playerstat_name td_info"><?= $playerstat['Playerstat']['name'] . ' ' . $statussymbols[$playerstat['Playerstat']['status']] . ' ' ?></td>
						<td class="playerstat_edit td_edit"><a href="<?= Anchors::Refer('admin_playerstats') . '/edit/' . $playerstat['Playerstat']['id'] ?>">editera</a></td>
<?php	if ( $playerstat['Playerstat']['ordernr'] < $maxOrder ) : ?>
						<td class="playerstat_movedown td_movedown"><a href="<?= Anchors::Refer('admin_playerstats') . '/movedown/' . $playerstat['Playerstat']['ordernr'] ?>">ner</a></td>
<?php	else : ?>
						<td></td>
<?php	endif; ?>
<?php	if ( $playerstat['Playerstat']['ordernr'] > $minOrder && false ) : ?>
						<td class="playerstat_moveup td_moveup"><a href="<?= Anchors::Refer('admin_playerstats') . '/moveup/' . $playerstat['Playerstat']['ordernr'] ?>">upp</a></td>
<?php	else : ?>
						<td></td>
<?php	endif; ?>
						<td class="playerstat_delete td_delete"><a href="<?= Anchors::Refer('admin_playerstats') . '/delete/' . $playerstat['Playerstat']['id'] ?>">ta bort</a></td>
						<td><?= $playerstat['Playerstat']['ordernr'] ?></td>
					</tr>
<?php	endforeach; ?>
<?php	if ( empty($playerstats) ) : ?>
					<tr></tr>
<?php	endif; ?>
				</table>
			<div class="div_footer_info">
<?php	foreach ( $statussymbols as $l => $statussymbol )	: ?>
				<p><?= $statussymbol . ' = ' . $visibilities[$l] ?></p>
<?php	endforeach; ?>
			</div>