<?php
Zend_Loader::loadClass ( 'FM_Models_FM_FaqPage' );
Zend_Loader::loadClass ( 'FM_Components_BaseComponent' );

class FM_Components_Util_FaqPage extends FM_Components_BaseComponent{
	
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
			$aboutUsModel = new FM_Models_FM_FaqPage();
			$aboutUs = $aboutUsModel->getRecordByKeys($keys);
			if(count($aboutUs)){
				foreach ($aboutUs as $key=>$value) {
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
	
	
	public function getCarousel() {
		return $this->header;
	}
	
	public function getHeader() {
		return $this->path . '/' . $this->medianame;
	}
	
	public static function updateStatement($keys, $args) {
		$aboutUsModel = new FM_Models_FM_FaqPage();
		return $aboutUsModel->edit($keys, $args);
	}
}