<?php
Zend_Loader::loadClass ( 'FM_Models_FM_HelpPopup' );
Zend_Loader::loadClass ( 'FM_Components_BaseComponent' );

class FM_Components_Util_HelpPopup extends FM_Components_BaseComponent{

	protected $id;
	protected $code;
	protected $text;

	public function __construct($keys = null) {
		if(is_array($keys)) {
			$HelpPopupModel = new FM_Models_FM_HelpPopup();
			$HelpPopup = $HelpPopupModel->getRecordByKeys($keys);
			if(count($HelpPopup)){
				foreach ($HelpPopup as $key=>$value) {
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

	/**
	 * @return the $active
	 */
	public function getCode() {
		return $this->code;
	}

	/**
	 * @return the $order
	 */
	public function getText() {
		return $this->text;
	}

	/**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}

	public function edit($args) {
		foreach ($args as $key=>$value) {
			if(property_exists($this, $key)) {
					$this->{$key} = $value;
				}
		}
		$HelpPopupModel = new FM_Models_FM_HelpPopup();
		if($HelpPopupModel->edit(array('id'=>$this->getId()), $this->toArray(array('id')))) {
			return true;
		}
		return false;
	}


	public static function insertHelpPopup($args) {
		$HelpPopupModel = new FM_Models_FM_HelpPopup();
		if($id = $HelpPopupModel->insertHelpPopup($args)) {
			return $id;
		}
		return false;
	}

	public static function getHelpPopup($args) {
		$HelpPopupModel = new FM_Models_FM_HelpPopup();
		$returnArray = array();
		if(count($HelpPopup = $HelpPopupModel->getHelpPopupByKeys($args))) {
			foreach ($HelpPopup as $index=>$values) {
				$HelpPopup = new FM_Components_Util_HelpPopup(array('id'=>$values['id']));
				$returnArray[] = $HelpPopup;
			}
		}
		return $returnArray;
	}

	public static function delete($keys) {
		$HelpPopupModel = new FM_Models_FM_HelpPopup();
		if($HelpPopup = new FM_Components_Util_HelpPopup($keys)) {
			if($HelpPopupModel->remove(array('id'=>$HelpPopup->getId()))) {
					return true;
			}
		}
		return false;
	}

}