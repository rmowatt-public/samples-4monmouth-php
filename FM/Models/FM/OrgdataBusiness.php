<?php
Zend_Loader::loadClass('FM_Models_BaseModel');
class FM_Models_FM_OrgdataBusiness extends FM_Models_BaseModel {

	protected $_tableName = 'orgdata_business';
	protected $_userInfoTable = 'orgdata';

	public function __construct() {
		parent::__construct('localhost', 'root', '', 'fm');
	}

	public function getBusinessDataByOrgId($id) {
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} u  WHERE orgId = {$id} ";
		return $this->getSingleRow($sql);
	}

	public function getOrgByKeys($keys){
		$where = $this->_makeWhere($keys, 'u');
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} u  WHERE {$where}";
		return $this->getSingleRow($sql);
	}

	public function insertRecord($args) {
		return $this->insert($args);
	}

	public function remove($args) {
		return $this->delete($this->_deleteString($args));
	}
}