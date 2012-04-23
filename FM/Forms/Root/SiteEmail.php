<?php
Zend_Loader::loadClass('Zend_Form');
Zend_Loader::loadClass('Zend_Form_Element_Select');
Zend_Loader::loadClass('Zend_Form_Element_Checkbox');
Zend_Loader::loadClass('FM_Forms_Element_File');


class FM_Forms_Root_SiteEmail extends Zend_Form {

	public function __construct($options = null) {
		$this->addElementPrefixPath('FM', 'FM/');
		parent::__construct($options);
		$this->addElement('text', 'name', array('label'=>'name :','class'=>'name', 'required'=>1));
		$this->addElement('text', 'email', array('label'=>'email :', 'class'=>'email', 'required'=>1));
		$this->addElement('hidden', 'id');
		$this->addElement('submit', 'submit', array('value'=>'submit', 'class'=>'sub'));
		$this->addElement('submit', 'clear', array('value'=>'clear', 'class'=>'sub', 'onclick'=>'FM.Utilities.clearSiteEmail();return false;'));
	}
}