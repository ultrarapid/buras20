        <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
        	<div class="div_radio left div_contact_radios">
            <input type="radio" name="team_id" id="team0" value="0"<?php echo (( $team_id == 0 ) ? ' checked="checked"' : '') ?> /><label for="team0">Alla</label>
<?php foreach ( $teams as $t ) : ?>
            <input type="radio" name="team_id" id="team<?php echo $t['Team']['id'] ?>"<?php echo (( $team_id == $t['Team']['id'] ) ? ' checked="checked"' : '') ?> value="<?php echo $t['Team']['id'] ?>" /><label for="team<?php echo $t['Team']['id'] ?>"><?php echo $t['Team']['name'] ?></label>
<?php endforeach; ?>
          </div>
        	<div class="div_input left div_contact_dropdown">
          	<select name="season_id">
            	<option value="0"<?php echo (( $season_id == 0 ) ? ' selected="selected"' : '') ?>>Alla</option>
<?php foreach ( $seasons as $s ) : ?>           
            	<option<?php echo (( $season_id == $s['Season']['id'] ) ? ' selected="selected"' : '') ?> value="<?php echo $s['Season']['id'] ?>"><?php echo substr($s['Season']['startdate'], 0, 4) . ' / ' . substr($s['Season']['enddate'], 0, 4) ?></option>
<?php endforeach; ?>              
            	<option value="2">2010 / 2012</option>                            
            </select>
          </div>
          <div>
	          <input class="input_submit input_contact_submit" type="submit" value="visa" />
          </div>
        </form>
        <table class="table_admin_small">
<?php if ( !empty($players) ) : ?>
					<thead>
          	<tr>
            	<td></td>
              <td>Namn</td>
              <td>Telefon</td>
              <td>Epost</td>
            </tr>
          </thead>
<?php endif; ?>
<?php foreach ( $players as $k => $player ) : ?>
<?php   $myEmail = $myPhone = ''; ?>
          <tr class="tr_player_row tr_<?= ( ( ($k+1)%2 ) ? 'odd' : 'even' ) ?>">
            <td class="td_player_img"><?php echo ( ( !empty($player['Player']['imgsrc']) ) ? '<img src="' . Anchors::Refer('playerimage_folder') . '/' . $player['Player']['imgsrc'] . '_tn.jpg" />' : '<span class="image_replacer"></span>' ) ?></td>
            <td class="td_player_name td_info"><?= $player['Player']['firstname'] . ' ' . $player['Player']['lastname'] ?></td>
<?php   foreach ( $player['PlayerStatValue'] as $psv ) : ?>
<?php 		//echo print_r($ps['Playerstat']['name']); ?>
<?php     if ( $psv['PlayerStatValue']['playerstat_id'] == 82 ) : ?>
<?php       $myEmail = $psv['PlayerStatValue']['value']; ?>
<?php     endif; ?>
<?php     if ( $psv['PlayerStatValue']['playerstat_id'] == 83 ) : ?>
<?php       $myPhone = $psv['PlayerStatValue']['value']; ?>
<?php     endif; ?>
<?php   endforeach; ?>
            <td class="td_info"><?php echo $myEmail ?></td>
            <td class="td_info"><?php echo $myPhone ?></td>
</tr>
<?php	endforeach; ?>
<?php	if ( empty($players) ) : ?>
          <tr></tr>
<?php	endif; ?>
        </table>
				
