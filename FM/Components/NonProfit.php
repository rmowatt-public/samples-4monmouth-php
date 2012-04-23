<?php
Zend_Loader::loadClass('FM_Components_Organization');
Zend_Loader::loadClass('FM_Models_FM_OrgdataBusiness');
Zend_Loader::loadClass('FM_Models_FM_SearchPrimaryCategories');
Zend_Loader::loadClass('FM_Models_FM_NpOrgCat');


class FM_Components_NonProfit extends FM_Components_Organization {

	protected $category;
	protected $specialty;
	protected $keywords = array();
	protected $categories;
	private $_businessDataTable;

	public function __construct($keys = null) {
		$catsModel = new FM_Models_FM_NporgCat();
		if(parent::__construct($keys)) {
			$cats = $catsModel->getCatIdsByKeys(array('orgId'=>$keys['id']));
			foreach ($cats as $i=>$val) {
				$this->categories[] = $val['catId'];
			}
			if(!count($cats)) {
				$this->categories[] = $this->getCategory();
			}
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

	public function getCategoryName() {
		$catTable = new FM_Models_FM_SearchPrimaryCategoriesOrgs();
		$cat = $catTable->getCategoryByKeys(array('id'=>$this->getCategory()));
		if(count($cat)) {
			return $cat['name'];
		}
		return false;
	}

	public function getCategoryNames() {
		$catTable = new FM_Models_FM_SearchPrimaryCategoriesOrgs();
		$cats = array();
		foreach ($this->getCategories() as $key=>$value) {
			$cat = $catTable->getCategoryByKeys(array('id'=>$value));
			if(count($cat)) {
				$cats[] = $cat['name'];
			}
		}
		return $cats;
	}

	public function getCategories() {
		return $this->categories;
	}

	public function getCategory() {
		return $this->category;
	}

	public static function insertNonProfit($args) {
		$orgsTable = new FM_Models_FM_NpOrgCat();
		$cats = array_key_exists('category', $args) ? $args['category'] : array();
		$args['category'] = 1;
		if($id = FM_Components_Organization::insert($args)){
			if(is_array($cats)) {
				foreach ($cats as $index=>$value) {
					$orgsTable->insertRecord(array('orgId'=>$id, 'catId'=>$value));
				}
			}
			return $id;
		}
		return false;
	}

	public static function getRandom() {
		$orgData = new FM_Models_FM_Orgdata();
		$org = $orgData->getRandomOrg();
		return  new FM_Components_Organization(array('id'=>$org['id']));
	}

	public static function getAll() {
		$orgData = new FM_Models_FM_Orgdata();
		$orgs = $orgData->getOrgsByKeys(array('type'=>3));
		$allOrgs = array();
		foreach($orgs as $key=>$values) {
			$allOrgs[] = new FM_Components_NonProfit(array('id'=>$values['id']));
		}
		return $allOrgs;
	}


	public static function update($args) {
		$businessDataTable = new FM_Models_FM_OrgdataBusiness();
		$orgsTable = new FM_Models_FM_NpOrgCat();
		$cats = array_key_exists('category', $args) ? $args['category'] : array();
		if(FM_Components_Organization::update($args)){
			$orgId = $args['orgId'];
			if(is_array($cats)) {
				$orgsTable->remove(array('orgId'=>$orgId));
				foreach ($cats as $index=>$value) {
					$orgsTable->insertRecord(array('orgId'=>$orgId, 'catId'=>$value));
				}
			}
			return true;
		}
		return false;
	}

	public static function getActiveForRoot() {
		$orgs = array();
		$orgData = new FM_Models_FM_Orgdata();
		$catTable = new FM_Models_FM_NporgCat();
		$activeOrgs = $orgData->getOrgsByKeys(array('type'=>3), 'name');
		foreach ($activeOrgs as $org) {
			$o = $orgData->getOrgRecordsForRoot($org['id']);
			$o['cats'] = self::parseCats($catTable->getOrgNames($org['id']));
			$orgs[] = $o;
		}
		return $orgs;
	}

	private function parseCats($cats) {
		$orgs = array();
		foreach ($cats as $k=>$v) {
			$orgs[] = $v['name'];
		}
		return $orgs;
	}

	public static function getByCategoryForRoot($catId) {
		$orgData = new FM_Models_FM_Orgdata();
		$morgs = $orgData->getNpOrgsByCategoryForRoot($catId);
		
		$catTable = new FM_Models_FM_NporgCat();
		foreach ($morgs as $org) {
			$o = $org;
			$o['cats'] = self::parseCats($catTable->getOrgNames($org['id']));
			$orgs[] = $o;
		}
		return $orgs;

	}
}

