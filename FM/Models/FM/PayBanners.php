<?php
Zend_Loader::loadClass('FM_Models_BaseModel');
class FM_Models_FM_PayBanners extends FM_Models_BaseModel {

	protected $_tableName = 'paybanners';

	public function __construct() {
		parent::__construct(null, null, null, 'FM');
	}

	public function getAll() {
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} b ORDER BY name ASC";
		return $this->getMultipleRows($sql);
	}
	
	public function getBannerById($id) {
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} b WHERE b.id = {$id}";
		return $this->getSingleRow($sql);
	}

	public function getBannerByKeys($keys){
		$where = $this->_makeWhere($keys, 'b');
		//print $where; exit;
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} b WHERE {$where}";
		return $this->getSingleRow($sql);
	}

	public function getBannersByKeys($keys){
		$where = $this->_makeWhere($keys, 'b');
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} b WHERE {$where} order by name";
		return $this->getMultipleRows($sql);
	}
	
	public function getRandom($keys = array(), $limit = null){
		$where = (count($keys)) ? ' WHERE ' . $this->_makeWhere($keys, 'b') : '';
		$limit = ($limit) ? ' LIMIT ' . $limit : '';
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} b WHERE active = 1 ORDER BY RAND()";
		return $this->getMultipleRows($sql);
	}
	
	public function getLike($string){
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} b WHERE name LIKE '%{$string}%' order by name";
		return $this->getMultipleRows($sql);
	}

	public function insertRecord($args) {
		$keys = array();
		$values = array();
		$insert = array();
		foreach ($args as $key=>$value) {
			if(in_array($key, $this->_colNames)){
				$insert[$key] = $value;
			}
		}
		return $this->insert($insert);
	}
	
	public function remove($args) {
		return $this->delete($this->_deleteString($args));
	}
	
	public function edit($keys, $args) {
		return $this->update($keys, $args);
	}
}