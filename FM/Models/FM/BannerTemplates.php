<?php
Zend_Loader::loadClass('FM_Models_BaseModel');
class FM_Models_FM_BannerTemplates  extends FM_Models_BaseModel {

	protected $_tableName = 'bannertemplates';



	public function __construct() {
		parent::__construct('localhost', 'root', '', 'fm');
	}


	public function getTemplateByKeys($keys){
		$where = $this->_makeWhere($keys, 't');
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} t  WHERE {$where}";
		return $this->getSingleRow($sql);
	}

	public function getTemplatesByKeys($keys){
		$where = $this->_makeWhere($keys, 't');
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} t  WHERE {$where}";
		return $this->getMultipleRows($sql);
	}
	
	public function getAll() {
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} ORDER BY id DESC";
		return $this->getMultipleRows($sql);
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