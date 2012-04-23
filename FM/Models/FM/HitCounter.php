<?php
Zend_Loader::loadClass('FM_Models_BaseModel');
class FM_Models_FM_HitCounter extends FM_Models_BaseModel {

	protected $_tableName = 'hitcounter';

	public function __construct() {
		parent::__construct(null, null, null, 'FM');
	}

	public function update($orgId) {
		$sql = "UPDATE {$this->_dbName}.{$this->_tableName} h SET h.count=h.count+1  WHERE orgId = {$orgId}";
		if(!count($this->getSingleRow("SELECT * FROM  {$this->_dbName}.{$this->_tableName} h  WHERE orgId = {$orgId}"))) {
			return $this->insert(array('orgId'=>$orgId, 'count'=>1));
		} else {
			return $this->execute($sql);
		}

	}
	
	public function getOrgCount($orgId) {
		return $this->getSingleRow("SELECT * FROM  {$this->_dbName}.{$this->_tableName} h  WHERE orgId = {$orgId}");
	}
}