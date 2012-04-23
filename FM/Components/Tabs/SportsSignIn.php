<?php
Zend_Loader::loadClass('FM_Components_Tabs_BaseTab');
Zend_Loader::loadClass('FM_Forms_ContactUsOrg');

class FM_Components_Tabs_SportsSignIn extends FM_Components_Tabs_BaseTab {
	
	protected $_profile;
	protected $_orgId;
	protected $_selected;

	
	public function __construct($orgId, $selected) {
		parent::__construct();
		$this->_orgId = $orgId;
		$this->_selected = $selected;
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
		return $this->_view->partial('tabs/mediasignin.phtml',
											array('profile'=>$this->getProfile(), 'id'=>$id, 'selected'=>$this->_selected));
	}
}