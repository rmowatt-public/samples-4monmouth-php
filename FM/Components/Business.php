<?php
Zend_Loader::loadClass('FM_Components_Organization');
Zend_Loader::loadClass('FM_Models_FM_OrgdataBusiness');
Zend_Loader::loadClass('FM_Models_FM_SearchPrimaryCategories');
Zend_Loader::loadClass('FM_Models_FM_BzOrgCat');


class FM_Components_Business extends FM_Components_Organization {

	protected $category;
	protected $categories = array();
	protected $specialty;
	protected $keywords = array();
	private $_businessDataTable;

	public function __construct($keys = null) {
		if(parent::__construct($keys)) {
			$this->keywords = array();
			$this->_businessDataTable = new FM_Models_FM_OrgdataBusiness();
			$catModel = new FM_Models_FM_BzOrgCat();

			if(count($data = $this->_businessDataTable->getBusinessDataByOrgId($this->id))) {
				if(!is_array($data) || !array_key_exists('id', $data)){return false;}
				foreach ($data as $key=>$value) {
					if(property_exists($this, $key) && $key != 'id') {
						if($key == 'keywords') {
							$keywords = explode(',', $value);
							if(!is_array($keywords)){continue;}
							foreach ($keywords as $word) {
								$this->keywords[] = trim($word);
							}

						} else{
							$this->{$key} = $value;
						}
					}
				}
				$cats = $catModel->getCatIdsByKeys(array('orgId'=>$data['orgId']));
				foreach ($cats as $i=>$val) {
					$this->categories[] = $val['catId'];
				}
				if(!count($cats)) {
					$this->categories[] = $this->getCategory();
				}
				return true;
			} else {
				return false;
			}
		}
		return false;
	}

	public static function delete($keys) {
		$businessDataTable = new FM_Models_FM_OrgdataBusiness();
		if($org = new FM_Components_Organization($keys)) {
			$orgId = $org->getId();
			if($org->deleteOrg()) {
				if($businessDataTable->remove(array('orgId'=>$orgId))) {
					return true;
				}
			}
		}
		return false;
	}
	
	/**
	public static function setCats() {
		$orgModel = new FM_Models_FM_OrgdataBusiness();
		$orgTowns = new FM_Models_FM_BzOrgCat();
		
		$orgs = $orgModel->getAll();
		//print_r($orgs);
		//exit;
		foreach ($orgs as $key=>$value) {
			$orgTowns->insertRecord(array('orgId'=>$value['orgId'], 'catId'=>$value['category']));
		}
	}
**/

	/**
	 * @param $keywords the $keywords to set
	 */
	public function setKeywords($keywords) {
		$this->keywords = $keywords;
	}

	/**
	 * @param $specialty the $specialty to set
	 */
	public function setSpecialty($specialty) {
		$this->specialty = $specialty;
	}

	public function getCategories() {
		return $this->categories;
	}

	/**
	 * @param $category the $category to set
	 */
	public function setCategory($category) {
		$this->category = $category;
	}

	public function getCategoryName() {
		$catTable = new FM_Models_FM_SearchPrimaryCategories();
		$cat = $catTable->getCategoryByKeys(array('id'=>$this->getCategory()));
		if(count($cat)) {
			return $cat['name'];
		}
		return false;
	}

	public function getCategoryNames() {
		$catTable = new FM_Models_FM_SearchPrimaryCategories();
		$cats = array();
		foreach ($this->getCategories() as $key=>$value) {
			$cat = $catTable->getCategoryByKeys(array('id'=>$value));
			if(count($cat)) {
				$cats[] = $cat['name'];
			}
		}
		return $cats;
	}

	/**
	 * @return the $keywords
	 */
	public function getKeywords() {
		return $this->keywords;
	}

	/**
	 * @return the $specialty
	 */
	public function getSpecialty() {
		return $this->specialty;
	}

	/**
	 * @return the $category
	 */
	public function getCategory() {
		return $this->category;
	}


	public static function insertBusiness($args) {
		$businessDataTable = new FM_Models_FM_OrgdataBusiness();
		$orgsTable = new FM_Models_FM_BzOrgCat();
		$cats = array_key_exists('category', $args) ? $args['category'] : array();
		$args['category'] = 1;
		if($id = FM_Components_Organization::insert($args)){
			$args['orgId'] = $id;
			if(is_array($cats)) {
				foreach ($cats as $index=>$value) {
					$orgsTable->insertRecord(array('orgId'=>$id, 'catId'=>$value));
				}
			}
			if($bid = $businessDataTable->insertRecord($args)) {
				return $id;
			}
		}
		return false;
	}

	public static function updateBusiness($args) {
		//print_r($args);exit;
		$businessDataTable = new FM_Models_FM_OrgdataBusiness();
		$orgsTable = new FM_Models_FM_BzOrgCat();
		$cats = array_key_exists('category', $args) ? $args['category'] : array();
		$args['category'] = 1;
		if(FM_Components_Organization::update($args)){
			$orgId = $args['orgId'];
			unset($args['orgId']);
			if(is_array($cats) && count($cats)) {
				$orgsTable->remove(array('orgId'=>$orgId));
				foreach ($cats as $index=>$value) {
					$orgsTable->insertRecord(array('orgId'=>$orgId, 'catId'=>$value));
				}
			}
			if($businessDataTable->edit(array('orgId'=>$orgId), $args)) {
				return true;
			}
		}
		return true;
	}

	public static function getAllActive() {
		$orgData = new FM_Models_FM_Orgdata();
		$activeOrgs = $orgData->getOrgsByKeys(array('active'=>1, 'type'=>2));
		$orgs = array();
		foreach ($activeOrgs as $org) {
			$orgs[] = new FM_Components_Business(array('id'=>$org['id']));
		}
		return  (count($orgs)) ? $orgs : false;
	}
	
	public static function getActiveRecords() {
		$orgData = new FM_Models_FM_Orgdata();
		$activeOrgs = $orgData->getOrgsByKeys(array('active'=>1, 'type'=>2));
		return  (count($activeOrgs)) ? $activeOrgs : false;
	}
	
	public static function getActiveForRoot() {
		$orgs = array();
		$orgData = new FM_Models_FM_Orgdata();
		$catTable = new FM_Models_FM_BzorgCat();
		$activeOrgs = $orgData->getOrgsByKeys(array('type'=>2), 'name');
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

	public static function getRandom() {
		$orgData = new FM_Models_FM_Orgdata();
		$org = $orgData->getRandomOrg();
		return  new FM_Components_Organization(array('id'=>$org['id']));
	}

	public static function getAll() {
		$orgData = new FM_Models_FM_Orgdata();
		$orgs = $orgData->getOrgsByKeys(array('type'=>2));
		$allOrgs = array();
		foreach($orgs as $key=>$values) {
			$allOrgs[] = new FM_Components_Business(array('id'=>$values['id']));
		}
		return $allOrgs;
	}
	
	public static function getByCategory($catId) {
		$orgData = new FM_Models_FM_Orgdata();
		$orgs = $orgData->getOrgsByCategory($catId);
		$allOrgs = array();
		foreach($orgs as $key=>$values) {
			$allOrgs[] = new FM_Components_Business(array('id'=>$values['id']));
		}
		return $allOrgs;
	}	
	
	public static function getByCategoryForRoot($catId) {
		$orgData = new FM_Models_FM_Orgdata();
		$morgs = $orgData->getBzOrgsByCategoryForRoot($catId);
		$catTable = new FM_Models_FM_BzorgCat();
		foreach ($morgs as $org) {
			$o = $orgData->getOrgRecordsForRoot($org['id']);
			$o['cats'] = self::parseCats($catTable->getOrgNames($org['id']));
			$orgs[] = $o;
		}
		return $orgs;
		
	}
}

