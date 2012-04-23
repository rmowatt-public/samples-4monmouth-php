<?php
Zend_Loader::loadClass('Zend_Form');
Zend_Loader::loadClass('Zend_Form_Element_Select');
Zend_Loader::loadClass('Zend_Form_Element_Checkbox');
Zend_Loader::loadClass('FM_Forms_Element_File');


class FM_Forms_Root_MediaKit extends Zend_Form {

	public function __construct($options = array()) {
		$options['src'] = array_key_exists('src', $options) ? $options['src'] : '';
		$this->addElementPrefixPath('FM', 'FM/');
		parent::__construct($options);
		$this->setAttrib('enctype', 'multipart/form-data');
		$file = new FM_Forms_Element_File('file');
		$file->setLabel('PDF :')
		->setRequired(true)
		->addValidator('NotEmpty');
		$this->addElement($file);
		
		$preview = new Zend_Form_Element_Text(array('label'=>'Click To View', 'name'=>'preview'));
		$preview->setDecorators(array(array('ViewScript', array(
		'viewScript' => 'form/preview.phtml',
		'class'      => 'form element',
		'src'=>$options['src'],
		'nobox'=>true
		))));
		$this->addElement($preview);
		
		$this->addElement('submit', 'submit', array('value'=>'submit', 'class'=>'sub'));
	}
}