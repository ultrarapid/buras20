<?php	if ( $messageType === 'text' ) : ?>
				<div class="div_text_feedback div_<?= $messageInfo ?>_message">
					<p><?= $messageText ?></p>
				</div>
<?php	endif; ?>
				<form class="form_standard" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
					<div class="div_select">
						<label for="input_stp_season" class="label_input">S&auml;song:</label>
						<select name="stpdata[season_id]" id="input_stp_season">
<?php	foreach ( $seasons as $season ) : ?>
							<option value="<?= $season['Season']['id'] ?>"<?= ( ( $season['Season']['id'] == $seasonid ) ? ' selected="selected"' : '' ) ?>><?= substr($season['Season']['startdate'], 0, 4) . ' / ' . substr($season['Season']['enddate'], 0, 4) ?></option>
<?php	endforeach; ?>
						</select>				
					</div>
					<div class="div_select">
						<label for="input_stp_team" class="label_input">Lag:</label>
						<select name="stpdata[team_id]" id="input_stp_team">
							<option value="<?= $team['Team']['id'] ?>" selected="selected"><?= $team['Team']['name'] ?></option>
						</select>				
					</div>
<?php 	if ( $seasonid > 0 && $team_id > 0 ) : ?>
					<div class="div_submit">
						<input type="submit" name="Byt" value="Byt lag / s&auml;song" class="input_submit" />
					</div>
					<h3>Spelars&ouml;k</h3>
					<div class="div_input">
						<label for="input_search_firstname" class="label_input">F&ouml;rnamn:</label>
						<input type="text" name="search[firstname]" id="input_search_firstname" value="<?= ( ( isset($filterFirstname) ) ? $filterFirstname : '' ) ?>" />
					</div>
					<div class="div_input">
						<label for="input_search_lastname" class="label_input">Efternamn:</label>
						<input type="text" name="search[lastname]" id="input_search_lastname" value="<?= ( ( isset($filterLastname) ) ? $filterLastname : '' ) ?>" />
					</div>
					<div class="div_select">
						<label for="input_search_stats" class="label_select">Info:</label>
						<select name="search[playerstat_id]" id="input_search_stats">
							<option value="0">-- V&auml;lj kategori --</option>
<?php		foreach ( $playerstats as $playerstat ) : ?>
							<option<?= ( ( isset($filterStat_id) ) ? ( ( $filterStat_id == $playerstat['Playerstat']['id'] ) ? ' selected="selected"' : '' ) : '' ) ?> value="<?= $playerstat['Playerstat']['id'] ?>"><?= $playerstat['Playerstat']['name'] ?></option>
<?php		endforeach; ?>
						</select>
					</div>
					<div class="div_input">
						<label for="input_search_value" class="label_input">V&auml;rde</label>
						<input type="text" name="search[value]" id="input_search_value" value="<?= ( ( isset($filterStatvalue) ) ? $filterStatvalue : '' ) ?>" />
					</div>
					<div class="div_submit">
						<input type="submit" name="Filter" value="Filtrera" class="input_submit" />
					</div>
				</form>
				<div class="div_playerlistwrapper">
					<div class="div_pickedplayerlist">
						<h3>Uttagna spelare</h3>
						<ul>					
<?php		foreach ( $players as $k => $player ) : ?>
							<li><?= $player['Player']['lastname'] . ', ' . $player['Player']['firstname'] ?> <a href="<?= Anchors::Refer('admin_seasonteamplayers') . '/delete/' . $seasonid . '/' . $team['Team']['id'] . '/' . $player['Seasonteamplayer']['id'] ?>">Ta bort</a></li>
<?php		endforeach; ?>
						</ul>
					</div>				
					<div class="div_filteredplayerlist">
						<h3>Spelare</h3>
						<ul>					
<?php		foreach ( $filterplayers as $j => $fplayer ) :
				if ( !in_array($fplayer['Player']['id'], $ids) ) : ?>
							<li><?= $fplayer['Player']['lastname'] . ', ' . $fplayer['Player']['firstname'] ?> <a href="<?= Anchors::Refer('admin_seasonteamplayers') . '/add/' . $seasonid . '/' . $team['Team']['id'] . '/' . $fplayer['Player']['id'] ?>">L&auml;gg till</a></li>
<?php			endif; ?>
<?php		endforeach; ?>
						</ul>
					</div>
					<div class="clear"></div>
				</div>
<?php	endif; ?>
