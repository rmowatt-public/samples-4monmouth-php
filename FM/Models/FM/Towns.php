<?php
Zend_Loader::loadClass('FM_Models_BaseModel');
class FM_Models_FM_Towns extends FM_Models_BaseModel {

	protected $_tableName = 'towns';

	public function __construct() {
		parent::__construct(null, null, null, 'FM');
	}

	public function getTowns() {
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} ORDER by name ASC";
		return $this->getMultipleRows($sql);
	}

	public function getTownByKeys($keys){
		$where = $this->_makeWhere($keys, 't');
		//print $where; exit;
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} t WHERE {$where}";
		return $this->getSingleRow($sql);
	}
	
	public function getTownsByKeys($keys){
		$where = $this->_makeWhere($keys, 't');
		//print $where; exit;
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} t WHERE {$where}";
		return $this->getMultipleRows($sql);
	}
}