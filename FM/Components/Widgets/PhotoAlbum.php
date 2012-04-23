<?php
Zend_Loader::loadClass('FM_Models_FM_PhotoGallery');
Zend_Loader::loadClass('FM_Components_Widgets_BaseWidget');

class FM_Components_Widgets_PhotoGallery extends FM_Components_Widgets_BaseWidget {

	protected $_view;
	protected $_photoGalleryTable;
	protected $_photos = array();

	public function __construct($view, $orgId, $id, $admin) {
		$this->_photoGalleryTable = new FM_Models_FM_PhotoGallery();
		$this->_photos = $this->_photoGalleryTable->getPhotosByKeys(array('orgId'=>$orgId));
		//print Zend_Json::encode($this->_photos);
		$org = new FM_Components_Organization(array('id'=>$orgId));
		$view->headScript()->appendFile(
		'/js/jquery/jquery.jcarousel.js',
		'text/javascript'
		);
		$json = Zend_Json::encode($this->_photos);
		$count = count($this->_photos);
		if($id) {
			$view->layout()->{$id} = $view->partial('widgets/photogallery/widget.phtml',
			array('org'=>$org, 'photos'=>array_splice($this->_photos, 0, 10), 'json'=>$json, 'admin'=>$admin, 'count'=>$count));
		}
	}

	public static function addPhoto($args) {
		$table = new FM_Models_FM_PhotoGallery();
		if($id = $table->insertPhoto($args)) {
			return $id;
		}
		return 0;
	}

	public static function getPhoto($id) {
		$table = new FM_Models_FM_PhotoGallery();
		return $table->getPhotoByKeys(array('id'=>$id));
	}

	public static function removeMedia($id) {
		$model = new FM_Models_FM_PhotoGallery();
		return $model->remove(array('id'=>$id));
	}

	public function getPhotos() {
		return $this->_photos;
	}

	public function getIndexedPhotos() {
		$photos = array();
		foreach ($this->getPhotos() as $i=>$photo) {
			$photos['p_'. $photo['id']] = $photo;
		}
		return $photos;

	}

}