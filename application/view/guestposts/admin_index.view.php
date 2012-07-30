      <div class="div-admin">
        <table class="table_admin_small table_admin_guestposts">
<?php foreach ( $gposts as $k => $gp ) : ?>
          <tr class="tr_gpost_row tr_<?php echo ( ( ($k+1)%2 ) ? 'odd' : 'even' ) ?>">
            <td class="gpost_author td_info"><?php echo $gp['Guestpost']['name'] ?></td>
            <td class="gpost_date td_info"><?php echo Formatter::ReadableDate($gp['Guestpost']['created']) . ' ' . substr($gp['Guestpost']['created'], 11, 5) ?></td>
            <td class="gpost_post td_info"><?php echo substr($gp['Guestpost']['body'], 0, 50) . ( ( strlen($gp['Guestpost']['body']) > 50 ) ? '...' : '' ) ?></td>
            <td class="game_delete td_delete"><a class="delete_link" href="<?php echo Anchors::Refer('admin_guestposts_delete') . '/' . $gp['Guestpost']['id'] ?>">ta bort</a></td>                      
          </tr>
<?php endforeach; ?>
<?php	if ( empty($gposts) ) : ?>
          <tr></tr>
<?php	endif; ?>
        </table>
      </div>