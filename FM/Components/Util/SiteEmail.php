<?php
Zend_Loader::loadClass('FM_Models_FM_SiteEmails');
Zend_Loader::loadClass('FM_Components_EmailFormatter');
Zend_Loader::loadClass('FM_Components_Util_TextAd');

class FM_Components_Util_SiteEmail {

	protected $id;
	protected $name;
	protected $email;
	protected $description;
	protected $date;



	public function __construct($keys) {
		$this->_emailModel = new FM_Models_FM_SiteEmails();
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
		$model = new FM_Models_FM_SiteEmails();
		if(!$orgId) {
			$emails = $model->getAll();
		} else {
			$emails = $model->getRecordsByKeys(array('orgId'=>$orgId));
		}
		$emailsArray = array();
		foreach($emails as $key=>$values) {
			$emailsArray[] = new FM_Components_Util_SiteEmail(array('id'=>$values['id']));
		}
		return $emailsArray;
	}


	public static function getLastTwenty($orgId = false) {
		$model = new FM_Models_FM_SiteEmails();
		$emails = $model->getLastTwenty(array('orgId'=>$orgId));
		$emailsArray = array();
		foreach($emails as $key=>$values) {
			$emailsArray[] = new FM_Components_Util_SiteEmail(array('id'=>$values['id']));
		}
		return $emailsArray;
	}

	/**
	 * @return the $abbr
	 */
	public function getName() {
		return $this->name;
	}

	public function getEmail() {
		return $this->email;
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

	public function getDescription() {
		return $this->description;
	}

	public static function insert($args) {
		$emailModel = new FM_Models_FM_SiteEmails();
		return $emailModel->insert($args);
	}

	public static function update($args =array(), $new = array()) {
		//print_r($args);print_r($new);exit;
		$emailModel = new FM_Models_FM_SiteEmails();
		return $emailModel->edit($args, $new);
	}


	public static function delete($keys) {
		$emailModel = new FM_Models_FM_SiteEmails();
		if($emailModel->remove($keys)) {
			return true;
		}
		return false;
	}

	public static function send($params) {
		$msg = FM_Components_EmailFormatter::formatSiteEmail($params);
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers2 = $headers;
		$headers .= 'From: '. $params['name'] . "\r\n" .
		'Reply-To: ' . $params['email'] . "\r\n" .
		'X-Mailer: PHP/' . phpversion();
		$email = new FM_Components_Util_SiteEmail(array('id'=>$params['dept']));
		if($email->getId()) {
			mail($email->getEmail(), 'An email from ' . $params['email'] . 'to account --> ' . $email->getName() , $msg, $headers);
			$headers2 .= 'From: '. $email->getEmail() ."\r\n" .
			'Reply-To: ' . $email->getEmail() . "\r\n" .
			'X-Mailer: PHP/' . phpversion();
			mail($params['email'], 'Your email to ' . $email->getName() .'@4monmouth.com' , $msg . '<p>Thank you for emailing us.<br />Someone will contact you soon.<div style="color:green">The 4Monmouth Team</div></p>' . FM_Components_Util_TextAd::getRandom(6), $headers2);
			return true;
		}
		return false;
	}


}