<?php
Zend_Loader::loadClass('FM_Models_BaseModel');
class FM_Models_FM_PhotoGallery extends FM_Models_BaseModel {


	protected $_tableName = 'photogallery';

	public function __construct() {
		parent::__construct(null, null, null, 'FM');
	}

	public function getPhotoById($id) {
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} b WHERE b.id = {$id}";
		return $this->getSingleRow($sql);
	}

	public function getPhotoByKeys($keys){
		$where = $this->_makeWhere($keys, 'b');
		//print $where; exit;
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} b WHERE {$where}";
		return $this->getSingleRow($sql);
	}

	public function getPhotosByKeys($keys){
		$where = $this->_makeWhere($keys, 'b');
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} b WHERE {$where} ORDER BY b.date DESC ";
		return $this->getMultipleRows($sql);
	}
	
	public function insertPhoto($args) {
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