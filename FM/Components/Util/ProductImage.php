<?php
Zend_Loader::loadClass ( 'FM_Models_FM_ProductImages' );
Zend_Loader::loadClass ( 'FM_Components_BaseComponent' );

class FM_Components_Util_ProductImage extends FM_Components_BaseComponent{

	protected $id;
	protected $orgId;
	protected $fileName = false;
	protected $height;
	protected $width;
	protected $type;
	protected $created;
	protected $active;

	public function __construct($keys = null) {
		if(is_array($keys)) {
			$ImageModel = new FM_Models_FM_ProductImages();
			$Image = $ImageModel->getImageByKeys($keys);
			if(count($Image)){
				foreach ($Image as $key=>$value) {
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
	
	public function getFileName() {
		return ($this->fileName) ? $this->fileName : false;;
	}

	public static function getActive() {
		$model = new FM_Models_FM_ProductImages();
		$s = $model->getImagesByKeys(array('active'=>'1'));
		$sArray = array();
		foreach($s as $key=>$values) {
			$sArray[] = new FM_Components_Util_ProductImage(array('id'=>$values['id']));
		}
		return $sArray;
	}

	public static function deleteBanner($args) {
		$ImageModel = new FM_Models_FM_ProductImages();
		return $ImageModel->remove($args);
	}

	public static function updateBanner($args =array(), $new = array()) {
		$ImageModel = new FM_Models_FM_ProductImages();
		return $ImageModel->edit($args, $new);
	}

	public static function insert($args) {
		//print_r($args);exit;
		//print_r($args);exit;
		$Model = new FM_Models_FM_ProductImages();
		if($id = $Model->insertRecord($args)) {
			return $id;
		}
		return false;
	}
	
	public static function hasRow($id) {
		$model = new FM_Models_FM_ProductImages();
		$Image = $model->getImagesByKeys(array('orgId'=>$id));
		return (count($Image) < 1) ? false : true;
	}
}