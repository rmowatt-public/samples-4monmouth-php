<?php
Zend_Loader::loadClass('FM_Models_FM_State');

class FM_Components_Util_State {

	protected $id;
	protected $state;
	protected $abbr;
	private $_stateModel;

	public function __construct($keys) {
		$this->_stateModel = new FM_Models_FM_State();
		if($state = $this->_stateModel->getStateByKeys($keys)) {
		if(count($state)){
				foreach ($state as $key=>$value) {
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
		$model = new FM_Models_FM_State();
		$states = $model->getStates();
		$statesArray = array();
		foreach($states as $key=>$values) {
			$statesArray[] = new FM_Components_Util_State(array('id'=>$values['id']));
		}
		return $statesArray;
	}

	/**
	 * @return the $abbr
	 */
	public function getAbbr() {
		return $this->abbr;
	}

	/**
	 * @return the $state
	 */
	public function getState() {
		return $this->state;
	}

	/**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}

}