<?php
Zend_Loader::loadClass ( 'FM_Models_FM_Faqs' );
Zend_Loader::loadClass ( 'FM_Components_BaseComponent' );

class FM_Components_Util_Faq extends FM_Components_BaseComponent{

	protected $id;
	protected $orgId;
	protected $question;
	protected $answer;
	protected $order;
	protected $active;

	public function __construct($keys = null) {
		if(is_array($keys)) {
			$faqModel = new FM_Models_FM_Faqs();
			$faq = $faqModel->getFaqByKeys($keys);
			if(count($faq)){
				foreach ($faq as $key=>$value) {
					if(property_exists($this, $key)) {
							$this->{$key} = $value;
					}
				}
				return true;
			}
			return false;
		}
		return true;
	}
	/**
	 * @param $active the $active to set
	 */
	public function setActive($active) {
		$this->active = $active;
	}

	/**
	 * @param $order the $order to set
	 */
	public function setOrder($order) {
		$this->order = $order;
	}

	/**
	 * @param $answer the $answer to set
	 */
	public function setAnswer($answer) {
		$this->answer = $answer;
	}

	/**
	 * @param $question the $question to set
	 */
	public function setQuestion($question) {
		$this->question = $question;
	}

	/**
	 * @param $orgId the $orgId to set
	 */
	public function setOrgId($orgId) {
		$this->orgId = $orgId;
	}

	/**
	 * @param $id the $id to set
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @return the $active
	 */
	public function getActive() {
		return $this->active;
	}

	/**
	 * @return the $order
	 */
	public function getOrder() {
		return $this->order;
	}

	/**
	 * @return the $answer
	 */
	public function getAnswer() {
		return $this->answer;
	}

	/**
	 * @return the $question
	 */
	public function getQuestion() {
		return $this->question;
	}

	/**
	 * @return the $orgId
	 */
	public function getOrgId() {
		return $this->orgId;
	}

	/**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}

	public function edit($args) {
		foreach ($args as $key=>$value) {
			if(property_exists($this, $key)) {
					$this->{$key} = $value;
				}
		}
		$faqModel = new FM_Models_FM_Faqs();
		if($faqModel->edit(array('id'=>$this->getId()), $this->toArray(array('id')))) {
			return true;
		}
		return false;
	}


	public static function insertFaq($args) {
		$faqModel = new FM_Models_FM_Faqs();
		if($id = $faqModel->insertFaq($args)) {
			return $id;
		}
		return false;
	}

	public static function getFaqs($args) {
		$faqModel = new FM_Models_FM_Faqs();
		$returnArray = array();
		if(count($faqs = $faqModel->getFaqsByKeys($args))) {
			foreach ($faqs as $index=>$values) {
				$faq = new FM_Components_Util_Faq(array('id'=>$values['id']));
				$returnArray[] = $faq;
			}
		}
		return $returnArray;
	}

	public static function delete($keys) {
		$faqModel = new FM_Models_FM_Faqs();
		if($faq = new FM_Components_Util_Faq($keys)) {
			if($faqModel->remove(array('id'=>$faq->getId()))) {
					return true;
			}
		}
		return false;
	}

}