      <div class="div-admin">
        <form class="form_standard" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
          <div class="div_select">
            <label for="input_season" class="label_input">Säsong:</label>
            <select name="gdata[season_id]" id="input_season">
<?php foreach ( $seasons as $s ) : ?>
              <option value="<?= $s['Season']['id'] ?>"<?= $s['Season']['id'] == $season_id ? ' selected="selected"' : '' ?>><?= substr($s['Season']['startdate'], 0, 4) . ' / ' . substr($s['Season']['enddate'], 0, 4) ?></option>
<?php endforeach; ?>
            </select>       
          </div>
          <div class="div_submit">
            <input type="hidden" name="form_id" value="1" />
            <input type="submit" name="Byt" value="Byt säsong" />
          </div>          
        </form>
        <form class="form_standard" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
          <div class="div_input">
            <label for="input_url" class="label_input">Url till tabell:</label>
            <input type="text" name="data[url]" value="<?= !empty($seasonteamtable) ? $seasonteamtable['Seasonteamtable']['url'] : '' ?>" />
          </div>
          <div class="div_input">
            <label for="input_division" class="label_input">Division:</label>
            <input type="text" name="data[division]" value="<?= !empty($seasonteamtable) ? $seasonteamtable['Seasonteamtable']['division'] : '' ?>" />
          </div>
          <div class="div_submit">
            <input type="hidden" name="form_id" value="2" />
            <input type="submit" name="Byt" value="Spara ändringar" />
          </div>          
        </form>
      </div>