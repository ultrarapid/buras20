<?php
class NewModel
{
  protected $model = get_class($this);

  protected $privateKey = 'id';

  protected $table = strtolower(get_class($this) . 's';

  protected $order = 'ASC';
  

  public function __contruct()
  {

  }
}