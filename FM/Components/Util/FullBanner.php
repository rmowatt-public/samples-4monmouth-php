<?php
Zend_Loader::loadClass('FM_Models_FM_FullBanners');

class FM_Components_Util_FullBanner extends FM_Components_BaseComponent {

	protected $id;
	protected $name;
	protected $file;
	protected $active;
	protected $created;
	protected $width;
	protected $height;
	protected $link;
	private $_FullBannerModel;

	public function __construct($keys) {
		$this->_FullBannerModel = new FM_Models_FM_FullBanners();
		if($template = $this->_FullBannerModel->getBannerByKeys($keys)) {
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
		$model = new FM_Models_FM_FullBanners();
		$templates = $model->getAll();
		$templatesArray = array();
		foreach($templates as $key=>$values) {
			$templatesArray[$values['id']] = new FM_Components_Util_FullBanner(array('id'=>$values['id']));
		}
		//sort($templatesArray);
		return $templatesArray;
	}

	public static function getLike($searchString) {
		$model = new FM_Models_FM_FullBanners();
		$templates =  $model->getLike($searchString);
		$templatesArray = array();
		foreach($templates as $key=>$values) {
			$templatesArray[$values['id']] = new FM_Components_Util_FullBanner(array('id'=>$values['id']));
		}
		sort($templatesArray);
		return array_reverse($templatesArray);
	}

	public static function getActive() {
		$model = new FM_Models_FM_FullBanners();
		$templates = $model->getBannersByKeys(array('active'=>'1'));
		$templatesArray = array();
		foreach($templates as $key=>$values) {
			$templatesArray[] = new FM_Components_Util_FullBanner(array('id'=>$values['id']));
		}
		return $templatesArray;
	}

	public static function getRandom() {
		$bannerModel = new FM_Models_FM_FullBanners();
		$banners =  $bannerModel->getRandom();
		return new FM_Components_Util_FullBanner(array('id'=>$banners[0]['id']));
	}

	public static function deleteFullBanner($args) {
		$bannerModel = new FM_Models_FM_FullBanners();
		return $bannerModel->remove($args);
	}

	public static function updateFullBanner($args =array(), $new = array()) {
		$bannerModel = new FM_Models_FM_FullBanners();
		return $bannerModel->edit($args, $new);
	}

	public static function insert($args) {
		$templateModel = new FM_Models_FM_FullBanners();
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

	public function getLink() {
		if(!stristr($this->link,  'http://')){
			$this->link = 'http://' . $this->link;
		}
		return $this->link;
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

}