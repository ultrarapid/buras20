          <div class="page-content">
<?php if ( !empty($games) ) : ?>
<?php   if ( $gameDetails == 0 ) : ?>
            <table class="game-status">
              <thead>
                <td>Datum</td>
                <td>Lag</td>
                <td>Resultat</td>
                <td>Matchinfo</td>
              </thead>
<?php	    foreach ( $games as $g ) : ?>
              <tr>
                <td><?= substr($g['Game']['gamedate'], 0, 10) ?></td>
                <td><?= ( ( $g['Game']['homegame'] == 1 ) ? 'Bur&aring;s IK - ' . $g['Game']['opponent'] : $g['Game']['opponent'] . ' - Bur&aring;s IK' ) ?></td>
                <td class="game-score"><?= ( ( $g['Game']['publish'] == 1 ) ? ( ( $g['Game']['homegame'] == 1 ) ? $g['Game']['ourscore'] . ' - ' . $g['Game']['theirscore'] : $g['Game']['theirscore'] . ' - ' . $g['Game']['ourscore'] ) : '' ) ?></td>
                <td>
                  <a href="<?= Anchors::Refer('base') . '/' . $section['Section']['url'] . '/' . substr($g['Season']['startdate'], 0, 4) . '-' . substr($g['Season']['enddate'], 0, 4) . '/' . $g['Gameformat']['slug'] . '/' . $g['Game']['slug']  ?>.html">matchinfo</a>
                </td>
              </tr>
<?php	    endforeach ; ?>
            </table>
<?php   elseif ( $gameDetails == 1 ) : ?>
            <div class="game-details">
              <p class="game-details-score"><?= ( ( $games['Game']['publish'] == 1 ) ? ( ( $games['Game']['homegame'] == 1 ) ? 'Bur&aring;s IK - ' . $games['Game']['opponent'] . ' ' . $games['Game']['ourscore'] . ' - ' . $games['Game']['theirscore'] . ' (' . $games['Game']['ourfirst'] . '-' . $games['Game']['theirfirst'] . ', ' . $games['Game']['oursecond'] . '-' . $games['Game']['theirsecond'] . ', ' . $games['Game']['ourthird'] . '-' . $games['Game']['theirthird'] . ')' : $games['Game']['opponent'] . ' - Bur&aring;s IK ' . $games['Game']['theirscore'] . ' - ' . $games['Game']['ourscore'] . ' (' . $games['Game']['theirfirst'] . '-' . $games['Game']['ourfirst'] . ', ' . $games['Game']['theirsecond'] . '-' . $games['Game']['oursecond'] . ', ' . $games['Game']['theirthird'] . '-' . $games['Game']['ourthird'] . ')' ) : '' ) ?></p>
              <div class="game-details-postgame">
<?= Formatter::ConvertLinebreaksToBr(Formatter::ConvertLocalLinks($games['Game']['postgame'])) ?>

              </div>
            </div>
<?php   endif; ?>
<?php else : ?>
            <div>
              <p>Inga matcher i angiven säsong</p>
            </div>
<?php endif; ?>
<?php if ( !empty($seasons) ) : ?>
            <nav class="season-nav">
              <ul>
<?php   foreach ( $seasons as $s ) : ?>
                <li>
                  <a<?= $s['Season']['id'] == $thisSeasonID ? ' class="active"' : ''  ?> href="<?= '/' . $activeSection['Section']['url'] . '/' . substr($s['Season']['startdate'], 0, 4) . '-' . substr($s['Season']['enddate'], 0, 4) . '.html' ?>"><?= substr($s['Season']['startdate'], 2, 2) . '/' . substr($s['Season']['enddate'], 2, 2)  ?></a>
                </li>
<?php   endforeach; ?>
              </ul>
            </nav>
<?php endif; ?>
          </div>
