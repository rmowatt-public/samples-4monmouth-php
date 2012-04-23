<?php
Zend_Loader::loadClass('Zend_Form');

class FM_Forms_Login extends Zend_Form {

	public function __construct($options = null) {
		$options['action'] = '/access/login';
		$options['style'] = 'overflow:hidden';
		parent::__construct($options);
		$this->addElement('text', 'uname', array('label'=>'user name :', 'name'=>'uname'));
		$this->addElement('password', 'password', array('label'=>'password :', 'name'=>'password'));
		$this->addElement('submit', 'submit', array('name'=>'submit'));
	}
}