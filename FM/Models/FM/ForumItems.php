<?php
Zend_Loader::loadClass('FM_Models_BaseModel');
class FM_Models_FM_ForumItems extends FM_Models_BaseModel {

	protected $_tableName = 'forumItem';

	public function __construct() {
		parent::__construct(null, null, null, 'FM');
	}

	public function getForumItemById($id) {
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} f WHERE f.id = {$id}";
		return $this->getSingleRow($sql);
	}

	public function getForumItemByKeys($keys){
		$where = $this->_makeWhere($keys, 'f');
		//print $where; exit;
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} f WHERE {$where}";
		return $this->getSingleRow($sql);
	}

	public function getForumItemsByKeys($keys){
		$where = $this->_makeWhere($keys, 'f');
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} f WHERE {$where} ORDER BY timestamp DESC";
		return $this->getMultipleRows($sql);
	}

	public function insertForumItem($args) {
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