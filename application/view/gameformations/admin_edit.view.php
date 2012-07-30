<p><a href="<?= Anchors::Refer('admin_start') ?>">tillbaka</a></p>
<?php	if ( $messageType === 'text' ) : ?>
				<div class="div_text_feedback div_<?= $messageInfo ?>_message">
					<p><?= $messageText ?></p>
				</div>
<?php	endif; ?>
				<form class="form_standard" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
					<div class="div_select">
						<label for="input_formation" class="label_input">L&auml;gg till formation:</label>
						<select name="data[formation_id]" id="input_formation">
<?php	foreach ( $availableFormations as $af ) : ?>
							<option value="<?= $af['Formation']['id'] ?>"><?= $af['Formation']['name'] ?></option>
<?php	endforeach; ?>
						</select>				
					</div>
					<div class="div_submit">
						<input type="submit" value="L&auml;gg till formation" />
					</div>
				</form>
				<div class="div_gameformation_wrapper">
<?php	foreach ( $gameFormations as $gf ) : ?>
					<div class="div_gameformation">
						<h3><?= $gf['Formation']['name']; ?></h3>
<?php		foreach ( $formationRules as $fr ) : ?>
<?php			if ( $fr['FormationRestriction']['format_id'] == $gf['Gameformation']['formation_id'] ) :
					$fCounter = 0;
					$pid = array();
					while ( $fCounter < $fr['FormationRestriction']['amount'] ) : ?>
<?php					$pCounter = 0; ?>
						<h4><?= $fr['Position']['name'] ?></h4>
<?php					foreach ( $gamePositions as $gp ) : ?>
<?php						if ( $gp['GamePosition']['gameformation_id'] == $gf['Gameformation']['id'] && $fr['Position']['id'] == $gp['GamePosition']['position_id'] && !in_array($gp['GamePosition']['player_id'], $pid) ) : 
								$pid[] = $gp['GamePosition']['player_id']; ?>
						<p class="p_playername"><?= $gp['Player']['firstname'] . ' ' . $gp['Player']['lastname'] ?></p>
						<p class="p_playerdeletelink"><a href="<?= Anchors::Refer('admin_gameformations') . '/delete/' . $game_id . '/' . $gp['GamePosition']['id'] ?>">Ta bort</a></p>
<?php							$pCounter++; ?>
<?php							break 1; ?>							
<?php						endif; ?>
<?php					endforeach; ?>
<?php					if ( $pCounter == 0 ) : ?>
						<p class="p_playeraddlink"><a href="<?= Anchors::Refer('admin_gameformations') . '/add/' . $game_id . '/' . $gf['Gameformation']['id'] . '/' . $fr['FormationRestriction']['position_id'] ?>">L&auml;gg till spelare</a></p>
<?php					endif; ?>
<?php					$fCounter++; ?>
<?php				endwhile; ?>
<?php			endif; ?>
<?php		endforeach; ?>
					</div>
<?php	endforeach; ?>
				</div>
