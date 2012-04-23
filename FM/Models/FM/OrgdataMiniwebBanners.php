<?php
Zend_Loader::loadClass('FM_Models_BaseModel');
class FM_Models_FM_OrgdataMiniwebBanners extends FM_Models_BaseModel {

	protected $_tableName = 'org_miniwebBanners';

	public function __construct() {
		parent::__construct('localhost', 'root', '', 'fm');
	}

	
	public function getActive() {
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} u  WHERE active  =  1 ORDER BY name ASC";
		return $this->getMultipleRows($sql);
	}
	
	public function getBannerByKeys($keys){
		$where = $this->_makeWhere($keys, 'u');
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} u  WHERE {$where}";
		return $this->getSingleRow($sql);
	}

	public function getBannersByKeys($keys){
		$where = $this->_makeWhere($keys, 'u');
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} u  WHERE {$where}";
		return $this->getMultipleRows($sql);
	}

	public function remove($args) {
		return $this->delete($this->_deleteString($args));
	}

	public function insertRecord($args) {
		return $this->insert($args);
	}

	public function updateRecord($args) {
		return $this->update(array('id'=>$args['id']) , array('active'=>$args['active']));
	}
}