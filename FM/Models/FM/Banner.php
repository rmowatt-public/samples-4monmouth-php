<?php
Zend_Loader::loadClass('FM_Models_BaseModel');
class FM_Models_FM_Banner extends FM_Models_BaseModel {

	protected $_colNames = array (
	'id',
	'oid',
	'type',
	'height',
	'width',
	'alt',
	'title',
	'url',
	'active',
	'name',
	'path',
	'medianame');

	protected $_tableName = 'banner';

	public function __construct() {
		parent::__construct(null, null, null, 'FM');
	}

	public function getBannerById($id) {
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} b WHERE b.id = {$id}";
		return $this->getSingleRow($sql);
	}

	public function getBannerByKeys($keys){
		$where = $this->_makeWhere($keys, 'b');
		//print $where; exit;
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} b WHERE {$where}";
		return $this->getSingleRow($sql);
	}

	public function getBannersByKeys($keys){
		$where = $this->_makeWhere($keys, 'b');
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} b WHERE {$where}";
		return $this->getMultipleRows($sql);
	}

	/**
	public function getRandom($keys = array(), $limit = null, $ni,  $oid = array()){
			//print __LINE__;exit;
		$oids = (count($oid)) ? implode(',', $oid) : false;
		$orIn = ($oids) ? ' AND b.oid NOT IN ('. $oids .') ' : '';
		$ids = (count($ni)) ? implode(',', $ni) : false;
		$notIn = ($ids) ? ' AND bz.catId = NULL OR  bz.catId NOT IN ('. $ids .') ' : '';
		$where = (count($keys)) ? ' WHERE ' . $this->_makeWhere($keys, 'b')  . " AND b.active = 1 " : ' WHERE b.active = 1 ';
		$limit = ($limit) ? ' LIMIT ' . $limit : '';
		$join = "LEFT JOIN {$this->_dbName}.orgdata od on tbl.oid = od.id WHERE od.active = 1 ";
		$sql = "SELECT tbl.*, od.type as btype, od.active as b_active from (SELECT b.* from {$this->_dbName}.{$this->_tableName} b RIGHT JOIN {$this->_dbName}.bzorg_cat bz ON b.oid = bz.orgId {$where} {$notIn} {$orIn} ORDER BY RAND() ) as tbl  {$join} GROUP BY tbl.oid ORDER BY RAND() {$limit} ";
		print $sql;exit;
		return $this->getMultipleRows($sql);
	}	
	**/

	public function getRandom($keys = array(), $limit = null, $ni,  $oid = array()){
		//print __LINE__;exit;
		$oids = (count($oid)) ? implode(',', $oid) : false;
		$orIn = ($oids ) ? ' AND b.oid NOT IN ('. $oids .') ' : '';
		$ids = (count($ni)) ? implode(',', $ni) : false;
		$notIn = ($ids) ? ' AND (bz.catId NOT IN ('. $ids .')  OR bz.orgId IS NULL) ' : '';
		$where = (count($keys)) ? ' WHERE ' . $this->_makeWhere($keys, 'b')  . " AND b.active = 1 " : ' WHERE b.active = 1 ';
		$limit = ($limit) ? ' LIMIT ' . $limit : '';
		$join = "LEFT JOIN {$this->_dbName}.orgdata od on tbl.oid = od.id WHERE od.active = 1 ";
		$sql = "SELECT tbl.*, od.type as btype, od.active as b_active from (SELECT bz.orgId, b.* from {$this->_dbName}.{$this->_tableName} b LEFT JOIN {$this->_dbName}.bzorg_cat bz ON b.oid = bz.orgId {$where} {$notIn} {$orIn} ORDER BY RAND() ) as tbl  {$join} GROUP BY tbl.oid ORDER BY RAND() {$limit} ";
		//print $sql;
		return $this->getMultipleRows($sql);
	}

	public function getBusinessRandom($keys = array(), $limit = null, $ni, $oid = array()){
		$where = (count($keys)) ? ' WHERE ' . $this->_makeWhere($keys, 'b')  . " AND b.active = 1 " : ' WHERE b.active = 1 ';
		$ids = (count($ni)) ? implode(',', $ni) : false;
		$oids = (count($oid)) ? implode(',', $oid) : false;
		$notIn = ($ids) ? ' AND bz.catId NOT IN ('. $ids .') ' : '';
		$orIn = ($oids) ? ' AND b.oid NOT IN ('. $oids .') ' : '';
		$limit = ($limit) ? ' LIMIT ' . $limit : '';
		$join = "LEFT JOIN {$this->_dbName}.orgdata od on tbl.oid = od.id WHERE od.active = 1 ";
		$sql = "SELECT tbl.*, od.active from (SELECT b.* from {$this->_dbName}.{$this->_tableName} b RIGHT JOIN {$this->_dbName}.bzorg_cat bz ON b.oid = bz.orgId {$where} {$notIn} {$orIn} ORDER BY RAND() ) as tbl  {$join} GROUP BY tbl.oid ORDER BY RAND() {$limit} ";
		//print $sql;exit;
		return $this->getMultipleRows($sql);
	}

	public function insertBanner($args) {
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