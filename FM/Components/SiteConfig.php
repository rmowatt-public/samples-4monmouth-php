<?php
Zend_Loader::loadClass('FM_Models_FM_SiteConfig');

class FM_Components_SiteConfig extends FM_Components_BaseComponent{


	protected $id = array();
	protected $paybanners = array();
	protected $fullbanners = array();
	protected $background;
	protected $npbanners;
	private $_configTable;

	public function __construct() {
		$this->_configTable = new FM_Models_FM_SiteConfig();
		$config = $this->_configTable->getOptions();
		if(is_array($config) && count($config)){
			foreach ($config as $key=>$value) {
				if(property_exists($this, $key)) {
					$this->{$key} = $value;
				}
			}
		}

	}

	public function usePayBanners() {
		return $this->paybanners;
	}
	
	public function useFullBanners() {
		return $this->fullbanners;
	}
	
	public function getBackground() {
		return $this->background;
	}
	
	public function npBannersEnabled() {
		return $this->npbanners;
	}

	public static function update($keys, $args) {
		$configModel = new FM_Models_FM_SiteConfig();
		return $configModel->edit($keys, $args);
	}

}


