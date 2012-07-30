        <form class="standard" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
          <div>							
            <input id="popup_id" type="hidden" value="<?= $messageType ?>" />
            <input id="popup_msg" type="hidden" value="<?= $messageInfo ?>" />
          </div>					
        </form>
        <div class="info"></div>
<?php if ( isset($section) && !empty($section) ) : ?>
<?php   if ( $section['Section']['postsection'] == 2 ) : ?>
        <a href="<?= Anchors::Refer('admin_posts_index') . '/' . $section['Section']['id'] ?>/">inl&auml;gg</a>
        <a href="<?= Anchors::Refer('admin_sections_edit') . '/' . $section['Section']['id'] ?>/">&auml;ndra standardtext f&ouml;r sidan</a>        
<?php   else: ?>
        <form class="standard" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
          <div class="form-div">
            <div class="div_input">
              <label for="section-name" class="data-label"><span>Menynamn</span></label>
              <input type="text" id="section-name" class="data-input" name="data[name]" value="<?= $section['Section']['name'] ?>" />
            </div>
            <div class="div_input">
              <label for="section-header" class="data-label"><span>Rubrik</span></label>
              <input type="text" id="section-header" class="data-input" name="data[header]" value="<?= $section['Section']['header'] ?>" />
            </div>
            <div class="div_input">
              <label for="section-url" class="data-label"><span>Url</span></label>
              <input type="text" id="section-url" class="data-input" name="data[url]" value="<?= $section['Section']['url'] ?>" disabled="disabled" />
            </div>            
            <div class="div_input">
              <label for="section-body" class="data-label"><span>Text</span></label>
              <textarea id="section-body" class="data-input tinymce" rows="42" cols="40" name="data[body]"><?= $section['Section']['body'] ?></textarea>
            </div>
            <div class="div_input">
              <label for="section-postsection" class="data-label"><span>Sidalternativ</span></label>
              <select id="section-postsection">
<?php foreach ( $settings as $k => $v ) : ?>
                <option value="<?= $k?>"<?= ( ( $section['Section']['postsection'] == $k ) ? ' selected="selected"' : '' ) ?>><?= $v ?></option>
<?php endforeach; ?>
              </select>
            </div>
            <div class="div_input">
              <label for="section-target" class="data-label"><span>Anpassad intern url</span></label>
              <input type="text" id="section-target" class="data-input" name="data[target]" value="<?= $section['Section']['target'] ?>" disabled="disabled" />
            </div>            
            <div class="div_radio">
              <label for="section-visible" class="data-label"><span>Synlig</span></label>
              <input type="checkbox" id="section-visible" class="data-input" value="1" name="data[visible]" <?= ( ( $section['Section']['visible'] == 1 ) ? 'checked="checked" ' : '' ) ?>/>
            </div>
            <div class="div_radio">
              <label for="section-commentenabled" class="data-label"><span>Till&aring;t kommentarer</span></label>
              <input type="checkbox" id="section-commentenabled" class="data-input" value="1" name="data[comment_enabled]" <?= ( ( $section['Section']['comment_enabled'] == 1 ) ? 'checked="checked" ' : '' ) ?>/>
            </div>
            <div class="div_radio">
              <label for="section-startpage" class="data-label"><span>Anv&auml;nd som startsida</span></label>
              <input type="checkbox" id="section-startpage" class="data-input" value="1" name="data[startpage]" <?= ( ( $section['Section']['startpage'] == 1 ) ? 'checked="checked" disabled="disabled" ' : '' ) ?>/>
            </div>
            <input type="hidden" name="data[id]" value="<?= $section['Section']['id'] ?>" />
            <input class="input_submit" type="submit" value="spara" />
          </div>	
        </form>
<?php   endif; ?>
<?php else: ?>
        <div>
          <h2>V&auml;lkommen</h2>
        </div>
<?php endif; ?>