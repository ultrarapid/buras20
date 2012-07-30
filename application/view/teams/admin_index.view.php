<?php /*


    <div class="submenu">
      <ul>
        <li><a class="btn-add" href="#">L&auml;gg till</a></li>
        <li><a class="btn-list" href="#">Visa</a></li> 
        <li><a class="btn" href="<?php echo Anchors::Refer('admin_seasonteamplayers') . '/index/' . $teamID ?>">Spelare</a></li>
        <li><a class="btn" href="<?php echo Anchors::Refer('admin_games') . '/index/' . $teamID ?>">Matcher</a></li>
        <li><a class="btn-settings" href="#">Mina inst&auml;llningar</a></li>
        <li><a class="btn-logout" href="#">Logga ut</a></li>                
      </ul>
    </div
		
		*/ ?>
    <div class="content">
      <h1><?php echo $team['Team']['name'] ?></h1>
    </div>
<?php /*
<a href="<?= Anchors::Refer('admin_seasonteamplayers') . '/edit/' . $teamID ?>">S&auml;songsuppst&auml;llning</a>
				<form class="standard" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
					<div class="input">
						<label for="input_team_name">Namn (ex Damer A, Herrar B):</label>
						<input name="data[name]" type="text" id="input_team_name" />
					</div>                  
					<div>
						<input id="popup_id" type="hidden" value="<?= $popup_id ?>" />
						<input id="popup_msg" type="hidden" value="<?= $popup_msg ?>" />					
						<input value="spara lag" type="submit" />
					</div>
				</form>
*/ ?>
