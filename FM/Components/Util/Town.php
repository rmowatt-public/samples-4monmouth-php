<?php
Zend_Loader::loadClass('FM_Models_FM_Towns');

class FM_Components_Util_Town{

	protected $id;
	protected $name;
	protected $abbr;
	protected $region;
	private $_townModel;

	public function __construct($keys) {
		$this->_townModel = new FM_Models_FM_Towns();
		if($town = $this->_townModel->getTownByKeys($keys)) {
		if(count($town)){
				foreach ($town as $key=>$value) {
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
		$model = new FM_Models_FM_Towns();
		$towns = $model->getTowns();
		$townsArray = array();
		foreach($towns as $key=>$values) {
			$townsArray[] = new FM_Components_Util_Town(array('id'=>$values['id']));
		}
		return $townsArray;
	}

	/**
	 * @return the $abbr
	 */
	public function getAbbr() {
		return $this->abbr;
	}

	/**
	 * @return the $town
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
	
	public function getRegion() {
		return $this->region;
	}

}