<?php
class Anchors 
{
	private static $init;
	private static $root = APPLICATION_FOLDER;
	private static $strs = array();
	
	private function __construct() {}
	
	public static function Internal($key)
	{
		if ( empty(self::$init) ) {
			self::$init = 1;
			self::LinkRegistry();
		}		
		return self::$strs[$key];
	}

	public static function Register($strs) 
	{
		self::$strs = array_merge(self::$strs, $strs);        
	}
	
	public static function Refer($key) 
	{
		if ( empty(self::$init) ) {
			self::$init = 1;
			self::LinkRegistry();
		}		
		return self::$root . self::$strs[$key];       
	}

	private static function LinkRegistry() 
	{
		require_once(ROOT . DS . 'application' . DS . 'registry' . DS . 'anchors.php');
	}
}