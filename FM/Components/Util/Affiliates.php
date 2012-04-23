<?php
Zend_Loader::loadClass ( 'FM_Models_FM_Affiliates' );
Zend_Loader::loadClass ( 'FM_Components_BaseComponent' );

class FM_Components_Util_Affiliates extends FM_Components_BaseComponent{
	
	protected $id;
	protected $content;
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
			$affiliatesModel = new FM_Models_FM_Affiliates();
			$affiliates = $affiliatesModel->getRecordByKeys($keys);
			if(count($affiliates)){
				foreach ($affiliates as $key=>$value) {
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
	
	public function getContent() {
		return $this->statement;
	}
	
		public function getHeader() {
		return $this->path . '/' . $this->medianame;
	}
	
	public function getCarousel() {
		return $this->header;
	}
	
	public static function updateStatement($keys, $args) {
		$affiliatesModel = new FM_Models_FM_Affiliates();
		return $affiliatesModel->edit($keys, $args);
	}
}