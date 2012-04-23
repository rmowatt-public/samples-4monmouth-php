<?php
Zend_Loader::loadClass('FM_Models_BaseModel');
class FM_Models_FM_Feedback extends FM_Models_BaseModel {

	protected $_tableName = 'feedback';

	public function __construct() {
		parent::__construct(null, null, null, 'FM');
	}

	public function getAllFeedback() {
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName}";
		return $this->getMultipleRows($sql);
	}
	
	public function getFeedbackById($id) {
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} f WHERE f.id = {$id}";
		return $this->getSingleRow($sql);
	}

	public function getFeedbackByKeys($keys){
		$where = $this->_makeWhere($keys, 'f');
		//print $where; exit;
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} f WHERE {$where}";
		return $this->getSingleRow($sql);
	}

	public function getFeedbacksByKeys($keys){
		$where = $this->_makeWhere($keys, 'f');
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} f WHERE {$where}";
		return $this->getMultipleRows($sql);
	}
/**
	public function insertFeedback($args) {
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
	**/
}