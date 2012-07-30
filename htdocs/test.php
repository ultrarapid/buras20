<?php


/*


http://www5.idrottonline.se/BurasIK-Innebandy
cname www -> peka.idrottonline.se

echo phpinfo();

	$string = 'lastname';
	$array = explode(' ', $string);
	print_r($array);


	$arr = array();
	if ( isset($arr) ) {
		print_r('isset');
	} else {
		print_r('is not set');
	}
	
	foreach ( $arr as $a ) {
		print_r($a['toodeloo']);
	}

class Model 
{
	protected $relations 	= array();
	
	public function __construct($bound = array()) 
	{
		$this->settings['model'] = get_class($this);
		$this->relations['manyToOne']	= array();		
		$this->relations['oneToMany']	= array();		
		$bound[] = get_class($this);
		$this->SetRelations();
		$this->BindRelations($bound);
	}
	
	public function __get($key) 
	{
		if ( array_key_exists($key, $this->relations) ) {
			return $this->relations[$key];
		} 	
	}
	
	protected function GetBound()
	{
		
	}
	
	protected function BindRelations($bound)
	{	
		foreach ( $this->relations['oneToMany'] as $key => $relation ) {
			if ( !in_array($relation['class'], $bound) ) {
				$bound[] = $relation['class'];
				$this->relations[$relation['class']] = new $relation['class']($bound);
			}
		}
		foreach ( $this->relations['manyToOne'] as $key => $relation ) {		
			if ( !in_array($relation['class'], $bound) ) {
				$bound[] = $relation['class'];
				$this->relations[$relation['class']] = new $relation['class']($bound);
			}			
		}
	}
	
	protected function SetRelations(){}	
	
}

class App_Model extends Model 
{
	public function __construct($bound = array()) 
	{
		parent::__construct($bound);
	}

	protected function SetRelations(){}
}

class Post extends App_Model
{	
	public function __construct($bound = array()) 
	{
		parent::__construct($bound);
	}

	protected function SetRelations()
	{
		$this->relations['oneToMany']  = array('comment' => array('class' => 'Comment', 'fk' => 'post_id'));
	}
	
	public function GetPosts()
	{
		return array(0 => array('id' => 1, 'author' => 'John', 'body' => 'Hej post'), 1 => array('id' => 2, 'author' => 'Jim', 'body' => 'Another post'));
	}

}

class Comment extends App_Model
{
	public function __construct($bound = array()) 
	{
		parent::__construct($bound);	
	}
	
	protected function SetRelations() 
	{
		$this->relations['manyToOne'] = array('post' => array('class' => 'Post', 'fk' => 'post_id'), 'user' => array('class' => 'User', 'fk' => 'user_id'));
	}
	
	public function GetComments() 
	{
		return array(0 => array('id' => 1, 'author' => 'Daniel', 'body' => 'Hej kommentar'), 1 => array('id' => 2, 'author' => 'Anders', 'body' => 'Kommentar numero dos'));
	}
}

class User extends App_Model
{
	public function __construct($bound = array())
	{
		parent::__construct($bound);
	}
	
	protected function SetRelations()
	{
		$this->relations['manyToOne']  = array('comment' => array('class' => 'Comment', 'fk' => 'comment_id'));
	}
	
	public function GetUser()
	{
		return array(0 => array('id' => 1, 'fullname' => 'Daniel Zetterlund'));		
	}
	
}

$comment = new Comment();

print_r($comment->GetComments());
print_r($comment->Post->GetPosts());

$post = new Post();
print_r($post->Comment->User->GetUser());


*/