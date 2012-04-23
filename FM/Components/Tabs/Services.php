<?php
Zend_Loader::loadClass('FM_Components_Tabs_BaseTab');
Zend_Loader::loadClass('FM_Models_FM_Services');

class FM_Components_Tabs_Services extends FM_Components_Tabs_BaseTab {

	protected $_profile;
	protected $_db;
	protected $_orgId;

	public function __construct($orgId) {
		parent::__construct();
		$this->_db = new FM_Models_FM_Services();
		$this->_orgId = $orgId;
	}

	public function setProfile($profile) {
		$this->_profile = $profile;
	}

	public function getProfile() {
		$s = $this->_db->getServiceByKeys(array('orgId'=>$this->_orgId));
		//print_r($s);exit;
		return (count($s) && array_key_exists('services', $s)) ? $s['services'] : '';
	}

	public function toHTML($id) {
		//print_r($this->_view);exit;
		return $this->_view->partial('tabs/services.phtml',
		array('services'=>$this->getProfile(), 'id'=>$id));
	}

	public static function editService($orgId, $content) {
		$model = new FM_Models_FM_Services();
		if($model->getServiceByKeys(array('orgId'=>$orgId))) {
			if($model->update(array('orgId'=>$orgId), array('services'=>$content))) {
				return true;
			}
		} else{
			if($model->insert(array('orgId'=>$orgId, 'services'=>$content))) {
				return true;
			}
			return false;
		}
	}
}