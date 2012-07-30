      <p><a href="<?= Anchors::Refer('admin_games') . '/getschedule/' . $team_id ?>">h&auml;mta spelschema fr&aring;n innebandy.se</a></p>
      <div class="div-admin">
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
      </div>