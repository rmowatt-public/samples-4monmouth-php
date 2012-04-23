<?php

Zend_Loader::loadClass('FM_Components_Events');

class FM_Components_Calendar_Day
{

	private $_data;
	private $_events = array();
	private $_requiredDetailKeys = array('eventId', 'name', 'location', 'description', 'orgId', 'datetag', 'time', 'orgname', 'type', 'frontPage');
	private $_isToday = false;
	private $_orgId = 0;

	public function __construct($month, $day, $year, $orgId = 0)
	{
		$this->_data = getdate(mktime(0, 0, 0, $month, $day, $year));
		$this->_data['tag'] = date('Ymd', strtotime($month . '/' . $day . '/' . $year));
		$this->_data['timestamp'] = $this->_data[0];
		$this->_orgId = $orgId;
		$this->_setEvents();
		//print_r($this->_data);
	}

	public function __get($name)
	{
		if (array_key_exists($name, $this->_data)) {
			return $this->_data[$name];
		}
		return false;
	}

	public function __set($name, $value)
	{
		return false;
	}

	public function __toString()
	{
		print '<pre>';
		print_r($this->_data);
		print '</pre>';
	}

	private function _setEvents()
	{
		$events = FM_Components_Events::getOrgEventsForDateTag($this->_orgId, $this->tag);
		if (is_array($events) && count($events) > 0) {
			foreach ($events as $event) {
				$event['time'] = $this->getFormattedTime($event);
				$this->addEvent($event['name'], $event);
			}
		}
	}

	public function getFormattedTime($event) {
		$str = '';
		if(($event['starttime'] == '' || $event['starttime'] == '00:00') && ($event['endtime'] == '' || $event['endtime'] == '00:00')) {
			$str = "All Day Event";
		} elseif(($event['starttime'] != '' && $event['starttime'] != '00:00')&& ($event['endtime'] == '' || $event['endtime'] == '00:00')) {
			$str = date('g:i A', strtotime($event['starttime'])) . ' - TBD';
		}
		elseif(($event['endtime'] != '' && $event['endtime'] != '00:00')&& ($event['starttime'] == '' || $event['starttime'] == '00:00')) {
			$str = 'Whenever - ' . date('g:i A', strtotime($event['endtime']));
		} else {
			$str = date('g:i A', strtotime($event['starttime'])) . ' - ' . date('g:i A', strtotime($event['endtime']));
		}
		return $str;
	}

	private function _checkDetails(array $details)
	{
		$return = array();
		foreach ($this->_requiredDetailKeys as $required) {
			if (array_key_exists($required, $details)) {
				$return[$required] = $details[$required];
			} else {
				return false;
			}
		}
		return $return;
	}

	public function isToday()
	{
		return $this->_isToday;
	}

	public function setAsToday()
	{
		$this->_isToday = true;
	}

	public function hasEvents()
	{
		return (count($this->_events) > 0)? true : false;
	}

	public function addEvent($name, array $details)
	{
		$details = $this->_checkDetails($details);
		if (!$details) {
			return false;
		}
		$this->_events[$name] = $details;
	}

	public function getEvent($name)
	{
		if (array_key_exists($name, $this->_events)) {
			return $this->_events[$name];
		}
		return false;
	}

	public function getEvents()
	{
		return (count($this->_events) > 0)? $this->_events : false;
	}

	static public function getToday($orgId = 0)
	{
		$today = getdate();
		return new FM_Components_Calendar_Day($today['mon'], $today['mday'], $today['year'], $orgId);
	}

}