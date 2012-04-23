<?php

require_once 'Calendar/Month.php';

Zend_Loader::loadClass('FM_Models_FM_Events');

class FM_Components_Calendar
{
	private $_orgId;
	
	static public function getMonth($month = 0, $year = 0, $orgId = 0)
	{
		$month = ($month > 0 && $month < 13)? $month : date('m', time());
		$year = ($year > 2008)? $year : date('m', time());
		$m = new FM_Components_Calendar_Month($month, $year, $orgId);
		return $m;
	}
	
	static public function saveNewEvent(array $eventDetails, $orgId)
	{
		foreach (array('name', 'location', 'description') as $req) {
			if (!array_key_exists($req, $eventDetails)) {
				return false;
			}
		}
	}
}
