<?php
Zend_Loader::loadClass('FM_Components_Widgets_BaseWidget');
Zend_Loader::loadClass('FM_Models_FM_Testimonials');
Zend_Loader::loadClass('FM_Components_Util_CouponTemplate');
Zend_Loader::loadClass('FM_Components_Coupon');
Zend_Loader::loadClass('FM_Components_Util_BannerTemplate');
Zend_Loader::loadClass('FM_Components_Banner');
Zend_Loader::loadClass('FM_Forms_Admin_Banner');

class FM_Components_Widgets_BannerLayout extends FM_Components_Widgets_BaseWidget {

	protected $orgConfig;
	protected $testimonials = array();
	protected $couponTemplates;
	protected $bannerTemplates;
	protected $coupons;
	protected $banners;
	
	public function __construct($view) {
		$this->_view = $view;
		//$view->headScript()->appendFile(
		//'/js/widgets/admin.js',
		//'text/javascript'
		//);

	}

	public function toHTML() {
		return $this->_view->partial('widgets/banners/top.phtml');
	}
}