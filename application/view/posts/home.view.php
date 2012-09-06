<?php	if ( isset($layoutSubMenu) && !empty($layoutSubMenu) ) : ?>
          <div id="div-section-<?= $section['Section']['id'] ?>" class="page-content">
<?php else: ?>
          <div id="div-section-<?= $section['Section']['id'] ?>" class="page-content-full">
<?php endif; ?>
            <div class="slider-wrapper theme-default">
              <div id="slider" class="nivoSlider">
                <img src="/img/nivo/slide1.jpg" alt="" />
                <a href="http://dev7studios.com">
                  <img src="/img/nivo/slide2.jpg" alt="" title="#htmlcaption" />
                </a>
                <img src="/img/nivo/slide3.jpg" alt="" title="This is an example of a caption" />
              </div>
              <div id="htmlcaption" class="nivo-html-caption">
                <strong>This</strong> is an example of a <em>HTML</em> caption with <a href="#">a link</a>.
              </div>            
            </div>
            <section class="calendar">
              <a href="#" class="month-prev month-arrow">&lt;</a>
              <header>
                <h3><span class="month">Juni</span><span class="year">2012</span></h3>
              </header>
              <a href="#" class="month-next month-arrow">&gt;</a>
              <ol>
                <li>M</li>
                <li>Ti</li>
                <li>O</li>
                <li>To</li>
                <li>F</li>
                <li>L</li>
                <li>S</li>
              </ol>
              <time datetime="2012-05-28" class="other-month"><a href="#">28</a></time>
              <time datetime="2012-05-29" class="other-month"><a href="#">29</a></time>
              <time datetime="2012-05-30" class="other-month"><a href="#">30</a></time>
              <time datetime="2012-05-31" class="other-month"><a href="#">31</a></time>
              <time datetime="2012-06-01"><a href="#">01</a></time>
              <time datetime="2012-06-02"><a href="#">02</a></time>
              <time datetime="2012-06-03" class="sunday"><a href="#">03</a></time>
              <time datetime="2012-06-04"><a href="#">04</a></time>
              <time datetime="2012-06-05"><a href="#">05</a></time>
              <time datetime="2012-06-06"><a href="#">06</a></time>
              <time datetime="2012-06-07"><a href="#">07</a></time>
              <time datetime="2012-06-08"><a href="#">08</a></time>
              <time datetime="2012-06-09"><a href="#">09</a></time>
              <time datetime="2012-06-10" class="sunday"><a href="#">10</a></time>
              <time datetime="2012-06-11"><a href="#">11</a></time>
              <time datetime="2012-06-12"><a href="#">12</a></time>
              <time datetime="2012-06-13"><a href="#">13</a></time>
              <time datetime="2012-06-14"><a href="#">14</a></time>
              <time datetime="2012-06-15"><a href="#">15</a></time>
              <time datetime="2012-06-16"><a href="#">16</a></time>
              <time datetime="2012-06-17" class="sunday"><a href="#">17</a></time>
              <time datetime="2012-06-18"><a href="#">18</a></time>
              <time datetime="2012-06-19"><a href="#">19</a></time>
              <time datetime="2012-06-20"><a href="#">20</a></time>
              <time datetime="2012-06-21"><a href="#">21</a></time>
              <time datetime="2012-06-22"><a href="#">22</a></time>
              <time datetime="2012-06-23"><a href="#">23</a></time>
              <time datetime="2012-06-24" class="sunday"><a href="#">24</a></time>
              <time datetime="2012-06-25"><a href="#">25</a></time>
              <time datetime="2012-06-26"><a href="#">26</a></time>
              <time datetime="2012-06-27"><a href="#">27</a></time>
              <time datetime="2012-06-28"><a href="#">28</a></time>
              <time datetime="2012-06-29"><a href="#">29</a></time>
              <time datetime="2012-06-30"><a href="#">30</a></time>
              <time datetime="2012-07-01" class="other-month"><a href="#">01</a></time>
            </section>
            <div class="clear"></div>
<?php	if ( isset($section['Section']['body']) ) : ?>
            <div class="page-body">
<?= Formatter::ConvertLinebreaksToBr(Formatter::ConvertLocalLinks($section['Section']['body'])) ?>
            </div>
<?php	endif; ?>

<?php if ( $section['Section']['postsection'] == 2 ) : ?>
<?php   foreach ( $posts as $post ) : ?>
            <div class="status">
              <h3 class="status-header"><span class="status-header-inline"><?= $post['Post']['header'] ?></span></h3>
              <div class="status-left">
<?php      if ( $section['Section']['comment_enabled'] == 1 && !$singlepost ) : ?>
<?php        $commentcount = 0; ?>
<?php        if ( array_key_exists('Comment', $post) ) : ?>
<?php          foreach ( $post['Comment'] as $comment ) : ?>
<?php            $commentcount++; ?>
<?php          endforeach; ?>
<?php        endif; ?>
                <div class="div-comment-count">
                  <p>
                    <a href="<?= Anchors::Refer('base') . '/' . $section['Section']['url'] . '/' . substr($post['Post']['created'], 0, 4) . '/' . substr($post['Post']['created'], 5, 2) . '/' . $post['Post']['url'] ?>.html" class="commentlink"><?= $commentcount//$format->easyDate($post['created']) ?></a>
                  </p>
                </div>
<?php      endif; ?>
              </div>
              <div class="status-date">
                <div class="status-day"><span class="oversized-character">@</span> <?= Formatter::ReadableDate($post['Post']['created']) . ' ' . substr($post['Post']['created'], 11, 5) ?></div>
              </div>
              <div class="status-body">
<?= Formatter::ConvertLinebreaksToBr(Formatter::ConvertLocalLinks($post['Post']['body'])) ?>

              </div>
              <div class="status-row">
              </div>
<?php     if ( $section['Section']['comment_enabled'] == 1 && $singlepost ) : ?>
              <div class="status-comment">
                <div class="status-comment-list">
<?php       foreach ( $post['Comment'] as $c ) : ?>
                  <div class="comment-author"><?= $c['Comment']['name'] ?></div>
                  <div class="comment-body"><?= $c['Comment']['text'] ?></div>
<?php       endforeach; ?>
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
<?php     endif; ?>
              <div class="clear"></div>
            </div>
<?php		endforeach; ?>
<?php	endif; ?>
          </div>