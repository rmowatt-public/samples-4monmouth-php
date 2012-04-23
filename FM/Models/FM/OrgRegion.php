<?php
Zend_Loader::loadClass('FM_Models_BaseModel');
class FM_Models_FM_OrgRegion extends FM_Models_BaseModel {

	protected  $_tableName = 'org_region';
	protected  $_userInfoTable = 'userdata';
	protected  $_colNames = array(
	'oid',
	'uid');

	public function __construct() {
		parent::__construct('localhost', 'root', '', 'fm');
	}

	public function getRecordsByKeys($keys){
		$where = $this->_makeWhere($keys, 'u');
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} u WHERE {$where}";
		return $this->getMultipleRows($sql);
	}
	
	public function getRegionIdsByKeys($keys){
		$where = $this->_makeWhere($keys, 'u');
		$sql = "SELECT regionId from {$this->_dbName}.{$this->_tableName} u WHERE {$where}";
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
}