<?php
class Post extends App_Model
{	
	public function __construct($bound = array()) 
	{
		parent::__construct($bound);
		$this->ordercol = 'created';
		$this->order 	= 'DESC';
		$this->usefk 	= true;
		$this->table	= 'bik_posts';
		$this->recursive = true;
	}

	public function CreateUniqueUrl($string)
	{
		$slug = Formatter::CreateSlug($string);
		$post = $this->GetByUrl($slug);
		if ( empty($post) ) {
			return $slug;
		} else {
			$i = 1;
			while ( !empty($post) ) {
				$i++;
				$post = $this->GetByUrl($slug . '-' . $i);
			}
			return $slug . '-' . $i;
		}		
	}
	
	public function GetArchive() 
	{
		return $this->GetResult("SELECT DISTINCT YEAR(created) AS 'year', MONTH(created) AS 'month' 
				  FROM " . $this->table . " ORDER BY created DESC");
	}
	
	public function IsDevelopmentPost($id)
	{
		//$recursive = $this->relations['recursive'];
		$this->relations['recursive'] = false;
		$this->conditions = array(0 => array('field' => 'id', 'value' => $id));
		$this->relations['manyToManyCondition'] = array('tag' => array('class' => 'Tag', 'join' => 'LEFT', 'fk' => 'post_id', 'joinFk' => 'tag_id', 'joinTable' => 'posts_tags', 'conditionStatement' => 'posts_tags.tag_id = 6'));
		$post = $this->Get();
		if ( empty($post) ) {
			return false;
		} else {
			return true;
		}
		//print_r($post);
	}

	public function SetTagCondition($condition = 'IS', $value)
	{
		// IS NYLL BEHÖVS
		if ( $condition == 'IS' ) {
			$this->relations['manyToManyCondition'] = array('tag' => array('class' => 'Tag', 'fk' => 'post_id', 'joinFk' => 'tag_id', 'joinTable' => 'posts_tags', 'field' => 'tag_id', 'condition' => '=', 'value' => $value));
		} else if ( $condition == 'ISNOT' ) {
			$this->distinct = true;
			$this->relations['manyToManyCondition'] = array('tag' => array('class' => 'Tag', 'join' => 'LEFT', 'fk' => 'post_id', 'joinFk' => 'tag_id', 'joinTable' => 'posts_tags', 'conditionStatement' => 'posts_tags.tag_id != ' . $value . ' OR posts_tags.tag_id IS NULL'));			
		}
		
	}

	protected function SetRelations()
	{
		$this->relations['oneToMany']  = array('comment' => array('class' => 'Comment', 'fk' => 'post_id'));
		$this->relations['hasOne'] = array('section' => array('class' => 'Section', 'fk' => 'section_id'));
	}
	
	public function GetLatestPosts($sectionID = 0, $numberOfPosts = 4)
	{
		$usefk = $this->usefk;
		$conditions = $this->conditions;
		$limit = $this->limit;
		$order = $this->order;
		$this->usefk = false;
		$this->limit = array('start' => 0, 'end' => $numberOfPosts);
		$this->order = 'DESC';
		if ( $sectionID > 0 ) {
			$this->conditions = array(0 => array('field' => 'section_id', 'value' => $sectionID), 
															1 => array('field' => 'published', 'value' => 1));
		} else if ( $sectionID == 0 ) {
			$this->conditions = array(0 => array('field' => 'published', 'value' => 1));			
		}
		$posts = $this->Get();
		$this->conditions = $conditions;
		$this->order = $order;
		$this->limit = $limit;
		$this->usefk = $usefk;
		return $posts;
	}
	

}