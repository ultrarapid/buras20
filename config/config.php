<?php

	date_default_timezone_set('Europe/Stockholm');
	//ini_set("session.gc_maxlifetime", "18000"); 	
	define('DEVELOPMENT_ENVIRONMENT', true);
	define('APPLICATION_FOLDER', '');
	
	function __autoload($className) {
		if ( file_exists(ROOT . DS . 'framework' . DS . strtolower($className) . '.php') ) {
			require_once(ROOT . DS . 'framework' . DS . strtolower($className) . '.php');
		} else if ( file_exists(ROOT . DS . 'application' . DS . 'controller' . DS . strtolower($className) . '.php') ) {
			require_once(ROOT . DS . 'application' . DS . 'controller' . DS . strtolower($className) . '.php');
		} else if ( file_exists(ROOT . DS . 'application' . DS . 'model' . DS . strtolower($className) . '.php') ) {
			require_once(ROOT . DS . 'application' . DS . 'model' . DS . strtolower($className) . '.php');
		} else if ( file_exists(ROOT . DS . 'application' . DS . 'view' . DS . strtolower($className) . '.php') ) {
			require_once(ROOT . DS . 'application' . DS . 'view' . DS . strtolower($className) . '.php');			
		} else if ( file_exists(ROOT . DS . 'application' . DS . 'classes' . DS . strtolower($className) . '.php') ) {
			require_once(ROOT . DS . 'application' . DS . 'classes' . DS . strtolower($className) . '.php');
		} else if ( file_exists(ROOT . DS . 'application' . DS . 'services' . DS . strtolower($className) . '.php') ) {
			require_once(ROOT . DS . 'application' . DS . 'services' . DS . strtolower($className) . '.php');			
		} else {
			/* Error Generation Code Here */
		}
	}

	$a_section = PublicWrapper::GetSection();
	$a_startpage = current($a_section->GetByStartpage(1));
	//$active_id = $startpage[0]['Section']['id'];

	define('DEFAULT_CONTROLLER', $a_startpage['Section']['controller']);
	define('DEFAULT_ACTION', $a_startpage['Section']['action']);
	define('DEFAULT_PARAMS', $a_startpage['Section']['params']);

	define('DEFAULT_PARENT', 0);
