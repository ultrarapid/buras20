        <form class="standard" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
          <div class="input">
            <label for="input_username">Anv&auml;ndarnamn:</label>
            <input name="data[username]" type="text" id="input_username" />
          </div>
          <div class="input">
            <label for="input_password">L&ouml;senord:</label>
            <input name="cdata[pw]" type="password" id="input_password" />
          </div>
          <div class="input">
            <label for="input_player">Kopplat till spelare:</label>
            <select name="cdata[player]" id="input_player">
              <option value="0">-- V&auml;lj spelare --</option>
<?php foreach ( $players as $p ) : ?>
              <option value="<?php echo $p['Player']['id'] ?>"><?php echo $p['Player']['firstname'] . ' ' . $p['Player']['lastname'] ?></option>
<?php endforeach; ?>
            </select>
          </div>          
          <input type="hidden" name="data[allow]" value="1" />
          <input id="message_info" type="hidden" value="<?= $messageInfo ?>" />
          <input id="message_text" type="hidden" value="<?= $messageText ?>" />					
          <input value="spara anv&auml;ndare" type="submit" />
        </form>