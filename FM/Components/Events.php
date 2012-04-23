<?php

/**
 * interface to events model and table
 */

Zend_Loader::loadClass('FM_Models_FM_Events');
Zend_Loader::loadClass('FM_Components_BaseComponent');
Zend_Loader::loadClass('FM_Components_Organization');

class FM_Components_Events extends FM_Components_BaseComponent
{
	
	protected $eventId;
	protected $datetag;
	protected $name;
	protected $location;
	protected $description;
	protected $starttime;
	protected $endtime;
	protected $frontPage;
	protected $orgId;

	public function __construct($keys = null) {
		if(is_array($keys)) {
			$eventModel = new FM_Models_FM_Events();
			$event = $eventModel->findEventByKeys($keys);
			if(is_array($event) && count($event)){
				foreach ($event as $key=>$value) {
					if(property_exists($this, $key)) {
						$this->{$key} = $value;
					}
				}
				return true;
			}
			return false;
		}
		return true;
	}

	private static $_model;

	private static function getModel()
	{
		if (self::$_model instanceof FM_Models_FM_Events) {
		} else {
			self::$_model = new FM_Models_FM_Events();
		}
		return self::$_model;
	}
	
	public function getOrgName() {
		$org = new FM_Components_Organization(array('id'=> $this->orgId));
		return $org->getName();
	}

	public static function getOrgEventsForDateTag($eventId, $datetag)
	{
		$events = self::getModel()->findOrgEventsForDateTag($eventId, $datetag);
		return $events;
	}

	public static function saveNewEvent(array $details)
	{
		if(array_key_exists('id', $details) && $details['id'] != '') {
			$id = $details['id'];
			unset($details['id']);
			return self::editEvent($id, $details);
		}
		$result = self::getModel()->saveNewEvent($details);
		return $result;
	}

	public static function editEvent($eventId, array $details)
	{
		$result = self::getModel()->editEvent($eventId, $details);
		return ($result) ? $eventId : $result;
	}

	public static function deleteEvent($eventId)
	{
		$result = self::getModel()->deleteEvent($eventId);
		return $result;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getDescription() {
		return $this->description;
	}
	
	public function getLocation() {
		return $this->location;
	}
	
	public function getDate() {
		return date('m-d-Y', strtotime($this->datetag));
	}
	
	public function getStartTime() {
		return  $this->starttime;
	}
	
	public function getEndTime() {
		return $this->endtime;
	}
	
	public function getFrontPage() {
		return $this->frotPage;
	}
	
	public function getFormattedTime() {
		$str = '';
		if(($this->getStartTime() == '' || $this->getStartTime() == '00:00') && ($this->getEndTime() == '' || $this->getEndTime() == '00:00')) {
			$str = "All Day Event";
		} elseif(($this->getStartTime() != '' && $this->getStartTime() != '00:00')&& ($this->getEndTime() == '' || $this->getEndTime() == '00:00')) {
			$str = $this->getStartTime() . ' - TBD';
		}
		elseif(($this->getEndTime() != '' && $this->getEndTime() != '00:00')&& ($this->getStartTime() == '' || $this->getStartTime() == '00:00')) {
			$str = 'Whenever - ' . $this->getEndTime();
		} else {
			$str = $this->getStartTime() . ' - ' . $this->getEndTime();
		}
		return $str;
	}
	
	public function getEventId() {
		return $this->eventId;
	}
}
