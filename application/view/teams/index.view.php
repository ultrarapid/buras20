<?php if ( !empty($table) ) : ?>
          <section class="graph">
              <canvas id="canvas" width="400" height="300">
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
          <section class="top-scorers">
<?php   foreach ( $topscorer as $k => $ts ) : ?>
            <div><?= $k+1 . ', ' . $ts['Player']['firstname'] . ' ' . $ts['Player']['lastname'] . ' (' . $ts['Player']['goals'] . ')' ?></div>  
<?php   endforeach; ?>
          </section>
          <section class="top-passers">
<?php   foreach ( $toppasser as $k => $tp ) : ?>
            <div><?= $k+1 . ', ' . $tp['Player']['firstname'] . ' ' . $tp['Player']['lastname'] . ' (' . $tp['Player']['assists'] . ')' ?></div>  
<?php   endforeach; ?>
          </section>
          <section class="top-penalty">
<?php   foreach ( $toppenalty as $k => $tpe ) : ?>
            <div><?= $k+1 . ', ' . $tpe['Player']['firstname'] . ' ' . $tpe['Player']['lastname'] . ' (' . $tpe['Player']['penaltyminutes'] . ') Favoritutvisning: ' . $penaltyCodes[$tpe['Player']['favcode']] ?></div>  
<?php   endforeach; ?>
          </section>
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
            <td class="span-team"><?= $table[$k+2]['team'] ?></td>
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
<?php       if ( $k > 0 ) : ?>
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
<?php       if ( $k < count($table) - 1 ) : ?>
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
<?php endif; ?>
          <div id="div-section-<?= $section['Section']['id'] ?>" class="page-content">
<?php	if ( isset($section['Section']['body']) ) : ?>
            <p class="page-body">
<?= Formatter::ConvertLinebreaksToBr(Formatter::ConvertLocalLinks($section['Section']['body'])) ?>

            </p>
<?php endif; ?>
          </div>
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
<?php if ( isset($stats) ) : ?>
          <form>
            <input type="hidden" id="inputColors" value="<?= $colors[0] . ',' . $colors[1] . ',' . $colors[2] ?>" />
            <input type="hidden" id="inputData" value="<?= $stats[0]['Game']['win'] . ',' . $stats[0]['Game']['draw'] . ',' . $stats[0]['Game']['loss'] ?>" />
          </form>
<?php endif; ?>