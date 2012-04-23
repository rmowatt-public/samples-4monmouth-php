<?php
Zend_Loader::loadClass('FM_Models_FM_PhotoGallery');
Zend_Loader::loadClass('FM_Components_Widgets_BaseWidget');
Zend_Loader::loadClass('FM_Components_Util_PhotoAlbum');

class FM_Components_Widgets_PhotoGallery extends FM_Components_Widgets_BaseWidget {

	protected $_view;
	protected $_photoGalleryTable;
	protected $_photos = array();
	protected $_albums;
	protected $_showAlbum;

	public function __construct($view, $orgId, $id, $admin) {
		$this->_photoGalleryTable = new FM_Models_FM_PhotoGallery();
		$this->_photos = $this->_photoGalleryTable->getPhotosByKeys(array('orgId'=>$orgId));
		//print Zend_Json::encode($this->_photos);
		$this->_albums = FM_Components_Util_PhotoAlbum::getActive($orgId);
		$org = new FM_Components_Organization(array('id'=>$orgId));
		$options = $org->getOrgConfig()->getOptions();
		$this->_showAlbum = $options['showPhotoAlbum'];
		$view->headScript()->appendFile(
		'/js/jquery/jquery.jcarousel.js',
		'text/javascript'
		);
		$view->headScript()->appendFile(
		'/js/tooltip.js',
		'text/javascript'
		);
		$json = Zend_Json::encode($this->_photos);
		$count = count($this->_photos);
		$albums = '';
		$album_array = array();
		foreach ($this->_albums as $key=>$value) {
			$ajson = Zend_Json::encode($value->getImages());
			$album_array[$value->getId()] =$value->getImages();
			$acount = count($value->getImages());
			$albums .= $view->partial('widgets/photogallery/widget.phtml',
			array('org'=>$org, 'photos'=>array_splice($value->getImages(), 0, 10), 'json'=>$ajson, 'admin'=>$admin, 'count'=>$acount, 'id'=>$value->getId(), 'albums'=>$this->_albums));
		}
		
		$album_array = Zend_Json::encode($album_array);
		//print_r($album_array);exit;
		if($id) {
			//print $albums;exit;
			$view->layout()->{$id} = $view->partial('widgets/photogallery/gallerywrap.phtml',
			array('admin'=>$admin, 'galleries'=>$albums, 'albums'=>$this->_albums, 'album_array'=>$album_array, 'default'=>$this->_showAlbum));
		
			//$view->partial('widgets/photogallery/widget.phtml',
			//array('org'=>$org, 'photos'=>array_splice($this->_photos, 0, 10), 'json'=>$json, 'admin'=>$admin, 'count'=>$count, 'albums'=>$this->_albums));
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