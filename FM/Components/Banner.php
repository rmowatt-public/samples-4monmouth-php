<?php
Zend_Loader::loadClass('FM_Components_BaseComponent');
Zend_Loader::loadClass('FM_Models_FM_Banner');
Zend_Loader::loadClass('FM_Models_FM_BannerTemplates');
Zend_Loader::loadClass('FM_Components_SiteConfig');


class FM_Components_Banner extends FM_Components_BaseComponent {

	protected $id;
	protected $oid;
	protected $type;
	protected $height;
	protected $width;
	protected $alt;
	protected $title;
	protected $url;
	protected $active = 0;
	protected $name;
	protected $path;
	protected $medianame;
	protected $headline;
	protected $copy;
	protected $created = 0;
	protected $template = null;
	protected $date;

	public function __construct($keys = null) {
		if(is_array($keys)) {
			$bannerModel = new FM_Models_FM_Banner();
			$banner = $bannerModel->getBannerByKeys($keys);
			if(count($banner)){
				foreach ($banner as $key=>$value) {
					if(property_exists($this, $key)) {
						$this->{$key} = $value;
					}
				}
				$bannerTemplateModel = new FM_Models_FM_BannerTemplates();
				$this->template = $bannerTemplateModel->getTemplateByKeys(array('id'=>$this->type));
				return true;
			}
			return false;
		}
		return true;
	}

	public function getHeadline() {
		return ($this->headline) ? $this->headline : 'n/a';
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

	public function getSponsor() {
		return $this->sponsor;
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

	public function getDate() {
		return $this->date;
	}

	public function isCreated() {
		return ($this->created == 1) ? true : false;
	}

	public function isActive() {
		return ($this->active == 1) ? true : false;
	}

	public function getFile() {
		return ($this->medianame && $this->medianame != '') ? $this->medianame : false;
	}

	public function getHeight() {
		return $this->height;
	}

	public function getWidth() {
		return $this->width;
	}

	public static function insertBanner($args) {
		if(!is_array($args)){return false;}
		$bannerModel = new FM_Models_FM_Banner();
		$banner = new FM_Components_Banner();
		$cleansedArgs = array();
		foreach ($args as $key=>$value) {
			if(property_exists($banner, $key)) {
				$cleansedArgs[$key] = $value;
			}
		}
		if(!count($cleansedArgs)){return false;}
		return $bannerModel->insertBanner($cleansedArgs);
	}

	public static function deleteBanner($args) {
		$bannerModel = new FM_Models_FM_Banner();
		return $bannerModel->remove($args);
	}

	public static function updateBanner($keys, $args) {
		$bannerModel = new FM_Models_FM_Banner();
		return $bannerModel->edit($keys, $args);
	}

	public static function getOrgBanners($oid) {
		$banner = array();
		$bannerModel = new FM_Models_FM_Banner();
		if($oid == 0 ) {
			$banners = $bannerModel->getAll();
		}else {
			$banners = $bannerModel->getBannersByKeys(array('oid'=>$oid));
		}
		if(count($banners)) {
			foreach ($banners as $record) {
				if($b= new FM_Components_Banner(array('id'=>$record['id']))){
					$banner[$record['id']] = $b;
				}
			}
		}
		if(!count($banner)){return $banner;}
		sort($banner);
		return array_reverse($banner);
	}
	
	
	public static function getOrgBannerRecords($oid) {
		$banner = array();
		$bannerModel = new FM_Models_FM_Banner();
		if($oid == 0 ) {
			$banners = $bannerModel->getAll();
		}else {
			$banners = $bannerModel->getBannersByKeys(array('oid'=>$oid));
		}
		/**
		if(count($banners)) {
			foreach ($banners as $record) {
				if($b= new FM_Components_Banner(array('id'=>$record['id']))){
					$banner[$record['id']] = $b;
				}
			}
		}
		**/
		if(!count($banners)){return $banners;}
		sort($banners);
		return array_reverse($banners);
	}

	protected function orderByNewest($banners) {

		$internal = array();
		foreach ($banners as $key=>$banner) {
			$internal[time($banner->getDate())] = $banner;
		}
		print_r($internal);exit;
		return sort($internal);
	}

	public static function getRandomBanners($keys = array(), $limit = null) {
		$banner = array();
		$bannerModel = new FM_Models_FM_Banner();
		$banners = $bannerModel->getRandom($keys, $limit);

		if(count($banners)) {
			foreach ($banners as $record) {
				if($b= new FM_Components_Banner(array('id'=>$record['id']))){
					$banner[] = $b;
				}
			}
		}
		return $banner;
	}

	public static function getSortedRandomBanners($keys = array(), $limit = null) {
		$site = new FM_Components_SiteConfig();
		$banner = array();
		$bannerModel = new FM_Models_FM_Banner();
		if(!$site->npBannersEnabled()) {
			$banners['18'] = $bannerModel->getBusinessRandom(array('type'=>18), 4, $keys, array());
			$banners['19'] = $bannerModel->getBusinessRandom(array('type'=>19), 4, $keys, self::getIds($banners['18']));
			$banners['20'] = $bannerModel->getBusinessRandom(array('type'=>20), 4, $keys, array_merge(self::getIds($banners['18']), self::getIds($banners['19'])));
		} else {
			$banners['18'] = $bannerModel->getRandom(array('type'=>18), 4, $keys, array());
			$banners['19'] = $bannerModel->getRandom(array('type'=>19), 4, $keys, self::getIds($banners['18']));
			$banners['20'] = $bannerModel->getRandom(array('type'=>20), 4, $keys, array_merge(self::getIds($banners['18']), self::getIds($banners['19'])));
		}

		//$banners = array_merge($banners[2],array_merge($banners[0], $banners[1]));
		$bannerTemplateModel = new FM_Models_FM_BannerTemplates();
			
		if(count($banners)) {
			$oids = array();
			foreach ($banners as $index=>$ba) {
				foreach ($ba as $record) {
						$record['template'] =  $bannerTemplateModel->getTemplateByKeys(array('id'=>$record['type']));
							$banner[] = $record;
				}
			}
		}
		return $banner;
	}
	
	private function getIds($args) {
		$ids = array();
		foreach ($args as $key=>$values) {
			$ids[] = $values['oid'];;
		}
		return $ids;
	}

	public static function getNewBanners($orgId) {
		$Banner = array();
		$couponModel = new FM_Models_FM_Banner();
		$args = array('created'=>0);
		if($orgId && $orgId != 0 ) {
			$args['oid'] = $orgId;
		}
		$Banners = $couponModel->getBannersByKeys($args);

		if(count($Banners)) {
			foreach ($Banners as $record) {
				if($b= new FM_Components_Banner(array('id'=>$record['id']))){
					$Banner[] = $b;
				}
			}
		}
		return $Banner;
	}
}