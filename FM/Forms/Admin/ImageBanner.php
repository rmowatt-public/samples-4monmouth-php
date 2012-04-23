<?php
Zend_Loader::loadClass('Zend_Form');
Zend_Loader::loadClass('Zend_Form_Element_Select');
Zend_Loader::loadClass('FM_Forms_Element_File');

class FM_Forms_Admin_ImageBanner extends Zend_Form {

	public function __construct($options = null) {
		$this->addElementPrefixPath('FM', 'FM/');

		$options['action'] = '';
		parent::__construct($options);
		$this->setName('upload');
		$this->setAttrib('enctype', 'multipart/form-data');

		$this->addElement('text', 'name', array('label'=>'name :', 'name'=>'name', 'required'=>true));
		$this->addElement('text', 'alt', array('label'=>'alt :', 'name'=>'alt'));
		$this->addElement('text', 'url', array('label'=>'url :', 'name'=>'url'));
		//$this->addElement('textarea', 'bannercopy', array('label'=>'copy :', 'name'=>'bannercopy', 'rows'=>4));
		
		$type = new Zend_Form_Element_Select(array('label'=>'width', 'name'=>'type'));
		$type->addMultiOption('940', '940');
		$type->addMultiOption('690', '690');
		$type->addMultiOption('245', '245');
		$this->addElement($type);
		
		
		$file = new FM_Forms_Element_File('file');
		$file->setLabel('Image')
		->setRequired(true)
		->addValidator('NotEmpty');
		$this->addElement($file);
	
		$this->addElement('submit', 'submit', array('name'=>'create', 'value'=>'create', 'style'=>'margin-right:600px;'));
		$this->addElement('hidden', 'editid');
	}
}