<?php
Zend_Loader::loadClass('FM_Models_BaseModel');
class FM_Models_FM_LimeCard extends FM_Models_BaseModel {

	protected $_tableName = 'imecard';

	public function __construct() {
		parent::__construct(null, null, null, 'FM');
	}

	public function getRecordById($id) {
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} m WHERE m.id = {$id}";
		return $this->getSingleRow($sql);
	}
	
		public function getAll() {
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} order BY name";
		return $this->getMultipleRows($sql);
	}

	public function getRecordByKeys($keys){
		$where = $this->_makeWhere($keys, 'm');
		//print $where; exit;
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} m WHERE {$where}";
		return $this->getSingleRow($sql);
	}

	public function getRecordsByKeys($keys){
		$where = $this->_makeWhere($keys, 'm');
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} m WHERE {$where}";
		return $this->getMultipleRows($sql);
	}

	public function search($searchTerm) {
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} m WHERE name LIKE '%{$searchTerm}%' OR keywords LIKE '%{$searchTerm}%'";
		return $this->getMultipleRows($sql);
	}

	public function alphaSearch($searchTerm) {
		$sql = "SELECT u.*, u.address AS address1, pc.name as catName from {$this->_dbName}.{$this->_tableName} u
				LEFT JOIN search_primaryCategories pc ON pc.id = u.catId
				WHERE u.name LIKE '{$searchTerm}%'
				AND pc.parent = 0 
				ORDER BY u.id DESC";
		return $this->getMultipleRows($sql);
	}

	public function catSearch($searchTerm) {
		$sql = "SELECT u.*, u.address AS address1, pc.name as catName from {$this->_dbName}.{$this->_tableName} u
				LEFT JOIN search_primaryCategories pc ON pc.id = u.catId
				WHERE u.catId = {$searchTerm}
				ORDER BY u.id DESC";
		return $this->getMultipleRows($sql);
	}
	
	public function regionSearch($region) {
		$sql = "SELECT u.*, u.address AS address1, pc.name as catName from {$this->_dbName}.{$this->_tableName} u
				LEFT JOIN search_primaryCategories pc ON pc.id = u.catId
				WHERE u.region = {$region}
				ORDER BY u.id DESC";
		return $this->getMultipleRows($sql);
	}


	public function insertRecord($args) {
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