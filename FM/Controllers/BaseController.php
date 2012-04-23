<?php
Zend_Loader::loadClass('Zend_Controller_Action');
Zend_Loader::loadClass('FM_Components_RSS_Horoscope');
Zend_Loader::loadClass('FM_Models_FM_User');
Zend_Loader::loadClass('Zend_Auth');
Zend_Loader::loadClass('Zend_Json');
Zend_Loader::loadClass('FM_Components_Member');
Zend_Loader::loadClass('FM_Components_SiteConfig');
Zend_Loader::loadClass('FM_Components_Util_PayBanner');
Zend_Loader::loadClass('FM_Components_Util_FullBanner');
Zend_Loader::loadClass('FM_Components_HitCounter');
Zend_Loader::loadClass('FM_Components_Util_MediaKit');

class FM_Controllers_BaseController extends Zend_Controller_Action{

	protected $_admin = false;
	protected $_user = null;
	protected $_siteConfig;
	protected $_mediakitLink;
	

	function init() {
		$this->_siteConfig = new FM_Components_SiteConfig();
		if(!stristr($_SERVER['REQUEST_URI'], 'ajax')) {
			$this->view->layout()->payBanner = ($this->_siteConfig->usePayBanners()) ? FM_Components_Util_PayBanner::getRandom() : false;
			$this->view->layout()->fullBanner = ($this->_siteConfig->useFullBanners()) ? FM_Components_Util_FullBanner::getRandom() : false;
			$this->view->layout()->background = $this->_siteConfig->getBackground();
		}
		
		$this->view->layout()->mediakit = FM_Components_Util_MediaKit::getLink();

		if($this->_getParam('id')){
			FM_Components_HitCounter::update($this->_getParam('id'));
		}

		if(stristr($_SERVER['REQUEST_URI'], 'admin')){
			$this->_admin = true;
			$this->view->layout()->admin = $this->_admin;
		}
		if(Zend_Auth::getInstance()->hasIdentity()){
			$this->view->layout()->name = Zend_Auth::getInstance()->getIdentity();
		}

		$namespace = new Zend_Session_Namespace('client');

		if(isset($namespace->user) && $namespace->user instanceof FM_Components_Member) {
			$this->_user = $namespace->user;
		}
	}

	function preDispatch() {

	}

	function postDispatch() {

	}

}