<?php
Zend_Loader::loadClass('FM_Models_FM_PayBanners');

class FM_Components_Util_PayBanner extends FM_Components_BaseComponent {

	protected $id;
	protected $name;
	protected $file;
	protected $active;
	protected $created;
	protected $width;
	protected $height;
	protected $link;
	private $_payBannerModel;

	public function __construct($keys) {
		$this->_payBannerModel = new FM_Models_FM_PayBanners();
		if($template = $this->_payBannerModel->getBannerByKeys($keys)) {
			if(count($template)){
				foreach ($template as $key=>$value) {
					if(property_exists($this, $key)) {
						$this->{$key} = $value;
					}
				}
				return true;
			}
		}
		return false;
	}

	public static function getAll() {
		$model = new FM_Models_FM_PayBanners();
		$templates = $model->getAll();
		$templatesArray = array();
		foreach($templates as $key=>$values) {
			$templatesArray[$values['id']] = new FM_Components_Util_PayBanner(array('id'=>$values['id']));
		}
		//sort($templatesArray);
		return $templatesArray;
	}

	public static function getActive() {
		$model = new FM_Models_FM_PayBanners();
		$templates = $model->getBannersByKeys(array('active'=>'1'));
		$templatesArray = array();
		foreach($templates as $key=>$values) {
			$templatesArray[] = new FM_Components_Util_PayBanner(array('id'=>$values['id']));
		}
		return $templatesArray;
	}
	
	public static function getRandom() {
		$bannerModel = new FM_Models_FM_PayBanners();
		$banners =  $bannerModel->getRandom();
		return new FM_Components_Util_PayBanner(array('id'=>$banners[0]['id']));
	}
	
	public static function getLike($searchString) {
		$bannerModel = new FM_Models_FM_PayBanners();
		$templates =  $bannerModel->getLike($searchString);
		$templatesArray = array();
		foreach($templates as $key=>$values) {
			$templatesArray[$values['id']] = new FM_Components_Util_PayBanner(array('id'=>$values['id']));
		}
		sort($templatesArray);
		return array_reverse($templatesArray);
	}

	public static function deletePayBanner($args) {
		$bannerModel = new FM_Models_FM_PayBanners();
		return $bannerModel->remove($args);
	}

	public static function updatePayBanner($args =array(), $new = array()) {
		$bannerModel = new FM_Models_FM_PayBanners();
		return $bannerModel->edit($args, $new);
	}

	public static function insert($args) {
		$templateModel = new FM_Models_FM_PayBanners();
		if($id = $templateModel->insertRecord($args)) {
			return $id;
		}
		return false;
	}


	/**
	 * @return the $template
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}

	public function getFile() {
		return $this->file;
	}
	
	public function getClass() {
		return $this->class;
	}

	
	public function getHeadline() {
		return $this->headline;
	}

	
	public function getWidth() {
		return $this->width;
	}

	
	public function getHeight() {
		return $this->height;
	}

	public function getLogo() {
		return ($this->logo == 0) ? '0' : '1';
	}

	public function isActive() {
		return ($this->active == 1) ? true : false;
	}
	
	public function getLink() {
		if(!$this->link || $this->link == ''){return false;}
		if(!stristr($this->link,  'http://')){
			$this->link = 'http://' . $this->link;
		}
		return $this->link;
	}

}