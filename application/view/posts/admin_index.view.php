					<table class="table_admin_small">
<?php	foreach ( $posts as $k => $post ) : ?>
						<tr class="tr_<?php echo ( ( ($k+1)%2 ) ? 'odd' : 'even' ) ?>">
							<td class="product_name"><?= $post['Post']['header'] ?></td>
							<td class="product_date"><?= $post['Post']['created'] ?></td>
              <td class="product_edit"><a href="<?= Anchors::Refer('admin_posts_edit') ?>/<?= $post['Post']['id'] ?>">editera</a></td>								
							<td class="product_delete"><a href="<?= Anchors::Refer('admin_posts') ?>/delete/<?= $post['Post']['id'] ?>">ta bort</a></td>
						</tr>
<?php	endforeach; ?>					
					</table>