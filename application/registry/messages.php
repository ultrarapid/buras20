<?php
	/* to use vars use str_replace('%var1%, 'replacement', Message::Load('msg'));
	 * $search = array('%var1%', '%var2%', '%var3%');
	 * $replace = array($var1, $var2, $var3);
	 * str_replace($search, $replace, Message::Load('msg'))
	 */
	 
	Message::Register('swe', array(
		'error_deleting'		=> 'Fel vid borttagning',
		'error_saving'			=> 'Fel vid sparning',
		'formation_changed'		=> 'Formation &auml;ndrad',
		'formation_created'		=> 'Formation skapad',
		'game_added'			=> 'Match tillagd',
		'game_saved'			=> 'Match&auml;ndringar sparade',
		'guestpost_deleted' => 'G&auml;stboksinl&auml;gg borttaget',
		'image_deleted'   => 'Bild borttagen',
		'image_delete_error'   => 'Fel vid borttagning',
		'image_saved' => 'Bild spadad',
		'player_added'			=> 'Spelare tillagd',
		'player_removed'		=> 'Spelare borttagen',
		'playerstat_deleted'	=> 'Egenskap borttagen',
		'position_changed'		=> 'Position &auml;ndrad',
		'position_created'		=> 'Position skapad',		
		'seasonteam_updated'	=> 'S&auml;songslag uppdaterat',
		'team_changed'			=> 'Lag &auml;ndrat',
		'team_created'			=> 'Lag skapat',
		'this_added'			=> '%var1% tillagd',
		'this_changed'			=> '%var1% &auml;ndrad',
		'this_deleted'			=> '%var1% borttagen',
		'this_saved'			=> '%var1% sparad',
		'this_updated'			=> '%var1% uppdaterad'
	));