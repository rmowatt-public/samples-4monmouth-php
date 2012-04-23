<?php
Zend_Loader::loadClass('FM_Components_Tabs_BaseTab');
Zend_Loader::loadClass('FM_Components_Util_SportsSchedule');

class FM_Components_Tabs_SportsSchedule extends FM_Components_Tabs_BaseTab {
	
	protected $_profile;
	
	public function __construct($org) {
		parent::__construct();
		$ss = new FM_Components_Util_SportsSchedule(array('orgId'=>$org->getId()));
		$this->setProfile($ss->getSchedule());
	}
	
	public function setProfile($profile) {
		$this->_profile = $profile;
	}
	
	public function getProfile() {
		return $this->_profile;
	}
	
	public function toHTML($id) {
		//print_r($this->_view);exit;
		return $this->_view->partial('tabs/sportsschedule.phtml',
											array('profile'=>$this->getProfile(), 'id'=>$id));
	}
}