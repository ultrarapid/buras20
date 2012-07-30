<?php

	define('DS', DIRECTORY_SEPARATOR);
	define('ROOT', dirname(dirname(__FILE__)));
  define('CALLED_URL', ((array_key_exists('url', $_GET)) ? $_GET['url'] : '' ));
  
/*
  $url = '';

  if ( array_key_exists('url', $_GET) ) {
    $url = $_GET['url'];
  }
*/
	
	require_once (ROOT . DS . 'framework' . DS . 'bootstrap.php');
