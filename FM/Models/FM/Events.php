<?php

Zend_Loader::loadClass('FM_Models_BaseModel');

class FM_Models_FM_Events extends FM_Models_BaseModel
{

	protected $_tableName = 'events';

	public function __construct()
	{
		parent::__construct(null, null, null, 'FM');
	}

	public function saveNewEvent(array $details)
	{
		$insert = array();
		foreach ($details as $key=>$value) {
			if (in_array($key, $this->_colNames)){
				$insert[$key] = $value;
			}
		}
		return $this->insert($insert);
	}

	public function editEvent($eventId, array $details)
	{
		return $this->update(array('eventId' => $eventId), $details);
	}

	public function deleteEvent($eventId)
	{
		return $this->delete($this->_deleteString(array('eventId' => $eventId)));
	}

	public function findEventByKeys($keys) {
		$where = $this->_makeWhere($keys, 'e');
		//print $where; exit;
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} e WHERE {$where}";
		return $this->getSingleRow($sql);

	}
	public function findEventById($eventId)
	{
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} WHERE eventId = {$eventId}";
		return $this->getSingleRow($sql);
	}

	public function findOrgEvents($orgId)
	{
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} WHERE orgId = {$orgId}";
		return $this->getSingleRow($sql);
	}
	
	public function findAllOrgEvents($orgId)
	{
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} WHERE orgId = {$orgId}";
		return $this->getMultipleRows($sql);
	}

	public function findOrgEventsForDateTag($orgId, $datetag)
	{
		$and = ($orgId != 0) ? " orgId = {$orgId} AND " : '';
		$sql = "SELECT e.*, o.name as orgname, o.type from {$this->_dbName}.{$this->_tableName} e JOIN FM.orgdata o ON  e.orgId = o.id WHERE {$and} datetag = {$datetag}";
		return $this->getMultipleRows($sql);
	}

	public function findOrgEventsForRange($orgId, $starttag, $endtag)
	{
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} "
		. "WHERE orgId = {$orgId} "
		. "AND datetag between {$starttag} and {$endtag}";
		return $this->getMultipleRows($sql);
	}

	public function findEventsForRange($starttag, $endtag)
	{
		$sql = "SELECT * from {$this->_dbName}.{$this->_tableName} "
		. "WHERE AND datetag between {$starttag} and {$endtag}";
		return $this->getMultipleRows($sql);
	}
}
