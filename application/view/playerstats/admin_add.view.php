<?php	if ( $messageType === 'text' ) : ?>
				<div class="div_text_feedback div_<?= $messageInfo ?>_message">
					<p><?= $messageText ?></p>
				</div>
<?php	endif; ?>
				<form class="form_standard" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
					<div class="div_input">
						<label for="input_playerstat_name" class="label_input"><span>Ben&auml;mning (ex l&auml;ngd, f&ouml;delseort)</span></label>
						<input name="playerdata[name]" value="<?= ( ( $id > 0 ) ? $psdata['Playerstat']['name'] : '' ) ?>" type="text" id="input_playerstat_name" />
						<input name="<?= ( ( $id > 0 ) ? 'playerdata' : 'junk' )?>[id]" value="<?= $id ?>" type="hidden" />
  				</div>
          <div class="div_input">
          	<label for="input_playerstat_order" class="label_input"><span>Sorteringsnummer</span></label>
						<input id="input_playerstat_order" name="<?= ( ( $id > 0 ) ? 'playerdata' : 'junk' )?>[ordernr]" value="<?= ( ( $id > 0 ) ? $psdata['Playerstat']['ordernr'] : '' ) ?>" type="text" />
					</div>
<?php	foreach ( $restrictions as $k => $restriction ) : ?>
					<div class="div_input">
						<label for="input_restriction_rest<?= $k ?>" class="label_input"><span><?= $restriction . ( ( $k == 2 ) ? ' (ex v&auml;rde1, v&auml;rde2)' : '' ) ?></span></label>
<?php		$counter = '';
			if ( $id > 0 && isset($psrdata) ) :
				if ( !empty($psrdata) ) :
					foreach ( $psrdata as $psr ) :
						if ( $psr['PlayerstatRestriction']['restriction'] == $k ) :
							$counter['value'] = $psr['PlayerstatRestriction']['value'];
							$counter['id']	  = $psr['PlayerstatRestriction']['id'];
						endif; 
					endforeach;
				endif;
			endif;
			if ( !empty($counter) ) : ?>
						<input name="restrictions[<?= $k ?>][value]" value="<?= $counter['value'] ?>" type="text" id="input_restriction_rest<?= $k ?>" />
						<input name="restrictions[<?= $k ?>][id]" value="<?= $counter['id'] ?>" type="hidden" />
<?php		else : ?>
						<input name="restrictions[<?= $k ?>][value]" value="" type="text" id="input_restriction_rest<?= $k ?>" />
<?php		endif; ?>
						<input name="restrictions[<?= $k ?>][restriction]" value="<?= $k ?>" type="hidden" />
					</div>				
<?php	endforeach; ?>
					<div class="div_select">
						<label for="input_playerstat_status" class="label_select"><span>Visas f&ouml;r</span></label>
						<select id="input_playerstat_status" name="playerdata[status]">
<?php	foreach ( $visibilities as $j => $visibility ) : ?>
							<option <?= ( ( $id > 0 ) ? ( ( $j == $psdata['Playerstat']['status'] ) ? 'selected="selected" ' : '' ) : '' ) ?>value="<?= $j ?>"><?= $visibility ?></option>
<?php	endforeach; ?>
						</select>
					</div>					
					<div>
<?php	if ( $messageType === 'popup' ) : ?>
						<input id="message_info" type="hidden" value="<?= $messageInfo ?>" />
						<input id="message_text" type="hidden" value="<?= $messageText ?>" />
<?php	endif; ?>						
						<input value="spara spelarinfo" type="submit" class="input_submit" />
					</div>
				</form>