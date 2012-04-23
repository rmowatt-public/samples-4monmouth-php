<?php
Zend_Loader::loadClass('FM_Models_BaseModel');
class FM_Models_FM_SportsUsers extends FM_Models_BaseModel {

	protected $_tableName = 'sportsusers';
	protected $_userInfoTable = 'userdata';
	protected $_colNames = array();

	public function __construct() {
		parent::__construct('localhost', 'root', '', 'fm');
	}

	public function getUserById($id) {
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} u WHERE u.id = {$id}";
		return $this->getSingleRow($sql);
	}

	public function getAll($orgId) {
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} where orgId = {$orgId} ORDER BY uname ASC";
		return $this->getMultipleRows($sql);
	}

	public function getUserByKeys($keys){
		$where = '';
		foreach ($keys as $key=>$value) {
			$where .= " u.{$key} = '{$value}' AND";
		}
		$where = substr($where, 0, -3);
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} u WHERE {$where}";
		return $this->getSingleRow($sql);
	}

	public function getUserByEmail($email){
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} u  WHERE u.email = '{$email}' ";
		return $this->getMultipleRows($sql);
	}
	
	public function authenticate($uname, $password, $oid) {
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} u WHERE u.uname = '{$uname}' AND u.pwd = '{$password}' AND u.orgId = '{$oid}'";
		$results = $this->getSingleRow($sql);
		if(is_array($results) && array_key_exists('id', $results)){
			return $results;
		}
		return false;
	}

	public function insertRecord($args) {
		return $this->insert($args);
	}

	public function remove($args) {
		return $this->delete($this->_deleteString($args));
	}
	
	public function edit($keys, $args) {
		return $this->update($keys, $args);
	}
}