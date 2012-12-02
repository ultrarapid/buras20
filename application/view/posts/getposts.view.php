<?php if ( isset($posts) && !empty($posts) ) : ?>
<?php   $i = 0; ?>
<?php   ob_start(); ?>
<?php   foreach ( $posts as $post ) : ?>
            <article class="status">
              <header>
                <h3 class="status-header">
                  <a href="<?= Anchors::Refer('base') . '/' . $section['Section']['url'] . '/' . substr($post['Post']['created'], 0, 4) . '/' . Formatter::GetMonthName(substr($post['Post']['created'], 5, 2)) . '/' . $post['Post']['url'] ?>.html">
                    <span class="status-header-inline"><?= $post['Post']['header'] ?></span>
                  </a>
                </h3>
              </header>
              <section class="status-left"></section>
              <section class="status-date">
                <time datetime="<?= $post['Post']['created'] ?>" class="status-day">
                  <span class="at-character">@</span> <?= Formatter::ReadableDate($post['Post']['created']) . ' <span class="status-time">' . substr($post['Post']['created'], 11, 5) ?></span>
                </time>
              </section>
              <div class="status-body">
<?= Formatter::ConvertLinebreaksToBr(Formatter::ConvertLocalLinks($post['Post']['body'])) ?>

              </div>
              <div class="status-row"></div>
              <div class="clear"></div>
            </article>
<?php     $i++; ?>
<?php   endforeach; ?>
<?php   echo json_encode(array('response' => true, 'posts' => ob_get_clean(), 'lastId' => $post['Post']['id'], 'count' => $i, 'paging' => $paging)); ?>
<?php else: ?>
<?php   echo json_encode(array('response' => false)); ?>
<?php endif; ?>