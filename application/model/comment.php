<?php
class Comment extends App_Model
{
	public function __construct($bound = array()) 
	{
		parent::__construct($bound);
		$this->table	= 'bik_comments';		
	}
	
	protected function SetRelations() 
	{
		$this->relations['manyToOne']  = array('post' => array('class' => 'Post', 'fk' => 'post_id'));
	}
	
	public function GetComments() 
	{
		return array(0 => array('id' => 1, 'author' => 'Daniel', 'body' => 'Hej kommentar'), 1 => array('id' => 2, 'author' => 'Anders', 'body' => 'Kommentar numero dos'));
	}
}