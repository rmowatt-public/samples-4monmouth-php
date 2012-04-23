<?php
Zend_Loader::loadClass('Zend_Controller_Action_HelperBroker');
class FM_Components_Tabs_BaseTab {
	
	protected $_view;
	
	public function __construct() {
		$this->_view = Zend_Controller_Action_HelperBroker::getExistingHelper('viewRenderer')->view;
	}
	
}