<?php
class Model 
{
	protected $relations 	= array();
	private   $settings 	= array();
	public    $displayQuery = false;//true;
	//protected $bound		= array();

	public function __construct($bound = array()) 
	{
		$this->settings['alias']			 = null;
		$this->settings['conditionFunction'] = false;
		$this->settings['conditions'] 		 = array();
		$this->settings['countRows']		 = false;
		$this->settings['countStmt']  	 	 = '*';
		$this->settings['distinct']			 = false;
		$this->settings['fetchOne']			 = false;
		$this->settings['deleteFieldCond']	 = 'AND';
		$this->settings['deleteFields']		 = false;
		$this->settings['fieldList']		 = '';
		$this->settings['fks']				 = null;
		$this->settings['groupby']			 = false;
		$this->settings['limit']			 = false;
		
		$this->settings['model']			 = get_class($this);
		
		// ASC or DESC for order, multi orders separated by space, or only order is used on all.
		$this->settings['order']			 = 'ASC';

		//use order
		$this->settings['orderby']			 = true; 
		
		//column for order, multi orders separated by space.
		$this->settings['ordercol'] 		 = 'id';
 
		//if order by objects from db use this column name	
		$this->settings['orderdbcol'] 		 = ''; 
		
		$this->settings['pk']		    	 = 'id';
		$this->settings['returnId']			 = false; //returns id on insert;
		$this->settings['table']			 = strtolower($this->settings['model']) . 's';
			
		$this->settings['usefk']			 = false;

		$this->relations['belongsTo']	= array();
		$this->relations['hasOne']		= array();		
		$this->relations['manyToMany']	= array();
		$this->relations['manyToOne']	= array();		
		$this->relations['oneToMany']	= array();
		$this->relations['saveKeys']	= false;
		$this->relations['idArray']		= array();
		$this->relations['jConditions']	= array();
		$this->relations['recursive']	= false;
		
		$this->relations['manyToManyCondition'] = array();
		
		$bound[] = get_class($this);
		$this->SetRelations();
		$this->BindRelations($bound);
	}

	public function __call($func, $args)
	{		
		// getBy-funktioner lägg till valfritt fältnamn + argument
		// ex: getById(1)
		// ex: getByName("Stojan")		
		if (substr($func, 0, 5) == "GetBy") {
			if (isset($args)) {
				$_field = strtolower(substr($func, 5));
				$value = $args[0];
				$conditionArray = $this->conditions;
				$newConditions = $conditionArray;
				$newConditions[] = array('field' => $_field, 'value' => '?');
				$this->conditions = $newConditions;
				$statement = $this->RenderAll();
				$result = $this->GetResult($statement, $value);
				if ( $this->saveKeys && $this->relations['recursive'] ) {
					$result = $this->GetJoinResults($result);			
				}				
				$this->conditions = $conditionArray;
				return $result;
				
			}
		} else {
			if (substr($func, 0, 3) == "Get") {
				if (isset($args)) {
					$getFieldEnd = strpos($func, 'By', 0);
					$getField = strtolower(substr($func, 3, $getFieldEnd-3));
					$condField = strtolower(substr($func, $getFieldEnd+2));
					$value = $args[0];
					$statement  = "SELECT " . $getField . " FROM " . $this->table;
					$statement .= " WHERE " . $this->table . "." . $condField . " = ?";
					$this->fetchOne = true;
					$result = $this->GetResult($statement, $value);
					$this->fetchOne = false;					
					return $result;
				}
			}
		}
	}
	
	public function __destruct()
	{
	}	

	public function __get($key) 
	{
		if ( array_key_exists($key, $this->relations) ) {
			//return is_array($this->relations[$key]) ? new ArrayObject($this->relations[$key]) : $this->relations[$key];
			return $this->relations[$key];
		} else {
			if ( array_key_exists($key, $this->settings) ) {
				//return is_array($this->settings[$key]) ? new ArrayObject($this->settings[$key]) : $this->settings[$key];
				return $this->settings[$key];
			}
		}
	}

	public function __isset($key) 
	{
		return array_key_exists($key, $this->settings);
	}

	public function __set($key, $value) 
	{
		if ( array_key_exists($key, $this->settings) ) {
			$this->settings[$key] = $value;
			return true;
		} else {
			if ( array_key_exists($key, $this->relations) ) {
				$this->relations[$key] = $value;
				return true;
			} else {
				return false;
			}
		}
	}

	public function Count()
	{
		$_stmt = "SELECT COUNT(" . $this->pk . ") FROM " . $this->table;
		if ( $this->displayQuery ) {
			echo " Q: " . $_stmt;
		}
		if ( $stmt = ConnectionFactory::GetFactory()->GetLink()->prepare($_stmt) ) {
			$stmt->execute() or die("count error");
			$stmt->bind_result($dbcount);
			$count = 0;
			while ( $stmt->fetch() ) {
				$count = $dbcount;
			}
			$stmt->close();
			return (int)$count;
		} else {
			return 0;
		}		
	}

  //
	public function CountConditional()
	{
		$statement  = "SELECT COUNT(" . $this->table . "." . $this->pk . ") FROM " . $this->table;
		$statement .= $this->RenderJoins();
		$statement .= $this->RenderWhere();
		$statement .= $this->RenderGroupBy();
		//print_r($statement);	
		
		$_stmt = $statement;
		//$this->displayQuery = true;
		if ( $this->displayQuery ) {
			echo " Q: " . $_stmt;
		}
		if ( $stmt = ConnectionFactory::GetFactory()->GetLink()->prepare($_stmt) ) {
			$stmt->execute() or die("count error");
			$stmt->bind_result($dbcount);
			$count = 0;
			while ( $stmt->fetch() ) {
				$count = $dbcount;
			}
			$stmt->close();
			return (int)$count;
		} else {
			return 0;
		}		
	}

	//ta bort post med primärnyckeln $id
	public function Del($id = 0) {
		$values = array();
		$_stmt = "DELETE FROM " . $this->table . " WHERE";
		if ( $this->deleteFields ) {
			$i = 1;
			foreach ( $this->deleteFields as $k => $v ) {
				if ($i > 1) {
					$_stmt .= " " . $this->deleteFieldCond;
				}
				$_stmt .= " " . $k . " = ?";
				$values[] = $v;
				$i++;
			}
		} else {
			$_stmt .= " " . $this->pk . " = ?";
			$values[] = $id;
		}
		if ( $this->displayQuery ) {
			echo " Q: " . $_stmt;
		}
		if ( $stmt = ConnectionFactory::GetFactory()->GetLink()->prepare($_stmt) ) {
			$this->BindVars($stmt, $values);
			$stmt->execute() or die("delete error");
			$stmt->close();
			return true;
		} else {
			return false;
		}
	}
	
	public function DelList($list) {
		$_stmt = "DELETE FROM " . $this->table . " WHERE " . $this->pk . " IN (" . implode(', ', $list) . ")";
		if ( $this->displayQuery ) {
			echo " Q: " . $_stmt;
		}		
		if ( $stmt = ConnectionFactory::GetFactory()->GetLink()->prepare($_stmt) ) {
			$stmt->bind_param("i", $id);
			$stmt->execute() or die("delete error");
			$stmt->close();
			return true;
		} else {
			return false;
		}		
	}

	public function ExecuteConvertionSQL()
	{

		$_stmt = "ALTER TABLE " . $this->table . " CONVERT TO CHARACTER SET utf8 COLLATE utf8_swedish_ci";
		
		if ( $this->displayQuery ) {
			echo " Q: " . $_stmt;
		}
		if ( $stmt = ConnectionFactory::GetFactory()->GetLink()->prepare($_stmt) ) {
			$stmt->execute() or die("switch error");
			$stmt->close();
			return true;
		} else {
			return false;
		}
	}

	public function Get()
	{
		$statement = $this->RenderAll();
		$mainResults = $this->GetResult($statement);
		if ( $this->saveKeys && $this->relations['recursive'] ) {
			$mainResults = $this->GetJoinResults($mainResults);
		}
		return $mainResults;		
	}

	public function GetAll()
	{
		$statement = $this->RenderAll();
		$mainResults = $this->GetResult($statement);
		if ( $this->saveKeys && $this->relations['recursive'] ) {
			$mainResults = $this->GetJoinResults($mainResults);			
		}
		return $mainResults;		
	}
	
	public function GetWithValues($values)
	{
		$statement = $this->RenderAll();
		$mainResults = $this->GetResult($statement, $values);
		if ( $this->saveKeys && $this->relations['recursive'] ) {
			$mainResults = $this->GetJoinResults($mainResults);			
		}
		return $mainResults;
	}
	
	public function MaxOrder()
	{
		$limit = $this->settings['limit'];
		$order = $this->settings['order'];
		$this->settings['limit'] = array('start' => 0, 'end' => 1);
		$this->settings['order'] = 'DESC';		
		$maxObject = current($this->Get());
		$this->settings['limit'] = $limit;
		$this->settings['order'] = $order;
		if ( !empty($maxObject) ) {
			return $maxObject[$this->model][$this->orderdbcol];
		} else {
			return 0;
		}
	}
	
	public function MinOrder()
	{
		$limit = $this->settings['limit'];
		$order = $this->settings['order'];
		$this->settings['limit'] = array('start' => 0, 'end' => 1);
		$this->settings['order'] = 'ASC';		
		$minObject = current($this->Get());
		$this->settings['limit'] = $limit;
		$this->settings['order'] = $order;
		return $minObject[$this->model][$this->orderdbcol];		
	}

	public function MoveDown($ordernr)
	{
		$orderCall = 'GetBy' . ucfirst(strtolower($this->orderdbcol));
		$thisObject = current($this->$orderCall($ordernr));
		$this->settings['limit'] = array('start' => 0, 'end' => 1);
		$this->settings['conditions'] = array(0 => array('stmt' => $this->table . '.' . $this->orderdbcol . ' > ? '));
		$this->settings['conditionFunction'] = true;
		$this->settings['order'] = 'ASC';
		$nextObject = current($this->GetWithValues($ordernr));
		if ( !empty($thisObject) && !empty($nextObject) ) {
			$newOrder = $nextObject[$this->model][$this->orderdbcol];
			$thisObject[$this->model][$this->orderdbcol] = $newOrder;
			$nextObject[$this->model][$this->orderdbcol] = $ordernr;
			if ( $this->Save($thisObject[$this->model]) ) {
				if ( $this->Save($nextObject[$this->model]) ) {
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		} else {
			return false;
		}
	}

	public function MoveUp($ordernr)
	{
		$orderCall = 'GetBy' . ucfirst(strtolower($this->orderdbcol));
		$thisObject = current($this->$orderCall($ordernr));
		$this->settings['limit'] = array('start' => 0, 'end' => 1);
		$this->settings['conditions'] = array(0 => array('stmt' => $this->table . '.' . $this->orderdbcol . ' < ? '));
		$this->settings['conditionFunction'] = true;
		$this->settings['order'] = 'DESC';
		$prevObject = current($this->GetWithValues($ordernr));
		if ( !empty($thisObject) && !empty($prevObject) ) {
			$newOrder = $prevObject[$this->model][$this->orderdbcol];
			$thisObject[$this->model][$this->orderdbcol] = $newOrder;
			$prevObject[$this->model][$this->orderdbcol] = $ordernr;
		
			if ( $this->Save($thisObject[$this->model]) ) {
				if ( $this->Save($prevObject[$this->model]) ) {
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		} else {
			return false;
		}
	}	

	// insert if primary key is in array, else update
	public function Save($args)
	{
		if ( !isset($args) ) {
			return false;
		} else {
			$fields = array_keys($args);
			$values = array_values($args);
			if ( in_array($this->pk, $fields) ) {
				$cval = null;
				$pki  = null;
				$cols = array();
				for ( $i=0 ; $i<count($fields) ; $i++ ) {
					if ( $fields[$i] == $this->pk ) {
						$cval = $values[$i];
						$pki  = $i; 
					} else {
						$cols[] = $fields[$i]." = ?";						
					}
				}				
				//primary key last in array for update
				unset($fields[$pki], $values[$pki]);
				$fields = array_values($fields);
				$values = array_values($values);
				$fields[count($fields)] = $this->pk;
				$values[count($values)] = $cval;
				
				$_stmt = "UPDATE ".$this->table." 
							SET ".implode(', ', $cols)." 
							WHERE ".$this->pk." = ?";
			} else {
				$str = "?";
				for ( $i=1 ; $i<count($fields) ; $i++ ) {
					$str .= ", ?";
				}
				$_stmt = "INSERT INTO ".$this->table." (".implode(', ', $fields).") 
							VALUES(".$str.")";
			}
			if ( $this->displayQuery ) {
				echo " Q: " . $_stmt;
			}
			if ( $stmt = ConnectionFactory::GetFactory()->GetLink()->prepare($_stmt) ) {
				$this->BindVars($stmt, $values);
				$stmt->execute() or die("save error");
				$stmt->close();
				$value = true;
				if ( $this->returnId ) {
					$value = ConnectionFactory::GetFactory()->GetLink()->insert_id;	
				}
				return $value;
			}
		}
	}

	protected function BindRelations($bound)
	{
		foreach ( $this->relations['belongsTo'] as $key => $relation ) {
			if ( !in_array($relation['class'], $bound) ) {
				$this->relations[$relation['class']] = new $relation['class']($bound);
			}
		}
		foreach ( $this->relations['hasOne'] as $key => $relation ) {
			if ( !in_array($relation['class'], $bound) ) {
				$this->relations[$relation['class']] = new $relation['class']($bound);
			}					
		}				
		foreach ( $this->relations['oneToMany'] as $key => $relation ) {
			if ( !in_array($relation['class'], $bound) ) {
				$this->relations[$relation['class']] = new $relation['class']($bound);
				$this->relations['saveKeys'] = true;
			}			
		}
		foreach ( $this->relations['manyToMany'] as $key => $relation ) {
			if ( !in_array($relation['class'], $bound) ) {
				$this->relations[$relation['class']] = new $relation['class']($bound);
				$this->relations['saveKeys'] = true;	
			}				
		}	
		foreach ( $this->relations['manyToOne'] as $key => $relation ) {
			if ( !in_array($relation['class'], $bound) ) {
				$this->relations[$relation['class']] = new $relation['class']($bound);
			}					
		}
		//print_r($bound);
	}
		
	// creates string with datatypes i.e: "issis"
	// i = int, s = string, d = double, b = blob
	protected function BindVars($statement, $params) {
		if ( $params != null ) {
			$types = '';
			foreach ( $params as $param ) {
				if ( is_int($param) ) {
					$types .= 'i';
				} elseif ( is_float($param) ) {
					$types .= 'd';
				} elseif ( is_string($param) ) {
					$types .= 's';
				} else {
					$types .= 'b';
				}
			}	
			// types som första parameter
			$bind_names[] = $types;	
			for ( $i=0 ; $i<count($params) ; $i++ ) {
				$bind_name = 'bind' . $i;
				$$bind_name = $params[$i];
				$bind_names[] = &$$bind_name;
			}	
			call_user_func_array(array($statement,'bind_param'), $bind_names);
		}
		return $statement;
	}

	protected function ClearRelations()
	{
		$this->relations['belongsTo']	= array();
		$this->relations['hasOne']		= array();		
		$this->relations['manyToMany']	= array();
		$this->relations['manyToOne']	= array();		
		$this->relations['oneToMany']	= array();
	}

	// get fields from this table, or specified table
	// prints in format table_field which is used when placing results in array array[table][field]
	protected function GetFields($table = '', $modelAlias = '', $tableAlias = '') {
		//print_r('alias: ' . $alias);
		if ( empty($modelAlias) ) {
			$modelAlias = $this->settings['model'];
		}
		if ( empty($table) ) {
			$table = $this->settings['table'];
		}
		if ( empty($tableAlias) ) {
			$tableAlias = $table;
		}

		$fields = array();
		$_stmt = 'DESCRIBE ' . $table;	
		if ( $stmt = ConnectionFactory::GetFactory()->GetLink()->prepare($_stmt) ) {
			$stmt->execute();			
			$meta = $stmt->result_metadata(); 
			while ( $field = $meta->fetch_field() ) { 
				$params[] = &$row[$field->name]; 
			}			
			call_user_func_array(array($stmt, 'bind_result'), $params);	
			while ( $stmt->fetch() ) {
				if ( $this->settings['distinct'] && $row['Field'] == $this->pk ) {
					$fields[] = 'DISTINCT (' . $tableAlias . '.' . $row['Field'] . ') AS ' . $modelAlias . '_' . $row['Field'];
				} else {
					$fields[] = $tableAlias . '.' . $row['Field'] . ' AS ' . $modelAlias . '_' . $row['Field'];
				}
			}			
			$stmt->close();
		} 
		return implode(', ', $fields);	
	}

	protected function GetJoinResults($mainResults) 
	{
		foreach ( $this->relations['oneToMany'] as $key => $relation ) {
			if ( array_key_exists('fk', $relation) ) {
				$statement  = "SELECT " . $this->relations[$relation['class']]->GetFields();
				$statement .= " FROM " . $this->relations[$relation['class']]->table;
				$statement .= " WHERE " . $this->relations[$relation['class']]->table . "." . $relation['fk'];
				$statement .= " IN (" . implode(', ' , $this->relations['idArray']) . ")";
				
				$joinedResults = $this->relations[$relation['class']]->GetResult($statement);

				foreach ( $joinedResults as $jKey => $joinObject ) {
					foreach ( $mainResults as $mKey => $mainObject ) {
						if ( $joinObject[$relation['class']][$relation['fk']] == $mainObject[$this->settings['model']][$this->settings['pk']] ) {
							$mainResults[$mKey][$relation['class']][] = $joinObject;
						}
					}				
				}
			}
		}
		foreach ( $this->relations['manyToMany'] as $key => $relation ) {
			if ( array_key_exists('joinTable', $relation) ) {
				$statement  = "SELECT " . $this->relations[$relation['class']]->GetFields() . ", " . $this->GetFields($relation['joinTable'], str_ireplace('_', '', $relation['joinTable']));
				$statement .= " FROM " . $this->relations[$relation['class']]->table;
				$statement .= " INNER JOIN " . $relation['joinTable'];
				$statement .= " ON " . $relation['joinTable'] . "." . $relation['joinFk'];
				$statement .= " = " . $this->relations[$relation['class']]->table . "." . $this->relations[$relation['class']]->pk;
				$statement .= " WHERE " . $relation['joinTable'] . "." . $relation['fk'];
				$statement .= " IN (" . implode(', ' , $this->relations['idArray']) . ")";
				if ( $this->displayQuery ) {
					echo "Q: " . $statement;
				}
				$joinedResults = $this->relations[$relation['class']]->GetResult($statement);
				//print_r($joinedResults);
				foreach ( $joinedResults as $jKey => $joinObject ) {
					//print_r($joinObject[str_ireplace('_', '', $relation['joinTable'])][$relation['fk']]);
						
					foreach ( $mainResults as $mKey => $mainObject ) {
					
						if ( $joinObject[str_ireplace('_', '', $relation['joinTable'])][$relation['fk']] == $mainObject[$this->settings['model']][$this->settings['pk']] ) {
							$mainResults[$mKey][$relation['class']][] = $joinObject;
						}
					}		
				}
			}
		}
		return $mainResults;
	}

	protected function GetResult($statement, $values = array())
	{
		//print_r('  SQL: ' . $statement);
		if ( $this->displayQuery ) {
			echo " Q: " . $statement;
		}		
		$result = array();
		if ( $stmt = ConnectionFactory::GetFactory()->GetLink()->prepare($statement) ) {
			if ( !empty($values) ) {
				if ( !is_array($values)) {
					$this->BindVars($stmt, array($values));
				} else {
					$this->BindVars($stmt, $values);
				}
			}			
			$stmt->execute();			
			$meta = $stmt->result_metadata(); 
			while ( $field = $meta->fetch_field() ) { 
				$params[] = &$row[$field->name]; 
			}			
			call_user_func_array(array($stmt, 'bind_result'), $params);	
			while ( $stmt->fetch() ) {
				foreach ( $row as $key => $val ) {
					$c[substr($key, 0, stripos($key, '_'))][substr($key, stripos($key, '_') + 1)] = $val;
					if ( $this->relations['saveKeys'] && $this->usefk && $this->relations['recursive'] ) {
						if ( substr($key, 0, stripos($key, '_')) == $this->model && substr($key, stripos($key, '_') + 1) == $this->pk ) {
							$this->relations['idArray'][] = $val;
						}
					}
				}
				$result[] = $c;
			}			
			$stmt->close();
			$meta->free();
		}
		return $result;
	}

	protected function RenderAll() 
	{
		$statement  = "SELECT " . $this->RenderColumns();
		$statement .= " FROM " . $this->table;
		$statement .= $this->RenderJoins();
		$statement .= $this->RenderWhere();
		$statement .= $this->RenderGroupBy();
		$statement .= $this->RenderOrderBy();
		$statement .= $this->RenderLimit();	
		//print_r($statement);	
		return $statement;	
/*
		$statement  = $this->RenderSelect();
		$statement .= $this->RenderJoin();

		//print_r($statement);
		return $statement;
		*/
	
	}

	protected function RenderColumns()
	{
		if ( empty($this->settings['fieldList']) ) {
  		$fields = $this->GetFields();
  		if ( $this->usefk ) {
  			foreach ( $this->relations['hasOne'] as $key => $relation ) {
  				$alias = array_key_exists('alias', $relation) ? $relation['alias']: '';
  				//print_r($param);
  				$fields .= ", " . $this->$relation['class']->GetFields('', $alias, $alias);		
  			}
  			foreach ( $this->relations['manyToOne'] as $key => $relation ) {
  				$alias = array_key_exists('alias', $relation) ? $relation['alias'] : '';
  				//print_r($param);
  				$fields .= ", " . $this->$relation['class']->GetFields('', $alias, $alias);
  			}						
  		}
  		//print_r($fields);
			return $fields;
		} else {
			return $this->settings['fieldList'];
		}
	}

	protected function RenderGroupBy()
	{
		$statement = '';
		if ( $this->settings['groupby'] ) {
			$statement .= " GROUP BY " . $this->settings['table'] . "." . $this->settings['pk'];	
		}
		return $statement;
	}

	protected function RenderJoins()
	{
		$statement = '';
		if ( $this->usefk ) {
			foreach ( $this->relations['hasOne'] as $key => $relation ) {
				if ( array_key_exists('fk', $relation) ) {
					$statement .= " " . ( ( array_key_exists('join', $relation) ) ? $relation['join'] : "INNER" ). " JOIN " . $this->$relation['class']->table . ( array_key_exists('alias', $relation) ? "AS " . $relation['alias'] : "" ) . " ON " . ( array_key_exists('alias', $relation) ? $relation['alias'] : $this->$relation['class']->table ) . "." . $this->$relation['class']->pk . " = " . $this->settings['table'] . "." . $relation['fk'] ;
				}
			}
			foreach ( $this->relations['belongsTo'] as $key => $relation ) {
				if ( array_key_exists('fk', $relation) ) {
					$statement .= " " . ( ( array_key_exists('join', $relation) ) ? $relation['join'] : "INNER" ). " JOIN " . $this->$relation['class']->table . ( array_key_exists('alias', $relation) ? " AS " . $relation['alias'] : "" ) . " ON " . ( array_key_exists('alias', $relation) ? $relation['alias'] : $this->$relation['class']->table ) . "." . $this->$relation['class']->pk . " = " . $this->settings['table'] . "." . $relation['fk'] ;	
				}
			}
			foreach ( $this->relations['manyToOne'] as $key => $relation ) {
				if ( array_key_exists('fk', $relation) ) {
					$statement .= " " . ( ( array_key_exists('join', $relation) ) ? $relation['join'] : "INNER" ). " JOIN " . $this->$relation['class']->table . ( array_key_exists('alias', $relation) ? " AS " . $relation['alias'] : "" ) . " ON " . ( array_key_exists('alias', $relation) ? $relation['alias'] : $this->$relation['class']->table ) . "." . $this->$relation['class']->pk . " = " . $this->settings['table'] . "." . $relation['fk'] ;
				}			
				/*
				$statement .= " " . ( ( array_key_exists('join', $relation) ) ? $relation['join'] : "INNER" ). " JOIN " . $this->$relation['class']->table . " ON " . $this->$relation['class']->table . "." . $this->$relation['class']->pk . " = " . $this->settings['table'] . "." . $relation['fk'] ;
				*/
			}			
			foreach ( $this->relations['manyToManyCondition'] as $key => $relation ) {
				if ( array_key_exists('joinTable', $relation) ) {
					$statement .= " " . ( ( array_key_exists('join', $relation) ) ? $relation['join'] : "INNER" ). " JOIN " . $relation['joinTable'] . " ON " . $relation['joinTable'] . "." . $relation['fk'] . " = " . ( ( array_key_exists('altTable', $relation) ) ? $relation['altTable'] : $this->table ) . "." . ( (array_key_exists('altKey', $relation) ) ? $relation['altKey'] : $this->pk ) ;
				}	
			}
		}
		return $statement;
	}

	protected function RenderLimit()
	{
		$statement = '';
		if ( $this->settings['limit'] ) {
			$statement .= " LIMIT " . $this->settings['limit']['start'] . ", " . $this->settings['limit']['end'];
		}
		return $statement;		
	}
	
	protected function RenderOrderBy()
	{
		$statement = '';
		$joinBind = false;
	
		if ( $this->usefk ) {
			foreach ( $this->relations['hasOne'] as $key => $relation ) {
				if ( array_key_exists('orderby', $relation) ) {
					if ( $relation['orderby'] == 1 ) {
						$statement .= $this->BindOrders($this->$relation['class']->table, $this->$relation['class']->ordercol, $this->$relation['class']->order);
						$joinBind = true;
					}
				}
			}
			foreach ( $this->relations['belongsTo'] as $key => $relation ) {
				if ( array_key_exists('orderby', $relation) ) {
					if ( $relation['orderby'] == 1 ) {
						$statement .= $this->BindOrders($this->$relation['class']->table, $this->$relation['class']->ordercol, $this->$relation['class']->order);
						$joinBind = true;						
					}
				}
			}
			foreach ( $this->relations['manyToOne'] as $key => $relation ) {
				if ( array_key_exists('orderby', $relation) ) {
					if ( $relation['orderby'] == 1 ) {
						$statement .= $this->BindOrders($this->$relation['class']->table, $this->$relation['class']->ordercol, $this->$relation['class']->order);
						$joinBind = true;						
					}
				}	
			}			
			foreach ( $this->relations['manyToManyCondition'] as $key => $relation ) {
				if ( array_key_exists('orderby', $relation) ) {
					if ( $relation['orderby'] == 1 ) {
						$statement .= $this->BindOrders($this->$relation['class']->table, $this->$relation['class']->ordercol, $this->$relation['class']->order);
						$joinBind = true;						
					}
				}
			}
		}	
		
		if ( $joinBind ) {
			$statement = " ORDER BY " . $statement;
		} else {
			if ( $this->settings['orderby'] ) {
				if ( empty($this->settings['orderdbcol']) ) {
					$statement .= $this->BindOrders($this->settings['table'], $this->settings['ordercol'], $this->settings['order']);
				} else {
					$statement .= $this->settings['orderdbcol'] . ' ASC';
				}
				$statement = " ORDER BY " . $statement;
			}
		}
		return $statement;		
	}

	protected function RenderStatement()
	{
		$statement  = "SELECT " . $this->RenderColumns();
		$statement .= " FROM " . $this->settings['table'] . " ";
		$statement .= $this->RenderJoins();
		return $statement;	
	}
		
	protected function RenderWhere()
	{
		$statement = '';
		$i = 0;
		//print_r($this->settings['conditions']);
		if ( !empty($this->settings['conditions']) ) {
			if ( !$this->settings['conditionFunction'] ) {
				//print_r($this->settings['conditions']);
				foreach ( $this->settings['conditions'] as $condition ) {
					//print_r($condition['field']);
					//print_r($condition['field']);
					$i++;
					$statement .= (( $i == 1 ) ? " WHERE " : " AND ");
					$statement .= $this->settings['table'] . "." . $condition['field'] . ( ( array_key_exists('separator', $condition) ) ? " " . $condition['separator'] . " " : " = " ) . $condition['value'];		
				}			
			} else {
				foreach ( $this->settings['conditions'] as $condition ) {
					$i++;					
					$statement .= (( $i == 1 ) ? " WHERE " : " AND ");
					if ( array_key_exists('stmt', $condition) ) {
						$statement .= $condition['stmt'];
					} else {
						$statement .= $this->settings['table'] . "." . $condition['field'] . ( ( array_key_exists('separator', $condition) ) ? " " . $condition['separator'] . " " : " = " ) . $condition['value'];
					}										
				}					
			}
		}
		if ( !empty($this->relations['manyToManyCondition']) ) {
			foreach ( $this->relations['manyToManyCondition'] as $relation ) {
				$joinTable = $relation['joinTable'];
				if ( !empty($relation['condition']) ) {
					//print_r($relation['condition']);
					foreach ( $relation['condition'] as $condition ) {
						$i++;
						$statement .= ( ( $i == 1 ) ? " WHERE " : " AND ");
						if ( array_key_exists('conditionStatement', $condition) ) {
							$statement .= $condition['conditionStatement'];
						} else {
							$statement .= $joinTable . "." . $condition['field'] . ( ( array_key_exists('separator', $condition) ) ? " " . $condition['separator'] . " " : " = " ) . $condition['value'];
						}				
					}
				}
			}
		}
		return $statement;		
	}	
	
	// overwritten i subclasses to set model relations
	protected function SetRelations(){}	
	
	// switches place on two columns if they use an ordercolumn
	protected function SwitchOrder($ordernr1, $ordernr2)
	{
		$_stmt = "UPDATE " . $this->table . " SET " . $this->orderdbcol . " = (" . $ordernr1 . " + " . $ordernr2 . " - " . $this->orderdbcol . ") WHERE " . $this->orderdbcol . " IN (" . $ordernr1 . ", " . $ordernr2 . ")";
		if ( $this->displayQuery ) {
			echo " Q: " . $_stmt;
		}
		if ( $stmt = ConnectionFactory::GetFactory()->GetLink()->prepare($_stmt) ) {
			$stmt->execute() or die("switch error");
			$stmt->close();
			return true;
		} else {
			return false;
		}			
	}
	
	private function BindOrders($table, $ordercol, $order) {
		$statement = '';
		$allCols = explode(' ', $ordercol);
		$allOrders = explode(' ', $order);
		foreach ( $allCols as $k => $col ) {
			if ( $k > 0 ) {
				$statement .= ', ';
			}
			if ( !stristr($col, '.') ) {
				$statement .= $table . '.' . $col . ' ';
			} else {
				$statement .= $col . ' ';
			}
			if ( array_key_exists($k, $allOrders) ) {
				$statement .= $allOrders[$k];
			} else {
				$statement .= $allOrders[0];
			}
		}		
		return $statement;
	}

}