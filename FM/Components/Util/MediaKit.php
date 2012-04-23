<?php
Zend_Loader::loadClass ( 'FM_Models_FM_MediaKit' );
Zend_Loader::loadClass ( 'FM_Components_BaseComponent' );

class FM_Components_Util_MediaKit extends FM_Components_BaseComponent{
	
	protected $id;
	protected $documentname;
	protected $uploaded;
	protected $active;

	public function __construct($keys = null) {
		if(is_array($keys)) {
			$mediaKitModel = new FM_Models_FM_MediaKit();
			$mediaKit = $mediaKitModel->getRecordByKeys($keys);
			if(count($mediaKit)){
				foreach ($mediaKit as $key=>$value) {
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
	
	public function getId() {
		return $this->id;
	}
	
	public function getName() {
		return $this->documentname;
	}
	
	public function getUploaded() {
		return $this->uploaded;
	}
	
	public function getActive() {
		return $this->active;
	}
	
	public static function getLink() {
		$mediaKitModel = new FM_Models_FM_MediaKit();
		$mkRes = $mediaKitModel->getNewest();
		return '/media/pdf/mediakit/' . $mkRes['documentname'];
	}
	
	public static function updateStatement($keys, $args) {
		$mediaKitModel = new FM_Models_FM_MediaKit();
		return $mediaKitModel->edit($keys, $args);
	}
	
	public static function insert($args) {
		//sprint_r($args);exit;
		if(!is_array($args)){return false;}
		$bannerModel = new FM_Models_FM_MediaKit();
		return $bannerModel->insertRecord($args);
	}
}