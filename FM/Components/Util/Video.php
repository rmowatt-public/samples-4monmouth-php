<?php
Zend_Loader::loadClass ( 'FM_Models_FM_VideoGallery' );
Zend_Loader::loadClass ( 'FM_Components_BaseComponent' );

class FM_Components_Util_Video extends FM_Components_BaseComponent{

	protected $id;
	protected $orgId;
	protected $video ;
	protected $description;
	protected $date;

	public function __construct($keys = null) {
		if(is_array($keys)) {
			$videoModel = new FM_Models_FM_Video ();
			$video = $videoModel->getVideoByKeys($keys);
			if(count($video)){
				foreach ($video as $key=>$value) {
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
	
	public function getId() {
		return $this->id;
	}
	
	public function getFileName() {
		return ($this->fileName) ? $this->fileName : false;
	}

	public static function getActive() {
		$model = new FM_Models_FM_Video();
		$s = $model->getVideosByKeys(array('active'=>'1'));
		$sArray = array();
		foreach($s as $key=>$values) {
			$sArray[] = new FM_Components_Util_Video(array('id'=>$values['id']));
		}
		return $sArray;
	}

	
	public static function getOrgActive($orgId) {
		$model = new FM_Models_FM_Video();
		$s = $model->getVideosByKeys(array('active'=>'1', 'orgId'=>$orgId));
		$sArray = array();
		foreach($s as $key=>$values) {
			$sArray[] = new FM_Components_Util_Video(array('id'=>$values['id']));
		}
		return $sArray;
	}
	
	
	public static function deleteVideo($args) {
		$videoModel = new FM_Models_FM_VideoGallery();
		return $videoModel->remove($args);
	}

	public static function updateVideo($args =array(), $new = array()) {
		$videoModel = new FM_Models_FM_Video();
		$videoModel->edit($args, array('active'=>0));
		return $videoModel->edit($args, $new);
	}

	public static function insert($args) {
		$Model = new FM_Models_FM_Video();
		if($id = $Model->insertRecord($args)) {
			return $id;
		}
		return false;
	}
	
	public static function hasRow($id) {
		$model = new FM_Models_FM_Video();
		$icon = $model->getVideosByKeys(array('orgId'=>$id));
		return (count($icon) < 1) ? false : true;
	}
}