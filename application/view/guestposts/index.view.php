          <form id="form-gb" class="form_standard" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
            <div class="div-inputs">
              <div class="div_input" id="div-name">
                <label class="label_input" for="input_name"><span>Namn*</span></label>
                <input name="data[name]" type="text" id="input_name" value="" />
              </div>
              <div class="div_input" id="div-email">
                <label class="label_input" for="input_email"><span>Epost* (visas ej p&aring; sidan)</span></label>
                <input name="data[email]" type="text" id="input_email" value="" />
              </div>
              <div class="div_input" id="div-url">
                <label class="label_input" for="input_url"><span>Hemsida</span></label>
                <input name="data[url]" type="text" id="input_url" value="" />
              </div>
              <div class="div_input" id="div-team">
                <label class="label_input" for="input_team"><span>Lag</span></label>
                <input name="data[team]" type="text" id="input_team" value="" />
              </div>
              <div class="div_input" id="div-game">
                <label class="label_input" for="input_game"><span>Misc</span></label>
                <input name="data[game]" type="text" id="input_game" value="" />
              </div>            
              <div class="div_textarea" id="div-msg1">
                <label class="label_textarea" for="textarea_text1"><span>Meddelande*</span></label>
                <textarea name="data[text1]" id="textarea_text1"></textarea>
              </div>
              <div class="div_textarea" id="div-msg2">
                <label class="label_textarea" for="textarea_text2"><span>Meddelande*</span></label>
                <textarea name="data[text2]" id="textarea_text2"></textarea>
              </div> 
              <div class="div_textarea" id="div-msg3">
                <label class="label_textarea" for="textarea_text3"><span>Meddelande*</span></label>
                <textarea name="data[text3]" id="textarea_text3"></textarea>
              </div>
              <input class="input_submit" value="Spara" type="submit" />
            </div>
          </form>
<?php  if ( !empty($gbposts) ) : ?>
          <div id="guestbook-posts" class="div-gbposts">
<?php    foreach ( $gbposts as $k => $post ) : ?>
            <div class="div-gbpost">
              <p class="name">
                <span class="value"><?= $post['Guestpost']['name'] ?></span>
              </p>
              <p class="misc-info">
                <span class="date-value">@ <?= Formatter::ReadableDate($post['Guestpost']['created']) . ' ' . substr($post['Guestpost']['created'], 11, 5) ?></span>
<?php      if ( !empty($post['Guestpost']['team']) ) : ?>
                <span class="team-value"><?= $post['Guestpost']['team'] ?></span>
<?php      endif; ?>
<?php      if ( !empty($post['Guestpost']['url']) ) : ?>
                <a href="<?= ( ( substr($post['Guestpost']['url'], 0, 7) == 'http://' ) ? $post['Guestpost']['url'] : 'http://' . $post['Guestpost']['url'] ) ?>" class="url-value" rel="nofollow"><?= ( ( strlen($post['Guestpost']['url']) > 40 ) ? substr($post['Guestpost']['url'], 0, 40) : $post['Guestpost']['url'] ) ?></a>
<?php      endif; ?>
              </p>
              <p class="body"><?= Formatter::ConvertLinebreaksToBr($post['Guestpost']['body']) ?></p>            
            </div>
<?php    endforeach; ?>
          </div>
<?php    if ( $amount > ($k+1) ) : ?>
          <div class="div-more-posts">
            <a id="more-posts" href="#">Visa fler</a>
            <input type="hidden" value="<?= $post['Guestpost']['id'] ?>" id="last_id" />
          </div>
<?php    endif; ?>
<?php  endif; ?>
          <input type="hidden" name="amount" value="<?= $amount ?>" />
          <input type="hidden" name="k-add-one" value="<?= ($k+1) ?>" />
