<?php
Zend_Loader::loadClass('FM_Models_FM_VideoGallery');
Zend_Loader::loadClass('FM_Components_Widgets_BaseWidget');
Zend_Loader::loadClass('FM_Components_Util_VideoAlbum');

class FM_Components_Widgets_VideoGallery extends FM_Components_Widgets_BaseWidget {

	protected $_view;
	protected $_videoGalleryTable;
	protected $_videos = array();
	protected $_albums;
	protected $_showAlbum;

	public function __construct($view, $orgId, $id, $admin) {
		$this->_videoGalleryTable = new FM_Models_FM_VideoGallery();
		$this->_videos = $this->_videoGalleryTable->getVideosByKeys(array('orgId'=>$orgId));
		//print Zend_Json::encode($this->_videos);
		$this->_albums = FM_Components_Util_VideoAlbum::getActive($orgId);
		$org = new FM_Components_Organization(array('id'=>$orgId));
		$options = $org->getOrgConfig()->getOptions();
		$this->_showAlbum = $options['showVideoAlbum'];
		$view->headScript()->appendFile(
		'/js/jquery/jquery.jcarousel.js',
		'text/javascript'
		);
		$view->headScript()->appendFile(
		'/js/tooltip.js',
		'text/javascript'
		);
		$view->headScript()->appendFile(
		'/js/widgets/videogallery.js',
		'text/javascript'
		);
		$json = Zend_Json::encode($this->_videos);
		$count = count($this->_videos);
		$albums = '';
		$album_array = array();
		foreach ($this->_albums as $key=>$value) {
			$ajson = Zend_Json::encode($value->getImages());
			$album_array[$value->getId()] =$value->getImages();
			$acount = count($value->getImages());
			$albums .= $view->partial('widgets/videogallery/widget.phtml',
			array('org'=>$org, 'videos'=>array_splice($value->getImages(), 0, 10), 'json'=>$ajson, 'admin'=>$admin, 'count'=>$acount, 'id'=>$value->getId(), 'albums'=>$this->_albums));
		}
		
		$album_array = Zend_Json::encode($album_array);
		//print_r($album_array);exit;
		if($id) {
			//print $albums;exit;
			$view->layout()->{$id} = $view->partial('widgets/videogallery/gallerywrap.phtml',
			array('admin'=>$admin, 'galleries'=>$albums, 'albums'=>$this->_albums, 'album_array'=>$album_array, 'default'=>$this->_showAlbum));
		
			//$view->partial('widgets/videogallery/widget.phtml',
			//array('org'=>$org, 'videos'=>array_splice($this->_videos, 0, 10), 'json'=>$json, 'admin'=>$admin, 'count'=>$count, 'albums'=>$this->_albums));
		}
	}

	public static function addVideo($args) {
		$table = new FM_Models_FM_VideoGallery();
		if($id = $table->insertVideo($args)) {
			return $id;
		}
		return 0;
	}

	public static function getVideo($id) {
		$table = new FM_Models_FM_VideoGallery();
		return $table->getVideoByKeys(array('id'=>$id));
	}

	public static function removeMedia($id) {
		$model = new FM_Models_FM_VideoGallery();
		return $model->remove(array('id'=>$id));
	}

	public function getVideos() {
		return $this->_videos;
	}

	public function getIndexedVideos() {
		$videos = array();
		foreach ($this->getVideos() as $i=>$video) {
			$videos['p_'. $video['id']] = $video;
		}
		return $videos;

	}

}