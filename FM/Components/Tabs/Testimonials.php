<?php
Zend_Loader::loadClass('FM_Components_Tabs_BaseTab');
Zend_Loader::loadClass('FM_Components_Util_Testimonial');
Zend_Loader::loadClass('FM_Forms_Review');

class FM_Components_Tabs_Testimonials extends FM_Components_Tabs_BaseTab {
	
	protected $_profile;
	protected $_orgId;
	protected $_testimonials;
	
	public function __construct($orgId) {
		parent::__construct();
		$this->_orgId = $orgId;
		$this->_testimonials = FM_Components_Util_Testimonial::getTestimonials(array('orgId'=>$orgId));
		$this->_view->headScript()->appendFile(
		'/js/widgets/askus.js',
		'text/javascript'
		);
		$this->_view->headScript()->appendFile(
		'/js/widgets/admin.js',
		'text/javascript'
		);
	}
	
	public function setProfile($profile) {
		$this->_profile = $profile;
	}
	
	public function getProfile() {
		$form =  new FM_Forms_Review(array('display'=>'inline'));
		$form->orgId->setValue($this->_orgId);
		return $form;
		//return $this->_profile;
	}
	
	public function toHTML($id) {
		//print_r($this->_view);exit;
		return $this->_view->partial('tabs/testimonials.phtml',
											array('testimonials'=>$this->_testimonials, 'id'=>$id, 'form'=>$this->getProfile()));
	}
}