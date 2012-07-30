<?php

	function SetReporting() {
		if ( DEVELOPMENT_ENVIRONMENT == true ) {
			error_reporting(E_ALL);
			ini_set('display_errors','On');
		} else {
			error_reporting(E_ALL);
			ini_set('display_errors','Off');
			ini_set('log_errors', 'On');
			ini_set('error_log', ROOT . DS . 'tmp' . DS . 'logs' . DS . 'error.log');
		}
		
	}

	function UnregisterGlobals() {
		if ( ini_get('register_globals') ) {
			$array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
			foreach ( $array as $value ) {
				foreach ( $GLOBALS[$value] as $key => $var ) {
					if ( $var === $GLOBALS[$key] ) {
						unset($GLOBALS[$key]);
					}
				}
			}
		}
	}

	function RouteURL($url) {
		$routing = GetRouting();
		$parsing = GetParsing();

		foreach ( $parsing as $pattern => $result ) {
  		if ( preg_match( $pattern, $url ) ) {
  			$url = preg_replace( $pattern, $result, $url );					
  		}
  	}
	
		foreach ( $routing as $pattern => $result ) {
			if ( preg_match( $pattern, $url ) ) {
				return preg_replace( $pattern, $result, $url );					
			}
		}
		return $url;
	}

	function GetParsing()
	{
		return array('/(.*)(.html$)|(.htm$)/' => '\1');
	}

	function GetRouting()
	{
		$section = PublicWrapper::GetSection();
		$sectionRoutingString = $section->GetSectionRouting();

		$routing = array(
			'/^admin\/(.*?)\/(.*)/' => '\1/admin_\2',
			'/^ajax\/(.*?)\/(.*)/' 	=> '\1/ajax_\2'
			//'/^nyheter(.*)/'		=> 'posts/index/1\1'
		);

		$routing = array_merge($routing, $sectionRoutingString);

		//$startpage = $section->GetByStartpage(1);



		//define('DEFAULT_ACTIVE', $startpage[0]['Section']['id']);
		//define('DEFAULT_ADMIN_PARENT', 0);
		//define('DEFAULT_ADMIN_ACTIVE', $startpage[0]['Section']['id']);
		return $routing;
	}

	function Redirector() {
		//global $url;
		
		$queryString = array();		
	
		$controller = '';
		$model = '';
		$action = '';

		$url = CALLED_URL;
		
		if ( !empty($url) ) {
			
			$url = RouteUrl($url);
			


			$urlArray = array();
			$urlArray = explode("/", $url);
		
			//print_r($urlArray);

			// if routed from public url
			if ( $urlArray[0] == 'public_view' ) {
				array_shift($urlArray);
				
				define('SECTION_PARENT', $urlArray[0]);
				array_shift($urlArray);
				define('SECTION_ACTIVE', $urlArray[0]);
				array_shift($urlArray);
			} else {

			}
			
			//print_r($urlArray);
			
			if ( isset($urlArray[0]) ) {
				if ( !empty($urlArray[0]) ) {
					$controller = $urlArray[0];
					array_shift($urlArray);
					if ( !empty($urlArray[0]) ) {
						$action = $urlArray[0];
						array_shift($urlArray);
						if ( !empty($urlArray) ) {
							$queryString = $urlArray;
						}							
					}
				}
			}
		}
	
		$controllerName = $controller;
		$controller = ucwords($controller);
		$controller .= 'Controller';

		//print_r($queryString);

/*
		print_r('hej');
	exit;	
 echo $controller . " " . $action;
 exit;

*/
		if ( class_exists($controller) ) {
	
			if ( (int)method_exists($controller, $action) ) {
				
				$dispatch = new $controller($controllerName, $action);
				call_user_func_array(array($dispatch,"PreExecute"),$queryString);			
				call_user_func_array(array($dispatch,$action),$queryString);
			} else {
	
				$page 			= $controllerName;
				$pageController = 'PagesController';
				if ( (int)method_exists($pageController, $page) ) {
	
					$dispatch = new $pageController('pages', $page);
					call_user_func_array(array($dispatch,"PreExecute"),$queryString);	
					call_user_func_array(array($dispatch,$page),$queryString);
				} else {
					$controllerName = DEFAULT_CONTROLLER;
					$action 				= DEFAULT_ACTION;
					$queryString 		= explode("/", DEFAULT_PARAMS);
					$controller 		= ucwords($controllerName) . 'Controller';
	
					if ( (int)method_exists($controller, $action) ) {
						$dispatch = new $controller($controllerName, $action);
						$queryString = ( ( empty($url) ) ? $queryString : explode("/", $url) );
						call_user_func_array(array($dispatch,"PreExecute"),$queryString);	
						call_user_func_array(array($dispatch,$action),$queryString);
					} else {
						//echo "NOOOOOOOOOOOOO";
					}
				}
			}
		} else {
			//print_r($controller);
			//header('location: ' . Anchors::Refer('logout'));
			//header('location: ' . $controller);
			//print_r('NOOOOOOOOOOOO');
			//exit;	
		}
	}
	
	function InitLanguage() {
		//require_once(ROOT . DS . 'system' . DS . 'language' . DS . APP_LANGUAGE . '.php');
	}

SetReporting();
UnregisterGlobals();
InitLanguage();
Redirector();

