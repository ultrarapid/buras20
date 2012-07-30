<?php
class Message 
{
    private static $currlang = 'swe';
	private static $init;
    private static $strs = array();

    public static function Register($lang, $strs) 
	{
        if ( empty(self::$strs[$lang]) ) {
            self::$strs[$lang] = array();
		}
        self::$strs[$lang] = array_merge(self::$strs[$lang], $strs);        
    }

	public static function InitMessages()
	{
		require_once(ROOT . DS . 'application' . DS . 'registry' . DS . 'messages' . '.php');		
	}

    public static function SetDefaultLang($lang) 
	{
        self::$currlang = $lang;        
    }

    public static function Load($key, $lang = '') 
	{
		if ( empty(self::$init) ) {
			self::$init = 1;			
			self::InitMessages();
		}
        if ( empty($lang) ) {
			$lang = self::$currlang;

		}
        $str = self::$strs[$lang][$key];
        if ( empty($str) ) {
            $str = "$lang.$key";            
        } 
        return $str;       
    }
}