      <div class="div-admin">
        <table class="table_admin_small table_admin_users">
<?php foreach ( $users as $k => $u ) : ?>
          <tr class="tr_users_row tr_<?php echo ( ( ($k+1)%2 ) ? 'odd' : 'even' ) ?>">
            <td class="td_username td_info"><?php echo $u['User']['username'] ?></td>
            <td class="td_username td_info">
              <a href="<?= Anchors::Refer('admin_user_editrole') . '/' . $u['User']['id'] ?>">&auml;ndra</a>
            </td>
          </tr>
<?php endforeach; ?>
        </table>
      </div>