<?php
Zend_Loader::loadClass('FM_Models_BaseModel');
class FM_Models_FM_Depts extends FM_Models_BaseModel {

	protected $_tableName = 'depts';

	public function __construct() {
		parent::__construct(null, null, null, 'FM');
	}

	public function getDeptById($id) {
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} d WHERE d.id = {$id}";
		return $this->getSingleRow($sql);
	}

	public function getDeptByKeys($keys){
		$where = $this->_makeWhere($keys, 'd');
		//print $where; exit;
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} d WHERE {$where}";
		return $this->getSingleRow($sql);
	}

	public function getDeptsByKeys($keys){
		$where = $this->_makeWhere($keys, 'd');
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} d WHERE {$where}";
		return $this->getMultipleRows($sql);
	}

	public function insertDept($args) {
		$keys = array();
		$values = array();
		$insert = array();
		foreach ($args as $key=>$value) {
			if(in_array($key, $this->_colNames)){
				$insert[$key] = $value;
			}
		}
		return $this->insert($insert);
	}

	public function remove($args) {
		return $this->delete($this->_deleteString($args));
	}

	public function edit($keys, $args) {
		return $this->update($keys, $args);
	}
}