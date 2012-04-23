<?php
Zend_Loader::loadClass('FM_Models_BaseModel');
class FM_Models_FM_VideoAlbum extends FM_Models_BaseModel {

	protected $_tableName = 'videoAlbum';

	public function __construct() {
		parent::__construct(null, null, null, 'FM');
	}

	public function getRecordById($id) {
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} v WHERE v.id = {$id}";
		return $this->getSingleRow($sql);
	}

	public function getAlbumByKeys($keys){
		$where = $this->_makeWhere($keys, 'v');
		//print $where; exit;
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} v WHERE {$where}";
		return $this->getSingleRow($sql);
	}

	public function getAlbumsByKeys($keys){
		$where = $this->_makeWhere($keys, 'v');
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} v WHERE {$where}";
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