<?php
class PublicWrapper extends ApplicationWrapper
{

  /**
   * Returns the menu from public context
   *
   * @return array() of sections
   */  
  public function GetMenu()
  {
    $section = self::GetSection();
    return $section->GetMenu(0);
  }

  /**
   * Return section object for public context
   *
   * @return Section
   */
  public function GetSection()
  {
    if ( empty(self::$_init) ) {
      self::$_init = 1;
      self::$_section = new Section();
    }
    return self::$_section;
  }

  /**
   * Returns the submenu from public context
   *
   * @return array() of sections
   */
  public function GetSubMenu()
  {
    $section = self::GetSection();
    return $section->GetMenu(self::$_parent);
  }
}