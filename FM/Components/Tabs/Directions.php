<?php
Zend_Loader::loadClass('FM_Components_Tabs_BaseTab');

class FM_Components_Tabs_Directions extends FM_Components_Tabs_BaseTab {
	
	protected $_profile;
	protected $org;
	
	public function __construct($org = false) {
		parent::__construct();
		$this->_view->headScript()->appendFile(
		'/js/widgets/directions.js',
		'text/javascript'
		);
		$this->org = $org;
	}
	
	public function setProfile($profile) {
		$this->_profile = $profile;
	}
	
	public function getProfile() {
		return $this->_profile;
	}
	
	public function toHTML($id) {
		//print_r($this->_view);exit;
		return $this->_view->partial('tabs/directions.phtml',
											array('profile'=>$this->getProfile(), 'id'=>$id, 'address' => $this->org->getFullAddress()));
	}
}