<?php
Zend_Loader::loadClass('FM_Models_BaseModel');
class FM_Models_FM_User extends FM_Models_BaseModel {

	protected $_tableName = 'user';
	protected $_userInfoTable = 'userdata';
	protected $_colNames = array();

	public function __construct() {
		parent::__construct('localhost', 'root', '', 'fm');
	}

	public function getUserById($id) {
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} u JOIN {$this->_dbName}.{$this->_userInfoTable} ud on u.id = ud.uid WHERE u.id = {$id}";
		return $this->getSingleRow($sql);
	}

	public function getAll() {
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} ORDER BY uname ASC";
		return $this->getMultipleRows($sql);
	}
	
	public function getAllForDD() {
		$sql = "SELECT u.*, ud.firstname, ud.lastname from {$this->_dbName}.{$this->_tableName} u JOIN {$this->_dbName}.userdata ud  on ud.uid = u.id ORDER BY uname ASC";
		return $this->getMultipleRows($sql);
	}

	public function getLike($like) {
		$parts = explode( ' ', $like);
		$r = array();
		foreach ($parts as $index=>$key) {
			$sql = "SELECT u.id, u.uname, ud.* from {$this->_dbName}.{$this->_tableName} u JOIN {$this->_dbName}.userdata ud  on ud.uid = u.id WHERE  (ud.firstname LIKE '%{$key}%' OR ud.lastname LIKE '%{$key}%') ORDER BY uname ASC";
			foreach ($this->getMultipleRows($sql) as $row=>$values) {
				$r[$values['id']] = $values;
			};
		}
		return $r;
	}

	public function getUserByKeys($keys){
		$where = '';
		foreach ($keys as $key=>$value) {
			$where .= " u.{$key} = '{$value}' AND";
		}
		$where = substr($where, 0, -3);
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} u JOIN {$this->_dbName}.{$this->_userInfoTable} ud on u.id = ud.uid WHERE {$where}";
		return $this->getSingleRow($sql);
	}

	public function getUserByEmail($email){
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} u JOIN {$this->_dbName}.{$this->_userInfoTable} ud on u.id = ud.uid WHERE ud.email = '{$email}' GROUP BY ud.email";
		return $this->getMultipleRows($sql);
	}

	public function authenticate($uname, $password) {
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} u WHERE u.uname = '{$uname}' AND u.pwd = '{$password}' ";
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