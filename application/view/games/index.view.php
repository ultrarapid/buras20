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
              <h2 class="game-details-scoreboard"><span class="game-details-teams"><?= ( ( $games['Game']['publish'] == 1 ) ? ( ( $games['Game']['homegame'] == 1 ) ? 'Bur&aring;s IK - ' . $games['Game']['opponent'] . '</span> <span class="game-details-result">' . $games['Game']['ourscore'] . ' - ' . $games['Game']['theirscore'] . ' (' . $games['Game']['ourfirst'] . '-' . $games['Game']['theirfirst'] . ', ' . $games['Game']['oursecond'] . '-' . $games['Game']['theirsecond'] . ', ' . $games['Game']['ourthird'] . '-' . $games['Game']['theirthird'] . ')' : $games['Game']['opponent'] . ' - Bur&aring;s IK ' . $games['Game']['theirscore'] . ' - ' . $games['Game']['ourscore'] . ' (' . $games['Game']['theirfirst'] . '-' . $games['Game']['ourfirst'] . ', ' . $games['Game']['theirsecond'] . '-' . $games['Game']['oursecond'] . ', ' . $games['Game']['theirthird'] . '-' . $games['Game']['ourthird'] . ')' ) : '' ) ?></span></h2>
              <div class="game-details-postgame">
<?= Formatter::ConvertLocalLinks($games['Game']['postgame']) ?>

              </div>
            </div>
            <section class="game-events">
<?php     if ( !empty($gameevents) ) : ?>
<?php       $ourScore = $theirScore = 0; ?>
              <section class="game-events-header">
                <h3>Matchhändelser</h3>
              </section>
              <section class="game-events-header-row">
                <span>Tid</span>
                <span>Händelse</span>
                <span>Lag</span>
                <span>Spelare</span>
                <span>Assist</span>
                <span>Info</span>
              </section>
              <section class="game-events-body">
<?php       foreach ( $gameevents as $ge ) : ?>
                <section class="game-events-row">
<?php         if ( $ge['Gameevent']['eventtype'] == 1 ) : ?>
<?php         $ge['Gameevent']['thisteam'] == 1 ? $ourScore++ : $theirScore++; ?>
<?php         endif; ?>
                  <span><?= $ge['Gameevent']['time'] == '01:00:00' ? '60:00' : substr($ge['Gameevent']['time'], 3); ?></span>
                  <span><?= $gameevent_types[$ge['Gameevent']['eventtype']] . ( $ge['Gameevent']['eventtype'] == 1 ? ' (' . ( $games['Game']['homegame'] == 1 ? $ourScore . ' - ' . $theirScore : $theirScore . ' - ' . $ourScore ) . ')' : '' ) ?></span>
                  <span><?= $ge['Gameevent']['thisteam'] ? $thisTeam : $games['Game']['opponent'] ?></span>
                  <span><?= $ge['Player']['firstname'] . ' ' . $ge['Player']['lastname'] ?></span>
                  <span><?= $ge['player2']['firstname'] . ' ' . $ge['player2']['lastname']?></span>
<?php         if ( $ge['Gameevent']['eventtype'] == 1 && ( $ge['Gameevent']['ourplayers'] != 5 || $ge['Gameevent']['theirplayers'] != 5 ) ) : ?>
<?php           if ( $ge['Gameevent']['thisteam'] == 1 ) : ?>
<?php             if ( $ge['Gameevent']['ourplayers'] > $ge['Gameevent']['theirplayers'] ) : ?>
                  <span>Powerplay (<?= $ge['Gameevent']['ourplayers'] . ' mot ' . $ge['Gameevent']['theirplayers'] ?>)</span>
<?php             elseif ( $ge['Gameevent']['ourplayers'] < $ge['Gameevent']['theirplayers'] ) : ?>
                  <span>Boxplay (<?= $ge['Gameevent']['ourplayers'] . ' mot ' . $ge['Gameevent']['theirplayers'] ?>)</span>
<?php             else : ?>
                  <span>Spel <?= $ge['Gameevent']['ourplayers'] . ' mot ' . $ge['Gameevent']['theirplayers'] ?></span>
<?php             endif; ?>
<?php           elseif ( $ge['Gameevent']['thisteam'] == 0 ) : ?>
<?php             if ( $ge['Gameevent']['theirplayers'] > $ge['Gameevent']['ourplayers'] ) : ?>
                  <span>Powerplay (<?= $ge['Gameevent']['theirplayers'] . ' mot ' . $ge['Gameevent']['ourplayers'] ?>)</span>
<?php             elseif ( $ge['Gameevent']['theirplayers'] < $ge['Gameevent']['ourplayers'] ) : ?>
                  <span>Boxplay (<?= $ge['Gameevent']['theirplayers'] . ' mot ' . $ge['Gameevent']['ourplayers'] ?>)</span>
<?php             else : ?>
                  <span>Spel <?= $ge['Gameevent']['theirplayers'] . ' mot ' . $ge['Gameevent']['ourplayers'] ?></span>
<?php             endif; ?>
<?php           endif; ?>
<?php         elseif ( $ge['Gameevent']['eventtype'] == 2 ) : ?>
                  <span><?= $penaltyCodes[$ge['Gameevent']['code']] ?></span>
<?php         else : ?>
                  <span></span>
<?php         endif; ?>
                </section>
<?php       endforeach; ?>
              </section>
<?php   endif; ?>
            </section>
<?php     endif; ?>
<?php else : ?>
            <div>
              <p>Inga matcher att visa</p>
            </div>
<?php endif; ?>
            <section class="history-nav">
<?php if ( !empty($seasons) ) : ?>
              <nav class="season-nav">
                <ul>
<?php   foreach ( $seasons as $s ) : ?>
                  <li>
                    <a<?= $s['Season']['id'] == $displaySeason['Season']['id'] ? ' class="active"' : ''  ?> href="<?= '/' . $activeSection['Section']['url'] . '/' . substr($s['Season']['startdate'], 0, 4) . '-' . substr($s['Season']['enddate'], 0, 4) . '.html' ?>"><?= substr($s['Season']['startdate'], 2, 2) . '/' . substr($s['Season']['enddate'], 2, 2)  ?></a>
                  </li>
<?php   endforeach; ?>
                  <li><a<?= $displaySeason['Season']['id'] == 0 ? ' class="active"' : ''  ?> href="<?= Anchors::Refer('base') . '/' . $activeSection['Section']['url'] . '/alla.html' ?>">Alla</a></li>
                </ul>
              </nav>
<?php endif; ?>
<?php if ( !empty($gameformats) ) : ?>
              <nav class="gameformat-nav">
                <ul>
<?php   foreach ( $gameformats as $g ) : ?>
                  <li>
                    <a<?= $g['Gameformat']['slug'] == $activeGameType ? ' class="active"' : ''  ?> href="<?= Anchors::Refer('base') . '/' . $activeSection['Section']['url'] . '/' . ( $displaySeason['Season']['id'] == 0 ? 'alla' : substr($displaySeason['Season']['startdate'], 0, 4)  . '-' . substr($displaySeason['Season']['enddate'], 0, 4) ) . '/' . $g['Gameformat']['slug'] . '.html' ?>"><?= $g['Gameformat']['name'] ?></a>
                  </li>
<?php   endforeach; ?>                  
                  <li><a<?= $activeGameType == 'alla' ? ' class="active"' : ''  ?> href="<?= Anchors::Refer('base') . '/' . $activeSection['Section']['url'] . '/' . ( $displaySeason['Season']['id'] == 0 ? 'alla' : substr($displaySeason['Season']['startdate'], 0, 4)  . '-' . substr($displaySeason['Season']['enddate'], 0, 4) ) . '/alla.html' ?>">Alla</a></li>
                </ul>
              </nav>            
<?php endif; ?>
            </section>
          </div>
