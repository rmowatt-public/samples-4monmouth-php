<?php
Zend_Loader::loadClass('FM_Components_Tabs_BaseTab');
Zend_Loader::loadClass('FM_Models_FM_Products');

class FM_Components_Tabs_ProductList extends FM_Components_Tabs_BaseTab {
	
	protected $_profile;
	protected $_products;
	protected $_orgId;
	protected $_table;
	protected $_admin = false;
	
	public function __construct($orgId, $admin = false) {
		parent::__construct();
		$this->_admin = $admin;
		$this->_orgId = $orgId;
		$this->_table = new FM_Models_FM_Products();
		$this->_products = $this->_table->getProductsByKeys(array('orgId'=>$this->_orgId));
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
		//print_r($this->_products);exit;
		return $this->_view->partial('tabs/productlist.phtml',
											array('products'=>$this->_products, 'admin'=>$this->_admin));
	}
}