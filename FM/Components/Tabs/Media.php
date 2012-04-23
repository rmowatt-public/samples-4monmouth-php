<?php
Zend_Loader::loadClass('FM_Components_Tabs_BaseTab');
Zend_Loader::loadClass('FM_Forms_ContactUsOrg');

class FM_Components_Tabs_Media extends FM_Components_Tabs_BaseTab {

	protected $_profile;
	protected $_orgId;
	protected $_isSports = false;

	public function __construct($orgId, $isSports = false) {
		parent::__construct();
		$this->_orgId = $orgId;
		if($isSports) {
			$this->_isSports = true;
		}
		$this->_view->headScript()->appendFile(
		'/js/widgets/askus.js',
		'text/javascript'
		);
	}

	public function setProfile($profile) {
		$this->_profile = $profile;
	}

	public function getProfile() {
		$form =  new FM_Forms_ContactUsOrg(array('display'=>'inline'));
		$form->orgId->setValue($this->_orgId);
		return $form;
		//return $this->_profile;
	}

	public function toHTML($id) {
		//print_r($this->_view);exit;
		//if($this->_isSports) {
		//	return $this->_view->partial('tabs/mediasignin.phtml',
		//	array('profile'=>$this->getProfile(), 'id'=>$id));
		//} else {
			return $this->_view->partial('tabs/media.phtml',
			array('profile'=>$this->getProfile(), 'id'=>$id));
		//}
	}
}