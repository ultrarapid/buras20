<?php if ( !empty($table) && !empty($stats) ) : ?>
          <div class="table-stats-wrapper">
            <section class="table-summary">
              <h3>Serieläget</h3>
              <table class="table-short">
<?php   foreach ( $table as $k => $r ) : ?>
<?php     if ( $k == 0 ) : ?>
                <tr class="table-first<?= $table[$k+1]['team'] == $thisteam ? ' table-before-thisteam' : '' ?> table-<?= $r['team'] == $thisteam ? 'this' : 'other' ?>team">
                  <td><?= $r['position'] ?></td>
                  <td class="span-team"><?= $r['team'] ?></td>
                  <td><?= $r['matches'] ?></td>
                  <td><?= $r['victories'] ?></td>
                  <td><?= $r['draws'] ?></td>
                  <td><?= $r['defeats'] ?></td>
                  <td><?= ((intval($r['plusgoals'])-intval($r['minusgoals'])) > 0 ? '+' : '') . (intval($r['plusgoals'])-intval($r['minusgoals'])) ?></td>        
                  <td><?= $r['points'] ?></td>
                </tr>
<?php     endif; ?>
<?php     if ( $r['team'] == $thisteam ) : ?>
<?php       if ( $k == count($table) -1 ) : ?>
                <tr class="table-other-team">
                  <td><?= $table[$k-3]['position'] ?></td>
                  <td class="span-team"><?= $table[$k-3]['team'] ?></td>
                  <td><?= $table[$k-3]['matches'] ?></td>
                  <td><?= $table[$k-3]['victories'] ?></td>
                  <td><?= $table[$k-3]['draws'] ?></td>
                  <td><?= $table[$k-3]['defeats'] ?></td>
                  <td><?= ((intval($table[$k-3]['plusgoals'])-intval($table[$k-3]['minusgoals'])) > 0 ? '+' : '' ). (intval($table[$k-3]['plusgoals'])-intval($table[$k-3]['minusgoals'])) ?></td>        
                  <td><?= $table[$k-3]['points'] ?></td>
                </tr>    
<?php       endif; ?>
<?php       if ( $k > count($table) - 3 ) : ?>
                <tr class="table-other-team">
                  <td><?= $table[$k-2]['position'] ?></td>
                  <td class="span-team"><?= $table[$k-2]['team'] ?></td>
                  <td><?= $table[$k-2]['matches'] ?></td>
                  <td><?= $table[$k-2]['victories'] ?></td>
                  <td><?= $table[$k-2]['draws'] ?></td>
                  <td><?= $table[$k-2]['defeats'] ?></td>
                  <td><?= ((intval($table[$k-2]['plusgoals'])-intval($table[$k-2]['minusgoals'])) > 0 ? '+' : '' ). (intval($table[$k-2]['plusgoals'])-intval($table[$k-2]['minusgoals'])) ?></td>        
                  <td><?= $table[$k-2]['points'] ?></td>
                </tr>    
<?php       endif;   ?>
<?php       if ( $k > 1 ) : ?>
                <tr class="table-before-thisteam table-other-team">
                  <td><?= $table[$k-1]['position'] ?></td>
                  <td class="span-team"><?= $table[$k-1]['team'] ?></td>
                  <td><?= $table[$k-1]['matches'] ?></td>
                  <td><?= $table[$k-1]['victories'] ?></td>
                  <td><?= $table[$k-1]['draws'] ?></td>
                  <td><?= $table[$k-1]['defeats'] ?></td>
                  <td><?= ((intval($table[$k-1]['plusgoals'])-intval($table[$k-1]['minusgoals'])) > 0 ? '+' : '' ). (intval($table[$k-1]['plusgoals'])-intval($table[$k-1]['minusgoals'])) ?></td>        
                  <td><?= $table[$k-1]['points'] ?></td>
                </tr>  
<?php       endif; ?>
<?php       if ( $k > 0 && $k < count($table) - 1 ) : ?>
                <tr class="table-thisteam">
                  <td><?= $r['position'] ?></td>
                  <td class="span-team"><?= $r['team'] ?></td>
                  <td><?= $r['matches'] ?></td>
                  <td><?= $r['victories'] ?></td>
                  <td><?= $r['draws'] ?></td>
                  <td><?= $r['defeats'] ?></td>
                  <td><?= ((intval($r['plusgoals'])-intval($r['minusgoals'])) > 0 ? '+' : '') . (intval($r['plusgoals'])-intval($r['minusgoals'])) ?></td>        
                  <td><?= $r['points'] ?></td>
                </tr>
<?php       endif; ?>
<?php       if ( $k < count($table) - 2 ) : ?>
                <tr class="table-after-thisteam table-other-team">
                  <td><?= $table[$k+1]['position'] ?></td>
                  <td class="span-team"><?= $table[$k+1]['team'] ?></td>
                  <td><?= $table[$k+1]['matches'] ?></td>
                  <td><?= $table[$k+1]['victories'] ?></td>
                  <td><?= $table[$k+1]['draws'] ?></td>
                  <td><?= $table[$k+1]['defeats'] ?></td>
                  <td><?= ((intval($table[$k+1]['plusgoals'])-intval($table[$k+1]['minusgoals'])) > 0 ? '+' : '' ). (intval($table[$k+1]['plusgoals'])-intval($table[$k+1]['minusgoals'])) ?></td>        
                  <td><?= $table[$k+1]['points'] ?></td>
                </tr>  
<?php       endif; ?>
<?php       if ( $k < 2 ) : ?>
                <tr class="table-other-team">
                  <td><?= $table[$k+2]['position'] ?></td>
                  <td class="span-team"><?= $table[$k+2]['team'] ?></td>
                  <td><?= $table[$k+2]['matches'] ?></td>
                  <td><?= $table[$k+2]['victories'] ?></td>
                  <td><?= $table[$k+2]['draws'] ?></td>
                  <td><?= $table[$k+2]['defeats'] ?></td>
                  <td><?= ((intval($table[$k+2]['plusgoals'])-intval($table[$k+2]['minusgoals'])) > 0 ? '+' : '' ). (intval($table[$k+2]['plusgoals'])-intval($table[$k+2]['minusgoals'])) ?></td>        
                  <td><?= $table[$k+2]['points'] ?></td>
                </tr>    
<?php       endif; ?>
<?php       if ( $k == 0 ) : ?>
                <tr class="table-other-team">
                  <td><?= $table[$k+3]['position'] ?></td>
                  <td class="span-team"><?= $table[$k+2]['team'] ?></td>
                  <td><?= $table[$k+3]['matches'] ?></td>
                  <td><?= $table[$k+3]['victories'] ?></td>
                  <td><?= $table[$k+3]['draws'] ?></td>
                  <td><?= $table[$k+3]['defeats'] ?></td>
                  <td><?= ((intval($table[$k+3]['plusgoals'])-intval($table[$k+3]['minusgoals'])) > 0 ? '+' : '' ). (intval($table[$k+3]['plusgoals'])-intval($table[$k+3]['minusgoals'])) ?></td>        
                  <td><?= $table[$k+3]['points'] ?></td>
                </tr>    
<?php       endif; ?>
<?php     endif; ?>
<?php     if ( $k == count($table) - 1 ) : ?>
                <tr class="table-last<?= $table[$k-1]['team'] == $thisteam ? ' table-after-thisteam' : '' ?> table-<?= $r['team'] == $thisteam ? 'this' : 'other' ?>team">
                  <td><?= $r['position'] ?></td>
                  <td class="span-team"><?= $r['team'] ?></td>
                  <td><?= $r['matches'] ?></td>
                  <td><?= $r['victories'] ?></td>
                  <td><?= $r['draws'] ?></td>
                  <td><?= $r['defeats'] ?></td>
                  <td><?= ((intval($r['plusgoals'])-intval($r['minusgoals'])) > 0 ? '+' : '') . (intval($r['plusgoals'])-intval($r['minusgoals'])) ?></td>        
                  <td><?= $r['points'] ?></td>
                </tr>
<?php     endif; ?>
<?php   endforeach; ?>
              </table>
            </section>
            <section class="graph">
              <canvas id="canvas" width="320" height="240">
                Skaffa dig en vettig webbläsare för att njuta fullt ut av sidan. Indvik Internet Explorer, ladda ner Google Chrome eller Mozilla Firefox.
              </canvas>
              <section class="series-statistics">
                <ul>
                  <li><span style="background-color: <?= $colors[0] ?>;"></span>Vinster</li>
                  <li><span style="background-color: <?= $colors[1] ?>;"></span>Oavgjorda</li>
                  <li><span style="background-color: <?= $colors[2] ?>;"></span>Förluster</li>
                </ul>
              </section>
            </section>
          </div>
          <div class="player-stats-wrapper">
            <section class="top-points top-list">
<?php   if ( !empty($toppoints) ) :
          $i = 1; ?>
              <header>
                <h3>Poängligan</h3>
              </header>
              <table>
<?php     foreach ( $toppoints as $k => $tp ) :
            $i = (( $k > 0 && $tp['Player']['points'] != $toppoints[$k-1]['Player']['points']) ? $k+1 : $i ) ?>
                <tr>
                  <td><?= $i ?></td>
                  <td><?= $tp['Player']['firstname'] . ' ' . $tp['Player']['lastname'] ?></td>
                  <td><?= $tp['Player']['points'] ?></td>
                </tr>
<?php     endforeach; ?>
              </table>
<?php   endif; ?>
            </section>
            <section class="top-played top-list">
<?php   if ( !empty($topplayedgames) ) :
          $i = 1; ?>
              <header>
                <h3>Spelade matcher</h3>
              </header>
              <table>
<?php     foreach ( $topplayedgames as $k => $tpg ) :
            $i = (( $k > 0 && $tpg['Player']['played'] != $topplayedgames[$k-1]['Player']['played'] ) ? $k+1 : $i ) ?>
                <tr>
                  <td><?= $i ?></td>
                  <td><?= $tpg['Player']['firstname'] . ' ' . $tpg['Player']['lastname'] ?></td>
                  <td><?= $tpg['Player']['played'] ?></td>
                </tr>
<?php     endforeach; ?>
              </table>
<?php   endif; ?>
            </section>                
            <section class="top-scorers top-list">
<?php   if ( !empty($topscorer) ) :
          $i = 1; ?>
              <header>
                <h3>Skytteligan</h3>
              </header>
              <table>
<?php     foreach ( $topscorer as $k => $ts ) :
            $i = (( $k > 0 && $ts['Player']['goals'] != $topscorer[$k-1]['Player']['goals']) ? $k+1 : $i ) ?>
                <tr>
                  <td><?= $i ?></td>
                  <td><?= $ts['Player']['firstname'] . ' ' . $ts['Player']['lastname'] ?></td>
                  <td><?= $ts['Player']['goals'] ?></td>
                </tr>
<?php     endforeach; ?>
              </table>
<?php   endif; ?>
            </section>
            <section class="top-passers top-list">
<?php   if ( !empty($toppasser) ) :
          $i = 1; ?>
              <header>
                <h3>Assistligan</h3>
              </header>
              <table> 
<?php     foreach ( $toppasser as $k => $tp ) :
            $i = (( $k > 0 && $tp['Player']['assists'] != $toppasser[$k-1]['Player']['assists'] ) ? $k+1 : $i ) ?>
                <tr>
                  <td><?= $i ?></td>
                  <td><?= $tp['Player']['firstname'] . ' ' . $tp['Player']['lastname'] ?></td>
                  <td><?= $tp['Player']['assists'] ?></td>
                </tr>
<?php     endforeach; ?>
              </table>
<?php   endif; ?>
            </section>
            <section class="top-penalty top-list">
<?php   if ( !empty($toppenalty) ) :
          $i = 1; ?>
              <header>
                <h3>Utvisningsligan</h3>
              </header>
              <table> 
<?php     foreach ( $toppenalty as $k => $tpe ) :
            $i = (( $k > 0 && $tpe['Player']['penaltyminutes'] != $toppenalty[$k-1]['Player']['penaltyminutes'] ) ? $k+1 : $i ) ?>
                <tr>
                  <td><?= $i ?></td>
                  <td><?= $tpe['Player']['firstname'] . ' ' . $tpe['Player']['lastname'] ?><span>Favorit: <?= $penaltyCodes[$tpe['Player']['favcode']] ?></span></td>
                  <td><?= $tpe['Player']['penaltyminutes'] ?> min</td>
                </tr>
<?php     endforeach; ?>
              </table>
<?php   endif; ?>
            </section>
          </div>
          <div class="powerplay-stats-wrapper">
            <section class="power-play-goals">
<?php   if ( !empty($powerplaygoals) ) : ?>
              <header>
                <h3>Powerplay</h3>
              </header>
              <p class="pp-percent uneven-percent"><?= $ppopportunities == 0 ? 0 : round(100 * ($powerplaygoals['Team']['ppcount'] / $ppopportunities)) ?>%</p>
              <p class="pp-count uneven-count"><?= $powerplaygoals['Team']['ppcount'] ?> av <?= $ppopportunities?></p>
              <p class="pp-bp-goals uneven-goals"><?= $powerplaygoalslost['Team']['bpcount'] ?> insläppta</p>
<?php   endif; ?>
            </section>
            <section class="opower-play-goals">
<?php   if ( !empty($opowerplaygoals) ) : ?>
              <header>
                <h3>Boxplay</h3>
              </header>
              <p class="opp-percent uneven-percent"><?= $oppopportunities == 0 ? 0 : round(100 * (($oppopportunities - $opowerplaygoals['Team']['ppcount']) / $oppopportunities)) ?>%</p>
              <p class="opp-count uneven-count"><?= ($oppopportunities - $opowerplaygoals['Team']['ppcount']) ?> av <?= $oppopportunities ?></p>
              <p class="opp-bp-goals uneven-goals"><?= $boxplaygoals['Team']['bpcount'] ?> gjorda</p>
<?php   endif; ?>
            </section>
<?php endif; ?>
          </div>
          <div style="clear: both;"></div
          <div id="div-section-<?= $section['Section']['id'] ?>" class="page-content">
<?php	if ( isset($section['Section']['body']) ) : ?>
            <p class="page-body">
<?= Formatter::ConvertLinebreaksToBr(Formatter::ConvertLocalLinks($section['Section']['body'])) ?>

            </p>
<?php endif; ?>
          </div>
          <section class="full-player-statistics">
<?php if ( !empty($allplayerstats) ) : ?>
            <header>
              <h3>Spelarstatistik</h3>
            </header>
            <table>
              <thead>
                <tr>
                  <td></td>
                  <td>Namn</td>
                  <td>SM</td>
                  <td>M</td>
                  <td>A</td>
                  <td>P</td>
                  <td>UM</td>
                </tr>
              </thead>
              <tbody>
<?php   $count = 1; ?>
<?php   foreach ( $allplayerstats as $k => $p) : ?>
<?php     if ( $k != 0 && ( $p['Player']['playedgames'] != $allplayerstats[$k-1]['Player']['playedgames'] || $p['Player']['goals'] != $allplayerstats[$k-1]['Player']['goals'] || $p['Player']['assists'] != $allplayerstats[$k-1]['Player']['assists']) ) { $count = $k + 1; } ?>
                <tr>
                  <td><?= $count ?></td>
                  <td><?= $p['Player']['firstname'] . ' ' . $p['Player']['lastname'] ?></td>
                  <td><?= $p['Player']['playedgames'] ?></td>
                  <td><?= $p['Player']['goals'] ?></td>
                  <td><?= $p['Player']['assists'] ?></td>
                  <td><?= $p['Player']['points'] ?></td>
                  <td><?= $p['Player']['pim'] ?></td>
                </tr>
<?php   endforeach; ?>
              </tbody>
            </table>
<?php endif; ?>
          </section>
          <section class="history-nav">
<?php if ( !empty($seasons) ) : ?>
            <nav class="season-nav">
              <ul>
<?php   foreach ( $seasons as $s ) : ?>
                <li>
                  <a<?= $s['Season']['id'] == $setSeason['Season']['id'] ? ' class="active"' : ''  ?> href="<?= '/' . $activeSection['Section']['url'] . '/' . substr($s['Season']['startdate'], 0, 4) . '-' . substr($s['Season']['enddate'], 0, 4) . '.html' ?>"><?= /* $s['Season']['id'] == $activeSeason['Season']['id'] || substr($s['Season']['startdate'], 0, 4) == substr($activeSeason['Season']['startdate'], 0, 4) - 1 ? ( $s['Season']['id'] == $activeSeason['Season']['id'] ? 'Aktuell Säsong' : 'Föregående Säsong' ) :  */ substr($s['Season']['startdate'], 2, 2) . '/' . substr($s['Season']['enddate'], 2, 2)  ?></a>
                </li>
<?php   endforeach; ?>
              </ul>
            </nav>
<?php endif; ?>
          </section>         
<?php if ( isset($stats) ) : ?>
          <form>
            <input type="hidden" id="inputColors" value="<?= $colors[0] . ',' . $colors[1] . ',' . $colors[2] ?>" />
            <input type="hidden" id="inputData" value="<?= $stats[0]['Game']['win'] . ',' . $stats[0]['Game']['draw'] . ',' . $stats[0]['Game']['loss'] ?>" />
          </form>
<?php endif; ?>