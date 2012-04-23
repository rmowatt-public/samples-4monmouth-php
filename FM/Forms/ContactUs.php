<?php
Zend_Loader::loadClass('Zend_Form');
Zend_Loader::loadClass('Zend_Form_Element_Select');

Zend_Loader::loadClass('Zend_Form_Element_Checkbox');
Zend_Loader::loadClass('FM_Models_FM_Depts');
Zend_Loader::loadClass('FM_Components_Util_SiteEmail');

class FM_Forms_ContactUs extends Zend_Form {

	public function __construct($options = null) {
		parent::__construct($options);
		$emails = FM_Components_Util_SiteEmail::getAll();
		$deptModel = new FM_Models_FM_Depts();
		$department = new Zend_Form_Element_Select(array('label'=>'dept :', 'name'=>'dept', 'required'=>1));
		foreach($emails as $index=>$email) {
			$department->addMultiOption($email->getId(), $email->getName());
		}
		$this->addElement($department);
		$this->addElement('text', 'name', array('label'=>'name:', 'name'=>'name'));
		$this->addElement('text', 'email', array('label'=>'email:', 'name'=>'email'));
		$this->addElement('textarea', 'message', array('label'=>'question:', 'name'=>'message', 'rows'=>3));
		$this->addElement('submit', 'submit', array('value'=>'submit', 'class'=>'sub'));
		$this->addElement('hidden', 'id', array('value'=>''));
	}
	//$options['onsubmit'] = 'FM.AskUs.submitRequest();return false;';
}