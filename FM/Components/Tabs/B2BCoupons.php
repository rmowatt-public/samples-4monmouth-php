<?php
Zend_Loader::loadClass('FM_Components_Tabs_BaseTab');
Zend_Loader::loadClass('FM_Components_Coupon');

class FM_Components_Tabs_B2BCoupons extends FM_Components_Tabs_BaseTab {
	
	protected $_profile;
	protected $_orgId;
	
	public function __construct($orgId) {
		parent::__construct();
		$this->_orgId = $orgId;
		$this->_view->headScript()->appendFile(
		'/js/widgets/askus.js',
		'text/javascript'
		);
	}
	
	public function setProfile($profile) {
		$this->_profile = $profile;
	}
	
	public function getProfile() {
		$coupons = FM_Components_Coupon::getOrgCoupons($this->_orgId, true, true);
		return $coupons;
	}
	
	public function toHTML($id) {
		//print_r($this->_view);exit;
		return $this->_view->partial('tabs/b2bcoupons.phtml',
											array('coupons'=>$this->getProfile(), 'id'=>$id));
	}
}