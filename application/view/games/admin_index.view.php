      <div class="div-admin">
        <form class="form_standard" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
          <div class="div_select">
            <label for="input_game" class="label_input">Match:</label>
            <select name="gdata[season_id]" id="input_game">
<?php foreach ( $seasons as $s ) : ?>
              <option value="<?= $s['Season']['id'] ?>"<?= $s['Season']['id'] == $season_id ? ' selected="selected"' : '' ?>><?= substr($s['Season']['startdate'], 0, 4) . ' / ' . substr($s['Season']['enddate'], 0, 4) ?></option>
<?php endforeach; ?>
            </select>       
          </div>
          <div class="div_submit">
            <input type="submit" name="Byt" value="Byt säsong" />
          </div>          
        </form>
        <table class="table_admin_small">
<?php	foreach ( $games as $k => $g ) : ?>
          <tr class="tr_game_row tr_<?= ( ( ($k+1)%2 ) ? 'odd' : 'even' ) ?>">
            <td class="game_opponent td_info"><?= $g['Game']['opponent'] . (( $g['Game']['homegame'] == 1 ) ? ' (hemma)' : ' (borta)') ?></td>
            <td class="game_date td_info"><?= $g['Game']['gamedate'] ?></td>
            <td class="game_result td_info"><?php echo ( ( $g['Game']['publish'] == 1 ) ? ( ( $g['Game']['homegame'] == 1 ) ? $g['Game']['ourscore'] . ' - ' . $g['Game']['theirscore'] : $g['Game']['theirscore'] . ' - ' . $g['Game']['ourscore'] ) : '' ) ?></td>
            <td class="game_edit td_edit"><a href="<?= Anchors::Refer('admin_games') . '/edit/' . $g['Game']['id'] ?>">editera</a></td>								
            <td class="game_delete td_delete"><a href="<?= Anchors::Refer('admin_games') . '/delete/' . $g['Game']['id'] ?>">ta bort</a></td>
          </tr>
<?php	endforeach; ?>
<?php	if ( empty($games) ) : ?>
          <tr></tr>
<?php	endif; ?>
        </table>
        <p><a href="<?= Anchors::Refer('admin_games') . '/getschedule/' . $team_id ?>">h&auml;mta spelschema  fr&aring;n innebandy.se</a></p>         
      </div>