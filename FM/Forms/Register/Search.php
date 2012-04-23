<?php
Zend_Loader::loadClass('Zend_Form');
Zend_Loader::loadClass('Zend_Form_Element_Select');
Zend_Loader::loadClass('FM_Models_FM_SearchPrimaryCategories');

class FM_Forms_Register_Search extends Zend_Form {

	public function __construct($options = array()) {
		//$options['onsubmit'] = 'FM.AskUs.submitRequest();return false;';
		parent::__construct($options);
		$this->addElement('text', 'search', array('label'=>'Search :', 'name'=>'search', 'required'=>1));
		$this->addElement('submit', 'submit');
	}
}