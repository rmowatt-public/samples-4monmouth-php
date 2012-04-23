<?php
Zend_Loader::loadClass('FM_Models_FM_CouponTemplates');

class FM_Components_Util_CouponTemplate{

	protected $id;
	protected $name;
	protected $image;
	protected $active;
	private $_templateModel;

	public function __construct($keys) {
		$this->_templateModel = new FM_Models_FM_CouponTemplates();
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
		$model = new FM_Models_FM_CouponTemplates();
		$templates = $model->getAll();
		$templatesArray = array();
		foreach($templates as $key=>$values) {
			$templatesArray[] = new FM_Components_Util_CouponTemplate(array('id'=>$values['id']));
		}
		return $templatesArray;
	}

	public static function getActive() {
		$model = new FM_Models_FM_CouponTemplates();
		$templates = $model->getTemplatesByKeys(array('active'=>'1'));
		$templatesArray = array();
		foreach($templates as $key=>$values) {
			$templatesArray[] = new FM_Components_Util_CouponTemplate(array('id'=>$values['id']));
		}
		return $templatesArray;
	}

	public static function deleteCouponTemplate($args) {
		$couponModel = new FM_Models_FM_CouponTemplates();
		return $couponModel->remove($args);
	}

	public static function updateCouponTemplate($args =array(), $new = array()) {
		$couponModel = new FM_Models_FM_CouponTemplates();
		return $couponModel->edit($args, $new);
	}

	public static function insertTemplate($args) {
		$templateModel = new FM_Models_FM_CouponTemplates();
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

	public function isActive() {
		return ($this->active == 1) ? true : false;
	}

}