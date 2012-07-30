<?php
class Event extends App_Model
{	
	public function __construct($bound = array())
	{
		parent::__construct($bound);
		$this->table	= 'bik_events';		
	}

	public function GetNextPublicEvent()
	{
		$conditions = $this->conditions;
		$limit = $this->limit;
		$order = $this->order;		
		$this->limit = array('start' => 0, 'end' => 1);
		$this->order = 'ASC';
		$date = date('Y-m-d H:i:s');
		$oneDayAgo = strtotime('-1 day', strtotime($date));
		$this->conditions = array(0 => array('field' => 'eventdate', 'separator' => '>', 'value' => "'" . date('Y-m-d H:i:s', $oneDayAgo) . "'"), 1 => array('field' => 'status', 'value' => 2));
		$event = current($this->Get());
		$this->conditions = $conditions;
		$this->order = $order;
		$this->limit = $limit;
		return $event;
	}

}