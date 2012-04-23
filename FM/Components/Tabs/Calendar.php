<?php

Zend_Loader::loadClass('FM_Components_Tabs_BaseTab');
Zend_Loader::loadClass('FM_Models_FM_Events');
Zend_Loader::loadClass('FM_Components_Calendar');
Zend_Loader::loadClass('FM_Components_Calendar_Month');

class FM_Components_Tabs_Calendar extends FM_Components_Tabs_BaseTab
{
	
	protected $_profile;
	protected $_orgId = 0;
	
	public function __construct($orgId = 0)
	{
		parent::__construct();
		$this->_orgId = $orgId;
	}
	
	public function setProfile($profile)
	{
		$this->_profile = $profile;
	}
	
	public function getProfile()
	{
		return $this->_profile;
	}
	
	public function toHTML($id) {
		$c = new FM_Components_Calendar_Month(0, 0, $this->_orgId);
		
		return $this->_view->partial(
			'tabs/calendar.phtml',
			array('id' => $id, 'events' => $c->getDaysWithEvents(), 'obj'=>$c)
		);
	}
}