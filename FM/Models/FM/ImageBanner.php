<?php
Zend_Loader::loadClass('FM_Models_BaseModel');
class FM_Models_FM_ImageBanner extends FM_Models_BaseModel {

	protected $_tableName = 'imageBanner';

	public function __construct() {
		parent::__construct(null, null, null, 'FM');
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
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} b WHERE {$where}";
		return $this->getMultipleRows($sql);
	}
	
	public function getRandom($keys = array()){
		$where = (count($keys)) ? ' WHERE ' . $this->_makeWhere($keys, 'b') : '';
		$sql = "SELECT * from (SELECT * from {$this->_dbName}.{$this->_tableName} b ORDER BY RAND() ) as tbl GROUP BY tbl.oid";
		return $this->getMultipleRows($sql);
	}

	public function insertBanner($args) {
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