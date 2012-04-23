<?php
Zend_Loader::loadClass('FM_Models_FM_Feedback');

class FM_Components_Util_Feedback {

	protected $id;
	protected $email;
	protected $feedback;
	protected $date;
	protected $approved;
	private $_feedbackModel;
	

	public function __construct($keys) {
		$this->_feedbackModel = new FM_Models_FM_Feedback();
		if($feedback = $this->_feedbackModel->getFeedbackByKeys($keys)) {
		if(count($feedback)){
				foreach ($feedback as $key=>$value) {
					if(property_exists($this, $key)) {
						$this->{$key} = $value;
					}
				}
				return true;
			}
		}
		return false;
	}

	public static function getAll($approved = false) {
		$model = new FM_Models_FM_Feedback();
		if(!$approved) {
		$feedbacks = $model->getAllFeedback();
		} else { 
			$feedbacks = $model->getFeedbacksByKeys(array('approved'=>1));
		}
		$feedbacksArray = array();
		foreach($feedbacks as $key=>$values) {
			$feedbacksArray[] = new FM_Components_Util_Feedback(array('id'=>$values['id']));
		}
		return $feedbacksArray;
	}

	/**
	 * @return the $abbr
	 */
	public function getDate() {
		return $this->date;
	}

	public function getEmail() {
		return $this->email;	
	}
	
	/**
	 * @return the $state
	 */
	public function getFeedback() {
		return $this->feedback;
	}

	/**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}
	
	public function isApproved() {
		return $this->approved;
	}
	
	public static function insertFeedback($args) {
		$feedbackModel = new FM_Models_FM_Feedback();
		return $feedbackModel->insert($args);
	}
	
	public static function updateFeedback($args =array(), $new = array()) {
		//print_r($args);print_r($new);exit;
		$feedbackModel = new FM_Models_FM_Feedback();
		return $feedbackModel->edit($args, $new);
	}

}