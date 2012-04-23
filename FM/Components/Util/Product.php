<?php
Zend_Loader::loadClass('FM_Models_FM_Products');

class FM_Components_Util_Product{

	protected $id;
	protected $orgId;
	protected $name;
	protected $description;
	protected $image;
	protected $link;
	protected $active;
	protected $created;
	
	private $_testModel;

	public function __construct($keys) {
		$this->_testModel = new FM_Models_FM_Products();
		if($test = $this->_testModel->getProductByKeys($keys)) {
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
	
	public function getId() {
		return $this->id;
	}
	
	public static function insertProduct($args) {
		$model = new FM_Models_FM_Products();
		return $model->insert($args);
	}
	
	public static function deleteProduct($args) {
		$model = new FM_Models_FM_Products();
		return $model->remove($args);
	}
	
	public static function getProducts($args) {
		$model = new FM_Models_FM_Products();
		$tests = $model->getProductsByKeys($args);
		$Products = array();
		if(count($tests)) {
			foreach($tests as $index=>$record) {
				$Products[] = new FM_Components_Util_Product(array('id'=>$record['id']));
			}
		}
		return $Products;
	}



}