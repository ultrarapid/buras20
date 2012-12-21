          <div class="page-content">
<?php if ( !empty($players) ) : ?>
<?php	  foreach ( $players as $p ) : ?>
            <div class="player-status">
<?php     if ( !empty($p['Player']['imgsrc']) ) : ?>
<?php       if ( $playerdetails == 0 ) : ?>
              <a href="<?= Anchors::Refer('base') . '/' . $section['Section']['url'] . '/' . $thisSeasonUrl . '/' . $p['Player']['slug'] . '.html' ?>">
<?php       endif; ?>
                <img class="<?= ( ( $playerdetails == 0 ) ? 'player-thumb' : 'player-large' ) ?>" src="<?= Anchors::Refer('playerimage_folder') . '/' . $p['Player']['imgsrc'] . ( ( $playerdetails == 0 ) ? '_tn' : '' ) . '.jpg' ?>" alt="<?= $p['Player']['firstname'] . ' ' . $p['Player']['lastname'] ?>" title="<?= $p['Player']['firstname'] . ' ' . $p['Player']['lastname'] ?>" />
<?php       if ( $playerdetails == 0 ) : ?>
              </a>
<?php       endif; ?>
<?php     else: ?>
<?php       if ( $playerdetails == 0 ) : ?>
              <div class="player-filler"></div>
<?php       endif; ?>
<?php     endif; ?>
<?php     if ( $playerdetails == 0 ) : ?>
              <p class="player-name-list">
                <a href="<?= Anchors::Refer('base') . '/' . $section['Section']['url'] . '/' . $thisSeasonUrl . '/' . $p['Player']['slug'] . '.html' ?>">
<?php     else: ?>
              <p class="player-name-details">
<?php     endif; ?>
                  <span><?= $p['Player']['firstname'] . ' ' . $p['Player']['lastname'] ?></span>
<?php     if ( $playerdetails == 0 ) : ?>
                </a>
              </p>
<?php     endif; ?>
            </div>
<?php     if ( $playerdetails == 1 ) : ?>
<?php       foreach ( $playerstats as $ps ) :?>
<?php         foreach ( $playerstatsvalues as $psv ) : ?>
<?php           if ( $ps['Playerstat']['id'] == $psv['PlayerStatValue']['playerstat_id'] && !empty($psv['PlayerStatValue']['value']) ) : ?>
            <div class="player-stat"><span><?= $ps['Playerstat']['name'] ?></span></div>
            <div class="player-stat-value"><?= $psv['PlayerStatValue']['value'] ?></div>
<?php           endif; ?>
<?php         endforeach; ?>
<?php       endforeach; ?>
<?php     endif; ?>
<?php	  endforeach ; ?>
<?php else : ?>
            <div>
              <p>Inga spelare att visa</p>
            </div>
<?php endif; ?>
          </div>
          <section class="history-nav">
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
          </section>        
          <div class="clear"></div>
