<?php
Zend_Loader::loadClass('FM_Models_BaseModel');
class FM_Models_FM_Faqs extends FM_Models_BaseModel {

	protected $_tableName = 'faqs';

	public function __construct() {
		parent::__construct(null, null, null, 'FM');
	}

	public function getFaqById($id) {
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} f WHERE f.id = {$id}";
		return $this->getSingleRow($sql);
	}

	public function getFaqByKeys($keys){
		$where = $this->_makeWhere($keys, 'f');
		//print $where; exit;
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} f WHERE {$where}";
		return $this->getSingleRow($sql);
	}

	public function getFaqsByKeys($keys){
		$where = $this->_makeWhere($keys, 'f');
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} f WHERE {$where}";
		return $this->getMultipleRows($sql);
	}

	public function insertFaq($args) {
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