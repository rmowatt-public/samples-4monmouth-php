<?php
Zend_Loader::loadClass('FM_Components_BaseComponent');
Zend_Loader::loadClass('FM_Models_FM_Orgdata');
Zend_Loader::loadClass('FM_Models_FM_UserOrg');
Zend_Loader::loadClass('FM_Components_Util_Logo');
Zend_Loader::loadClass('FM_Components_Util_MiniwebBanner');
Zend_Loader::loadClass('FM_Components_OrgConfig');
Zend_Loader::loadClass('FM_Components_HitCounter');
Zend_Loader::loadClass('FM_Models_FM_OrgRegion');
Zend_Loader::loadClass('FM_Models_FM_OrgTown');
Zend_Loader::loadClass('FM_Components_Util_Town');
Zend_Loader::loadClass('FM_Components_Util_Icon');

class FM_Components_Organization extends FM_Components_BaseComponent{

	protected $id;
	protected $name;
	protected $address1;
	protected $address2;
	protected $city;
	protected $state;
	protected $zip;
	protected $phone;
	protected $email;
	protected $maillist;
	protected $type;
	protected $description;
	protected $active;
	protected $region;
	protected $logo;
	protected $miniwebBanner;
	protected $admin;
	protected $hits;
	protected $regions = array();
	protected $website;
	protected $towns;
	protected $icon;
	protected $slug;
	protected $limeCard;

	private $_orgConfig;

	public function __construct($keys = null) {
		if(is_array($keys)) {
			$orgModel = new FM_Models_FM_Orgdata();
			$orgTowns = new FM_Models_FM_OrgTown();
			$org = $orgModel->getOrgByKeys($keys);
			if(is_array($org) && count($org)){
				foreach ($org as $key=>$value) {
					if(property_exists($this, $key)) {
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
				$this->_orgConfig = new FM_Components_OrgConfig(array('orgId'=>$this->id, 'type'=>$this->type), $this->id);
				$this->logo = new FM_Components_Util_Logo(array('orgId'=>$this->id));
				$this->miniwebBanner = new FM_Components_Util_MiniwebBanner(array('orgId'=>$this->id));
				$this->icon = new FM_Components_Util_Icon(array('orgId'=>$this->id));
				$this->hits = FM_Components_HitCounter::getHits($this->id);
				$orgRegion = new FM_Models_FM_OrgRegion();
				$regions = $orgRegion->getRecordsByKeys(array('orgId'=>$this->id));
				foreach ($regions as $region) {
					$this->regions[] = $region['regionId'];
				}
				$towns = $orgTowns->getTownIdsByKeys(array('orgId'=>$org['id']));
				foreach ($towns as $i=>$val) {
					$this->towns[] = $val['townId'];
				}
				if(!count($towns)) {
					$this->towns[] = $this->getRegion();
				}
				//print_r($this->logo);exit;
				return true;
			}
			return false;
		}
		return true;
	}

	public function getCats() {
		return $this->_orgConfig->getCats();
	}

	public function inRegion($regionId) {
		return in_array($regionId, $this->regions);
	}

	public function getHitCount() {
		return $this->hits;
	}

	public function getOrgConfig() {
		return $this->_orgConfig;
	}
	
	public function getAdminId() {
		return $this->admin;
	}

	public function getFullAddress() {
		$r = array();
		$r['address1'] = $this->getAddress1();
		$r['address2'] = $this->getAddress2();
		$r['city'] = $this->getCity();
		$r['state'] = $this->getState();
		$r['zip'] = $this->getZip();
		return $r;
	}

	/**
	 * @param $region the $region to set
	 */
	public function setRegion($region) {
		$this->region = $region;
	}

	/**
	 * @return the $region
	 */
	public function getRegion() {
		return $this->region;
	}


	public static function updateActive($keys = array()) {
		if(count($keys)) {
			$userModel = new FM_Models_FM_Orgdata();
			return $userModel->updateRecord($keys[0]);
		}
	}

	public function deleteOrg() {
		$userModel = new FM_Models_FM_Orgdata();
		if($userModel->remove(array('id'=>$this->getId()))) {
			return true;
		}
		return false;
	}

	/**
	 * @param $active the $active to set
	 */
	public function setActive($active) {
		$this->active = $active;
	}

	/**
	 * @return the $active
	 */
	public function getActive() {
		return $this->active;
	}

	/**
	 * @param $description the $description to set
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * @param $type the $type to set
	 */
	public function setType($type) {
		$this->type = $type;
	}

	/**
	 * @param $maillist the $maillist to set
	 */
	public function setMaillist($maillist) {
		$this->maillist = $maillist;
	}

	/**
	 * @param $email the $email to set
	 */
	public function setEmail($email) {
		$this->email = $email;
	}

	/**
	 * @param $phone the $phone to set
	 */
	public function setPhone($phone) {
		$this->phone = $phone;
	}

	/**
	 * @param $zip the $zip to set
	 */
	public function setZip($zip) {
		$this->zip = $zip;
	}

	/**
	 * @param $state the $state to set
	 */
	public function setState($state) {
		$this->state = $state;
	}

	/**
	 * @param $city the $city to set
	 */
	public function setCity($city) {
		$this->city = $city;
	}

	/**
	 * @param $address2 the $address2 to set
	 */
	public function setAddress2($address2) {
		$this->address2 = $address2;
	}

	/**
	 * @param $address1 the $address1 to set
	 */
	public function setAddress1($address1) {
		$this->address1 = $address1;
	}

	/**
	 * @param $name the $name to set
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @param $id the $id to set
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @return the $description
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @return the $type
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @return the $maillist
	 */
	public function getMaillist() {
		return $this->maillist;
	}

	/**
	 * @return the $email
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * @return the $phone
	 */
	public function getPhone() {
		return $this->phone;
	}

	/**
	 * @return the $zip
	 */
	public function getZip() {
		return $this->zip;
	}

	public function hasAddress() {
		if(!$this->getZip() || $this->getZip() == '') {
			return false;
		}
		return true;
	}

	/**
	 * @return the $state
	 */
	public function getState() {
		return $this->state;
	}

	/**
	 * @return the $city
	 */
	public function getCity() {
		return $this->city;
	}
	
	public function getSlug() {
		return ($this->slug) ? $this->slug : false;
	}

	/**
	 * @return the $address2
	 */
	public function getAddress2() {
		return $this->address2;
	}

	/**
	 * @return the $address1
	 */
	public function getAddress1() {
		return $this->address1;
	}

	/**
	 * @return the $name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}

	public function getLogoObj() {
		return $this->logo;
	}

	public function getIconObj() {
		return $this->icon;
	}

	public function getIconImage() {
		$noImage = array('noimagetv.jpg', 'photounavailable.jpg', 'noimggreyx.jpg', 'greybgd_noimg.jpg' );
		shuffle($noImage);
		return ($this->icon && $name = $this->icon->getFileName()) ? $name :  $noImage[0] ;
	}

	public function getMiniWebBannerObj() {
		return $this->miniwebBanner;
	}

	public function getWebsite() {
		return $this->website;
	}

	public function getLink() {
		$link = '';
		switch ($this->getType()) {
			case 2:
				$link = '/merchant/' . $this->getId();
				break;
			case 3:
				$link = '/org/' . $this->getId();
				break;
			case 4:
				$link = '/sports/' . $this->getId();
				break;
		}
		return $link;
	}
	
	public static function getOrgsLike($searchTerm, $type = 0) {
		$orgData = new FM_Models_FM_Orgdata();
		$activeOrgs = $orgData->getOrgsLike($searchTerm, $type);
		$catTable = ($type == 2) ? new FM_Models_FM_BzorgCat() : new FM_Models_FM_NporgCat();
		foreach ($activeOrgs as $org) {
			$o = $orgData->getOrgRecordsForRoot($org['id']);
			$o['cats'] = self::parseCats($catTable->getOrgNames($org['id']));
			$orgs[] = $o;
		}
		return $orgs;
		
	}
	
	public static function getMaillistOrgs($orgTypes) {
		$orgData = new FM_Models_FM_Orgdata();
		$activeOrgs = $orgData->getMaillistOrgs($orgTypes);
		return $activeOrgs;
	}
	
	public static function searchOrgs($searchTerm) {
		$orgData = new FM_Models_FM_Orgdata();
		$activeOrgs = $orgData->getOrgsLike($searchTerm);
		return $activeOrgs;
	}
	
	private function parseCats($cats) {
		$orgs = array();
		foreach ($cats as $k=>$v) {
			$orgs[] = $v['name'];
		}
		return $orgs;
	}
	
	public static function getOrgType($orgId) {
		$org = new FM_Components_Organization(array('id'=>$orgId));
		if($org->getId()) {
			return $org->getType();
		}
		return false;
	}

	public static function getMemberOrgs($uid) {
		$userOrgTable = new FM_Models_FM_UserOrg();
		$orgs = $userOrgTable->getRecordsByKeys(array('uid'=>$uid));
		$userOrgs = array();
		if(count($orgs)) {
			foreach ($orgs as $org) {
				$userOrgs[] = new FM_Components_Organization(array('id'=>$org['oid']));
			}
		}
		return $userOrgs;
	}

	public static function removeMember($oid, $uid) {
		$userOrgTable = new FM_Models_FM_UserOrg();
		return $userOrgTable->remove(array('oid'=>$oid, 'uid'=>$uid));
	}
	
	public static function getOrgMembers($oid) {
		$userOrgTable = new FM_Models_FM_UserOrg();
		return $userOrgTable->getRecordsByKeys(array('oid'=>$oid));
	}


	public static function getActiveOrgs() {
		$orgData = new FM_Models_FM_Orgdata();
		$activeOrgs = $orgData->getActive();
		$orgs = array();
		foreach ($activeOrgs as $org) {
			$orgs[] = new FM_Components_Organization(array('id'=>$org['id']));
		}
		return  (count($orgs)) ? $orgs : false;
	}
	
	
	public static function getActiveOrgRecords() {
		$orgData = new FM_Models_FM_Orgdata();
		$activeOrgs = $orgData->getActive();
		return  (count($activeOrgs)) ? $activeOrgs : false;
	}

	public static function getRandomBusiness($limit = 1) {
		$orgData = new FM_Models_FM_Orgdata();
		$org = $orgData->getRandomOrg($limit);
		foreach ($orgs as $org ) {
			$returnOrgs[] = new FM_Components_Organization(array('id'=>$org['id']));
		}
		return  $returnOrgs;
	}

	public static function getRandom($limit = 1, $nonProfit = false, $excluding = array(), $activeOnly = false) {
		$orgData = new FM_Models_FM_Orgdata();
		$orgs = $orgData->getRandomOrg($limit, $nonProfit, $activeOnly);
		$i = 1;
		$returnOrgs = array();
		foreach ($orgs as $org ) {
			$orgi = new FM_Components_Organization(array('id'=>$org['id']));
			$in = false;
			foreach ($orgi->getCats() as $oi=>$id) {
				if(in_array($id, $excluding)) {
					$in = true;
				}
			}
			if(!$in) {
					$returnOrgs[$i] = $orgi;
				}
			$i++;
		}
		return  (is_array($returnOrgs) && count($returnOrgs)) ? $returnOrgs : FM_Components_Organization::getRandom($limit, $nonProfit, $excluding);
	}

	public static function getRandomActive($limit = 1, $nonProfit = false, $excluding = array()) {
		$orgData = new FM_Models_FM_Orgdata();
		$orgs = $orgData->getRandomOrg($limit, $nonProfit);
		foreach ($orgs as $org ) {
			$org = new FM_Components_Organization(array('id'=>$org['id']));
			foreach ($org->getCats() as $i=>$id) {
				if(!in_array($id, $excluding)) {
					$returnOrgs[] = $org;
				}
			}
		}
		return  (count($returnOrgs)) ? $returnOrgs : FM_Components_Organization::getRandom($limit, $nonProfit, $excluding);
	}

	public static function insert($args) {
		$orgModel = new FM_Models_FM_Orgdata();
		$orgTowns = new FM_Models_FM_OrgTown();
		$userOrg = new FM_Models_FM_UserOrg();
		
		$towns = $args['town'];
		$args['town'] = 1;
		if($id = $orgModel->insertRecord($args)) {
			$userOrg->insertRecord(array('oid'=>$id, 'uid'=>$args['admin']));
			$orgRegion = new FM_Models_FM_OrgRegion();
			foreach ($args  as $value=>$t) {
				if(stristr($value, 'region')) {
					$regions = explode('_', $value);
					$orgRegion->insertRecord(array('orgId'=>$id, 'regionId'=>$regions[1]));
				}

			}
			if(is_array($towns)) {
				foreach ($towns as $index=>$value) {
					$orgTowns->insertRecord(array('orgId'=>$id, 'townId'=>$value));
				}
			}
			return $id;
		}
		return false;
	}
	
	public static function findBySlug($slug) {
		$orgModel = new FM_Models_FM_Orgdata();
		$org = $orgModel->getOrgByKeys(array('slug'=>$slug));
		return (is_array($org) && count($org)) ? $org : false;
	}
	
	public static function findSlugsLike($slug) {
		$orgModel = new FM_Models_FM_Orgdata();
		$org = $orgModel->getOrgSlugsLike($slug);
		return (is_array($org) && count($org)) ? $org : false;
	}
	
	/**
	public static function setTowns() {
		$orgModel = new FM_Models_FM_Orgdata();
		$orgTowns = new FM_Models_FM_OrgTown();
		
		$orgs = $orgModel->getAll();
		foreach ($orgs as $key=>$value) {
			$orgTowns->insertRecord(array('orgId'=>$value['id'], 'townId'=>$value['region']));
		}
	}


	public static function setTowns() {
		$orgModel = new FM_Models_FM_Orgdata();
		$orgTowns = new FM_Models_FM_OrgTown();
		$regions = new FM_Models_FM_OrgRegion();
		
		$otowns = $orgTowns->getAll();
		$i = 0;
		foreach ($otowns as $key=>$value) {
			$i++;
			$town = new FM_Components_Util_Town(array('id'=>$value['townId']));
			print $value['orgId'];
			$regions->insertRecord(array('orgId'=>$value['orgId'], 'regionId'=>$town->getRegion()));
		}
		print 'total ' . $i;
	}
	
**/

	public static function update($args) {
		$orgModel = new FM_Models_FM_Orgdata();
		$orgTowns = new FM_Models_FM_OrgTown();
		$towns = $args['town'];
		$args['town'] = 1;
		$id = $args['orgId'];
		unset($args['orgId']);
		$orgRegion = new FM_Models_FM_OrgRegion();
		
		$ri = 0;
		foreach ($args  as $value=>$t) {
			if(stristr($value, 'region')) {
				if($ri === 0) {
					$orgRegion->remove(array('orgId'=>$id));
					$ri++;
				}
				$regions = explode('_', $value);
				$orgRegion->insertRecord(array('orgId'=>$id, 'regionId'=>$regions[1]));
			}

		}
		if(is_array($towns)) {
			$orgTowns->remove(array('orgId'=>$id));
			foreach ($towns as $index=>$value) {
				$orgTowns->insertRecord(array('orgId'=>$id, 'townId'=>$value));
			}
		}
		if($orgModel->edit(array('id'=>$id), $args)) {
			return true;
		}
		return true;
	}

	public static function getTypeName($type) {
		switch($type) {
			case 2:
				return 'business';
			case 3:
				return 'non-profit';
			case 4:
				return 'sports';
			default:
				return 'all';
		}
	}
	
	
	public static function getTypeController($type) {
		switch($type) {
			case 2:
				return 'merchant';
			case 3:
				return 'org';
			case 4:
				return 'sports';
			default:
				return '/';
		}
	}
	
	public function getFormattedWebsite() {
		$website = (stristr($this->website, 'http://')) ? $this->website : 'http://' . $this->website;
		return $website;
	}
}

