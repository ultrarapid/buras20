<?php
class ConnectionFactory
{
	private static $_db;
	private static $_factory;

	private function __clone(){} 
	private function __construct(){} 
	
	public static function GetFactory()
	{
		if ( !isset(self::$_factory ) ) {
			self::$_factory = new ConnectionFactory();
    }
    return self::$_factory; 
	}
	
	public static function GetLink() 
	{
		if ( !isset(self::$_db) ) {
			require(ROOT . DS . 'config' . DS . 'connection.php');
			self::$_db = new mysqli($mysql_server, $mysql_user, $mysql_password, $mysql_database);
			if ( mysqli_connect_errno() ) {
				throw new Exception(mysqli_connect_error(), mysqli_connect_errno());
				exit;
      		}					
		}
		return self::$_db;		
	}
}