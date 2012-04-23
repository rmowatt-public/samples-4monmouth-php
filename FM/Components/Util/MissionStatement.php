<?php
Zend_Loader::loadClass ( 'FM_Models_FM_MissionStatement' );
Zend_Loader::loadClass ( 'FM_Components_BaseComponent' );

class FM_Components_Util_MissionStatement extends FM_Components_BaseComponent{
	
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
			$statementModel = new FM_Models_FM_MissionStatement();
			$statement = $statementModel->getRecordByKeys($keys);
			if(count($statement)){
				foreach ($statement as $key=>$value) {
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
		return ($this->header == '') ? false : $this->header;
	}
	
	public static function updateStatement($keys, $args) {
		$statementModel = new FM_Models_FM_MissionStatement();
		return $statementModel->edit($keys, $args);
	}
}