<?php
abstract class ApplicationWrapper 
{

  /**
   * ID of active section
   * @var int
   */
  protected static $_active = 0;

  /**
   * ID of active parent
   * @var int
   */  
  protected static $_parent = 0;

  /**
   * ID of default startpage
   * @var int
   */   
  protected static $_start  = 0;

  /**
   * Is set if section has been initialized
   * @var int
   */    
  protected static $_init;

  /**
   * Application section object
   * @var section
   */  
  protected static $_section;

  /**
   * Constructor
   *
   * Set to private to avoid multiple instances
   */
  private final function __construct(){}

  /**
   * Get the main menu in the active context
   *
   * @abstract
   */
  abstract public function GetMenu();

  /**
   * Get the section object in the active context
   *
   * @abstract
   */
  abstract public function GetSection();

  /**
   * Get the sub menu in the active context
   *
   * @abstract
   */
  abstract public function GetSubMenu();

  /**
   * Returns id of active section
   *
   * @return int
   */
  public static function GetActive()
  {
    return self::$_active;
  }

  /**
   * Sets the id of active section
   *
   * @param int $id ID of active section
   * @return void
   */
  public static function SetActive($id)
  {
    self::$_active = $id;
  }

  /**
   * Returns id of active parent
   *
   * @return int
   */  
  public static function GetParent() { 
    return self::$_parent; 
  }

  /**
   * Sets the id of active parent
   *
   * @param int $id ID of active parent
   * @return void
   */
  public static function SetParent($id)
  {
    self::$_active = $id;  
  }

  /**
   * Returns id of set startpage
   *
   * @return int
   */
  public static function GetStart()
  {
    return self::$_start;
  }

  /**
   * Sets the id of startpage
   *
   * @param int $id ID of startpage
   * @return void
   */
  public static function SetStart($id)
  {
    self::$_start = $id;
  }
}