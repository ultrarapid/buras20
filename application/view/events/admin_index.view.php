					<table class="table_admin_small">
<?php	foreach ( $events as $k => $event ) : ?>
						<tr class="tr_<?php echo ( ( ($k+1)%2 ) ? 'odd' : 'even' ) ?>">
							<td class="event_name"><?= $event['Event']['header'] ?></td>
              <td class="event_edit"><a href="<?= Anchors::Refer('admin_event_edit') ?>/<?= $event['Event']['id'] ?>">editera</a></td>								
							<td class="event_delete"><a href="<?= Anchors::Refer('admin_events') ?>/delete/<?= $event['Event']['id'] ?>">ta bort</a></td>
						</tr>
<?php	endforeach; ?>					
					</table>