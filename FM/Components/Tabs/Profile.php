<?php
Zend_Loader::loadClass('FM_Components_Tabs_BaseTab');

class FM_Components_Tabs_Profile extends FM_Components_Tabs_BaseTab {
	
	protected $_profile;
	
	public function init() {
		parent::__construct();
	}
	
	public function setProfile($profile) {
		$this->_profile = $profile;
	}
	
	public function getProfile() {
		return $this->_profile;
	}
	
	public function toHTML($id) {
		//print_r($this->_view);exit;
		return $this->_view->partial('tabs/profile.phtml',
											array('profile'=>$this->getProfile(), 'id'=>$id));
	}
}