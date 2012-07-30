<?php
class User extends App_Model
{
	
	public function __construct($bound = array())
	{
		parent::__construct($bound);
		$this->table = 'bik_users';
	}	
	
	public function Login($login, $pw) {
		require($this->connection);		
		if ($stmt = $mysqli->prepare("SELECT id, username, fullname, phone, email FROM " . $this->name . "
									 WHERE username = ? AND password = ?
									 ORDER BY id")) {		
			$this->BindVars($stmt, array(0 => $login, 1 => $pw));
			$stmt->execute();		
			$meta = $stmt->result_metadata(); 
			while ($field = $meta->fetch_field()) { 
				$params[] = &$row[$field->name]; 
			}	
			call_user_func_array(array($stmt, 'bind_result'), $params);
			$result = array();
			while ($stmt->fetch()) { 
				foreach($row as $key => $val) { 
					$c[$key] = $val; 
				}
				$result[] = $c; 
			}			
			$stmt->close();
			$meta->free();
		} 
		$mysqli->close(); 		
		return $result;
	}
		
}