<?php
Zend_Loader::loadClass('FM_Models_BaseModel');
class FM_Models_FM_Orgdata extends FM_Models_BaseModel {

	protected $_tableName = 'orgdata';

	public function __construct() {
		parent::__construct('localhost', 'root', '', 'fm');
	}

	
	public function getActive() {
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} u  WHERE active  =  1 ORDER BY name ASC";
		return $this->getMultipleRows($sql);
	}
	
	public function getRandomOrg($limit = 1, $nonProfit = false, $activeOnly = false) {
		if(!$nonProfit){$np = ' WHERE u.type = 2 ';}
		else {
			$np = ' WHERE u.type = 3 ';
		}
		if($activeOnly) {
			$np .= ' AND u.active = 1 ';
		}
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} u {$np} GROUP BY id ORDER BY RAND() LIMIT {$limit}  ";
		return $this->getMultipleRows($sql);
	}

	public function getOrgByKeys($keys){
		$where = $this->_makeWhere($keys, 'u');
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} u  WHERE {$where}";
		return $this->getSingleRow($sql);
	}
	
	public function getOrgSlugsLike($slug) {
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} u  WHERE u.slug LIKE '%{$slug}%'ORDER BY id ASC";
		return $this->getMultipleRows($sql);
	}
	
	public function getMaillistOrgs($orgTypes) {
		if(!is_array($orgTypes)) {
			$orgTypes = array($orgTypes);
		}
		$in = '(';
		foreach ($orgTypes as $key=>$index) {
			$in .= $index .',';
		}
		$in .= '999)';
		//$sql = "SELECT uo.uid, od.admin from {$this->_dbName}.{$this->_tableName} od LEFT JOIN user_org uo ON u.id  = uo.oid WHERE od.maillist = 1 AND od.type IN {$in} GROUP BY uo.uid";
		$sql = "SELECT email, uo.* from {$this->_dbName}.{$this->_tableName} od LEFT JOIN user_org uo ON od.id  = uo.oid  WHERE od.maillist = 1 AND od.type IN {$in}";
		//print $sql;
		return $this->getMultipleRows($sql);
	}

	public function getOrgsByKeys($keys, $orderKey = null){
		$orderKey = ($orderKey) ? $orderKey : 'id';
		$where = $this->_makeWhere($keys, 'u');
		$sql = "SELECT u.*, c.count from {$this->_dbName}.{$this->_tableName} u LEFT JOIN FM.hitcounter c ON u.id = c.id WHERE {$where} ORDER BY {$orderKey} ASC";
		return $this->getMultipleRows($sql);
	}
	
	public function getMemberOrgsById($id){
		$orderKey = ($orderKey) ? $orderKey : 'id';
		$where = $this->_makeWhere($keys, 'u');
		$sql = "SELECT u.* from {$this->_dbName}.{$this->_tableName} u LEFT JOIN user_org uo ON u.id  = uo.oid WHERE u.admin = {$id} OR uo.uid = {$id} GROUP BY u.id";
		return $this->getMultipleRows($sql);
	}
	
	public function getOrgMemberIdsByKeys($keys) {
		$where = $this->_makeWhere($keys, 'u');
		$sql = "SELECT c.uid, u.admin  from {$this->_dbName}.{$this->_tableName} u LEFT JOIN FM.user_org c ON u.id = c.oid WHERE {$where} ";
		return $this->getMultipleRows($sql);
	
	}
	
	public function getOrgsByCategory($catId){
		//$where = $this->_makeWhere($keys, 'u');
		$sql = "SELECT u.*from {$this->_dbName}.{$this->_tableName} u  JOIN {$this->_dbName}.bzorg_cat b on b.orgId = u.id  WHERE b.catId = {$catId} ORDER BY u.id ASC";
		return $this->getMultipleRows($sql);
	}
	
	public function getBzOrgsByCategoryForRoot($catId){
		//$where = $this->_makeWhere($keys, 'u');
		$sql = "SELECT u.id, u.name, u.email, u.active, h.count from {$this->_dbName}.{$this->_tableName} u  LEFT JOIN {$this->_dbName}.hitcounter h on u.id = h.orgId JOIN {$this->_dbName}.bzorg_cat b on b.orgId = u.id  WHERE b.catId = {$catId} ORDER BY u.id ASC";
		return $this->getMultipleRows($sql);
	}
	
	public function getNpOrgsByCategoryForRoot($catId){
		//$where = $this->_makeWhere($keys, 'u');
		$sql = "SELECT u.id, u.name, u.email, u.active, h.count from {$this->_dbName}.{$this->_tableName} u  LEFT JOIN {$this->_dbName}.hitcounter h on u.id = h.orgId JOIN {$this->_dbName}.nporg_cat b on b.orgId = u.id  WHERE b.catId = {$catId} ORDER BY u.id ASC";
		//print $sql;exit;
		return $this->getMultipleRows($sql);
	}
	
	public function getOrgRecordsForRoot($id) {
		$sql = "SELECT u.id, u.name, u.email, u.active, h.count from {$this->_dbName}.{$this->_tableName} u  LEFT JOIN {$this->_dbName}.hitcounter h on u.id = h.orgId  WHERE u.id = {$id} ORDER BY id ASC";
		return $this->getSingleRow($sql);
	}

	public function remove($args) {
		return $this->delete($this->_deleteString($args));
	}

	public function insertRecord($args) {
		return $this->insert($args);
	}

	public function updateRecord($args) {
		return $this->update(array('id'=>$args['id']) , array('active'=>$args['active']));
	}
	
	public function getOrgsLike($searchTerm, $type = 0) {
		$and = ($type == 0) ? '' : " AND u.type = {$type} ";
		$sql = "SELECT u.id, u.name, u.email, u.active, h.count from {$this->_dbName}.{$this->_tableName} u  LEFT JOIN {$this->_dbName}.hitcounter h on u.id = h.orgId  WHERE u.name LIKE '%{$searchTerm}%' {$and} ORDER BY id ASC";
		return $this->getMultipleRows($sql);
	}
	
	public function limecardSearch($ids, $searchTerm) {
		$in = count($ids) ? " OR u.id IN ({$this->arrayToInString($ids)}) " : '';
		$sql = "SELECT id, type, name, address1, city, state, zip, phone, website from {$this->_dbName}.{$this->_tableName} u WHERE u.name LIKE '%{$searchTerm}%' OR u.keywords LIKE '%{$searchTerm}%' OR u.description LIKE '%{$searchTerm}%' {$in} GROUP BY u.id";
		return $this->getMultipleRows($sql);
	}
	
	public function alphabeticalSearch($searchTerm) {
		$sql = "SELECT u.*,bz.catId, pc.name as catName from {$this->_dbName}.{$this->_tableName} u  
				LEFT JOIN bzorg_cat bz ON u.id = bz.orgId 
				LEFT JOIN search_primaryCategories pc ON bz.catId = pc.id
				WHERE u.name LIKE '{$searchTerm}%'
				AND u.limeCard = 1
				AND pc.parent = 0 
				ORDER BY u.id DESC";
				return $this->getMultipleRows($sql);
	}
	
	public function catSearch($searchTerm) {
		$sql = "SELECT u.*, bz.catId, pc.name as catName from {$this->_dbName}.{$this->_tableName} u  
				LEFT JOIN bzorg_cat bz ON u.id = bz.orgId 
				LEFT JOIN search_primaryCategories pc ON bz.catId = pc.id
				WHERE pc.id = {$searchTerm}
				AND u.limeCard = 1
				ORDER BY u.id DESC";
				return $this->getMultipleRows($sql);
	}
	
	public function regionSearch($regionArray) {
		$sql = "SELECT o.*, pc.name as catName FROM {$this->_dbName}.{$this->_tableName} o
				LEFT JOIN bzorg_cat bz ON o.id = bz.orgId 
				LEFT JOIN search_primaryCategories pc ON bz.catId = pc.id
				LEFT JOIN FM.org_town ot ON ot.orgId = o.id WHERE ot.townId IN (". $this->arrayToInString($regionArray) .") 
				AND o.type = 2 
				AND o.limeCard = 1
				GROUP BY o.id ORDER BY o.active DESC, o.name ASC ";
		return $this->getMultipleRows($sql);
	}
	
	//public function edit($keys, $args) {
		//return $this->update($keys, $args);
	//}
}