<?php
Zend_Loader::loadClass('FM_Components_BaseComponent');
Zend_Loader::loadClass('FM_Models_FM_User');
Zend_Loader::loadClass('FM_Models_FM_UserOrg');
Zend_Loader::loadClass('FM_Models_FM_Orgdata');
Zend_Loader::loadClass('FM_Models_FM_UserData');
Zend_Loader::loadClass('FM_Components_Business');
Zend_Loader::loadClass('FM_Components_NonProfit');
Zend_Loader::loadClass('FM_Components_Sports');
Zend_Loader::loadClass('FM_Components_Organization');

class FM_Components_Member extends FM_Components_BaseComponent{

	protected $uid;
	protected $pwd;
	protected $uname;
	protected $firstname;
	protected $middlename;
	protected $lastname;
	protected $address1;
	protected $address2;
	protected $city;
	protected $state;
	protected $zip;
	protected $phone;
	protected $email;
	protected $maillist;
	protected $orgs = array();

	public function __construct($keys = null) {
		if(is_array($keys)) {
			$userModel = new FM_Models_FM_User();
			$user = $userModel->getUserByKeys($keys);
			if(is_array($user)){
				foreach ($user as $key=>$value) {
					if(property_exists($this, $key)) {
						$this->{$key} = $value;
					}
				}
				$orgModel = new FM_Models_FM_UserOrg();
				$orgDataModel = new FM_Models_FM_Orgdata();
				//print_r($orgDataModel->getMemberOrgsById($this->getId()));
				//exit;
				$this->orgs = array_map("FM_Components_Member::getOrgIds",$orgDataModel->getMemberOrgsById($this->getId()));
				return true;
			}
			return false;
		}
		return true;
	}

	public static function userNameExists($uname) {
		$userModel = new FM_Models_FM_User();
		$uname = $userModel->getUserByKeys(array('uname'=>$uname));
		return (count($uname) > 0);
	}

	public function getOrgs() {
		$orgs = array();
		foreach ($this->orgs as $index=>$orgId) {
			$org = new FM_Components_Organization(array('id'=>$orgId));
			$orgs[] = $org;
		}
		return $orgs;
	}

	public function getFirstName() {
		return $this->firstname;
	}

	public function getMiddleName() {
		return $this->middlename;
	}

	public function getLastName() {
		return $this->lastname;
	}

	public function getFullName() {
		return $this->firstname . ' ' . $this->middlename . ' ' . $this->lastname;
	}

	public function getAddress1() {
		return $this->address1;
	}

	public function getAddress2() {
		return $this->address2;
	}

	public function getCity() {
		return $this->city;
	}

	public function getState() {
		return $this->state;
	}

	public function getZip() {
		return $this->zip;
	}

	public function getPhone() {
		return $this->phone;
	}

	public function wantsMail() {
		return ($this->maillist == 1) ? true : false;
	}

	public function getId() {
		return $this->uid;
	}

	public function getUserName() {
		return $this->uname;
	}

	public function getEmail() {
		return $this->email;
	}

	public function getPassword() {
		return $this->pwd;
	}

	public function getOrgId() {
		if(count($this->orgs)) {
			return $this->orgs[0];
		}
		return false;
	}
	
	public function getOrgIdsOnly() {
		if(count($this->orgs)) {
			return $this->orgs;
		}
		return false;
	}

	public function isRoot() {
		return ($this->getId() == 25);
	}

	public function frontEndAdmin() {
		return ($this->getId() == 26);
	}


	public static function getOrgIds($el) {
		return $el['id'];
	}

	public static function getAll() {
		$userModel = new FM_Models_FM_User();
		$users = $userModel->getAll();
		$allUsers = array();
		foreach ($users as $user) {
			$allUsers[] = new FM_Components_Member(array('id'=>$user['id']));
		}
		return $allUsers;
	}

	public static function getMembersLike($like) {
		$userModel = new FM_Models_FM_User();
		$users = $userModel->getLike($like);
		$allUsers = array();
		foreach ($users as $user) {
			$allUsers[] = new FM_Components_Member(array('id'=>$user['id']));
		}
		return $allUsers;
	}

	public static function getMemberRecordsLike($like) {
		$userModel = new FM_Models_FM_User();
		$users = $userModel->getLike($like);
		return $users;
	}

	public static function getMemberRecord($id) {
		$userModel = new FM_Models_FM_User();
		$user = $userModel->getUserByKeys(array('id'=>$id));
		//print_r($user);exit;
		return $user;
	}



	public static function getAllForDropdown() {
		$userModel = new FM_Models_FM_User();
		$users = $userModel->getAllForDD();
		return $users;
	}

	public static function addMember($args) {
		$userModel = new FM_Models_FM_User();
		if($id = $userModel->insertRecord($args))  {
			$dataTable = new FM_Models_FM_UserData();
			$args['uid'] = $id;
			$dataTable->insertRecord($args);
			return $id;
		}
		return false;
	}

	public static function addUserOrg($args) {
		$orgModel = new FM_Models_FM_UserOrg();
		return $orgModel->insertRecord($args);
	}

	public function inOrg($orgId) {
		return in_array($orgId, array_values($this->orgs));

	}

	public static function getMembersByOrgType($orgType = 0) {
		$orgs = array();
		switch($orgType) {
			case 2 :
				$orgs = FM_Components_Business::getAll();
				break;
			case 3 :
				$orgs = FM_Components_NonProfit::getAll();
				break;
			case 4 :
				$orgs = FM_Components_Sports::getAll();
				break;
			default :
				return FM_Components_Member::getAll();
		}

		if(!count($orgs)){return $orgs;}//no data send empty array

		$ids = array();
		foreach ($orgs as $org) {
			$ids[] = $org->getId();
		}
		$userOrgTable = new FM_Models_FM_UserOrg();
		$users = $userOrgTable->getRecordsByDataSet('oid', $ids, 'uid');

		$allUsers = array();
		if(count($users)) {
			foreach ($users as $user) {
				$allUsers[$user['uid']] = new FM_Components_Member(array('id'=>$user['uid']));
			}
			return $allUsers;
		} else {
			return $users;//empty data set send empty array
		}
		return false;//everything failed
	}

	public static function getMemberRecordsByOrgType($orgType = 0) {
		$orgTable = new FM_Models_FM_Orgdata();
		$memberIds = $orgTable->getOrgMemberIdsByKeys(array('type'=>$orgType));
		//print_r($memberIds);exit;
		$userModel = new FM_Models_FM_User();
		$users = array();
		foreach ($memberIds as $key=>$values) {
			$users[$values['uid']] = $userModel->getUserByKeys(array('id'=>$values['uid']));
			$users[$values['admin']] = $userModel->getUserByKeys(array('id'=>$values['admin']));
		}
		return $users;
	}

	public static function getAllMemberRecords()	{
		$userModel = new FM_Models_FM_User();
		$records = $userModel->getAll();
		$users = array();
		foreach ($records as $key=>$values)	{
			$users[] = FM_Components_Member::getMemberRecord($values['id']);
		}
		return $users;
	}



	public static function deleteMember($args) {
		$result = false;
		$member = new FM_Components_Member($args);
		if($member) {
			$id = $member->getId();
			$userModel = new FM_Models_FM_User();
			$userOrgModel = new FM_Models_FM_UserOrg();
			$userDataModel = new FM_Models_FM_UserData();
			if($userModel->remove(array('id'=>$id))) {
				$userDataModel->remove(array('uid'=>$id));
				$userOrgModel->remove(array('uid'=>$id));
				$result = true;
			}
		}
		return $result;
	}

	public static function update($keys, $args) {
		$userModel = new FM_Models_FM_User();
		$userOrgModel = new FM_Models_FM_UserOrg();
		$userDataModel = new FM_Models_FM_UserData();
		$userKeys = $keys;
		if(array_key_exists('uid', $userKeys)) {
			unset($userKeys['uid']);
			$userKeys['id'] = $keys['uid'];
		}
		if(
		$userModel->edit($userKeys, $args) ||
		$userOrgModel->edit($keys, $args) ||
		$userDataModel->edit($keys, $args)
		) {
			return true;
		} else {
			return false;
		}
	}

	public static function memberExists($id) {
		$userModel = new FM_Models_FM_User();
		return (is_array($userModel->getUserById($id))) ? true : false;
	}

	public static function getMemberByEmail($email) {
		$userModel = new FM_Models_FM_User();
		$members = $userModel->getUserByEmail($email);
		$allUsers = array();
		foreach ($members as $index=>$member) {
			if($member['id']) {
				$allUsers[] = new FM_Components_Member(array('id'=>$member['uid']));
			}
		}
		return $allUsers;
	}


}

