<form class="form-schedule-season" method="post" action="<?= $_SERVER['REQUEST_URI'] ?>">
  <input type="text" name="url" value="" />
  <select name="season_id">
<?php foreach ( $seasons as $s ) : ?>
    <option value="<?= $s['Season']['id'] ?>"<?= ( ( $s['Season']['id'] == $set_season['Season']['id'] ) ? ' selected="selected"' : '' ) ?>><?= substr($s['Season']['startdate'], 0, 4) . ' / ' . substr($s['Season']['enddate'], 0, 4) ?></option>
<?php endforeach; ?>
  </select>
  <input type="hidden" name="form_id" value="1" />
  <input type="submit" value="Välj säsong" />
</form>

<?php if ( !empty($gibf_games) ) : ?>
<form class="form-get-schedule" method="post" action="<?= $_SERVER['REQUEST_URI'] ?>">
  <table class="table-schedule">
    <thead>
      <tr>
        <th>MatchID</th>
        <th>Datum</th>
        <th>Hemmalag</th>
        <th>Bortalag</th>
        <th>Plats</th>
        <th>Slug</th>
        <th>Aktivera</th>
      </tr>
    </thead>
    <tbody>
<?php   foreach ( $gibf_games as $k => $g ) : ?>
<?php     $exists = false; ?>
<?php     foreach ( $games as $ga ) : ?>
<?php       if ( substr($ga['Game']['gamedate'], 0, 10) == substr($g['gamedate'], 0, 10) ) : ?>
<?php         $exists = true; ?>
<?php         break; ?>
<?php       endif; ?>
<?php     endforeach; ?>
      <tr<?= ( ( $exists ) ? ' class="tr-exists"' : '' ) ?>>
        <td><?= $g['ibfid'] ?></td>
        <td><?= substr($g['gamedate'], 0, 16) ?></td>
        <td><?= $g['homegame'] == 1 ? $thisTeam : $g['opponent'] ?></td>
        <td><?= $g['homegame'] == 0 ? $thisTeam : $g['opponent'] ?></td>
        <td><?= $g['location'] ?></td> 
        <td><?= ( ( $g['homegame'] == 1 ) ? Formatter::CreateSlug($thisTeam) . '---' . Formatter::CreateSlug($g['opponent']) : Formatter::CreateSlug($g['opponent']) . '---' . Formatter::CreateSlug($thisTeam) ) . '-' . ( ( substr($g['gamedate'], 8, 1) == '0' ) ? substr($g['gamedate'], 9, 1) : substr($g['gamedate'], 8, 2) ) . '-' . Formatter::GetMonthName(substr($g['gamedate'], 5, 2)) . '-' . substr($g['gamedate'], 0, 4) ?></td>
        <td>
          <input type="checkbox" name="control[<?= $k ?>][value]" class="checkbox-select-all" value="1"<?= ( ( $exists ) ? ' disabled="disabled"' : '' ) ?> />
          <input type="hidden" name="data[<?= $k ?>][gamedate]" value="<?= $g['gamedate'] ?>" />
          <input type="hidden" name="data[<?= $k ?>][opponent]" value="<?= $g['opponent'] ?>" />
          <input type="hidden" name="data[<?= $k ?>][homegame]" value="<?= $g['homegame'] ?>" />
          <input type="hidden" name="data[<?= $k ?>][location]" value="<?= $g['location'] ?>" /> 
          <input type="hidden" name="data[<?= $k ?>][ourscore]" value="<?= $g['ourscore'] ?>" />
          <input type="hidden" name="data[<?= $k ?>][theirscore]" value="<?= $g['theirscore'] ?>" />
          <input type="hidden" name="data[<?= $k ?>][ibfid]" value="<?= $g['ibfid'] ?>" />
          <input type="hidden" name="data[<?= $k ?>][slug]" value="<?= ( ( $g['homegame'] == 1 ) ? Formatter::CreateSlug($thisTeam) . '---' . Formatter::CreateSlug($g['opponent']) : Formatter::CreateSlug($g['opponent']) . '---' . Formatter::CreateSlug($thisTeam) ) . '-' . ( ( substr($g['gamedate'], 8, 1) == '0' ) ? substr($g['gamedate'], 9, 1) : substr($g['gamedate'], 8, 2) ) . '-' . Formatter::GetMonthName(substr($g['gamedate'], 5, 2)) . '-' . substr($g['gamedate'], 0, 4) ?>" />                                   
        </td>       
      </tr>
<?php   endforeach; ?>
    </tbody>
  </table>
  <input type="hidden" name="season_id" value="<?= $set_season['Season']['id'] ?>" />
  <input type="hidden" name="url" value="<?= $getUrl ?>" />
  <input type="hidden" name="form_id" value="2" />
  <input type="hidden" id="checkbox_controller" value="0" />
  <input type="submit" value="lägg till valda" />
</form>
<p id="par-link">
  <a href="#" id="a-select-all">Markera alla</a>
</p>
<?php endif; ?>