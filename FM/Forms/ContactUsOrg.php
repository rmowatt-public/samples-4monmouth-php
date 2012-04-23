<?php
Zend_Loader::loadClass('Zend_Form');
Zend_Loader::loadClass('Zend_Form_Element_Select');

Zend_Loader::loadClass('Zend_Form_Element_Checkbox');
Zend_Loader::loadClass('FM_Models_FM_Depts');

class FM_Forms_ContactUsOrg extends Zend_Form {

	public function __construct($options = null) {
		if(!isset($options['onsubmit'])) {
			$options['onsubmit'] = 'FM.AskUs.submitRequest();return false;';
		}
		if(!isset($options['id'])) {
			$options['id'] = 'contactUsOrgForm';
		}
		parent::__construct($options);
		$this->addElement('text', 'name', array('label'=>'name:', 'name'=>'name'));
		$this->addElement('text', 'email', array('label'=>'email:', 'name'=>'email'));
		$this->addElement('textarea', 'message', array('label'=>'question:', 'name'=>'message', 'rows'=>3));
		$this->addElement('submit', 'submit', array('value'=>'submit', 'class'=>'sub'));
		$this->addElement('hidden', 'orgId', array('value'=>''));
	}
}