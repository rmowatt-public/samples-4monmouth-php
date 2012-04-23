<?php
Zend_Loader::loadClass('FM_Models_FM_Testimonials');

class FM_Components_Util_Testimonial{

	protected $id;
	protected $from;
	protected $testimonial;
	protected $orgId;
	protected $active;
	protected $name;

	private $_testModel;

	public function __construct($keys) {
		$this->_testModel = new FM_Models_FM_Testimonials();
		if($test = $this->_testModel->getTestimonialByKeys($keys)) {
			if(count($test)){
				foreach ($test as $key=>$value) {
					if(property_exists($this, $key)) {
						$this->{$key} = $value;
					}
				}
				return true;
			}
		}
		return false;
	}

	public function getFrom() {
		return $this->from;
	}
	
	public function getName() {
		return $this->name;
	}

	public function getTestimonial() {
		return $this->testimonial;
	}

	public function getId() {
		return $this->id;
	}

	public function isActive() {
		return ($this->active == 1) ? true : false;
	}

	public static function insertTestimonial($args) {
		$model = new FM_Models_FM_Testimonials();
		return $model->insert($args);
	}

	public static function deleteTestimonial($args) {
		$model = new FM_Models_FM_Testimonials();
		return $model->remove($args);
	}

	public static function editTestimonial($args, $new) {
		$model = new FM_Models_FM_Testimonials();
		return $model->edit($args, $new);
	}

	public static function getTestimonials($args) {
		$model = new FM_Models_FM_Testimonials();
		$tests = $model->getTestimonialsByKeys($args);
		$testimonials = array();
		if(count($tests)) {
			foreach($tests as $index=>$record) {
				$testimonials[] = new FM_Components_Util_Testimonial(array('id'=>$record['id']));
			}
		}
		return $testimonials;
	}



}