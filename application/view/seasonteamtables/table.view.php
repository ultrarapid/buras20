          <div class="div-table">
<?php if ( !empty($tableRows) ) : ?>
            <table class="table-standings">
              <thead>
                <tr>
                  <th></th>
                  <th class="th-team">Lag</th>
                  <th>M</th>
                  <th>V</th>
                  <th>O</th>
                  <th>F</th>
                  <th>+</th>
                  <th>-</th>
                  <th>S</th>
                  <th>P</th>
                </tr>
              </thead>
              <tbody>
<?php   foreach ( $tableRows as $r ) : ?>
                <tr<?= ( ( $r['position'] % 2 ) ? ' class="tr-odd"' : ' class="tr-even"' ) ?>>
                  <td><?= $r['position'] ?></td>
                  <td class="td-team"><?= $r['team'] ?></td>
                  <td><?= $r['matches'] ?></td>
                  <td><?= $r['victories'] ?></td>
                  <td><?= $r['draws'] ?></td>
                  <td><?= $r['defeats'] ?></td>
                  <td><?= $r['plusgoals'] ?></td>
                  <td><?= $r['minusgoals'] ?></td>
                  <td><?= (intval($r['plusgoals'])-intval($r['minusgoals'])) ?></td>        
                  <td><?= $r['points'] ?></td>
                </tr>
<?php   endforeach; ?>
              </tbody>
            </table>
<?php else : ?>
            <div>
              <p>Ingen tabell för angiven säsong</p>
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
