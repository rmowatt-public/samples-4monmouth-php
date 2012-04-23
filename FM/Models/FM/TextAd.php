<?php
Zend_Loader::loadClass('FM_Models_BaseModel');
class FM_Models_FM_TextAd extends FM_Models_BaseModel {

	protected $_tableName = 'textad';

	public function __construct() {
		parent::__construct(null, null, null, 'FM');
	}

	public function getTextAdById($id) {
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} m WHERE m.id = {$id}";
		return $this->getSingleRow($sql);
	}

	public function getTextAdByKeys($keys){
		$where = $this->_makeWhere($keys, 'm');
		//print $where; exit;
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} m WHERE {$where}";
		return $this->getSingleRow($sql);
	}

	public function getTextAdsByKeys($keys){
		$where = $this->_makeWhere($keys, 'm');
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} m WHERE {$where}";
		return $this->getMultipleRows($sql);
	}
	
	public function getRandom($keys = array(), $limit = 1){
		$where = (count($keys)) ? ' WHERE ' . $this->_makeWhere($keys, 'b') . ' AND b.active = 1 AND o.active = 1 ' : ' WHERE b.active = 1 AND o.active = 1 ';
		$limit = ($limit) ? ' LIMIT ' . $limit : '';
		$sql = "SELECT b.* from {$this->_dbName}.{$this->_tableName} b RIGHT JOIN {$this->_dbName}.orgdata o on b.orgId = o.id {$where} GROUP BY o.id ORDER BY RAND()  {$limit} ";
		return $this->getMultipleRows($sql);
	}	
}