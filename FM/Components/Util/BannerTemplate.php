<?php
Zend_Loader::loadClass('FM_Models_FM_BannerTemplates');

class FM_Components_Util_BannerTemplate extends FM_Components_BaseComponent {

	protected $id;
	protected $name;
	protected $image;
	protected $active;
	protected $class;
	protected $headline;
	protected $width;
	protected $height;
	protected $bgd;
	protected $logo;
	private $_templateModel;

	public function __construct($keys) {
		$this->_templateModel = new FM_Models_FM_BannerTemplates();
		if($template = $this->_templateModel->getTemplateByKeys($keys)) {
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
		$model = new FM_Models_FM_BannerTemplates();
		$templates = $model->getAll();
		$templatesArray = array();
		foreach($templates as $key=>$values) {
			$templatesArray[] = new FM_Components_Util_BannerTemplate(array('id'=>$values['id']));
		}
		return $templatesArray;
	}

	public static function getActive() {
		$model = new FM_Models_FM_BannerTemplates();
		$templates = $model->getTemplatesByKeys(array('active'=>'1'));
		$templatesArray = array();
		foreach($templates as $key=>$values) {
			$templatesArray[] = new FM_Components_Util_BannerTemplate(array('id'=>$values['id']));
		}
		return $templatesArray;
	}

	public static function deleteBannerTemplate($args) {
		$bannerModel = new FM_Models_FM_BannerTemplates();
		return $bannerModel->remove($args);
	}

	public static function updateBannerTemplate($args =array(), $new = array()) {
		$bannerModel = new FM_Models_FM_BannerTemplates();
		return $bannerModel->edit($args, $new);
	}

	public static function insertTemplate($args) {
		$templateModel = new FM_Models_FM_BannerTemplates();
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

	public function getImage() {
		return $this->image;
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

}