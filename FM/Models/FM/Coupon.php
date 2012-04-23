<?php
Zend_Loader::loadClass('FM_Models_BaseModel');
class FM_Models_FM_Coupon  extends FM_Models_BaseModel {

	protected $_tableName = 'coupon';



	public function __construct() {
		parent::__construct('localhost', 'root', '', 'fm');
	}


	public function getCouponByKeys($keys, $active = false){
		if($active){$keys['active'] = 1;}
		$where = $this->_makeWhere($keys, 'u');
		$sql = "SELECT u.*, o.name as orgname from {$this->_dbName}.{$this->_tableName} u LEFT JOIN FM.orgdata o on u.orgId = o.id WHERE {$where} ";
		return $this->getSingleRow($sql);
	}

	public function getCouponsIn($keys){
		$in = implode(',', $keys);
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} u  WHERE id IN ( {$in} )";
		return $this->getMultipleRows($sql);
	}
	
	public function getCouponsByKeys($keys, $active = false){
		if($active){$keys['active'] = 1;}
		$where = $this->_makeWhere($keys, 'u');
		$sql = "SELECT u.*, o.name as orgname from {$this->_dbName}.{$this->_tableName} u LEFT JOIN FM.orgdata o on u.orgId = o.id WHERE {$where} ORDER BY id DESC";
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