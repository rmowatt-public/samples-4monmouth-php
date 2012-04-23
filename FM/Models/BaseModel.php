<?php
Zend_Loader::loadClass('Zend_Db');
Zend_Loader::loadClass('FM_Models_Db');


class FM_Models_BaseModel {

	protected $_conn;
	protected $_dbName = 'FM';

	public function __construct($host = null, $userName = null, $password = null, $dbName = null) {
		$this->_conn = FM_Models_Db::getInstance()->getConnection();
		$this->_colNames = array_keys($this->_conn->describeTable($this->_tableName));
	}
	
	public function getAll() {
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName}";
		return $this->getMultipleRows($sql);
	}

	public function getSingleRow($sql) {
		$values =  $this->_conn->fetchRow($sql);
		$returnValues = array();
		if(is_array($values)) {
			foreach ($values as $key=>$value) {
				$returnValues[$key] = html_entity_decode($value);
			}
		}
		return $returnValues;
	}

	public function getMultipleRows($sql) {
		$values =  $this->_conn->fetchAll($sql);
		$returnValues = array();
		if(is_array($values)) {
			foreach ($values as $key=>$value) {
				if(is_array($value)) {
					foreach ($value as $key1=>$value1) {
						$returnValues[$key][$key1] = html_entity_decode($value1);
					}
				}
			}
		}
		return $returnValues;
	}

	public function getOne() {

	}

	public function getColumns() {
		//print __FILE__ . ' ' . __LINE__; exit;
	try{ $this->_conn->describeTable($this->_dbName . '.' . $this->_tableName);
	}catch(Exception $e) {
		print $e->getMessage();exit;
	}
	}

	public function insert($args) {
		$args = $this->_clean($args);
		if($this->_conn->insert($this->_dbName . '.' . $this->_tableName, $args)) {
			return $this->_conn->lastInsertId();
		}
		return false;
	}
	
	public function update($keys, $args) {
		$where = $this->_deleteString($keys);
		if(!count($where) || !count($this->_clean($args))){return false;}
		return $this->_conn->update($this->_dbName . '.' . $this->_tableName, $this->_clean($args), $where);
	}
	
	public function delete($args) {
		return $this->_conn->delete($this->_dbName . '.' . $this->_tableName, $args);
	}

	protected function _deleteString($args) {
		$returnSArray = array();
		$args = $this->_clean($args);
		foreach ($args as $key=>$value) {
			$returnSArray[] = "{$key} =  '{$value}'";
		}
		return $returnSArray;
	}

	protected function _makeWhere($keys, $tableId) {
		$where = '';
		$keys = $this->_clean($keys);
		foreach ($keys as $key=>$value) {
			if($tableId){$where .= "$tableId.";}
			$where .= "{$key} = '{$value}' AND ";
		}
		$where = substr($where, 0, -4);
		return $where;
	}

	protected function _clean($args) {
		if(!is_array($args)){return array();}
		$returnArray = array();
		foreach ($args as $key=>$value) {
			if(in_array($key, $this->_colNames)){
				$returnArray[$key] = stripslashes(htmlentities(trim($value)));
			}
		}
		return $returnArray;
	}

	public function edit($keys, $args) {
		//print_r($keys);print_r($args);
		return $this->update($keys, $args);
	}
	
	public function remove($args) {
		return $this->delete($this->_deleteString($args));
	}
	
	public function execute($sql) {
		return $this->_conn->exec($sql);
	}
	
	protected function injectTables(){}
	
	protected function arrayToInString($a) {
		$string = '';
		foreach ($a as $key=>$value) {
			$string .= $value . ', ';
		}
		return substr($string, 0, -2);
	}

}