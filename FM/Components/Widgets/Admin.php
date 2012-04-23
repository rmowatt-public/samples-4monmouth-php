<?php
Zend_Loader::loadClass('FM_Components_Widgets_BaseWidget');
Zend_Loader::loadClass('FM_Models_FM_Testimonials');
Zend_Loader::loadClass('FM_Components_Util_CouponTemplate');
Zend_Loader::loadClass('FM_Components_Coupon');
Zend_Loader::loadClass('FM_Components_Util_BannerTemplate');
Zend_Loader::loadClass('FM_Components_Banner');
Zend_Loader::loadClass('FM_Forms_Admin_Banner');
Zend_Loader::loadClass('FM_Forms_Events');
Zend_Loader::loadClass('FM_Components_Calendar');
Zend_Loader::loadClass('FM_Components_Calendar_Month');
Zend_Loader::loadClass('FM_Components_SportsUser');
Zend_Loader::loadClass('FM_Components_Util_Email');
Zend_Loader::loadClass('FM_Models_FM_Services');
Zend_Loader::loadClass('FM_Models_FM_Menu');
Zend_Loader::loadClass('FM_Components_Util_TextAd');
Zend_Loader::loadClass('FM_Components_Util_Icon');
Zend_Loader::loadClass('FM_Models_FM_SportsSchedule');
Zend_Loader::loadClass('FM_Components_SiteConfig');



class FM_Components_Widgets_Admin extends FM_Components_Widgets_BaseWidget {

	protected $orgConfig;
	protected $orgObj;
	protected $testimonials = array();
	protected $couponTemplates;
	protected $bannerTemplates;
	protected $coupons;
	protected $banners;
	protected $sportsusers;
	protected $sportsemails;
	protected $services;
	protected $menu;
	protected $textAds;
	protected $icon;
	protected $schedule;

	public function __construct($view, $orgConfig, $orgObj) {
		//print_r($orgObj);exit;
		$this->_view = $view;
		$this->orgConfig = $orgConfig;
		$this->orgObj = $orgObj;
		$testimonialTable = new FM_Models_FM_Testimonials();
		$this->testimonials = $testimonialTable->getTestimonialsByKeys(array('orgId'=>$this->orgConfig->getOrgId()));
		//print_r($this->testimonials);exit;
		$this->couponTemplates = FM_Components_Util_CouponTemplate::getActive();
		$this->coupons = FM_Components_Coupon::getAllOrgCoupons($this->orgConfig->getOrgId());
		$this->bannerTemplates = FM_Components_Util_BannerTemplate::getActive();
		$this->banners = FM_Components_Banner::getOrgBanners($this->orgConfig->getOrgId());
		$this->sportsusers = FM_Components_SportsUser::getAll($this->orgConfig->getOrgId());
		$this->sportsemails = FM_Components_Util_Email::getAll($this->orgConfig->getOrgId());
		$sm = new FM_Models_FM_Services();
		$this->services = $sm->getServiceByKeys(array('orgId'=>$this->orgConfig->getOrgId()));
		$mm = new FM_Models_FM_Menu();
		$this->menu = $mm->getMenuByKeys(array('orgId'=>$this->orgConfig->getOrgId()));
		$ss = new FM_Models_FM_SportsSchedule();
		$this->schedule = $ss->getScheduleByKeys(array('orgId'=>$this->orgConfig->getOrgId()));
		$this->textAds = FM_Components_Util_TextAd::getOrgAds($this->orgConfig->getOrgId());
		$this->icon = FM_Components_Util_Icon::getOrgActive($this->orgConfig->getOrgId());

		$view->headScript()->appendFile(
		'/js/widgets/coupon.js',
		'text/javascript'
		);

		//$view->headScript()->appendFile(
		//'/js/widgets/admin.js',
		//'text/javascript'
		//);

		$view->headScript()->appendFile(
		'/js/widgets/banner.js',
		'text/javascript'
		);

		//$view->headScript()->appendFile(
		//'/js/swfupload/swfupload.js',
		//'text/javascript'
		//);

		$this->_view->headScript()->appendFile(
		'/js/tiny_mce/tiny_mce.js',
		'text/javascript'
		);

		//$view->headScript()->appendFile(
		//'/js/widgets/photogallery.js',
		//'text/javascript'
		//);

	}

	public function toHTML() {
		//print_r($this->orgConfig->getCommon());exit;
		$displayElements = array();
		$type =  $this->orgObj->getType();

		$common = $this->orgConfig->getCommon();
		$tabs = $common['tabs'];

		$displayElements['orgdataAdmin'] = $orgdataAdmin =  $this->_view->partial('widgets/admin/parts/orgdata.phtml',
		array('orgdata'=>$this->orgObj));
		$displayElements['common'] = $common =  $this->_view->partial('widgets/admin/parts/common.phtml',
		array('config'=>$this->orgConfig->getCommon(), 'type'=>$type));
		//print_r($this->icon);exit;
		$displayElements['topbanners'] = $common =  $this->_view->partial('widgets/admin/parts/topbanners.phtml',
		array('config'=>$this->orgConfig->getCommon(), 'type'=>$type, 'icon'=>$this->icon[0]));

		$displayElements['profile'] = $profile =  $this->_view->partial('widgets/admin/parts/profile.phtml',
		array('profile'=>$this->orgObj->get('description'), 'orgType'=>$this->orgObj->getType()));

		if($type == 2) {


			$display = ($tabs['realestate'] == 1) ? 'inline' : 'none';
			$displayElements['realestatelist'] = $realestateAdmin  =  $this->_view->partial('widgets/admin/parts/realestatelist.phtml',
			array('orgdata'=>$this->orgObj, 'display'=>$display));

			$menu = $this->menu;
			$display = ($tabs['menu'] == 1) ? 'inline' : 'none';
			$displayElements['menu'] = $menu  =  $this->_view->partial('widgets/admin/parts/menu.phtml',
			array('orgdata'=>$this->orgObj, 'display'=>$display, 'orgId'=>$this->orgConfig->getOrgId(), 'profile'=>$menu['menu']));



			$display = ($tabs['coupons'] == 1) ? 'inline' : 'none';
			$displayElements['coupontemplates'] = $couponTemplates = $this->_view->partial('widgets/admin/parts/createcoupon.phtml',
			array('templates'=>$this->couponTemplates, 'display'=>$display));
			$displayElements['availCoupons'] = $availCoupons = $this->_view->partial('widgets/admin/parts/managecoupons.phtml',
			array('coupons'=>$this->coupons, 'display'=>$display));

			$displayElements['textAd'] = $availCoupons = $this->_view->partial('widgets/admin/parts/textad.phtml',
			array('orgId'=>$this->orgConfig->getOrgId()));

			$displayElements['availtextAds'] = $availBanners = $this->_view->partial('widgets/admin/parts/managetextads.phtml',
			array('ads'=>$this->textAds));


		}
		if($type != 4) {
			$display = ($tabs['products'] == 1) ? 'inline' : 'none';
			$displayElements['productlist'] = $productlistAdmin  =  $this->_view->partial('widgets/admin/parts/productlist.phtml',
			array('orgdata'=>$this->orgObj, 'display'=>$display));
			$siteConfig = new FM_Components_SiteConfig();
			if($type == 2 || $siteConfig->npBannersEnabled()) {
				$display = ($tabs['banners'] == 1) ? 'inline' : 'none';
				$displayElements['bannertemplates'] = $bannerTemplates = $this->_view->partial('widgets/admin/parts/createbanner.phtml',
				array('templates'=>$this->bannerTemplates, 'display'=>$display, 'form'=> new FM_Forms_Admin_Banner()));
				$displayElements['availBanners'] = $availBanners = $this->_view->partial('widgets/admin/parts/managebanners.phtml',
				array('banners'=>$this->banners, 'display'=>$display));
			}
			$display = ($tabs['services'] == 1) ? 'inline' : 'none';
			$services = $this->services;
			$displayElements['services'] = $services  =  $this->_view->partial('widgets/admin/parts/services.phtml',
			array('orgdata'=>$this->orgObj, 'display'=>$display, 'orgId'=>$this->orgConfig->getOrgId(), 'profile'=>$services['services'] ));

			$display = ($tabs['reviews'] == 1) ? 'inline' : 'none';
			$displayElements['testimonials'] = $displayElements[] = $testimonials = $this->_view->partial('widgets/admin/parts/testimonials.phtml',
			array('testimonials'=>$this->testimonials, 'display'=>$display, 'orgId'=>$this->orgConfig->getOrgId()));

		}

		if($type == 4) {
			$displayElements['sportsuser'] = $displayElements[] = $testimonials = $this->_view->partial('widgets/admin/parts/addsportsuser.phtml',
			array('users'=>$this->sportsusers));

			$displayElements['sendemail'] = $displayElements[] = $sendemail = $this->_view->partial('widgets/admin/parts/sendemail.phtml',
			array('users'=>$this->sportsusers, 'emails'=>$this->sportsemails));

			$displayElements['emaillist'] = $displayElements[] = $sendemail = $this->_view->partial('widgets/admin/parts/emaillist.phtml',
			array('emails'=>$this->sportsemails));

			$menu = $this->menu;
			$display = ($tabs['sportsschedule'] == 1) ? 'inline' : 'none';
			$displayElements['sportsschedule'] = $sportsschedule =  $this->_view->partial('widgets/admin/parts/sportsschedule.phtml',
			array('orgdata'=>$this->orgObj, 'display'=>'inline', 'orgId'=>$this->orgConfig->getOrgId(), 'profile'=>$this->schedule['schedule']));


			$displayElements['pwd'] = $this->_view->partial('widgets/admin/parts/pwdprotect.phtml', array('sport'=>$this->orgObj));
		}

		$calendarForm = new FM_Forms_Events();
		$c = new FM_Components_Calendar_Month(0, 0, $this->orgConfig->getOrgId());
		$display = ($tabs['events'] == 1) ? 'inline' : 'none';
		$displayElements['calendar'] = $calendar = $this->_view->partial(
		'widgets/admin/parts/calendar.phtml',
		array('form' => $calendarForm,
		'events' => $c->getDaysWithEvents(),
		'orgId' =>  $this->orgConfig->getOrgId(),
		'display'=>$display,
		'type'=>$type
		)
		);

		$displayElements['availEvents'] = $displayElements[] = $sendemail = $this->_view->partial('widgets/admin/parts/manageevents.phtml',
		array('events'=> FM_Components_Calendar_Month::getAll($this->orgConfig->getOrgId()),'display'=>$display));

		return $this->_view->partial('widgets/admin/widget.phtml',
		$displayElements);
	}
}