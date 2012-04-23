<?php
Zend_Loader::loadClass('FM_Models_FM_Regions');
Zend_Loader::loadClass('FM_Models_FM_Towns');

class FM_Components_Util_Region{

	protected $id;
	protected $name;
	protected $abbr;
	private $_regionModel;

	public function __construct($keys) {
		$this->_regionModel = new FM_Models_FM_Regions();
		if($region = $this->_regionModel->getRegionByKeys($keys)) {
		if(count($region)){
				foreach ($region as $key=>$value) {
					if(property_exists($this, $key)) {
						$this->{$key} = $value;
					}
				}
				return true;
			}
		}
		return false;
	}

	public static function getAll() {
		$model = new FM_Models_FM_Regions();
		$regions = $model->getRegions();
		$regionsArray = array();
		foreach($regions as $key=>$values) {
			$regionsArray[] = new FM_Components_Util_Region(array('id'=>$values['id']));
		}
		return $regionsArray;
	}
	
	public static function getTownIdsByRegion($regionId) {
		$model = new FM_Models_FM_Towns();
		$towns = $model->getTownsByKeys(array('region'=>$regionId));
		$townArray = array();
		foreach($towns as $key=>$values) {
			$townArray[] = $values['id'];
		}
		return $townArray;
	}

	/**
	 * @return the $abbr
	 */
	public function getAbbr() {
		return $this->abbr;
	}

	/**
	 * @return the $region
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}

}