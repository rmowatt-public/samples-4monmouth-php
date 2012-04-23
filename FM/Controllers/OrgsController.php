<?php
Zend_Loader::loadClass('FM_Controllers_BaseController');
Zend_Loader::loadClass('FM_Components_Organization');
Zend_Loader::loadClass('FM_Components_Widgets_AskUs');
Zend_Loader::loadClass('FM_Components_Widgets_Profile');
Zend_Loader::loadClass('FM_Components_Widgets_Testimonials');
Zend_Loader::loadClass('FM_Components_Tabs_TabGroup');
Zend_Loader::loadClass('FM_Components_Tabs_Profile');
Zend_Loader::loadClass('FM_Components_Tabs_Directions');
Zend_Loader::loadClass('FM_Components_Banner');
Zend_Loader::loadClass('FM_Components_Tabs_Media');
Zend_Loader::loadClass('FM_Components_Tabs_Calendar');
Zend_Loader::loadClass('FM_Components_Tabs_Admin');
Zend_Loader::loadClass('FM_Components_Widgets_Admin');
Zend_Loader::loadClass('FM_Components_Widgets_PhotoGallery');
Zend_Loader::loadClass('FM_Components_Tabs_Contact');
Zend_Loader::loadClass('FM_Components_Tabs_Testimonials');
Zend_Loader::loadClass('FM_Components_Tabs_Forum');
Zend_Loader::loadClass('FM_Components_Tabs_ProductList');
Zend_Loader::loadClass('FM_Components_Tabs_Services');
Zend_Loader::loadClass('FM_Components_Tabs_Testimonials');
Zend_Loader::loadClass('FM_Components_Widgets_VideoGallery');


class OrgsController extends FM_Controllers_BaseController{

	function indexAction() {
		$this->_helper->layout->setLayout('layout2col');
		$this->view->layout()->cols = 2;
		if($this->_request->getParam('id') == 0 || !$org = new FM_Components_Organization(array('id'=>$this->_request->getParam('id')))) {
			$this->_redirect('/');
		}
		
		if($org->getType() == 2) {
			$this->_redirect ( '/merchant/' . $this->_request->getParam('id') );
		}
		if($org->getType() == 4) {
			$this->_redirect ( '/sports/' . $this->_request->getParam('id') );
		}

		if(!$this->_user && !$org->getActive() ) {
			$this->_redirect ( '/' );
		}

		if(!$org->getActive() &&  !($this->_user->isRoot() || $this->_user->frontEndAdmin())) {
			$this->_redirect ( '/' );
		}

		$this->view->orgId = $this->_request->getParam('id');
		$orgConfig = $org->getOrgConfig();
		$siteAdmin = false;
		
		if($this->_user && ($this->_user->inOrg($this->_request->getParam ( 'id' )) || $this->_user->isRoot())){//user is admin for this group, show edit
			$siteAdmin = $this->_user;
		}

		//$gallery = new FM_Components_Widgets_PhotoGallery($this->view, $this->_request->getParam ( 'id' ), 'photogallery', $siteAdmin);

		$banners = FM_Components_Banner::getSortedRandomBanners(array(),3);
		$this->view->layout()->banners = $this->view->partial('banner/bannerleftindex.phtml',
		array('banners'=>$banners));

		$this->view->layout()->leftcol = $this->view->partial('orgs/leftcol.phtml', array('orgdata'=>$this->view->partial('widgets/profile/synop.phtml', array('org'=>$org)), 'banners'=>$banners));

		$tg = new FM_Components_Tabs_TabGroup();

		$org = new FM_Components_Organization(array('id'=>$this->_request->getParam('id')));
		$tab = new FM_Components_Tabs_Profile();
		$tab->setProfile($org->get('description'));
		$tg->addTab($tab, 'profile', 'profile', true);

		$common = $orgConfig->getTabs();
		//print_r($common);exit;
		if($common['services']) {
			$services = new FM_Components_Tabs_Services($this->_request->getParam ( 'id' ));
			$tg->addTab($services, 'services', 'mservices', false);
		}
		if($common['products']) {
			$pl = new FM_Components_Tabs_ProductList($this->_request->getParam ( 'id' ), $siteAdmin);
			$tg->addTab($pl, 'products', 'products', false);
		}
		if($common['media'] || $common['video']) {
			$media = new FM_Components_Tabs_Media($this->_request->getParam ( 'id' ));
			$media->setProfile('This is the profile tab');
			$tg->addTab($media, 'media', 'media', false);
		}
		/**
		if($common['video']) {
			$media = new FM_Components_Tabs_Media($this->_request->getParam ( 'id' ));
			$media->setProfile('This is the profile tab');
			$tg->addTab($media, 'media', 'media', false);
		}
		**/
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
		if($common['forum']) {
			$forum = new FM_Components_Tabs_Forum($this->_request->getParam ( 'id' ), $siteAdmin);
			$tg->addTab($forum, 'forum', 'forum', false);
		}
		if($common['directions']) {
			$dir = new FM_Components_Tabs_Directions($org);
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
		//$tg->addTab($videoGallery , 'videogallery', 'videogallery', false);
		//print_r($videoGallery);exit;
		/**
		if($common['testimonials']) {
			$dir = new FM_Components_Tabs_Testimonials($this->_request->getParam ( 'id' ));
			$dir->setProfile('these are directions');
			$tg->addTab($dir, 'testimonials', 'testimonials', false);
		}
		**/


		if($siteAdmin) {
			$adminTab = new FM_Components_Tabs_Admin();
			$admin = new FM_Components_Widgets_Admin($this->view, $orgConfig, $org);
			$adminTab->setProfile($admin->toHTML());
			$tg->addTab($adminTab, 'admin', 'admin');
		}

		$this->view->layout()->logo = $org->getLogoObj()->getFileName();
		$this->view->layout()->miniwebBanner = $mw = $org->getMiniWebBannerObj()->getFileName();
		$this->view->layout()->profiletab = $tg->toHTML();
		$this->view->layout()->comments = $this->view->partial('widgets/askus/contact.phtml');
		$this->view->pageId = 'orgs';
	}

}