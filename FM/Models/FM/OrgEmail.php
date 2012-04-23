<?php
Zend_Loader::loadClass('FM_Models_BaseModel');
class FM_Models_FM_OrgEmail extends FM_Models_BaseModel {

	protected $_tableName = 'org_email';



	public function __construct() {
		parent::__construct('localhost', 'root', '', 'fm');
	}


	public function getRecordByKeys($keys){
		$where = $this->_makeWhere($keys, 'u');
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} u  WHERE {$where}";
		return $this->getSingleRow($sql);
	}

	public function getRecordsByKeys($keys){
		$where = $this->_makeWhere($keys, 'u');
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} u  WHERE {$where}";
		return $this->getMultipleRows($sql);
	}

	public function getLastTwenty($keys){
		$where = $this->_makeWhere($keys, 'u');
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} u  WHERE {$where} ORDER BY date DESC LIMIT 20";
		return $this->getMultipleRows($sql);
	}
	public function remove($args) {
		return $this->delete($this->_deleteString($args));
	}

	public function insertRecord($args) {
		return $this->insert($args);
	}
	
	public function edit($keys, $args) {
		return $this->update($keys, $args);
	}
}