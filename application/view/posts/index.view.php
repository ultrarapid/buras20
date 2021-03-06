<?php	if ( isset($layoutSubMenu) && !empty($layoutSubMenu) ) : ?>
          <div id="div-section-<?= $section['Section']['id'] ?>" class="page-content">
<?php else: ?>
          <div id="div-section-<?= $section['Section']['id'] ?>" class="page-content-full">
<?php endif; ?>
<?php	if ( isset($section['Section']['body']) ) : ?>
            <div class="page-body">
<?= Formatter::ObfuscateEmails(Formatter::ConvertLocalLinks($section['Section']['body'])) ?>

            </div>
<?php	endif; ?>
<?php if ( $section['Section']['postsection'] == 2 ) : ?>
<?php   if ( !empty($posts) ) : ?>
            <div id="news-posts">
<?php     foreach ( $posts as $k => $post ) : ?>
              <article class="status">
                <header>
                  <h3 class="status-header">
<?php       if ( !$singlepost ) : ?>
                    <a href="<?= Anchors::Refer('base') . '/' . $section['Section']['url'] . '/' . substr($post['Post']['created'], 0, 4) . '/' . Formatter::GetMonthName(substr($post['Post']['created'], 5, 2)) . '/' . $post['Post']['url'] ?>.html">
<?php       endif; ?>                    
                      <span class="status-header-inline"><?= $post['Post']['header'] ?></span>
<?php       if ( !$singlepost ) : ?>
                    </a>
<?php       endif; ?>
                  </h3>
                </header>
                <section class="status-left">
<?php       if ( $section['Section']['comment_enabled'] == 1 && !$singlepost ) : ?>
<?php         $commentcount = 0; ?>
<?php         if ( array_key_exists('Comment', $post) ) : ?>
<?php           foreach ( $post['Comment'] as $comment ) : ?>
<?php             $commentcount++; ?>
<?php           endforeach; ?>
<?php         endif; ?>
                  <div class="div-comment-count">
                    <p>
                      <a href="<?= Anchors::Refer('base') . '/' . $section['Section']['url'] . '/' . substr($post['Post']['created'], 0, 4) . '/' . substr($post['Post']['created'], 5, 2) . '/' . $post['Post']['url'] ?>.html" class="commentlink"><?= $commentcount//$format->easyDate($post['created']) ?></a>
                    </p>
                  </div>
<?php       endif; ?>
                </section>
                <section class="status-date">
                  <time datetime="<?= $post['Post']['created'] ?>" class="status-day"><span class="at-character">@</span> <?= Formatter::ReadableDate($post['Post']['created']) . ' <span class="status-time">' . substr($post['Post']['created'], 11, 5) ?></span></time>
                </section>
                <div class="status-body">
<?= Formatter::ConvertLinebreaksToBr(Formatter::ConvertLocalLinks($post['Post']['body'])) ?>

                </div>
                <div class="status-row">
                </div>
<?php       if ( $section['Section']['comment_enabled'] == 1 && $singlepost ) : ?>
                <div class="status-comment">
                  <div class="status-comment-list">
<?php         foreach ( $post['Comment'] as $c ) : ?>
                    <div class="comment-author"><?= $c['Comment']['name'] ?></div>
                    <div class="comment-body"><?= $c['Comment']['text'] ?></div>
<?php         endforeach; ?>
                  </div>
                  <div class="status-comment-form">
                    <form class="commentform" id="kommentera" method="post" action="<?= $_SERVER['REQUEST_URI'] ?>">
                      <div class="input">
                        <label for="CommentName" class="formlabel">Namn:</label>
                        <input name="data[name]" type="text" maxlength="255" value="" id="CommentName" />
                      </div> 
                      <div class="input">
                        <label for="CommentEmail" class="formlabel">Epost (visas ej):</label>
                        <input name="data[email]" type="text" maxlength="255" value="" id="CommentEmail" />
                      </div>
                      <div class="input">
                        <label for="CommentHomepage" class="formlabel">Hemsida:</label>
                        <input name="data[homepage]" type="text" maxlength="255" value="" id="CommentHomepage" />
                      </div> 
                      <div class="input">
                        <label for="CommentFix" class="formlabel">Skriv "hej" h&auml;r (spam kontroll):</label>
                        <input name="fix" type="text" value="" id="CommentFix" />
                      </div> 
                      <div class="input">
                        <label for="CommentText" class="formlabel">Kommentar:</label>
                        <textarea name="data[text]" cols="30" rows="6" id="CommentText" ></textarea>
                      </div> 
                      <input type="hidden" name="data[post_id]" value="<?= $post['Post']['id'] ?>" id="CommentPostId" /> 
                      <div class="submit">
                        <input type="submit" value="Kommentera" />
                      </div>
                    </form>
                  </div>
                </div>
<?php       endif; ?>
                <div class="clear"></div>
              </article>
<?php		  endforeach; ?>
            </div>
<?php     if ( !$singlepost && $amount > ($k+1) ) : ?>
            <div class="div-more-posts">
              <a id="more-posts" href="#">Visa fler &raquo;</a>
              <input type="hidden" value="<?= $post['Post']['id'] ?>" id="last_id" />
              <input type="hidden" id="section" value="<?= $section['Section']['id'] ?>" />
            </div>
<?php     endif; ?>
<?php   endif; ?>
<?php   if ( !$singlepost ) : ?>
            <input type="hidden" name="amount" value="<?= $amount ?>" />
            <input type="hidden" name="k-add-one" value="<?= ($k+1) ?>" />
<?php   endif; ?>
<?php	endif; ?>
          </div>