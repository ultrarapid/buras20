<p><a href="<?= Anchors::Refer('admin_start') ?>">tillbaka</a></p>
				<form class="standard" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
					<div class="input">
						<label for="input_gameformat_name">Namn (ex Serie, Bj&ouml;rnslaget):</label>
						<input name="data[name]" type="text" id="input_gameformat_name" />
					</div>                  
					<div class="input">
						<label for="input_gameformat_periods">Perioder:</label>
                        <select name="data[periods]" id="input_gameformat_periods">
                        	<option value="1">1</option>
                        	<option value="2">2</option>
                        	<option selected="selected" value="3">3</option>                                                        
                        </select>
					</div>
					<div class="input">
						<label for="input_gameformat_time">Periodtid:</label>
                        <select name="data[periodtime]" id="input_gameformat_time">
                        	<option value="1">1</option>
                        	<option value="2">2</option>
                        	<option value="3">3</option>
                        	<option value="4">4</option>
                        	<option value="5">5</option>
                        	<option value="6">6</option>
                        	<option value="7">7</option>
                        	<option value="8">8</option>
                        	<option value="9">9</option>
                        	<option value="10">10</option>
                        	<option value="11">11</option>
                        	<option value="12">12</option>
                        	<option value="13">13</option>
                        	<option value="14">14</option>
                        	<option value="15">15</option>
                        	<option value="16">16</option>
                        	<option value="17">17</option>
                        	<option value="18">18</option>
                        	<option value="19">19</option>
                        	<option selected="selected" value="20">20</option>                             
                        </select>
					</div>
					<div class="input">
                        <input checked="checked" name="data[overtime]" type="radio" value="0">Utan &ouml;vertid</input>
                        <input name="data[overtime]" type="radio" value="1">Med &ouml;vertid</input>
					</div>                         									
					<input id="popup_id" type="hidden" value="<?= $popup_id ?>" />
					<input id="popup_msg" type="hidden" value="<?= $popup_msg ?>" />					
					<input value="spara matchformat" type="submit" />
				</form>
			
			
				<form class="standard" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
					<table class="edit_table">
<?php	foreach ( $gameformats as $gameformat ) : ?>
						<tr class="gameformat_row">
							<td class="gameformat_name"><?= $gameformat['Gameformat']['name'] ?></td>
							<td class="gameformat_matchtime"><?= $gameformat['Gameformat']['periods'] . ' x ' . $gameformat['Gameformat']['periodtime'] ?></td>
                            <td class="game_edit"><a href="<?= Anchors::Refer('admin_gameformats_edit') ?>/<?= $game['Gameformat']['id'] ?>">editera</a></td>								
							<td class="game_delete"><a href="<?= $_SERVER['REQUEST_URI'] ?>/delete/<?= $gameformat['Gameformat']['id'] ?>">ta bort</a></td>
						</tr>
<?php	endforeach; ?>					
					</table>			
				</form>			