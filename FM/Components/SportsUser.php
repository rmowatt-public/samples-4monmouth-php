<?php
Zend_Loader::loadClass('FM_Components_BaseComponent');
Zend_Loader::loadClass('FM_Models_FM_SportsUsers');
Zend_Loader::loadClass('FM_Models_FM_UserOrg');
Zend_Loader::loadClass('FM_Models_FM_UserData');
Zend_Loader::loadClass('FM_Components_Business');
Zend_Loader::loadClass('FM_Components_NonProfit');
Zend_Loader::loadClass('FM_Components_Sports');
Zend_Loader::loadClass('FM_Components_Organization');
Zend_Loader::loadClass('Zend_Session_Namespace');

class FM_Components_SportsUser extends FM_Components_BaseComponent{

	protected $id;
	protected $pwd;
	protected $uname;
	protected $email;
	protected $orgId;
	protected $fullname;

	public function __construct($keys = null) {
		if(is_array($keys)) {
			$userModel = new FM_Models_FM_SportsUsers();
			$user = $userModel->getUserByKeys($keys);
			if(is_array($user)){
				foreach ($user as $key=>$value) {
					if(property_exists($this, $key)) {
						$this->{$key} = $value;
					}
				}
				return true;
			}
			return false;
		}
		return true;
	}


	public function wantsMail() {
		return ($this->maillist == 1) ? true : false;
	}

	public function getId() {
		return $this->id;
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

	public function getFullName() {
		return $this->fullname;
	}


	public static function getAll($id) {
		$userModel = new FM_Models_FM_SportsUsers();
		$users = $userModel->getAll($id);
		$allUsers = array();
		foreach ($users as $user) {
			$allUsers[] = new FM_Components_SportsUser(array('id'=>$user['id']));
		}
		return $allUsers;
	}

	public static function addUser($args) {
		$userModel = new FM_Models_FM_SportsUsers();
		if($id = $userModel->insertRecord($args))  {
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

	public static function deleteMember($args) {
		$userModel = new FM_Models_FM_SportsUsers();
		return $userModel->remove($args);
	}

	public static function update($keys, $args) {
		$userModel = new FM_Models_FM_SportsUsers();
		return $userModel->edit($keys, $args);
	}

	public static function authenticate($uname, $pwd, $orgId) {
		$userModel = new FM_Models_FM_SportsUsers();
		return $userModel->authenticate($uname, $pwd, $orgId);
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
				$allUsers[] = new FM_Components_SportsUser(array('id'=>$member['uid']));
			}
		}
		return $allUsers;
	}

	public static function generatePassword ($length = 8)
	{

		// start with a blank password
		$password = "";

		// define possible characters
		$possible = "0123456789bcdfghjkmnpqrstvwxyz";

		// set up a counter
		$i = 0;

		// add random characters to $password until $length is reached
		while ($i < $length) {

			// pick a random character from the possible ones
			$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);

			// we don't want this character if it's already in the password
			if (!strstr($password, $char)) {
				$password .= $char;
				$i++;
			}

		}

		// done!
		return $password;

	}



}

