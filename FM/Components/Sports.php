<?php
Zend_Loader::loadClass('FM_Components_Organization');
Zend_Loader::loadClass('FM_Models_FM_OrgdataSports');
Zend_Loader::loadClass('FM_Models_FM_SportOptions');
Zend_Loader::loadClass('FM_Models_FM_SearchPrimaryCategories');


class FM_Components_Sports extends FM_Components_Organization {

	protected $category;
	protected $specialty;
	protected $protected;
	protected $keywords = array();
	private $_businessDataTable;

	public function __construct($keys = null) {
		if(parent::__construct($keys)) {
			$sportsDataModel = new FM_Models_FM_OrgdataSports();
			$sportData = $sportsDataModel->getSportsDataByOrgId($this->getId());
			$this->category = $sportData['category'];
			$this->protected = $sportData['protected'];
			return true;
		}
		return false;
	}

	public static function delete($keys) {
		$businessDataTable = new FM_Models_FM_OrgdataBusiness();
		if($org = new FM_Components_Organization($keys)) {
			if($org->deleteOrg()) {
				return true;
			}
		}
		return false;
	}

	public function isProtected() {
		return ($this->protected == 1) ? true : false;
	}


	public static function insertSports($args) {
		$businessDataTable = new FM_Models_FM_OrgdataBusiness();
		if($id = FM_Components_Organization::insert($args)){
			$sportsTable = new FM_Models_FM_OrgdataSports();
			$args['orgId'] = $id;
			$sportsTable->insertRecord($args);
			return $id;
		}
		return false;
	}

	public static function update($args) {
		$sportsTable = new FM_Models_FM_OrgdataSports();
		FM_Components_Organization::update($args);
		$orgId = $args['orgId'];
		unset($args['orgId']);
		if($sportsTable->edit(array('orgId'=>$orgId), $args)) {
			return true;
		}
		return true;

		return false;
	}


	public static function updateProtected($args) {
		$sportsTable = new FM_Models_FM_OrgdataSports();
		$orgId = $args['orgId'];
		unset($args['orgId']);
		if($sportsTable->edit(array('orgId'=>$orgId), $args)) {
			return true;
		}
		return true;

		return false;
	}

	public static function getRandom() {
		$orgData = new FM_Models_FM_Orgdata();
		$org = $orgData->getRandomOrg();
		return  new FM_Components_Organization(array('id'=>$org['id']));
	}

	public static function getAll() {
		$orgData = new FM_Models_FM_Orgdata();
		$orgs = $orgData->getOrgsByKeys(array('type'=>4));
		$allOrgs = array();
		foreach($orgs as $key=>$values) {
			$allOrgs[] = new FM_Components_Sports(array('id'=>$values['id']));
		}
		return $allOrgs;
	}

	public static function getAllForRoot() {
		$orgData = new FM_Models_FM_Orgdata();
		$orgs = $orgData->getOrgsByKeys(array('type'=>4), 'name');
		return $orgs;
	}
	
	public static function getByCategoryForRoot($catId) {
		$orgData = new FM_Models_FM_Orgdata();
		$morgs = array();
		$catTable = new FM_Models_FM_OrgdataSports();
		$orgs = $catTable->getOrgsByKeys(array('category'=>$catId));
		foreach ($orgs as $org) {
			$data = $orgData->getOrgByKeys(array('id'=>$org['orgId']));
			if($data['id']) {
				$morgs[] = $data;
			}
		}
		return $morgs;
	}

	public static function getCategoryName($id) {
		$orgData = new FM_Models_FM_SportOptions();
		$data = $orgData->getSportByKeys(array('id'=>$id));
		return $data['name'];
	}
	
	public function getCategory() {
		return $this->category;
	}
}

