<?php
/*
$parsing = array(
	// remove html and htm from extension
	'/(.*)(.html$)|(.htm$)/' => '\1'
);

$sct_public = new Section();
$sectionRoutingString = $sct_public->GetSectionRouting();

$routing = array(
	'/^admin\/(.*?)\/(.*)/' => '\1/admin_\2',
	'/^ajax\/(.*?)\/(.*)/' 	=> '\1/ajax_\2'
	//'/^nyheter(.*)/'		=> 'posts/index/1\1'
);

$routing = array_merge($routing, $sectionRoutingString);

$startpage = $sct_public->GetByStartpage(1);

define('DEFAULT_CONTROLLER', 'posts');
define('DEFAULT_ACTION', 'index');
define('DEFAULT_PARENT', 0);
define('DEFAULT_ACTIVE', $startpage[0]['Section']['id']);
//define('DEFAULT_ADMIN_PARENT', 0);
//define('DEFAULT_ADMIN_ACTIVE', $startpage[0]['Section']['id']);
*/