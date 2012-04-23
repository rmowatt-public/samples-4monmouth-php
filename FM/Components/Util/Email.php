<?php
Zend_Loader::loadClass('FM_Models_FM_OrgEmail');

class FM_Components_Util_Email {

	protected $id;
	protected $orgId;
	protected $subject;
	protected $content;
	protected $date;



	public function __construct($keys) {
		$this->_emailModel = new FM_Models_FM_OrgEmail();
		if($email = $this->_emailModel->getRecordByKeys($keys)) {
			if(count($email)){
				foreach ($email as $key=>$value) {
					if(property_exists($this, $key)) {
						$this->{$key} = $value;
					}
				}
				return true;
			}
		}
		return false;
	}

	public static function getAll($orgId = false) {
		$model = new FM_Models_FM_OrgEmail();
		if(!$orgId) {
			$emails = $model->getAll();
		} else {
			$emails = $model->getRecordsByKeys(array('orgId'=>$orgId));
		}
		$emailsArray = array();
		foreach($emails as $key=>$values) {
			$emailsArray[] = new FM_Components_Util_Email(array('id'=>$values['id']));
		}
		return $emailsArray;
	}


	public static function getLastTwenty($orgId = false) {
		$model = new FM_Models_FM_OrgEmail();
		$emails = $model->getLastTwenty(array('orgId'=>$orgId));
		$emailsArray = array();
		foreach($emails as $key=>$values) {
			$emailsArray[] = new FM_Components_Util_Email(array('id'=>$values['id']));
		}
		return $emailsArray;
	}

	/**
	 * @return the $abbr
	 */
	public function getDate() {
		return $this->date;
	}

	public function getOrgId() {
		return $this->orgId;
	}

	/**
	 * @return the $state
	 */
	public function getRecord() {
		return $this->email;
	}

	/**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}

	public function getSubject() {
		return $this->subject;
	}

	public function getContent() {
		return $this->content;
	}

	public static function insertEmail($args) {
		$emailModel = new FM_Models_FM_OrgEmail();
		return $emailModel->insert($args);
	}

	public static function updateEmail($args =array(), $new = array()) {
		//print_r($args);print_r($new);exit;
		$emailModel = new FM_Models_FM_OrgEmail();
		return $emailModel->edit($args, $new);
	}


	public static function delete($keys) {
		$emailModel = new FM_Models_FM_OrgEmail();
		if($emailModel->remove($keys)) {
			return true;
		}
		return false;
	}


}