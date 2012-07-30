<form class="form-schedule-season" method="post" action="<?= $_SERVER['REQUEST_URI'] ?>">
  <input type="text" name="url" value="" />
  <select name="season_id">
<?php foreach ( $seasons as $season ) : ?>
    <option value="<?= $season['Season']['id'] ?>"<?= ( ( $season['Season']['id'] == $set_season['Season']['id'] ) ? ' selected="selected"' : '' ) ?>><?= substr($season['Season']['startdate'], 0, 4) . ' / ' . substr($season['Season']['enddate'], 0, 4) ?></option>
<?php endforeach; ?>
  </select>
  <select name="game_id">
<?php foreach ( $games as $game ) : ?>
    <option value="<?= $g['Game']['id'] ?>"<?= ( ( $game['Game']['id'] == $set_game['Game']['id'] ) ? ' selected="selected"' : '' ) ?>><?= $game['Game']['opponent'] ?></option>
<?php endforeach; ?>
  </select>
  <input type="hidden" name="form_id" value="1" />
  <input type="submit" value="V&auml;lj s&auml;song / match" />
</form>