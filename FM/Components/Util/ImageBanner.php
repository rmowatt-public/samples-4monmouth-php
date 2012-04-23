<?php
Zend_Loader::loadClass('FM_Components_BaseComponent');
Zend_Loader::loadClass('FM_Models_FM_ImageBanner');
//Zend_Loader::loadClass('FM_Models_FM_ImageTemplates');


class FM_Components_Util_ImageBanner extends FM_Components_BaseComponent {

	protected $id;
	protected $name;
	protected $file;
	protected $height;
	protected $width;
	protected $alt;
	protected $title;
	protected $url;
	protected $active = 0;
	protected $created = 0;

	public function __construct($keys = null) {
		if(is_array($keys)) {
			$bannerModel = new FM_Models_FM_ImageBanner();
			$banner = $bannerModel->getBannerByKeys($keys);
			if(count($banner)){
				foreach ($banner as $key=>$value) {
					if(property_exists($this, $key)) {
						$this->{$key} = $value;
					}
				}
				//$bannerTemplateModel = new FM_Models_FM_ImageTemplates();
				//$this->template = $bannerTemplateModel->getTemplateByKeys(array('id'=>$this->type));
				return true;
			}
			return false;
		}
		return true;
	}

	public function getId() {
		return $this->id;
	}

	public function getTitle() {
		return $this->title;
	}

	public function getName() {
		return $this->name;
	}


	public function getOrgId() {
		return $this->oid;
	}

	public function getCode() {
		return $this->code;
	}

	public function getType() {
		return $this->type;
	}

	public function getOffer() {
		return $this->offer;
	}

	public function getCopy() {
		return $this->copy;
	}

	public function isCreated() {
		return ($this->created == 1) ? true : false;
	}

	public function isActive() {
		return ($this->active == 1) ? true : false;
	}

	public function getFile() {
		return ($this->file && $this->file != '') ? $this->file : false;
	}

	public static function insertBanner($args) {
		//sprint_r($args);exit;
		if(!is_array($args)){return false;}
		$bannerModel = new FM_Models_FM_ImageBanner();
		return $bannerModel->insertBanner($args);
	}

	public static function deleteBanner($args) {
		$bannerModel = new FM_Models_FM_ImageBanner();
		return $bannerModel->remove($args);
	}

	public static function updateBanner($keys, $args) {
		$bannerModel = new FM_Models_FM_ImageBanner();
		return $bannerModel->edit($keys, $args);
	}

	public static function getOrgBanners($oid) {
		$banner = array();
		$bannerModel = new FM_Models_FM_ImageBanner();
		$banners = $bannerModel->getBannersByKeys(array('oid'=>$oid));
		if(count($banners)) {
			foreach ($banners as $record) {
				if($b= new FM_Components_Banner(array('id'=>$record['id']))){
					$banner[] = $b;
				}
			}
		}
		return $banner;
	}

	public static function getRandomBanners() {
		$banner = array();
		$bannerModel = new FM_Models_FM_ImageBanner();
		$banners = $bannerModel->getRandom();

		if(count($banners)) {
			foreach ($banners as $record) {
				if($b= new FM_Components_Banner(array('id'=>$record['id']))){
					$banner[] = $b;
				}
			}
		}
		return $banner;
	}

	public static function getBannersBySize($size) {
		$Banner = array();
		$couponModel = new FM_Models_FM_ImageBanner();
		$Banners = $couponModel->getBannersByKeys(array('width'=>$size));

		if(count($Banners)) {
			foreach ($Banners as $record) {
				if($b= new FM_Components_Util_ImageBanner(array('id'=>$record['id']))){
					$Banner[] = $b;
				}
			}
		}
		return $Banner;
	}
}