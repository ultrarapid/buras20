<?php	if ( $messageType === 'text' ) : ?>
        <div class="div_text_feedback div_<?= $messageInfo ?>_message">
          <p><?= $messageText ?></p>
        </div>
<?php	endif; ?>
        <form id="form_game" class="form_standard" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
          <div class="div_form_section div_form_section_one">
            <div class="div_input">
              <label class="label_input" for="input_game_opponent"><span>Motst&aring;ndare:</span></label>
              <input name="data[opponent]" type="text" id="input_game_opponent" value="<?= ( ( $id > 0 ) ? $gamedata['Game']['opponent'] : '' ) ?>" />
            </div>
            <div class="div_radio">
              <div class="div_radio_wrap">
                <input id="input_game_homegame" name="data[homegame]" type="radio" value="1" <?= ( ( $id > 0 ) ? ( ( $gamedata['Game']['homegame'] == 1 ) ? 'checked="checked"' : '' ) : '' ) ?>" />
                <label class="label_radio" for="input_game_homegame"><span>Hemmamatch</span></label>
              </div>
              <div class="div_radio_wrap">
                <input id="input_game_awaygame" name="data[homegame]" type="radio" value="0" <?= ( ( $id > 0 ) ? ( ( $gamedata['Game']['homegame'] == 0 ) ? 'checked="checked"' : '' ) : '' ) ?>" />
                <label class="label_radio" for="input_game_awaygame"><span>Bortamatch</span></label>
              </div>
            </div>
            <div class="div_input">
              <label class="label_input" for="input_game_location"><span>Hall:</span></label>
              <input name="data[location]" type="text" id="input_game_location" value="<?= ( ( $id > 0 ) ? $gamedata['Game']['location'] : '' ) ?>" />
            </div>
            <div class="div_input">
              <label class="label_input" for="input_game_date"><span>Matchdatum:</span></label>
              <input name="format[gamedate]" type="text" id="input_game_date" value="<?= ( ( $id > 0 ) ? substr($gamedata['Game']['gamedate'], 0, 10) : '' ) ?>" />
            </div>
            <div class="div_input">
              <label class="label_input" for="input_game_time"><span>Matchtid:</span></label>
              <input name="format[gametime]" type="text" id="input_game_time" value="<?= ( ( $id > 0 ) ? substr($gamedata['Game']['gamedate'], 11, 5) : '' ) ?>" />
            </div>
            <div class="div_input">
              <label class="label_input" for="input_game_gather"><span>Samlingstid:</span></label>
              <input name="data[gathertime]" type="text" id="input_game_gather" value="<?= ( ( $id > 0 ) ? substr($gamedata['Game']['gathertime'], 0, 5) : '' ) ?>" />
            </div>
<?php /*            
            <div class="div_input">
              <label class="label_input" for="input_game_ibfid"><span>IbfID:</span></label>
              <input name="data[ibfid]" type="text" id="input_game_ibfid" value="<?= ( ( $id > 0 ) ? $gamedata['Game']['ibfid'] : '' ) ?>" />
            </div>
						*/ ?>
<?php	if ( $id > 0 ) : ?>
            <div class="div_link">
              <a href="<?= Anchors::Refer('admin_gamesetups') . '/edit/' . $id ?>">Laguttagning</a>
            </div>
            <div class="div_link">
              <a href="<?= Anchors::Refer('admin_gameevents') . '/edit/' . $id ?>">Matchh&auml;ndelser</a>
            </div>
<?php 	endif; ?>            
          </div>
          <div class="div_form_section div_form_section_two">
            <p class="p_result"><?= ( ( $id > 0 ) ? ( ( $gamedata['Game']['homegame'] == 1 ) ? 'Bur&aring;s IK - ' . $gamedata['Game']['opponent'] : $gamedata['Game']['opponent'] . ' - Bur&aring;s IK' ) : '' )  ?></p>
            <div class="div_extra div_result">
              <label class="label_extra"><span>Resultat</span></label>
              <input type="hidden" id="ourscore" value="<?= ( ( $id > 0 ) ? $gamedata['Game']['ourscore'] : '0' ) ?>" />
              <input type="hidden" id="ourfirst" value="<?= ( ( $id > 0 ) ? $gamedata['Game']['ourfirst'] : '0' ) ?>" />
              <input type="hidden" id="oursecond" value="<?= ( ( $id > 0 ) ? $gamedata['Game']['oursecond'] : '0' ) ?>" />
              <input type="hidden" id="ourthird" value="<?= ( ( $id > 0 ) ? $gamedata['Game']['ourthird'] : '0' ) ?>" />
              <input type="hidden" id="theirscore" value="<?= ( ( $id > 0 ) ? $gamedata['Game']['theirscore'] : '0' ) ?>" />
              <input type="hidden" id="theirfirst" value="<?= ( ( $id > 0 ) ? $gamedata['Game']['theirfirst'] : '0' ) ?>" />
              <input type="hidden" id="theirsecond" value="<?= ( ( $id > 0 ) ? $gamedata['Game']['theirsecond'] : '0' ) ?>" />
              <input type="hidden" id="theirthird" value="<?= ( ( $id > 0 ) ? $gamedata['Game']['theirthird'] : '0' ) ?>" />
              <select name="rawdata[homescore]">
<?php for ( $i = 0 ; $i <= $max_game_goals ; $i++ ) : ?>
                <option<?= ( ( $id > 0 ) ? ( ( $gamedata['Game']['homegame'] == 1 ) ? ( ( $i == $gamedata['Game']['ourscore'] ) ? ' selected="selected"' : '' ) : ( ( $i == $gamedata['Game']['theirscore'] ) ? ' selected="selected"' : '' ) ) : '' ) ?> value="<?= $i ?>"><?= $i ?></option>
<?php endfor ?>
              </select>
              <span> - </span>
              <select name="rawdata[awayscore]">
<?php for ( $i = 0 ; $i <= $max_game_goals ; $i++ ) : ?>
                <option<?= ( ( $id > 0 ) ? ( ( $gamedata['Game']['homegame'] == 1 ) ? ( ( $i == $gamedata['Game']['theirscore'] ) ? ' selected="selected"' : '' ) : ( ( $i == $gamedata['Game']['ourscore'] ) ? ' selected="selected"' : '' ) ) : '' ) ?> value="<?= $i ?>"><?= $i ?></option>
<?php endfor ?>
              </select>
            </div>
            <div class="div_extra div_periodresult">
              <label class="label_extra"><span>Periodresultat</span></label>
              <select name="rawdata[homefirst]">
<?php for ( $i = 0 ; $i <= $max_period_goals ; $i++ ) : ?>
                <option<?= ( ( $id > 0 ) ? ( ( $gamedata['Game']['homegame'] == 1 ) ? ( ( $i == $gamedata['Game']['ourfirst'] ) ? ' selected="selected"' : '' ) : ( ( $i == $gamedata['Game']['theirfirst'] ) ? ' selected="selected"' : '' ) ) : '' ) ?> value="<?= $i ?>"><?= $i ?></option>
<?php endfor ?>
              </select>
              <span>-</span>
              <select name="rawdata[awayfirst]">
<?php for ( $i = 0 ; $i <= $max_period_goals ; $i++ ) : ?>
                <option<?= ( ( $id > 0 ) ? ( ( $gamedata['Game']['homegame'] == 1 ) ? ( ( $i == $gamedata['Game']['theirfirst'] ) ? ' selected="selected"' : '' ) : ( ( $i == $gamedata['Game']['ourfirst'] ) ? ' selected="selected"' : '' ) ) : '' ) ?> value="<?= $i ?>"><?= $i ?></option>
<?php endfor ?>
              </select>
              <span>, </span>
              <select name="rawdata[homesecond]">
<?php for ( $i = 0 ; $i <= $max_period_goals ; $i++ ) : ?>
                <option<?= ( ( $id > 0 ) ? ( ( $gamedata['Game']['homegame'] == 1 ) ? ( ( $i == $gamedata['Game']['oursecond'] ) ? ' selected="selected"' : '' ) : ( ( $i == $gamedata['Game']['theirsecond'] ) ? ' selected="selected"' : '' ) ) : '' ) ?> value="<?= $i ?>"><?= $i ?></option>
<?php endfor ?>
              </select>
              <span>-</span>
              <select name="rawdata[awaysecond]">
<?php for ( $i = 0 ; $i <= $max_period_goals ; $i++ ) : ?>
                <option<?= ( ( $id > 0 ) ? ( ( $gamedata['Game']['homegame'] == 1 ) ? ( ( $i == $gamedata['Game']['theirsecond'] ) ? ' selected="selected"' : '' ) : ( ( $i == $gamedata['Game']['oursecond'] ) ? ' selected="selected"' : '' ) ) : '' ) ?> value="<?= $i ?>"><?= $i ?></option>
<?php endfor ?>
              </select>
              <span>, </span>
              <select name="rawdata[homethird]">
<?php for ( $i = 0 ; $i <= $max_period_goals ; $i++ ) : ?>
                <option<?= ( ( $id > 0 ) ? ( ( $gamedata['Game']['homegame'] == 1 ) ? ( ( $i == $gamedata['Game']['ourthird'] ) ? ' selected="selected"' : '' ) : ( ( $i == $gamedata['Game']['theirthird'] ) ? ' selected="selected"' : '' ) ) : '' ) ?> value="<?= $i ?>"><?= $i ?></option>
<?php endfor ?>
              </select>
              <span>-</span>
              <select name="rawdata[awaythird]">
<?php for ( $i = 0 ; $i <= $max_period_goals ; $i++ ) : ?>
                <option<?= ( ( $id > 0 ) ? ( ( $gamedata['Game']['homegame'] == 1 ) ? ( ( $i == $gamedata['Game']['theirthird'] ) ? ' selected="selected"' : '' ) : ( ( $i == $gamedata['Game']['ourthird'] ) ? ' selected="selected"' : '' ) ) : '' ) ?> value="<?= $i ?>"><?= $i ?></option>
<?php endfor ?>
              </select>
            </div>
            <div class="div_select">
              <label class="label_select" for="input_gameformat"><span>Matchformat:</span></label>
              <select name="data[gameformat_id]" id="input_gameformat">
<?php	foreach ( $gameformats as $gf ) : ?>
                <option <?= ( ( $id > 0 ) ? ( ( $gamedata['Game']['gameformat_id'] == $gf['Gameformat']['id'] ) ? 'selected="selected" ' : '' ) : ( ( $gf['Gameformat']['id'] == $defaultFormat ) ? 'selected="selected" ' : '' ) ) ?>value="<?= $gf['Gameformat']['id']  ?>"><?= $gf['Gameformat']['name'] . '  [ ' . $gf['Gameformat']['periods'] . 'x' . $gf['Gameformat']['periodtime'] . ' ]' ?></option>
<?php	endforeach; ?>
              </select>
            </div>
            <div class="div_select">
              <label class="label_select" for="input_season"><span>S&auml;song:</span></label>
              <select name="data[season_id]" id="input_season">
<?php	foreach ( $seasons as $s ) : ?>
                <option <?= ( ( $id > 0 ) ? ( ( $gamedata['Game']['season_id'] == $s['Season']['id'] ) ? 'selected="selected" ' : '' ) : '' ) ?>value="<?= $s['Season']['id'] ?>"><?= substr($s['Season']['startdate'], 0, 4) . ' / ' . substr($s['Season']['enddate'], 0, 4) ?></option>
<?php	endforeach; ?>
              </select>
            </div>
<?php if ( $id == 0 ) : ?>
            <div class="div_select">
              <label class="label_select" for="input_season"><span>Lag:</span></label>
              <select name="data[team_id]" id="input_season">
<?php   foreach ( $allteams as $t ) : ?>
<?php     foreach ( $_SESSION['User']['teams'] as $ut ) : ?>
<?php       if ( $ut == $t['Team']['id'] ) : ?>
                <option <?= ( ( $id > 0 ) ? ( ( $gamedata['Game']['team_id'] == $t['Team']['id'] ) ? 'selected="selected" ' : '' ) : '' ) ?>value="<?= $t['Team']['id'] ?>"><?= $t['Team']['name'] ?></option>
<?php       endif; ?>
<?php     endforeach; ?>
<?php	  endforeach; ?>
              </select>
            </div>
<?php endif; ?>
            <div class="div_select">
              <label class="label_select" for="input_publich"><span>Match spelad (aktiverar matchdata):</span></label>
              <select name="data[publish]" id="input_publish">
                <option value="0"<?= ( ( $id > 0 ) ? ( ( $gamedata['Game']['publish'] == 0 ) ? ' selected="selected"' : '' ) : '' ) ?>>Ej spelad</option>
  <option value="1"<?= ( ( $id > 0 ) ? ( ( $gamedata['Game']['publish'] == 1 ) ? ' selected="selected"' : '' ) : '' ) ?>>Spelad</option>
              </select>
            </div>
            <input class="input_submit" value="spara match" type="submit" />
          </div>        
          <div class="div_textarea" style="margin-top:90px;">
            <label class="label_textarea" for="input_pregame"><span>Inf&ouml;rsnack:</span></label>
            <textarea name="data[pregame]" id="input_pregame" class="tinymce" cols="80" rows="22"><?= ( ( $id > 0 ) ? $gamedata['Game']['pregame'] : '' ) ?></textarea>
          </div>
          <div class="div_textarea">
            <label class="label_textarea" for="input_postgame"><span>Referat:</span></label>
            <textarea name="data[postgame]" id="input_postgame" class="tinymce" cols="80" rows="22"><?= ( ( $id > 0 ) ? $gamedata['Game']['postgame'] : '' ) ?></textarea>
          </div>
          <div>
            <input name="<?= ( ( $id > 0 && array_key_exists('id', $gamedata['Game']) ) ? 'data[id]' : 'id' ) ?>" type="hidden" value="<?= ( ( $id > 0 ) ? $gamedata['Game']['id'] : 0 ) ?>" />
<?php	if ( $messageType === 'popup' ) : ?>
            <input id="message_info" type="hidden" value="<?= $messageInfo ?>" />
            <input id="message_text" type="hidden" value="<?= $messageText ?>" />
<?php	endif; ?>
            <input class="input_submit" value="spara match" type="submit" />
          </div>
        </form>			
        <table class="table_admin_small">
<?php	foreach ( $games as $k => $game ) : ?>
          <tr class="tr_game_row tr_<?= ( ( ($k+1)%2 ) ? 'odd' : 'even' ) ?>">
            <td class="game_opponent td_info"><?= $game['Game']['opponent'] ?></td>
            <td class="game_date td_info"><?= $game['Game']['gamedate'] ?></td>
            <td class="game_edit td_edit"><a href="<?= Anchors::Refer('admin_games') . '/edit/' . $game['Game']['id'] ?>">editera</a></td>								
            <td class="game_delete td_delete"><a href="<?= Anchors::Refer('admin_games') . '/delete' . $game['Game']['id'] ?>">ta bort</a></td>
          </tr>
<?php	endforeach; ?>
<?php	if ( empty($games) ) : ?>
          <tr></tr>
<?php	endif; ?>
        </table>
