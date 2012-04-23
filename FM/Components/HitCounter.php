<?php
Zend_Loader::loadClass('FM_Components_BaseComponent');
Zend_Loader::loadClass('FM_Models_FM_HitCounter');
Zend_Loader::loadClass('FM_Models_FM_BannerTemplates');


class FM_Components_HitCounter extends FM_Components_BaseComponent {


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

	public static function update($orgId) {
		$hc = new FM_Models_FM_HitCounter();
		return $hc->update($orgId);
	}
	
	public static function getHits($orgId) {
		$hc = new FM_Models_FM_HitCounter();
		if(count($count = $hc->getOrgCount($orgId))) {
			return $count['count'];
		}
		return 0;
	}
}