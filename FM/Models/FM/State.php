<?php
Zend_Loader::loadClass('FM_Models_BaseModel');
class FM_Models_FM_State extends FM_Models_BaseModel {

	protected $_tableName = 'state';

	public function __construct() {
		parent::__construct(null, null, null, 'FM');
	}

	public function getStates() {
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} ORDER by abbr ASC";
		return $this->getMultipleRows($sql);
	}

	public function getStateByKeys($keys){
		$where = $this->_makeWhere($keys, 's');
		//print $where; exit;
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} s WHERE {$where}";
		return $this->getSingleRow($sql);
	}
}