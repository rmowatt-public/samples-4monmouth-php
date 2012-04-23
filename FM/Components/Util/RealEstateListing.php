<?php
Zend_Loader::loadClass('FM_Models_FM_RealEstateListings');

class FM_Components_Util_RealEstateListing{

	protected $id;
	protected $orgId;
	protected $name;
	protected $description;
	protected $image;
	protected $link;
	protected $active;
	protected $created;
	protected $address;
	protected $city;
	protected $state;
	protected $zip;
	
	private $_testModel;

	public function __construct($keys) {
		$this->_testModel = new FM_Models_FM_RealEstateListings();
		if($test = $this->_testModel->getRealEstateListingByKeys($keys)) {
		if(count($test)){
				foreach ($test as $key=>$value) {
					if(property_exists($this, $key)) {
						$this->{$key} = $value;
					}
				}
				return true;
			}
		}
		return false;
	}

	public function getImage() {
		return $this->image;	
	}
	
	public function getDescription() {
		return $this->description;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getAddress() {
		return $this->address;
	}
	
	public function getCity() {
		return $this->city;
	}
	
	public function getState() {
		return $this->state;
	}
	
	public function getZip() {
		return $this->zip;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public static function insertRealEstateListing($args) {
		$model = new FM_Models_FM_RealEstateListings();
		return $model->insert($args);
	}
	
	public static function deleteRealEstateListing($args) {
		$model = new FM_Models_FM_RealEstateListings();
		return $model->remove($args);
	}
	
	public static function getRealEstateListings($args) {
		$model = new FM_Models_FM_RealEstateListings();
		$tests = $model->getRealEstateListingsByKeys($args);
		$RealEstateListings = array();
		if(count($tests)) {
			foreach($tests as $index=>$record) {
				$RealEstateListings[] = new FM_Components_Util_RealEstateListing(array('id'=>$record['id']));
			}
		}
		return $RealEstateListings;
	}



}