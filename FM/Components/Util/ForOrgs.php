<?php
Zend_Loader::loadClass ( 'FM_Models_FM_ForOrganizations' );
Zend_Loader::loadClass ( 'FM_Components_BaseComponent' );

class FM_Components_Util_ForOrgs extends FM_Components_BaseComponent{
	
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
			$forOrganizationsModel = new FM_Models_FM_ForOrganizations();
			$forOrganizations = $forOrganizationsModel->getRecordByKeys($keys);
			if(count($forOrganizations)){
				foreach ($forOrganizations as $key=>$value) {
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
		//print_r($keys);
		//print_r($args);
		//exit;
		$forOrganizationsModel = new FM_Models_FM_ForOrganizations();
		return $forOrganizationsModel->edit($keys, $args);
	}
}