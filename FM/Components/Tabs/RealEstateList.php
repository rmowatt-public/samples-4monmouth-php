<?php
Zend_Loader::loadClass('FM_Components_Tabs_BaseTab');
Zend_Loader::loadClass('FM_Models_FM_RealEstateListings');

class FM_Components_Tabs_RealEstateList extends FM_Components_Tabs_BaseTab {
	
	protected $_profile;
	protected $_listings;
	protected $_orgId;
	protected $_table;
	protected $_admin = false;
	
	public function __construct($orgId, $admin = false) {
		parent::__construct();
		$this->_admin = $admin;
		$this->_orgId = $orgId;
		$this->_table = new FM_Models_FM_RealEstateListings();
		$this->_listings = $this->_table->getRealEstateListingsByKeys(array('orgId'=>$this->_orgId));
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
	
	public function toHTML($id = null) {
		//print_r($this->_listings);exit;
		return $this->_view->partial('tabs/realestatelist.phtml',
											array('listings'=>$this->_listings, 'admin'=>$this->_admin));
	}
}