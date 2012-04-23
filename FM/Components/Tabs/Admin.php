<?php

Zend_Loader::loadClass('FM_Components_Tabs_BaseTab');

class FM_Components_Tabs_Admin extends FM_Components_Tabs_BaseTab {

	protected $_profile;

	public function init() {
		parent::__construct();
		$this->_view->headScript()->appendFile(
			'/js/widgets/admin.js',
			'text/javascript'
		);
	}

	public function setProfile($profile) {
		$this->_profile = $profile;
	}

	public function getProfile() {
		return $this->_profile;
	}

	public function toHTML($id) {
		return $this->_view->partial(
			'tabs/admin.phtml',
			array('profile'=>$this->getProfile(), 'id'=>$id)
		);
	}
}