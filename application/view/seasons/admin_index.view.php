<p><a href="<?= Anchors::Refer('admin_start') ?>">tillbaka</a></p>
				<form class="standard" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
					<div class="input">
						<label for="input_season_start">Startdatum (&Aring;&Aring;&Aring;&Aring;-MM-DD):</label>
						<input name="data[startdate]" type="text" id="input_season_start" />
					</div>
					<div class="input">
						<label for="input_season_end">Slutdatum (&Aring;&Aring;&Aring;&Aring;-MM-DD):</label>
						<input name="data[enddate]" type="text" id="input_season_end" />
					</div><?php
					/*             
					<div class="input">
						<label for="input_season_start">Startdatum:</label>
						<select id="input_season_start" name="format[start_year]">
<?php	for ( $year = date('Y') ; $year >= $firstSeason ; $year-- ) : ?>
							<option value="<?= $year ?>"><?=  $year ?></option>
<?php 	endfor; ?>
						</select>
						<select id="input_season_start_month" name="format[start_month]">
<?php	for ( $month = 1 ; $month <= 12 ; $month++ ) : ?>
							<option value="<?= str_pad($month, 2, 0, STR_PAD_LEFT) ?>"><?=  str_pad($month, 2, 0, STR_PAD_LEFT) ?></option>
<?php 	endfor; ?>
						</select>
						<select id="input_season_start_day" name="format[start_day]">
<?php	for ( $day = 1 ; $day <= 31 ; $day++ ) : ?>
							<option value="<?= str_pad($day, 2, 0, STR_PAD_LEFT) ?>"><?=  str_pad($day, 2, 0, STR_PAD_LEFT) ?></option>
<?php 	endfor; ?>
						</select>
					</div>
					<div class="input">
						<label for="input_season_end">Slutdatum:</label>
						<select id="input_season_end" name="format[end_year]">
<?php	for ( $year = date('Y') ; $year >= $firstSeason ; $year-- ) : ?>
							<option value="<?= $year ?>"><?=  $year ?></option>
<?php 	endfor; ?>
						</select>
						<select id="input_season_end_month" name="format[end_month]">
<?php	for ( $month = 1 ; $month <= 12 ; $month++ ) : ?>
							<option value="<?= str_pad($month, 2, 0, STR_PAD_LEFT) ?>"><?=  str_pad($month, 2, 0, STR_PAD_LEFT) ?></option>
<?php 	endfor; ?>
						</select>
						<select id="input_season_end_day" name="format[end_day]">
<?php	for ( $day = 1 ; $day <= 31 ; $day++ ) : ?>
							<option value="<?= str_pad($day, 2, 0, STR_PAD_LEFT) ?>"><?=  str_pad($day, 2, 0, STR_PAD_LEFT) ?></option>
<?php 	endfor; ?>
						</select>
					</div> */ ?>
					<div>
						<input id="popup_id" type="hidden" value="<?= $popup_id ?>" />
						<input id="popup_msg" type="hidden" value="<?= $popup_msg ?>" />					
						<input value="skapa s&auml;song" type="submit" />
					</div>
				</form>
				<form class="standard" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
					<table class="edit_table">
<?php	foreach ( $seasons as $season ) : ?>
						<tr class="season_row">
							<td class="season_name"><?= substr($season['Season']['startdate'], 0, 4) . '/' . substr($season['Season']['enddate'], 0, 4) ?></td>
							<td class="season_start"><?= $season['Season']['startdate'] ?></td>
							<td class="season_end"><?= $season['Season']['enddate'] ?></td>
							<td class="season_edit"><a href="<?= Anchors::Refer('admin_seasons_edit') ?>/<?= $season['Season']['id'] ?>">editera</a></td>								
							<td class="season_delete"><a href="<?= $_SERVER['REQUEST_URI'] ?>/delete/<?= $season['Season']['id'] ?>">ta bort</a></td>
						</tr>
<?php	endforeach; ?>
<?php	if ( empty($seasons) ) : ?>
						<tr></tr>
<?php	endif; ?>
					</table>			
				</form>
