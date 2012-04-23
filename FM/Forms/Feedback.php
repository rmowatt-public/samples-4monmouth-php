<?php
Zend_Loader::loadClass('Zend_Form');

class FM_Forms_Feedback extends Zend_Form {

	public function __construct($options = null) {
		parent::__construct($options);
		$this->addElement('text', 'email', array('label'=>'email :', 'name'=>'email'));
		$this->addElement('textarea', 'feedback', array('label'=>'feedback :', 'name'=>'feedback'));
		//$this->addElement('hidden', 'orgId', array('name'=>'orgId'));
		$this->addElement('submit', 'submit', array('name'=>'submit', 'class'=>'sub'));
	}
}