<?php
Zend_Loader::loadClass('Zend_Form');
Zend_Loader::loadClass('Zend_Form_Element_Select');
Zend_Loader::loadClass('Zend_Form_Element_Checkbox');
Zend_Loader::loadClass('FM_Models_FM_SearchPrimaryCategories');
Zend_Loader::loadClass('Zend_Form_Element_Select');

class FM_Forms_LimeCard extends Zend_Form {

	public function __construct($options = null, $uname = false, $email = false) {
		$options['id'] = 'limecardForm';
		parent::__construct($options);
		//$this->addElement('text', 'email', array('label'=>'search:', 'name'=>'limecardsearch', 'style'=>'width:300px;'));
		$pc = new FM_Models_FM_SearchPrimaryCategories ();
		$category = new Zend_Form_Element_Select(array('label'=>'', 'name'=>'category', 'onchange'=>'limecardselectcat(this)'));
		$category->addMultiOption('', 'Select A Category');
		$category->addMultiOption(0, 'ALL');
		foreach($pc->getRootCategories() as $index=>$values) {
			$category->addMultiOption($values['id'], ucwords(strtolower($values['name'])));
		}
		$this->addElement($category) ;
		//$this->addElement('submit', 'submit', array('value'=>'submit', 'class'=>'sub'));
	}

}