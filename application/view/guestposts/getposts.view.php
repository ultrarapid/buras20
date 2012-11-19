<?php if ( isset($gbposts) && !empty($gbposts) ) : ?>
<?php   $i = 0; ?>
<?php   ob_start(); ?>
<?php    foreach ( $gbposts as $k => $post ) : ?>
            <div class="div-gbpost">
              <p class="name">
                <span class="value"><?= $post['Guestpost']['name'] ?></span>
              </p>
              <p class="body"><?= Formatter::ConvertLinebreaksToBr($post['Guestpost']['body']) ?></p>
              <p class="misc-info">
                <span class="date-value">- <?= Formatter::ReadableDate($post['Guestpost']['created']) . ' ' . substr($post['Guestpost']['created'], 11, 5) ?></span>
<?php      if ( !empty($post['Guestpost']['team']) ) : ?>
                <span class="team-value"><?= $post['Guestpost']['team'] ?></span>
<?php      endif; ?>
<?php      if ( !empty($post['Guestpost']['url']) ) : ?>
                <a href="<?= ( ( substr($post['Guestpost']['url'], 0, 7) == 'http://' ) ? $post['Guestpost']['url'] : 'http://' . $post['Guestpost']['url'] ) ?>" class="url-value" rel="nofollow"><?= ( ( strlen($post['Guestpost']['url']) > 40 ) ? substr($post['Guestpost']['url'], 0, 40) : $post['Guestpost']['url'] ) ?></a>
<?php      endif; ?>
              </p>        
            </div>
<?php      $i++; ?>
<?php    endforeach; ?>
<?php	  echo json_encode(array('response' => true, 'posts' => ob_get_clean(), 'lastId' => $post['Guestpost']['id'], 'count' => $i, 'paging' => $paging));	?>
<?php else: ?>
<?php   echo json_encode(array('response' => false)); ?>
<?php endif; ?>