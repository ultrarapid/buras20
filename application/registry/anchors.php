<?php
	
	Anchors::Register(array(
	  'admin'     => '/admin',
		'base'			=> '',
		'cache'     => ROOT . DS . 'tmp' . DS . 'cache',
		'force'			=> '/admin/pages/index',
		'login'			=> '/users/login',
		'logout'		=> '/users/logout',
		'includes'	=> ROOT . DS . 'application' . DS . 'view' . DS . 'includes',
		'start'     => '/',
		'admin_section_default' => '/admin/sections/index'
	));

	Anchors::Register(array(
		'admin_formations'			=> '/admin/formations',
		'admin_events'					=> '/admin/events',
		'admin_event_edit'			=> '/admin/events/edit',
		'admin_gameevents'			=> '/admin/gameevents',
		'admin_gameformations'		=> '/admin/gameformations',
		'admin_gameformations_edit' => '/admin/gameformations/edit',
		'admin_gameformats'			=> '/admin/gameformats',
		'admin_gameformats_delete'	=> '/admin/gameformats/delete',
		'admin_gameformats_edit'	=> '/admin/gameformats/edit',
		'admin_gameformats_index'	=> '/admin/gameformats/index',
		'admin_games'				=> '/admin/games',
		'admin_games_delete'		=> '/admin/games/delete',
		'admin_games_edit'			=> '/admin/games/edit',
		'admin_games_index'			=> '/admin/games/index',
		'admin_gamesetups'			=> '/admin/gamesetups',
		'admin_guestposts_delete' => '/admin/guestposts/delete',		
		'admin_guestposts_index' => '/admin/guestposts/index',
		'admin_mysettings'  		=> '/admin/players/mysettings',
		'admin_players'				=> '/admin/players',
		'admin_playerstats'			=> '/admin/playerstats',
		'admin_positions'			=> '/admin/positions',
		'admin_posts'				=> '/admin/posts',
		'admin_seasons'				=> '/admin/seasons',
		'admin_seasonteamplayers'	=> '/admin/seasonteamplayers',
		'admin_sections'			=> '/admin/sections',
		'admin_sections_edit'			=> '/admin/sections/edit',		
		'admin_sections_index'			=> '/admin/sections/index',	
		'admin_start'				=> '/admin/sections/index',
		'admin_teams'				=> '/admin/teams',
		
		'admin_posts_edit'			=> '/admin/posts/edit',
		'admin_posts_index'			=> '/admin/posts/index',		
		//'admin_posts'				=> '/admin/posts/index',
		//'admin_seasons'				=> '/admin/seasons/index',
		'admin_role_edit' => '/admin/roles/edit',
		'admin_role_addsection' => '/admin/roles/addsection',
		'admin_role_delsection' => '/admin/roles/delsection',
		'admin_seasons_edit'		=> '/admin/seasons/edit',		
		//'admin_sections'			=> '/admin/sections/index',
		'admin_sections_edit'		=> '/admin/sections/edit',		
		//'admin_start'				=> '/admin/pages/index',
		'admin_teams_edit'			=> '/admin/teams/edit',
		'admin_user_edit'				=> '/admin/users/edit',
		'admin_user_editrole'				=> '/admin/users/editrole',		
		'admin_user_addrole'=> '/admin/users/addrole',
		'admin_user_delrole'=> '/admin/users/delrole',
		'admin_user_addteam'=> '/admin/users/addteam',
		'admin_user_delteam'=> '/admin/users/delteam',
		'admin_user_mysettings' => '/admin/users/mysettings',
		//'admin_teams'				=> '/admin/teams/index',		
		'posts_view'				=> '/posts/view',				
		'posts'						=> '/posts/index',
		'sections'					=> '/sections/index'
	));	
	
	Anchors::Register(array(
		'news'	=> '/nyheter'
	));

	Anchors::Register(array(
		'mens_game'	=> '/herr/matcher'
	));
	
	Anchors::Register(array(
		'graphics_folder'	=> '/img',
		'javascript_folder'	=> '/js',
		'stylesheet_folder'	=> '/css',
		'playerimage_folder' => '/bilder/spelarbilder'
	));
	
	Anchors::Register(array(
		'layout_archive' => Anchors::Internal('includes') . DS . 'archive.view.php'
	));
