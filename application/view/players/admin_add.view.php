<?php	if ( $messageType === 'text' ) : ?>
				<div class="div_text_feedback div_<?= $messageInfo ?>_message">
					<p><?= $messageText ?></p>
				</div>
<?php	endif; ?>
				<form class="form_standard" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post" enctype="multipart/form-data">
					<div class="div_input">
						<label for="input_player_firstname" class="label_input">F&ouml;rnamn:</label>
						<input name="data[firstname]" value="<?= ( ( $id > 0 ) ? $pdata['Player']['firstname'] : '' ) ?>" type="text" id="input_player_firstname" />
					</div>
					<div class="div_input">
						<label for="input_player_lastname" class="label_input">Efternamn:</label>
						<input name="data[lastname]" value="<?= ( ( $id > 0 ) ? $pdata['Player']['lastname'] : '' ) ?>" type="text" id="input_player_lastname" />
					</div>
          <div class="div_input">
            <label for="input_player_img" class="label_input">Spelarbild:</label>
            <input name="playerimg" type="file" id="input_player_img" size="2" />
          </div>
<?php	foreach ( $playerstats as $k => $playerstat ) : ?>
					<div class="div_input">
						<label for="input_playerstat_stat<?= $playerstat['Playerstat']['id'] ?>" class="label_input"><?= ucfirst($playerstat['Playerstat']['name']) ?>:</label>						
<?php		$counter = '';
			$select = false;
			if ( !empty($psvdata) ) :
				foreach ( $psvdata as $psv ) : 
					if ( $psv['PlayerStatValue']['playerstat_id'] == $playerstat['Playerstat']['id'] ) :
						$counter['value'] = $psv['PlayerStatValue']['value'];
						$counter['id']	  = $psv['PlayerStatValue']['id'];
					endif;
				endforeach;
			endif; 
			foreach ( $restrictiondata as $r ) :
			//print_r($r['PlayerstatRestriction']['playerstat_id'] . ' = ' . $playerstat['Playerstat']['id'] . ', ' . $r['PlayerstatRestriction']['playerstat_id'] . '......');
				if ( $r['PlayerstatRestriction']['playerstat_id'] == $playerstat['Playerstat']['id'] && $r['PlayerstatRestriction']['restriction'] == 2 ) :
					$options = explode(',', str_replace(', ', ',', $r['PlayerstatRestriction']['value'])); ?>
						<select name="psvdata[<?= $k ?>][value]" id="input_playerstat_stat<?= $playerstat['Playerstat']['id'] ?>">
<?php				foreach ( $options as $l => $option ) : ?>
							<option<?= ( ( !empty($counter) ) ? ( ( $counter['value'] == $option ) ? ' selected="selected"' : '' ) : ( ( $l == 0 ) ? ' selected="selected"' : '' ) ) ?> value="<?= $option ?>"><?= $option ?></option>
<?php				endforeach; ?>
						</select>
<?php				if ( !empty($counter) ) : ?>
						<input name="psvdata[<?= $k ?>][id]" type="hidden" value="<?= $counter['id'] ?>" />
<?php				endif;
					$counter = '';
					$select = true;
				endif; ?>
<?php		endforeach;		
			
			
			if ( !empty($counter) ) : ?>
						<input name="psvdata[<?= $k ?>][value]" id="input_playerstat_stat<?= $playerstat['Playerstat']['id'] ?>" value="<?= $counter['value'] ?>" />
						<input name="psvdata[<?= $k ?>][id]" type="hidden" value="<?= $counter['id'] ?>" />
<?php		else : 
				if ( !$select ) : ?>
						<input name="psvdata[<?= $k ?>][value]" id="input_playerstat_stat<?= $playerstat['Playerstat']['id'] ?>" value="" />
<?php			endif; 
			endif; ?>
						<input name="psvdata[<?= $k ?>][playerstat_id]" type="hidden" value="<?= $playerstat['Playerstat']['id']  ?>" />
					</div>
<?php	endforeach; ?>
					<div>
						<input name="<?= ( ( $id > 0 ) ? 'data[id]' : 'id' ) ?>" type="hidden" value="<?= ( ( $id > 0 ) ? $pdata['Player']['id'] : 0 ) ?>" />
<?php	if ( $messageType === 'popup' ) : ?>
						<input id="message_info" type="hidden" value="<?= $messageInfo ?>" />
						<input id="message_text" type="hidden" value="<?= $messageText ?>" />
<?php	endif; ?>
						<input value="spara spelare" type="submit" class="input_submit" />
					</div>
				</form>