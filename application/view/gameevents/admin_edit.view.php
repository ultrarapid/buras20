<?php $ourScore = $theirScore = 0; ?>
<?php	if ( $messageType === 'text' ) : ?>
				<div class="div_text_feedback div_<?= $messageInfo ?>_message">
					<p><?= $messageText ?></p>
				</div>
<?php	endif; ?>
				<form class="form_standard" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
          <div class="div_select">
						<label for="input_game" class="label_input">Händelse:</label>
						<select name="gsdata[event_id]" id="input_game">
<?php	foreach ( $gameevent_types_flipped as $key => $val ) : ?>
							<option value="<?= $val ?>"><?= $key ?></option>
<?php	endforeach; ?>
						</select>	
					</div>
        </form>
<?php if ( isset($gameevents) && !empty($gameevents) ) : ?>
          <table class="game_events">
            <thead>
              <tr>
                <td>Tid</td>
                <td>Händelse</td>
                <td>Lag</td>
                <td>Spelare</td>
                <td>Assist</td>
                <td>Info</td>
              </tr>
            </thead>
            <tbody>
<?php   foreach ( $gameevents as $ge ) : ?>
<?php     if ( $ge['Gameevent']['eventtype'] == 1 ) : ?>
<?php       $ge['Gameevent']['thisteam'] == 1 ? $ourScore++ : $theirScore++; ?>
<?php     endif; ?>
<?php     //print_r($ge); ?>
              <tr>
                <td><?= $ge['Gameevent']['time'] ?></td>
                <td><?= ( $ge['Gameevent']['eventtype'] == 1 ? $ourScore . ' - ' . $theirScore . ' ' : '' ) . $gameevent_types[$ge['Gameevent']['eventtype']] ?></td>
                <td><?= $ge['Gameevent']['thisteam'] ? $thisTeam : $game['Game']['opponent'] ?>
                <td><?= $ge['Player']['firstname'] . ' ' . $ge['Player']['lastname'] ?></td>
                <td><?= $ge['player2']['firstname'] . ' ' . $ge['player2']['lastname']?></td>
<?php     if ( $ge['Gameevent']['eventtype'] == 1 && ( $ge['Gameevent']['ourplayers'] != 5 || $ge['Gameevent']['theirplayers'] != 5 ) ) : ?>
<?php       if ( $ge['Gameevent']['thisteam'] == 1 ) : ?>
<?php         if ( $ge['Gameevent']['ourplayers'] > $ge['Gameevent']['theirplayers'] ) : ?>
                <td>Powerplay (<?= $ge['Gameevent']['ourplayers'] . ' mot ' . $ge['Gameevent']['theirplayers'] ?>)</td>
<?php         elseif ( $ge['Gameevent']['ourplayers'] < $ge['Gameevent']['theirplayers'] ) : ?>
                <td>Boxplay (<?= $ge['Gameevent']['ourplayers'] . ' mot ' . $ge['Gameevent']['theirplayers'] ?>)</td>
<?php         else : ?>
                <td>Spel <?= $ge['Gameevent']['ourplayers'] . ' mot ' . $ge['Gameevent']['theirplayers'] ?></td>
<?php         endif; ?>
<?php       elseif ( $ge['Gameevent']['thisteam'] == 0 ) : ?>
<?php         if ( $ge['Gameevent']['theirplayers'] > $ge['Gameevent']['ourplayers'] ) : ?>
                <td>Powerplay (<?= $ge['Gameevent']['theirplayers'] . ' mot ' . $ge['Gameevent']['ourplayers'] ?>)</td>
<?php         elseif ( $ge['Gameevent']['theirplayers'] < $ge['Gameevent']['ourplayers'] ) : ?>
                <td>Boxplay (<?= $ge['Gameevent']['theirplayers'] . ' mot ' . $ge['Gameevent']['ourplayers'] ?>)</td>
<?php         else : ?>
                <td>Spel <?= $ge['Gameevent']['theirplayers'] . ' mot ' . $ge['Gameevent']['ourplayers'] ?></td>
<?php         endif; ?>
<?php       endif; ?>
<?php     elseif ( $ge['Gameevent']['eventtype'] == 2 ) : ?>
                <td><?= $penaltyCodes[$ge['Gameevent']['code']] ?></td>
<?php     else : ?>
                <td></td>
<?php     endif; ?>
              </tr>
<?php   endforeach; ?>  
            </tbody>
          </table>
<?php endif; ?>
<?php if ( isset($gibf_events) && !empty($gibf_events) ) : ?>
  			<form class="form_standard" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
        	<table class="gibf_events">
          	<thead>
            	<tr>
              	<td>Tid</td>
                <td>Händelse</td>
                <td>Info</td>
                <td>Spelare</td>
                <td>Assist</td>
                <td>Lag</td>
              </tr>
            </thead>
            <tbody>
<?php   foreach ( $gibf_events as $k => $e ) : ?>
              <tr>
                <td>
                  <span><?= $e['time'] ?></span>
                  <input type="hidden" value="<?= '00:' . $e['time']?>" name="data[<?= $k ?>][time]" />
                </td>
                <td>	
                  <span><?= $e['eventName'] ?></span>
                  <input type="hidden" value="<?= $gameevent_types_flipped[$e['eventName']] ?>" name="data[<?= $k ?>][eventtype]" />                  
                </td>
<?php     if ( array_key_exists('goal', $e) ) : ?>
                <td><?= $e['goal_scoreboard'] ?></td>
                <td>
                  <span><?= $e['goal_primary_name'] ?></span>
                  <input type="hidden" value="<?= $e['goal_primary_id'] ?>" name="ctrl[<?= $k ?>][primaryplayer_id]" />
                  <input type="hidden" value="<?= $e['goal_primary_name'] ?>" name="ctrl[<?= $k ?>][primaryplayer_name]" />
<?php       if ( array_key_exists('goal_secondary_name', $e) ) : ?>
                  <input type="hidden" value="<?= $e['goal_secondary_id'] ?>" name="ctrl[<?= $k ?>][secondaryplayer_id]" />
                  <input type="hidden" value="<?= $e['goal_secondary_name'] ?>" name="ctrl[<?= $k ?>][secondaryplayer_name]" />
<?php       endif; ?>                  
                </td>
                <td><?= ( ( array_key_exists('goal_secondary_name', $e) ) ? $e['goal_secondary_name'] : '' ) ?></td>
<?php     elseif ( array_key_exists('penalty', $e) ): ?>
                <td><?= $e['penalty_name'] ?></td>
                <td><?= $e['penalty_player_name'] ?></td>
                <td>
                  <input type="hidden" value="<?= $e['penalty_code'] ?>" name="data[<?= $k ?>][code]" />
                  <input type="hidden" value="<?= $e['penalty_player_id'] ?>" name="ctrl[<?= $k ?>][penalty_player_id]" />
                  <input type="hidden" value="<?= $e['penalty_player_name'] ?>" name="ctrl[<?= $k ?>][penalty_player_name]" />
                  <input type="hidden" value="<?= $e['penalty_time_team'] ?>" name="ctrl[<?= $k ?>][teamtime]" />
                  <input type="hidden" value="<?= $e['penalty_time_player'] ?>" name="ctrl[<?= $k ?>][playertime]" />                
                </td>
<?php     else: ?>
                <td></td>
                <td></td>
                <td></td>                                
<?php     endif; ?>
                <td>
                  <span><?= $e['team'] ?></span>
                  <input type="hidden" value="<?= ( ( $e['team'] == $thisTeam ) ? 1 : 0 ) ?>" name="data[<?= $k ?>][thisteam]" />             
                </td>
              </tr>
<?php   endforeach; ?>
<tr><td> <input type="hidden" name="form_id" value="2" /><input type="submit" value="Spara matchdata" /></td></tr>
            </tbody>
          </table>
        </form>
<?php else: ?>
				<a href="<?= Anchors::Refer('admin_gameevents') . '/edit/' . $game['Game']['id'] . '/1' ?>">Hämta från innebandy.se</a>
<?php endif; ?>