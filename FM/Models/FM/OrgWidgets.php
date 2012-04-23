<?php
Zend_Loader::loadClass('FM_Models_BaseModel');
class FM_Models_FM_OrgWidgets extends FM_Models_BaseModel {

	protected $_tableName = 'org_widgets';



	public function __construct() {
		parent::__construct('localhost', 'root', '', 'fm');
	}


	public function getWidgetByKeys($keys){
		$where = $this->_makeWhere($keys, 'u');
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} u  WHERE {$where}";
		return $this->getSingleRow($sql);
	}

	public function getWidgetsByKeys($keys){
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

	public function edit($keys, $args) {
		return $this->update($keys, $args);
	}
}