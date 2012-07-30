<p><a href="<?= Anchors::Refer('admin_start') ?>">tillbaka</a></p>
<?php	if ( $messageType === 'text' ) : ?>
				<div class="div_text_feedback div_<?= $messageInfo ?>_message">
					<p><?= $messageText ?></p>
				</div>
<?php	endif; ?>
				<div class="div_gameposition_wrapper">
<?php	foreach ( $gameplayers as $gp ) : ?>
					<div class="div_gameplayer">
						<p><?= $gp['Player']['firstname'] . ' ' . $gp['Player']['lastname']; ?></p>
						<p><a href="<?= Anchors::Refer('admin_gameformations') . '/add/' . $game_id . '/' . $gf_id . '/' . $pos_id . '/' . $gp['Player']['id'] ?>">V&auml;lj</a></p>
					</div>
<?php	endforeach; ?>
				</div>
