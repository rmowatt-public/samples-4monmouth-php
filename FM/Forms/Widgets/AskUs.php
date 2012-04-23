<?php
Zend_Loader::loadClass('Zend_Form');

class FM_Forms_Widgets_AskUs extends Zend_Form {

	public function __construct($options = null) {
		$options['onsubmit'] = 'FM.AskUs.submitRequest();return false;';
		parent::__construct($options);
		$this->addElement('text', 'email', array('label'=>'email :', 'name'=>'email'));
		$this->addElement('textarea', 'question', array('label'=>'question :', 'name'=>'question'));
		$this->addElement('submit', 'submit', array('name'=>'submit'));
	}
}