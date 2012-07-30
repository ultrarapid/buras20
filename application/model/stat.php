<?php
class Stat extends App_Model
{
	public function __construct($bound = array())
	{
		parent::__construct($bound);
		$this->ordercol = 'ordernumber';
		$this->orderdbcol = 'ordernumber';		
		$this->order 	= 'ASC';
		$this->usefk 	= true;
		$this->table	= 'bik_stats';
	}

}