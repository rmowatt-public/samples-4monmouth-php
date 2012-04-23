<?php
Zend_Loader::loadClass('FM_Models_BaseModel');
class FM_Models_FM_SearchPrimaryCategories extends FM_Models_BaseModel {

	protected $_tableName = 'search_primaryCategories';

	public function __construct() {
		parent::__construct('localhost', 'root', '', 'fm');
	}

	public function getPrimaryCategories() {
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} ORDER BY parent asc, name asc ";
		return $this->getMultipleRows($sql);
	}
	
	public function getRootCategories() {
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} WHERE  parent = 0 ORDER BY name asc";
		return $this->getMultipleRows($sql);
	}

	public function getOrgByKeys($keys){
		$where = $this->_makeWhere($keys, 'u');
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} u  WHERE {$where} ORDER BY name asc";
		return $this->getSingleRow($sql);
	}

	public function getCategoryByKeys($keys){
		$where = $this->_makeWhere($keys, 'u');
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} u  WHERE {$where} ORDER BY name asc";
		return $this->getSingleRow($sql);
	}
	
	public function getCategoriesByKeys($keys){
		$where = $this->_makeWhere($keys, 'u');
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} u  WHERE {$where} ORDER BY name asc";
		return $this->getMultipleRows($sql);
	}

	public function insertRecord($args) {
		return $this->insert($args);
	}

	public function searchByCat($searchTerm) {
		$sql = "SELECT bz.orgId FROM {$this->_dbName}.{$this->_tableName} pc JOIN {$this->_dbName}.bzorg_cat bz ON pc.id = bz.catId WHERE pc.name LIKE '%{$searchTerm}%' GROUP BY bz.orgId;";
		return $this->getMultipleRows($sql);
	}
}