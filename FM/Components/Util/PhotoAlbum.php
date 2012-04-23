<?php
Zend_Loader::loadClass ( 'FM_Models_FM_PhotoAlbum' );
Zend_Loader::loadClass ( 'FM_Models_FM_PhotoGallery' );
Zend_Loader::loadClass ( 'FM_Components_BaseComponent' );

class FM_Components_Util_PhotoAlbum extends FM_Components_BaseComponent{

	protected $id = 'default';
	protected $orgId;
	protected $name;
	protected $active;
	protected $description;
	protected $created;
	protected $photoAlbum;
	protected $images = array();
	protected $_photoGalleryTable;

	public function __construct($keys = null) {
		if(is_array($keys)) {
			$PhotoAlbumModel = new FM_Models_FM_PhotoAlbum();
			$PhotoAlbum = $PhotoAlbumModel->getAlbumByKeys($keys);
			if(count($PhotoAlbum)){
				foreach ($PhotoAlbum as $key=>$value) {
					if(property_exists($this, $key)) {
						$this->{$key} = $value;
					}
				}
				$this->_photoGalleryTable = new FM_Models_FM_PhotoGallery();
				$this->images = $this->_photoGalleryTable->getPhotosByKeys(array('photoAlbum'=>$this->id));
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
		 return $this->name;
	}
	
	public function getDescription() {
		return ($this->description != '') ? $this->description : false;
	}
	
	public function setImages($images) {
		$this->images = $images;
	}
	
	public function getImages() {
		/**
		$images = array();
		foreach ($this->images  as $key=>$values) {
			$images[$values['id']] = $values;
		}
		return $images;
		**/
		return $this->images;
	}

	public static function getActive($orgId) {
		$model = new FM_Models_FM_PhotoAlbum();
		$photoGalleryTable = new FM_Models_FM_PhotoGallery();
		$s = $model->getAlbumsByKeys(array('active'=>'1', 'orgId'=>$orgId));
		$default = $photoGalleryTable->getPhotosByKeys(array('orgId'=>$orgId, 'photoAlbum'=>0));
		$sArray = array();
		$defaultAlbum = new FM_Components_Util_PhotoAlbum();
		$defaultAlbum->setImages($default);
		$sArray[] = $defaultAlbum;
		foreach($s as $key=>$values) {
			$sArray[] = new FM_Components_Util_PhotoAlbum(array('id'=>$values['id']));
		}
		//print_r($sArray);exit;
		return $sArray;
	}

	public static function deleteAlbum($id) {
		$PhotoAlbumModel = new FM_Models_FM_PhotoAlbum();
		$model = new FM_Models_FM_PhotoGallery();
		$model->remove(array('photoAlbum'=>$id));
		return $PhotoAlbumModel->remove(array('id'=>$id));
	}

	public static function updateBanner($args =array(), $new = array()) {
		$PhotoAlbumModel = new FM_Models_FM_PhotoAlbum();
		$res = $PhotoAlbumModel->getPhotoAlbumByKeys($args);
		if(count($res) > 0) {
			return $PhotoAlbumModel->edit($args, $new);
		} else {
			return self::insert($new);
		}
	}

	public static function insert($args) {
		$Model = new FM_Models_FM_PhotoAlbum();
		if($id = $Model->insert($args)) {
			return $id;
		}
		return false;
	}

	public static function hasRow($id) {
		$model = new FM_Models_FM_PhotoAlbum();
		$PhotoAlbum = $model->getPhotoAlbumsByKeys(array('orgId'=>$id));
		return (count($PhotoAlbum) < 1) ? false : true;
	}
}