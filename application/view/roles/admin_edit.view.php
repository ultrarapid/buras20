      <div class="admin_roles">
        <h3>Kopplade sektioner</h3>
        <div class="div-admin">
          <table class="table_admin_small table_admin_roles">
<?php $i = 0; ?>
<?php foreach ( $sections as $s ) : ?>
<?php   foreach ( $section_roles as $sr ) : ?>
<?php     if ( $s['Section']['id'] == $sr['SectionRole']['section_id'] ) : ?>
<?php       $i++; ?>
                <tr class="tr_roles_row tr_<?php echo ( ( ($i+1)%2 ) ? 'odd' : 'even' ) ?>">
                  <td class="section-name"><?= ( ( empty($s['Section']['adminname']) ) ? $s['Section']['name'] : $s['Section']['adminname'] ) ?></td>
                  <td class="section-del">
                    <a href="<?= Anchors::Refer('admin_role_delsection') . '/' . $sr['SectionRole']['id'] ?>">ta bort</a>
                  </td>
                </tr>
<?php     endif; ?>
<?php   endforeach; ?>
<?php endforeach; ?>
          </table>       
        </div>
        <h3>Ej kopplade sektioner</h3>      
        <div class="div-admin">
          <table class="table_admin_small table_admin_roles">
<?php $i = 0; ?>
<?php foreach ( $sections as $s ) : ?>
<?php   foreach ( $section_roles as $k => $sr ) : ?>
<?php     if ( $s['Section']['id'] == $sr['SectionRole']['section_id'] ) : ?>
<?php       break; ?>
<?php     endif; ?>
<?php     if ( $k == $section_size-1 ) : ?>
<?php       $i++; ?>
                <tr class="tr_roles_row tr_<?php echo ( ( ($i+1)%2 ) ? 'odd' : 'even' ) ?>">
                  <td class="section-name"><?= ( ( empty($s['Section']['adminname']) ) ? $s['Section']['name'] : $s['Section']['adminname'] ) ?></td>              
                  <td class="section-del">
                    <a href="<?= Anchors::Refer('admin_role_addsection') . '/' . $role['Role']['id'] . '/' . $s['Section']['id'] ?>">l&auml;gg till</a>
                  </td>
<?php     endif; ?>
<?php   endforeach; ?>
<?php   if ( $section_size == 0) : ?>
<?php     $i++; ?>
                <tr class="tr_roles_row tr_<?php echo ( ( ($i+1)%2 ) ? 'odd' : 'even' ) ?>">
                  <td class="section-name"><?= ( ( empty($s['Section']['adminname']) ) ? $s['Section']['name'] : $s['Section']['adminname'] ) ?></td>              
                  <td class="section-del">
                    <a href="<?= Anchors::Refer('admin_role_addsection') . '/' . $role['Role']['id'] . '/' . $s['Section']['id'] ?>">l&auml;gg till</a>
                  </td>
<?php   endif; ?>
<?php endforeach; ?>
          </table>       
        </div>
      </div>        