<?php
Zend_Loader::loadClass('FM_Models_BaseModel');
class FM_Models_FM_NporgCat extends FM_Models_BaseModel {

	protected  $_tableName = 'nporg_cat';
	protected  $_userInfoTable = 'userdata';
	protected  $_colNames = array(
	'oid',
	'uid');

	public function __construct() {
		parent::__construct('localhost', 'root', '', 'fm');
	}

	public function getRecordByKeys($keys){
		$where = $this->_makeWhere($keys, 'u');
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} u WHERE {$where}";
		return $this->getSingleRow($sql);
	}

	public function getIdsByKeys($keys){
		$where = $this->_makeWhere($keys, 'u');
		$sql = "SELECT catId from {$this->_dbName}.{$this->_tableName} u WHERE {$where}";
		return $this->getMultipleRows($sql);
	}


	public function getRecordsByKeys($keys){
		$where = $this->_makeWhere($keys, 'u');
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} u WHERE {$where}";
		return $this->getMultipleRows($sql);
	}


	public function getCatIdsByKeys($keys){
		$where = $this->_makeWhere($keys, 'u');
		$sql = "SELECT catId from {$this->_dbName}.{$this->_tableName} u WHERE {$where}";
		return $this->getMultipleRows($sql);
	}

	public function insertRecord($args) {
		return $this->insert($args);
	}

	public function getRecordsByDataSet($key, $args, $sort = false) {
		if(!is_array($args) || !count($args)){ return false;}
		$sort = ($sort) ? $sort : $key;
		$dataset = implode(' , ', $args);
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} u WHERE {$key} IN ($dataset) ORDER BY {$sort} ASC";
		//print $sql;
		//exit;
		return $this->getMultipleRows($sql);
	}

	public function remove($args) {
		return $this->delete($this->_deleteString($args));
	}

	public function edit($keys, $args) {
		return $this->update($keys, $args);
	}

	public function getOrgNames($orgId) {
		$sql = "SELECT s.name from {$this->_dbName}.{$this->_tableName} u  JOIN {$this->_dbName}.search_primaryCategoriesOrgs s ON  u.catId = s.id WHERE u.orgId = {$orgId}";
		return $this->getMultipleRows($sql);
	}
	
	public function searchByCat($searchTerm) {
		$sql = "SELECT bz.orgId FROM {$this->_dbName}.{$this->_tableName} pc JOIN {$this->_dbName}.nporg_cat np ON pc.id = np.catId WHERE pc.name LIKE '%{$searchTerm}%';";
		return $this->getMultipleRows($sql);
	}
}