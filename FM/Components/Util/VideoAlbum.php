<?php
Zend_Loader::loadClass ( 'FM_Models_FM_VideoGallery' );
Zend_Loader::loadClass ( 'FM_Models_FM_VideoAlbum' );
Zend_Loader::loadClass ( 'FM_Models_FM_VideoGallery' );
Zend_Loader::loadClass ( 'FM_Components_BaseComponent' );

class FM_Components_Util_VideoAlbum extends FM_Components_BaseComponent{

	protected $id = 'default';
	protected $orgId;
	protected $name;
	protected $active;
	protected $description;
	protected $created;
	protected $videoAlbum;
	protected $images = array();
	protected $_videoGalleryTable;

	public function __construct($keys = null) {
		if(is_array($keys)) {
			$VideoAlbumModel = new FM_Models_FM_VideoAlbum();
			$VideoAlbum = $VideoAlbumModel->getAlbumByKeys($keys);
			if(count($VideoAlbum)){
				foreach ($VideoAlbum as $key=>$value) {
					if(property_exists($this, $key)) {
						$this->{$key} = $value;
					}
				}
				$this->_videoGalleryTable = new FM_Models_FM_VideoGallery();
				$this->images = $this->_videoGalleryTable->getVideosByKeys(array('videoAlbum'=>$this->id));
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
		$model = new FM_Models_FM_VideoAlbum();
		$videoGalleryTable = new FM_Models_FM_VideoGallery();
		$s = $model->getAlbumsByKeys(array('active'=>'1', 'orgId'=>$orgId));
		$default = $videoGalleryTable->getVideosByKeys(array('orgId'=>$orgId, 'videoAlbum'=>0));
		$sArray = array();
		$defaultAlbum = new FM_Components_Util_VideoAlbum();
		$defaultAlbum->setImages($default);
		$sArray[] = $defaultAlbum;
		foreach($s as $key=>$values) {
			$sArray[] = new FM_Components_Util_VideoAlbum(array('id'=>$values['id']));
		}
		//print_r($sArray);exit;
		return $sArray;
	}

	public static function deleteAlbum($id) {
		$VideoAlbumModel = new FM_Models_FM_VideoAlbum();
		$model = new FM_Models_FM_VideoGallery();
		$model->remove(array('videoAlbum'=>$id));
		return $VideoAlbumModel->remove(array('id'=>$id));
	}
	
	public static function updateBanner($args =array(), $new = array()) {
		$VideoAlbumModel = new FM_Models_FM_VideoAlbum();
		$res = $VideoAlbumModel->getVideoAlbumByKeys($args);
		if(count($res) > 0) {
			return $VideoAlbumModel->edit($args, $new);
		} else {
			return self::insert($new);
		}
	}

	public static function insert($args) {
		$Model = new FM_Models_FM_VideoAlbum();
		if($id = $Model->insert($args)) {
			return $id;
		}
		return false;
	}

	public static function hasRow($id) {
		$model = new FM_Models_FM_VideoAlbum();
		$VideoAlbum = $model->getVideoAlbumsByKeys(array('orgId'=>$id));
		return (count($VideoAlbum) < 1) ? false : true;
	}
}