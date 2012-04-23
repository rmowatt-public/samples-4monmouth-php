<?php
Zend_Loader::loadClass('FM_Models_BaseModel');
class FM_Models_FM_RealEstateListings extends FM_Models_BaseModel {

	protected $_tableName = 'realEstateLisitngs';

	public function __construct() {
		parent::__construct(null, null, null, 'FM');
	}

	public function getRealEstateListingById($id) {
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} m WHERE m.id = {$id}";
		return $this->getSingleRow($sql);
	}

	public function getRealEstateListingByKeys($keys){
		$where = $this->_makeWhere($keys, 'm');
		//print $where; exit;
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} m WHERE {$where}";
		return $this->getSingleRow($sql);
	}

	public function getRealEstateListingsByKeys($keys){
		$where = $this->_makeWhere($keys, 'm');
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} m WHERE {$where} ORDER BY id DESC";
		return $this->getMultipleRows($sql);
	}
}