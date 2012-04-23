<?php
Zend_Loader::loadClass ('FM_Controllers_BaseController');
Zend_Loader::loadClass ('FM_Components_Business');
Zend_Loader::loadClass ('FM_Forms_Login');
Zend_Loader::loadClass('FM_Components_Widgets_AskUs');
Zend_Loader::loadClass('FM_Components_Widgets_Profile');
Zend_Loader::loadClass('FM_Components_Widgets_Testimonials');
Zend_Loader::loadClass('FM_Components_Tabs_TabGroup');
Zend_Loader::loadClass('FM_Components_Tabs_Profile');
Zend_Loader::loadClass('FM_Components_Tabs_Directions');
Zend_Loader::loadClass('FM_Components_Tabs_Admin');
Zend_Loader::loadClass('FM_Components_Widgets_Admin');
Zend_Loader::loadClass('FM_Components_Widgets_PhotoGallery');
Zend_Loader::loadClass('FM_Components_Banner');
Zend_Loader::loadClass('FM_Components_Coupon');
Zend_Loader::loadClass('FM_Components_OrgConfig');
Zend_Loader::loadClass('FM_Components_Tabs_Contact');
Zend_Loader::loadClass('FM_Components_Tabs_ProductList');
Zend_Loader::loadClass('FM_Components_Tabs_RealEstateList');
Zend_Loader::loadClass('FM_Components_Tabs_Testimonials');
Zend_Loader::loadClass('FM_Components_Tabs_Forum');
Zend_Loader::loadClass('FM_Components_Tabs_Calendar');
Zend_Loader::loadClass('FM_Components_Tabs_Media');
Zend_Loader::loadClass('FM_Components_Tabs_Coupons');
Zend_Loader::loadClass('FM_Components_Tabs_Services');
Zend_Loader::loadClass('FM_Components_Tabs_Menu');
Zend_Loader::loadClass('FM_Components_Tabs_B2BCoupons');
Zend_Loader::loadClass('FM_Components_Widgets_VideoGallery');

class B2bController extends FM_Controllers_BaseController {

	private $_business;

	public function indexAction() {
		$this->_helper->layout->setLayout('layout2col');
		$this->view->layout()->cols = 2;
		if ($this->_request->getParam ( 'id' ) == 0 || !$this->_business = new FM_Components_Business( array ('id' => $this->_request->getParam ( 'id' ) ) )) {
			$this->_redirect ( '/' );
		}
		if(!$this->_business->getId()) {
			$this->_redirect ( '/' );
		}
		if($this->_business->getType() == 4) {
			$this->_redirect ( '/sports/' . $this->_request->getParam('id') );
		}
		if($this->_business->getType() == 3) {
			$this->_redirect ( '/org/' . $this->_request->getParam('id') );
		}
		if(!$this->_business->getCategory()){
			//$this->_redirect ( '/' );
		}
		
		//print_r($this->_business);exit;
		$this->view->orgId = $this->_request->getParam ( 'id' );
		$orgConfig = $this->_business->getOrgConfig();
		$common = $orgConfig->getTabs();

		$siteAdmin = false;
		//print_r($this->_user->getOrgId());exit;
		if($this->_user && ($this->_user->inOrg($this->_request->getParam ( 'id' ))  || $this->_user->isRoot() || ($this->_user->frontEndAdmin() && !stristr($_SERVER['REQUEST_URI'], 'root')))){//user is admin for this group, show edit
			$siteAdmin = true;
		}
		if(!$this->_user && !$this->_business->getActive() ) {
			$this->_redirect ( '/' );
		}
		if(!$this->_business->getActive() &&  !($this->_user->isRoot() || $this->_user->frontEndAdmin())) {
			$this->_redirect ( '/' );
		}
		//print_r($this->_business->getCategories()); exit;
		$banners = FM_Components_Banner::getSortedRandomBanners($this->_business->getCategories());
		//exit;
		$this->view->layout()->banners = $this->view->partial('banner/bannerleftindex.phtml',
		array('banners'=>$banners));

		$gallery = new FM_Components_Widgets_PhotoGallery($this->view, $this->_request->getParam ( 'id' ), 'photogallery', $siteAdmin);

		$this->view->layout()->leftcol = $this->view->partial('b2b/leftcol.phtml',
		array('orgdata'=>$this->view->partial('widgets/profile/synop.phtml', array('org'=>$this->_business)),
		'banners'=>$banners)
		);

		$tg = new FM_Components_Tabs_TabGroup();
		$tab = new FM_Components_Tabs_Profile();
		$tab->setProfile($this->_business->get('description'));
		$tg->addTab($tab, 'profile', 'profile', true);
		if($common['menu']) {
			$menu = new FM_Components_Tabs_Menu($this->_request->getParam ( 'id' ), $siteAdmin);
			$tg->addTab($menu, 'menu', 'mmenu', false);
		}
		if($common['services']) {
			$services = new FM_Components_Tabs_Services($this->_request->getParam ( 'id' ));
			$tg->addTab($services, 'services', 'mservices', false);
		}
		if($common['products']) {
			$pl = new FM_Components_Tabs_ProductList($this->_request->getParam ( 'id' ), $siteAdmin);
			$tg->addTab($pl, 'products', 'products', false);
		}
		if($common['realestate']) {
			$rel = new FM_Components_Tabs_RealEstateList($this->_request->getParam ( 'id' ), $siteAdmin);
			$tg->addTab($rel, 'r/e listings', 'realestate', false);
		}

		if($common['coupons']) {
			$coupons = new FM_Components_Tabs_Coupons($this->_request->getParam ( 'id' ));
			$tg->addTab($coupons, 'coupons', 'coupons', false);
			if($this->_user) {
				$b2bcoupons = new FM_Components_Tabs_B2BCoupons($this->_request->getParam ( 'id' ));
				$tg->addTab($b2bcoupons, 'b2bcoupons', 'b2bcoupons', false);
			}
		}
		if($common['media']) {
			$media = new FM_Components_Tabs_Media($this->_request->getParam ( 'id' ));
			$media->setProfile('This is the profile tab');
			$tg->addTab($media, 'media', 'media', false);
		}
		if($common['events']) {
			// CALENDAR TAB!!!!
			$calendarTab = new FM_Components_Tabs_Calendar($this->_request->getParam ('id'));
			$tg->addTab($calendarTab, 'events', 'calendar', false);
		}
		if($common['reviews']) {
			$dir = new FM_Components_Tabs_Testimonials($this->_request->getParam ( 'id' ));
			$dir->setProfile('these are directions');
			$tg->addTab($dir, 'reviews', 'testimonials', false);
		}
		/**
		if($common['forum']) {
			$forum = new FM_Components_Tabs_Forum($this->_request->getParam ( 'id' ), $siteAdmin);
			$tg->addTab($forum, 'forum', 'forum', false);
		}
		**/
		if($common['directions']) {
			$dir = new FM_Components_Tabs_Directions($this->_business);
			$dir->setProfile('these are directions');
			$tg->addTab($dir, 'directions', 'directions', false);
		}
		if($common['contact']) {
			$dir = new FM_Components_Tabs_Contact($this->_request->getParam ( 'id' ));
			$dir->setProfile('these are directions');
			$tg->addTab($dir, 'contact us', 'contact', false);
		}
		
		$this->view->video = false;
		$this->view->photo = false;
		if($common['media']) {
			$this->view->layout()->photo = true;
			$gallery = new FM_Components_Widgets_PhotoGallery($this->view, $this->_request->getParam ( 'id' ), 'photogallery', $siteAdmin);
		}
		if($common['video']) {
				$this->view->layout()->video = true;
				$videoGallery = new FM_Components_Widgets_VideoGallery($this->view, $this->_request->getParam ( 'id' ), 'videogallery', $siteAdmin);
			}
			
			
		if($siteAdmin) {
			$adminTab = new FM_Components_Tabs_Admin();
			$admin = new FM_Components_Widgets_Admin($this->view, $this->_business->getOrgConfig(), $this->_business);
			$adminTab->setProfile($admin->toHTML());
			$tg->addTab($adminTab, 'admin', 'admin');
		}
		$this->view->layout()->profiletab = $tg->toHTML();
		$this->view->layout()->logo = $this->_business->getLogoObj()->getFileName();
		$this->view->layout()->miniwebBanner = $mw = $this->_business->getMiniWebBannerObj()->getFileName();
		$this->view->crumb = 'home >> business >> ' . $this->_business->getName();
		$this->view->content = new FM_Forms_Login ( );
		$this->view->coupons = $coupons = FM_Components_Coupon::getOrgCoupons($this->_request->getParam ( 'id' ));
		$this->view->pageId = 'b2b';

	}

	public function ajaxcommonfeaturesAction() {
		if($this->_user && ($this->_user->getOrgId() || $this->_user->isRoot())){//user is admin for this group, show edit
			switch($_POST['type']) {
				case 'tabs' :
					FM_Components_OrgConfig::updateConfig('tabs', $_POST['orgId'], array($_POST['column']=>$_POST['status']));
					print '1';
					break;
				case 'widgets' :
					FM_Components_OrgConfig::updateConfig('widgets', $_POST['orgId'], array($_POST['column']=>$_POST['status']));
					print '1';
					break;
				case 'options' :
					FM_Components_OrgConfig::updateConfig('options', $_POST['orgId'], array($_POST['column']=>$_POST['status']));
					print '1';
					break;
				default :
					print '0';
					exit;
			}
			//print_r($_POST);
			exit;
		}
		print 'user no admin';
		exit;
	}
}