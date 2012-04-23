<?php
Zend_Loader::loadClass ( 'FM_Models_FM_Legal' );
Zend_Loader::loadClass ( 'FM_Components_BaseComponent' );

class FM_Components_Util_Legal extends FM_Components_BaseComponent{
	
	protected $id;
	protected $title;
	protected $statement;
	protected $active;
	protected $date;
	protected $medianame;
	protected $path;
	protected $height;
	protected $width;
	protected $header;
	
	public function __construct($keys = null) {
		if(is_array($keys)) {
			$legalModel = new FM_Models_FM_Legal();
			$legal = $legalModel->getRecordByKeys($keys);
			if(count($legal)){
				foreach ($legal as $key=>$value) {
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
	
	public function getStatement() {
		return $this->statement;
	}
	
	public function getTitle() {
		return $this->title;
	}
	
	public function getHeader() {
		return $this->path . '/' . $this->medianame;
	}
	
	public function getCarousel() {
		return $this->header;
	}
	
	public static function updateStatement($keys, $args) {
		$legalModel = new FM_Models_FM_Legal();
		return $legalModel->edit($keys, $args);
	}
}