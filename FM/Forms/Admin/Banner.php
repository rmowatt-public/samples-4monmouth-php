<?php
Zend_Loader::loadClass('Zend_Form');
Zend_Loader::loadClass('FM_Forms_Element_File');

class FM_Forms_Admin_Banner extends Zend_Form {

	public function __construct($options = null) {
		$this->addElementPrefixPath('FM', 'FM/');

		$options['action'] = '';
		parent::__construct($options);
		$this->setName('upload');
		$this->setAttrib('enctype', 'multipart/form-data');

		$this->addElement('text', 'bannername', array('label'=>'name :', 'name'=>'bannername', 'required'=>true));
		//$this->addElement('text', 'height', array('label'=>'height (in px) :', 'name'=>'height'));
		//$this->addElement('text', 'width', array('label'=>'width (in px) :', 'name'=>'width'));
		$this->addElement('text', 'banneralt', array('label'=>'alt :', 'name'=>'banneralt'));
		$this->addElement('text', 'bannertitle', array('label'=>'title:', 'name'=>'bannertitle'));
		$this->addElement('text', 'bannerurl', array('label'=>'url :', 'name'=>'bannerurl'));
		$this->addElement('textarea', 'bannercopy', array('label'=>'copy :', 'name'=>'bannercopy', 'rows'=>4));
		/**
		$file = new FM_Forms_Element_File('file');
		$file->setLabel('File')
		->setRequired(true)
		->addValidator('NotEmpty');
		$this->addElement($file);
		**/
		$this->addElement('submit', 'submit', array('name'=>'create', 'value'=>'create', 'onclick'=>'FM.BannerWidget.create(); return false;'));
		$this->addElement('hidden', 'editid');
	}
}