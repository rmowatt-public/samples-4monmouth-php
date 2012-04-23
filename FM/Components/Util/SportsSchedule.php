<?php
Zend_Loader::loadClass ( 'FM_Models_FM_SportsSchedule' );
Zend_Loader::loadClass ( 'FM_Components_BaseComponent' );

class FM_Components_Util_SportsSchedule extends FM_Components_BaseComponent{

	protected $id;
	protected $orgId;
	protected $schedule;
	protected $active;

	public function __construct($keys = null) {
		if(is_array($keys)) {
			$sportsScheduleModel = new FM_Models_FM_SportsSchedule();
			$sportsSchedule = $sportsScheduleModel->getScheduleByKeys($keys);
			if(count($sportsSchedule)){
				foreach ($sportsSchedule as $key=>$value) {
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

	public function getSchedule() {
		return $this->schedule;
	}

	public function getOrgId() {
		return $this->orgId;
	}


	public function getActive() {
		return $this->active;
	}


	public function getId() {
		return $this->id;
	}

	public static function updateStatement($keys, $args) {
		$sportsScheduleModel = new FM_Models_FM_SportsSchedule();
		return $sportsScheduleModel->edit($keys, $args);
	}

	public static function insert($args) {
		$sportsScheduleModel = new FM_Models_FM_SportsSchedule();
		if($sportsScheduleModel->insert($args)) {
			return true;
		}
		return false;
	}
}