<?php
class Section extends App_Model
{	
	public function __construct($bound = array()) 
	{
		parent::__construct($bound);
		$this->orderdbcol = 'orderNr';
		$this->table	    = 'bik_sections';
		
	}
	
	public function GetAdminMenu($allowed = array(), $parentID = 0)
	{
		$resetConditions = $this->conditions;
		$conditions = $resetConditions;
		//$this->conditions = array(0 => array('field' => 'id', 'separator' => 'IN', 'value' => '(' . implode(', ', $allowed) . ')'), 1 => array('field' => 'admin', 'value' => '1'), 2 => array('field' => 'parent_id', 'value' => $parentID));
		
		if ( $parentID == -2 ) {
			$conditions[] = array('field' => 'id', 'separator' => 'IN', 'value' => '(' . implode(', ', $allowed) . ')');
			$conditions[] = array('field' => 'parent_id', 'value' => 0);
			$conditions[] = array('field' => 'postsection', 'separator' => '!=', 'value' => 1);
		}
		else if ( $parentID == -1 ) {
//			$this->conditions = array(0 => array('field' => 'id', 'separator' => 'IN', 'value' => '(' . implode(', ', $allowed) . ')'));
			$conditions[] = array('field' => 'id', 'separator' => 'IN', 'value' => '(' . implode(', ', $allowed) . ')');
		} else {
		  //$this->conditions = array(0 => array('field' => 'id', 'separator' => 'IN', 'value' => '(' . implode(', ', $allowed) . ')'), 1 => array('field' => 'parent_id', 'value' => $parentID));
			$conditions[] = array('field' => 'id', 'separator' => 'IN', 'value' => '(' . implode(', ', $allowed) . ')');
			$conditions[] = array('field' => 'parent_id', 'value' => $parentID);
			//$this->conditions = array(0 => array('field' => 'id', 'separator' => 'IN', 'value' => '(' . implode(', ', $allowed) . ')'), 1 => array('field' => 'parent_id', 'value' => $parentID));
		}
		//$this->
		$this->conditions = $conditions;
		$adminsections = $this->Get();
		$this->conditions = $resetConditions;
		return $adminsections;
	}

	public function GetAllowed($allowed = array())
	{
		$conditions = $this->conditions;
		//$this->conditions = array(0 => array('field' => 'id', 'separator' => 'IN', 'value' => '(' . implode(', ', $allowed) . ')'), 1 => array('field' => 'admin', 'value' => '1'), 2 => array('field' => 'parent_id', 'value' => $parentID));
		$this->conditions = array(0 => array('field' => 'id', 'separator' => 'IN', 'value' => '(' . implode(', ', $allowed) . ')'));
		$adminsections = $this->Get();
		$this->conditions = $conditions;
		return $adminsections;
	}
	
	public function GetMenu($parentID = 0)
	{
			$conditions = $this->conditions;
			if ( $parentID == 0 ) {
				$this->conditions = array(0 => array('field' => 'parent_id', 'separator' => 'IN', 'value' => '(18, ' . $parentID . ')'),1 => array('field' => 'visible', 'value' => 1));
			} else {
					$this->conditions = array(0 => array('field' => 'parent_id', 'value' => $parentID),1 => array('field' => 'visible', 'value' => 1));		
			}
			$menu = $this->Get();
			$this->conditions = $conditions;		
			return $menu;

	}
	
	public function GetSectionRouting()
	{
		//get only needed columns
		$fieldList = $this->fieldList;
		$this->fieldList = $this->table . '.id AS ' . $this->model . '_id, ' . $this->table . '.parent_id AS ' . $this->model . '_parent_id, ' . $this->table . '.url AS ' . $this->model . '_url, ' . $this->table . '.postsection AS ' . $this->model . '_postsection, ' . $this->table . '.target AS ' . $this->model . '_target';
		
		//redirect for all visible sections active
		$sections = $this->GetByVisible(1);
		//$sections = $this->GetAll();
		//print_r($sections);
		$array = array();
		foreach ( $sections as $section ) {
			if ( $section['Section']['postsection'] == 2 ) {			
				// postsection 1, 2, for single and multipost sections
				$array["/^" . str_replace('/', '\/', $section['Section']['url']) . "(.*)/"] = "public_view/" . $section['Section']['parent_id'] . "/" . $section['Section']['id'] . "/" . "posts/index/" . $section['Section']['id'] . '$1';
				// str_replace is regex fix where forward slash needs to be escaped
			} else if ( $section['Section']['postsection'] == 1 ) {
				$array["/^" . str_replace('/', '\/', $section['Section']['url']) . "$/"] = "public_view/" . $section['Section']['parent_id'] . "/" . $section['Section']['id'] . "/" . "posts/index/" . $section['Section']['id'];
			} else if ( $section['Section']['postsection'] == 0 ) {
				// postsection 0 for custom classes
				$array["/^" . str_replace('/', '\/', $section['Section']['url']) . "(.*)/"] = "public_view/" . $section['Section']['parent_id'] . "/" . $section['Section']['id'] . "/" . $section['Section']['target'] . '$1';			
			}
		}
		$this->fieldList = $fieldList;
		return $array;
	}

	public function GetSectionSettings()
	{
		return array(2 => 'Inläggssida', 1 => 'Statisk sida', 0 => 'Anpassad' );
	}
	
	public function HasSubMenu($id)
	{
		$objects = $this->GetByParent_id($id);
		if ( !empty($objects) ) {
			return true;
		} else {
			return false;		
		}
	}
	
	protected function SetRelations() { }
	
}