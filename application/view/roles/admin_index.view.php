      <div class="div-admin">
        <table class="table_admin_small table_admin_roles">
<?php foreach ( $roles as $k => $r ) : ?>
          <tr class="tr_roles_row tr_<?php echo ( ( ($k+1)%2 ) ? 'odd' : 'even' ) ?>">
            <td class="td_rolename td_info"><?php echo $r['Role']['name'] ?></td>
            <td class="td_rolename td_info">
              <a href="<?= Anchors::Refer('admin_role_edit') . '/' . $r['Role']['id'] ?>">editera</a>
            </td>
          </tr>
<?php endforeach; ?>
        </table>
      </div>