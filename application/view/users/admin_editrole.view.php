        <div class="div-admin">
          <div class="div-user">
            <ul class="ul-active-role">
<?php foreach ( $roles as $r ) : ?>
<?php   foreach ( $userRoles as $ur ) : ?>
<?php     if ( $r['Role']['id'] == $ur['UserRole']['role_id'] ) : ?>
              <li>
                <span class="role-name"><?= $r['Role']['id'] . ' ' . $r['Role']['name'] ?></span>
                <span class="role-del"><a href="<?= Anchors::Refer('admin_user_delrole') . '/' . $ur['UserRole']['id'] ?>">ta bort</a></span>
              </li>
<?php     endif; ?>
<?php   endforeach; ?>
<?php endforeach; ?>
            </ul>
            <ul class="ul-inactive-role">
<?php foreach ( $roles as $r ) : ?>
<?php   foreach ( $userRoles as $k => $ur ) : ?>
<?php     if ( $r['Role']['id'] == $ur['UserRole']['role_id'] ) : ?>
<?php       break; ?>
<?php     endif; ?>
<?php     if ( $k == $roleSize-1 ) : ?>
              <li>
                <span class="role-name"><?= $r['Role']['id'] . ' ' . $r['Role']['name'] ?></span>
                <span class="role-add"><a href="<?= Anchors::Refer('admin_user_addrole') . '/' . $id . '/' . $r['Role']['id'] ?>">l&auml;gg till</a></span>
              </li>
<?php     endif; ?>
<?php   endforeach; ?>
<?php   if ( $roleSize == 0) : ?>
              <li>
                <span class="role-name"><?= $r['Role']['id'] . ' ' . $r['Role']['name'] ?></span>
                <span class="role-add"><a href="<?= Anchors::Refer('admin_user_addrole') . '/' . $id . '/' . $r['Role']['id'] ?>">l&auml;gg till</a></span>
              </li>
<?php   endif; ?>
<?php endforeach; ?>
            </ul>
            
            <ul class="ul-active-team">
<?php foreach ( $teams as $t ) : ?>
<?php   foreach ( $userTeams as $ut ) : ?>
<?php     if ( $t['Team']['id'] == $ut['UserTeam']['team_id'] ) : ?>
              <li>
                <span class="team-name"><?= $t['Team']['id'] . ' ' . $t['Team']['name'] ?></span>
                <span class="team-del"><a href="<?= Anchors::Refer('admin_user_delteam') . '/' . $ut['UserTeam']['id'] ?>">ta bort</a></span>
              </li>
<?php     endif; ?>
<?php   endforeach; ?>
<?php endforeach; ?>
            </ul>
            <ul class="ul-inactive-team">
<?php foreach ( $teams as $t ) : ?>
<?php   foreach ( $userTeams as $k => $ut ) : ?>
<?php     if ( $t['Team']['id'] == $ut['UserTeam']['team_id'] ) : ?>
<?php       break; ?>
<?php     endif; ?>
<?php     if ( $k == $teamSize-1 ) : ?>
              <li>
                <span class="team-name"><?= $t['Team']['id'] . ' ' . $t['Team']['name'] ?></span>
                <span class="team-add"><a href="<?= Anchors::Refer('admin_user_addteam') . '/' . $id . '/' . $t['Team']['id'] ?>">l&auml;gg till</a></span>
              </li>
<?php     endif; ?>
<?php   endforeach; ?>
<?php   if ( $teamSize == 0) : ?>
              <li>
                <span class="team-name"><?= $t['Team']['id'] . ' ' . $t['Team']['name'] ?></span>
                <span class="team-add"><a href="<?= Anchors::Refer('admin_user_addteam') . '/' . $id . '/' . $t['Team']['id'] ?>">l&auml;gg till</a></span>
              </li>
<?php   endif; ?>
<?php endforeach; ?>
            </ul>
            
            
          </div>
        </div>
