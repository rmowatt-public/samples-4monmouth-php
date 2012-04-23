<?php
Zend_Loader::loadClass('FM_Models_FM_Video');
Zend_Loader::loadClass('FM_Components_Widgets_BaseWidget');
Zend_Loader::loadClass('FM_Components_Util_VideoAlbum');

class FM_Components_Widgets_Video extends FM_Components_Widgets_BaseWidget {

	protected $_view;
	protected $_videoTable;
	protected $_videos = array();
	protected $_albums;
	protected $_showAlbum;

	public function __construct($view, $orgId, $id, $admin) {
		$this->_videoTable = new FM_Models_FM_Video();
		$this->_videos = $this->_videoTable->getVideosByKeys(array('orgId'=>$orgId));
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
		$json = Zend_Json::encode($this->_videos);
		$count = count($this->_videos);
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
			//array('org'=>$org, 'photos'=>array_splice($this->_videos, 0, 10), 'json'=>$json, 'admin'=>$admin, 'count'=>$count, 'albums'=>$this->_albums));
		}
	}

	public static function addVideo($args) {
		$table = new FM_Models_FM_Video();
		if($id = $table->insertRecord($args)) {
			return $id;
		}
		return 0;
	}

	public static function getVideo($id) {
		$table = new FM_Models_FM_Video();
		return $table->getRecordByKeys(array('id'=>$id));
	}

	public static function removeMedia($id) {
		$model = new FM_Models_FM_Video();
		return $model->remove(array('id'=>$id));
	}

	public function getVideos() {
		return $this->_videos;
	}


}