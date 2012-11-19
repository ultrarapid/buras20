<?php	if ( $messageType === 'text' ) : ?>
				<div class="div_text_feedback div_<?= $messageInfo ?>_message">
					<p><?= $messageText ?></p>
				</div>
<?php	endif; ?>
				<form class="form_standard" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
					<div class="div_select">
						<label for="input_game" class="label_input">Match:</label>
						<select name="gsdata[game_id]" id="input_game">
<?php	foreach ( $games as $g ) : ?>
							<option value="<?= $g['Game']['id'] ?>"<?= ( ( $g['Game']['id'] == $game_id ) ? ' selected="selected"' : '' ) ?>><?= $g['Game']['opponent'] . ' ' . $g['Game']['gamedate'] ?></option>
<?php	endforeach; ?>
						</select>				
					</div>
<?php 	if ( $game_id > 0 ) : ?>
					<div class="div_submit">
						<input type="submit" name="Byt" value="Byt match" />
					</div>
				</form>
				<div class="div_playerlistwrapper">
					<div class="div_pickedplayerlist">
						<h3>Uttagna spelare</h3>
						<ul>					
<?php		foreach ( $gameplayers as $k => $player ) : ?>
							<li><?= $player['Player']['firstname'] . ' ' . $player['Player']['lastname'] ?> <a href="<?= Anchors::Refer('admin_gamesetups') . '/delete/' . $game_id . '/' . $player['Player']['id'] . '/' . $player['Gamesetup']['id'] ?>">Ta bort</a></li>
<?php		endforeach; ?>
						</ul>
					</div>				
					<div class="div_filteredplayerlist">
						<h3>Spelare</h3>
						<ul>					
<?php		foreach ( $seasonplayers as $j => $fplayer ) :
				if ( !in_array($fplayer['Player']['id'], $ids) ) : ?>
							<li><?= $fplayer['Player']['firstname'] . ' ' . $fplayer['Player']['lastname'] ?> <a href="<?= Anchors::Refer('admin_gamesetups') . '/add/' . $game_id . '/' . $fplayer['Player']['id'] ?>">L&auml;gg till</a></li>
<?php			endif; ?>
<?php		endforeach; ?>
						</ul>
					</div>
					<div class="div_clear"></div>
					<div><a href="<?= Anchors::Refer('admin_gamesetups_edit') . '/' . $game_id . '/1' ?>">Hämta från innebandy.se</a></div><br />
					<div><a href="<?= Anchors::Refer('admin_gameformations_edit') . '/' . $game_id ?>">Formationer</a></div>
				</div>
<?php	endif; ?>
