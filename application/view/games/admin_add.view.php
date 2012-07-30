<?php	if ( $messageType === 'text' ) : ?>
        <div class="div_text_feedback div_<?= $messageInfo ?>_message">
          <p><?= $messageText ?></p>
        </div>
<?php	endif; ?>
        <form id="form_game" class="form_standard" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
          <div class="div_form_section div_form_section_one">
            <div class="div_input">
              <label class="label_input" for="input_game_opponent"><span>Motst&aring;ndare:</span></label>
              <input name="data[opponent]" type="text" id="input_game_opponent" value="" />
            </div>
            <div class="div_radio">
              <div class="div_radio_wrap">
                <input id="input_game_homegame" name="data[homegame]" type="radio" checked="checked" />
                <label class="label_radio" for="input_game_homegame"><span>Hemmamatch</span></label>
              </div>
              <div class="div_radio_wrap">
                <input id="input_game_awaygame" name="data[homegame]" type="radio" value="0" />
                <label class="label_radio" for="input_game_awaygame"><span>Bortamatch</span></label>
              </div>
            </div>
            <div class="div_input">
              <label class="label_input" for="input_game_location"><span>Hall:</span></label>
              <input name="data[location]" type="text" id="input_game_location" value="" />
            </div>
            <div class="div_input">
              <label class="label_input" for="input_game_date"><span>Matchdatum:</span></label>
              <input name="format[gamedate]" type="text" id="input_game_date" value="" />
            </div>
            <div class="div_input">
              <label class="label_input" for="input_game_time"><span>Matchtid:</span></label>
              <input name="format[gametime]" type="text" id="input_game_time" value="" />
            </div>
            <div class="div_input">
              <label class="label_input" for="input_game_gather"><span>Samlingstid:</span></label>
              <input name="data[gathertime]" type="text" id="input_game_gather" value="" />
            </div>         
          </div>
          <div class="div_form_section div_form_section_two">
            <div class="div_extra div_result">
              <label class="label_extra"><span>Resultat</span></label>
              <input type="hidden" id="ourscore" value="" />
              <input type="hidden" id="ourfirst" value="" />
              <input type="hidden" id="oursecond" value="" />
              <input type="hidden" id="ourthird" value="" />
              <input type="hidden" id="theirscore" value="" />
              <input type="hidden" id="theirfirst" value="" />
              <input type="hidden" id="theirsecond" value="" />
              <input type="hidden" id="theirthird" value="" />
              <select name="rawdata[homescore]">
<?php for ( $i = 0 ; $i <= $max_game_goals ; $i++ ) : ?>
                <option value="<?= $i ?>"><?= $i ?></option>
<?php endfor ?>
              </select>
              <span> - </span>
              <select name="rawdata[awayscore]">
<?php for ( $i = 0 ; $i <= $max_game_goals ; $i++ ) : ?>
                <option value="<?= $i ?>"><?= $i ?></option>
<?php endfor ?>
              </select>
            </div>
            <div class="div_extra div_periodresult">
              <label class="label_extra"><span>Periodresultat</span></label>
              <select name="rawdata[homefirst]">
<?php for ( $i = 0 ; $i <= $max_period_goals ; $i++ ) : ?>
                <option value="<?= $i ?>"><?= $i ?></option>
<?php endfor ?>
              </select>
              <span>-</span>
              <select name="rawdata[awayfirst]">
<?php for ( $i = 0 ; $i <= $max_period_goals ; $i++ ) : ?>
                <option value="<?= $i ?>"><?= $i ?></option>
<?php endfor ?>
              </select>
              <span>, </span>
              <select name="rawdata[homesecond]">
<?php for ( $i = 0 ; $i <= $max_period_goals ; $i++ ) : ?>
                <option value="<?= $i ?>"><?= $i ?></option>
<?php endfor ?>
              </select>
              <span>-</span>
              <select name="rawdata[awaysecond]">
<?php for ( $i = 0 ; $i <= $max_period_goals ; $i++ ) : ?>
                <option value="<?= $i ?>"><?= $i ?></option>
<?php endfor ?>
              </select>
              <span>, </span>
              <select name="rawdata[homethird]">
<?php for ( $i = 0 ; $i <= $max_period_goals ; $i++ ) : ?>
                <option value="<?= $i ?>"><?= $i ?></option>
<?php endfor ?>
              </select>
              <span>-</span>
              <select name="rawdata[awaythird]">
<?php for ( $i = 0 ; $i <= $max_period_goals ; $i++ ) : ?>
                <option value="<?= $i ?>"><?= $i ?></option>
<?php endfor ?>
              </select>
            </div>
            <div class="div_select">
              <label class="label_select" for="input_gameformat"><span>Matchformat:</span></label>
              <select name="data[gameformat_id]" id="input_gameformat">
<?php	foreach ( $gameformats as $gf ) : ?>
                <option value="<?= $gf['Gameformat']['id']  ?>"<?php echo ( ( $gf['Gameformat']['id'] == $defaultFormat ) ? ' selected="selected"' : '' ) ?>><?= $gf['Gameformat']['name'] . '  [ ' . $gf['Gameformat']['periods'] . 'x' . $gf['Gameformat']['periodtime'] . ' ]' ?></option>
<?php	endforeach; ?>
              </select>
            </div>
            <div class="div_select">
              <label class="label_select" for="input_season"><span>S&auml;song:</span></label>
              <select name="data[season_id]" id="input_season">
<?php	foreach ( $seasons as $s ) : ?>
                <option value="<?= $s['Season']['id'] ?>"><?= substr($s['Season']['startdate'], 0, 4) . ' / ' . substr($s['Season']['enddate'], 0, 4) ?></option>
<?php	endforeach; ?>
              </select>
            </div>
            <div class="div_select">
              <label class="label_select" for="input_season"><span>Lag:</span></label>
              <select name="data[team_id]" id="input_season">
<?php   foreach ( $allteams as $t ) : ?>
<?php     foreach ( $_SESSION['User']['teams'] as $ut ) : ?>
<?php       if ( ($ut == $t['Team']['id'] && $team_id == 0) || ($ut == $t['Team']['id'] && $ut == $team_id && $team_id > 0) ) : ?>
                <option value="<?= $t['Team']['id'] ?>"><?= $t['Team']['name'] ?></option>
<?php       endif; ?>
<?php     endforeach; ?>
<?php	  endforeach; ?>
              </select>
            </div>
            <div class="div_select">
              <label class="label_select" for="input_publich"><span>Match spelad (aktiverar matchdata):</span></label>
              <select name="data[publish]" id="input_publish">
                <option value="0" selected="selected">Ej spelad</option>
                <option value="1">Spelad</option>
              </select>
            </div>
            <input class="input_submit" value="spara match" type="submit" />        
          </div>
          <div class="div_textarea">
            <label class="label_textarea" for="input_pregame"><span>Inf&ouml;rsnack:</span></label>
            <textarea name="data[pregame]" id="input_pregame" cols="60" rows="12"></textarea>
          </div>
          <div class="div_textarea">
            <label class="label_textarea" for="input_postgame"><span>Referat:</span></label>
            <textarea name="data[postgame]" id="input_postgame" cols="60" rows="12"></textarea>
          </div>
          <div>
<?php	if ( $messageType === 'popup' ) : ?>
            <input id="message_info" type="hidden" value="<?= $messageInfo ?>" />
            <input id="message_text" type="hidden" value="<?= $messageText ?>" />
<?php	endif; ?>
            <input class="input_submit" value="spara match" type="submit" />
          </div>
        </form>
