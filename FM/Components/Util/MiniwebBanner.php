<?php
Zend_Loader::loadClass ( 'FM_Models_FM_OrgdataMiniwebBanners' );
Zend_Loader::loadClass ( 'FM_Components_BaseComponent' );

class FM_Components_Util_MiniwebBanner extends FM_Components_BaseComponent{

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
			$logoModel = new FM_Models_FM_OrgdataMiniwebBanners ();
			$logo = $logoModel->getBannerByKeys($keys);
			if(count($logo)){
				foreach ($logo as $key=>$value) {
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
		return ($this->fileName) ? $this->fileName : false;;
	}

	public static function getActive() {
		$model = new FM_Models_FM_OrgdataMiniwebBanners();
		$s = $model->getBannersByKeys(array('active'=>'1'));
		$sArray = array();
		foreach($s as $key=>$values) {
			$sArray[] = new FM_Components_Util_Banner(array('id'=>$values['id']));
		}
		return $sArray;
	}

	public static function deleteBanner($args) {
		$logoModel = new FM_Models_FM_OrgdataMiniwebBanners();
		return $logoModel->remove($args);
	}

	public static function updateBanner($args =array(), $new = array()) {
		$logoModel = new FM_Models_FM_OrgdataMiniwebBanners();
		$res = $logoModel->getBannerByKeys($args);
		if(count($res) > 0) {
			//print __FILE__ . ' ' . __LINE__;exit;
			//$logoModel->edit($args, array('active'=>0));
			return $logoModel->edit($args, $new);
		} else {
			return self::insert($new);
		}
	}

	public static function insert($args) {
		$Model = new FM_Models_FM_OrgdataMiniwebBanners();
		if($id = $Model->insertRecord($args)) {
			return $id;
		}
		return false;
	}

	public static function hasRow($id) {
		$model = new FM_Models_FM_OrgdataMiniwebBanners();
		$banner = $model->getBannersByKeys(array('orgId'=>$id));
		return (count($banner) < 1) ? false : true;
	}
}