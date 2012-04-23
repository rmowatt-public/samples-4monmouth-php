<?php
Zend_Loader::loadClass('FM_Models_BaseModel');
class FM_Models_FM_Regions extends FM_Models_BaseModel {

	protected $_tableName = 'regions';

	public function __construct() {
		parent::__construct(null, null, null, 'FM');
	}

	public function getRegions() {
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} ORDER by name ASC";
		return $this->getMultipleRows($sql);
	}

	public function getRegionByKeys($keys){
		$where = $this->_makeWhere($keys, 'r');
		//print $where; exit;
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} r WHERE {$where}";
		return $this->getSingleRow($sql);
	}
}