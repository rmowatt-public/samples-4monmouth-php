<?php
Zend_Loader::loadClass('FM_Components_Organization');
Zend_Loader::loadClass('FM_Models_FM_OrgdataBusiness');
Zend_Loader::loadClass('FM_Models_FM_ContactUs');


class FM_Components_Email extends FM_Components_Organization {

	protected $id;
	protected $dept;
	protected $name;
	protected $email;
	protected $message;
	protected $orgId;
	protected $timestamp;

	private $_contactUsTable;

	public function __construct($keys = null) {
		//if(parent::__construct($keys)) {
			$this->_contactUsTable = new FM_Models_FM_ContactUs();
			if(count($data = $this->_contactUsTable->getRecordByKeys($keys))) {
				foreach ($data as $key=>$value) {
					if(property_exists($this, $key) ) {
							$this->{$key} = $value;
					}
				}
				return true;
			}
		//}
		return false;
	}

	public static function insertEmail($keys) {
		$contactUsTable = new FM_Models_FM_ContactUs();
		if($contactUsTable->insertRecord($keys)) {
			return true;
		}
		return false;
	}

	public static function getEmails($args) {
		$contactUsTable = new FM_Models_FM_ContactUs();
		if($args['dept'] == 0) {
			$records = $contactUsTable->getRecordsByKeys(array('orgId'=>0));
		} else {
			$records = $contactUsTable->getRecordsByKeys(array('dept'=>$args['dept']));
		}
		$emails = array();

		if(count($records)) {
			foreach($records as $key=>$value) {
				$email = new FM_Components_Email(array('id'=>$value['id']));
				$emails[] = $email;
			}
		}
		return $emails;
	}
	/**
	 * @param $timestamp the $timestamp to set
	 */
	public function setTimestamp($timestamp) {
		$this->timestamp = $timestamp;
	}

	/**
	 * @param $orgId the $orgId to set
	 */
	public function setOrgId($orgId) {
		$this->orgId = $orgId;
	}

	/**
	 * @param $message the $message to set
	 */
	public function setMessage($message) {
		$this->message = $message;
	}

	/**
	 * @param $email the $email to set
	 */
	public function setEmail($email) {
		$this->email = $email;
	}

	/**
	 * @param $name the $name to set
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @param $dept the $dept to set
	 */
	public function setDept($dept) {
		$this->dept = $dept;
	}

	/**
	 * @param $id the $id to set
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @return the $timestamp
	 */
	public function getTimestamp() {
		return $this->timestamp;
	}

	/**
	 * @return the $orgId
	 */
	public function getOrgId() {
		return $this->orgId;
	}

	/**
	 * @return the $message
	 */
	public function getMessage() {
		return $this->message;
	}

	/**
	 * @return the $email
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * @return the $name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return the $dept
	 */
	public function getDept() {
		return $this->dept;
	}

	/**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}

}