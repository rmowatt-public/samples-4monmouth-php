<?php
Zend_Loader::loadClass('FM_Models_BaseModel');
class FM_Models_FM_SportsSchedule extends FM_Models_BaseModel {

	protected $_tableName = 'sportsschedule';

	public function __construct() {
		parent::__construct(null, null, null, 'FM');
	}

	public function getScheduleById($id) {
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} m WHERE m.id = {$id}";
		return $this->getSingleRow($sql);
	}

	public function getScheduleByKeys($keys){
		$where = $this->_makeWhere($keys, 'm');
		//print $where; exit;
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} m WHERE {$where}";
		return $this->getSingleRow($sql);
	}

	public function getSchedulesByKeys($keys){
		$where = $this->_makeWhere($keys, 'm');
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} m WHERE {$where}";
		return $this->getMultipleRows($sql);
	}
}