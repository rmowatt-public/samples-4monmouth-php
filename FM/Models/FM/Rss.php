<?php
Zend_Loader::loadClass('FM_Models_BaseModel');
class FM_Models_FM_Rss extends FM_Models_BaseModel {

	protected $_tableName = 'rss';



	public function __construct() {
		parent::__construct('localhost', 'root', '', 'fm');
	}


	public function getRssByKeys($keys){
		$where = $this->_makeWhere($keys, 'u');
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} u  WHERE {$where}";
		return $this->getSingleRow($sql);
	}

	public function getOptions(){
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} ORDER BY id ASC LIMIT 1";
		return $this->getSingleRow($sql);
	}

	public function remove($args) {
		return $this->delete($this->_deleteString($args));
	}

	public function insertRecord($args) {
		return $this->insert($args);
	}
	
	public function edit($keys, $args) {
		return $this->update($keys, $args);
	}
}