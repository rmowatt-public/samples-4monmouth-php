<?php
Zend_Loader::loadClass('Zend_Form');
Zend_Loader::loadClass('Zend_Form_Element_Select');
Zend_Loader::loadClass('Zend_Form_Element_Checkbox');
Zend_Loader::loadClass('FM_Forms_Element_File');


class FM_Forms_Root_AddCouponTemplate extends Zend_Form {

	public function __construct($options = null) {
		$this->addElementPrefixPath('FM', 'FM/');
		parent::__construct($options);
		$this->setAttrib('enctype', 'multipart/form-data');
		$this->addElement('text', 'name', array('label'=>'template name :', 'name'=>'name', 'required'=>1));
		$file = new FM_Forms_Element_File('file');
		$file->setLabel('File :')
		->setRequired(true)
		->addValidator('NotEmpty');
		$this->addElement($file);
		$this->addElement('submit', 'submit', array('value'=>'submit', 'class'=>'sub'));
	
		$this->addDisplayGroup(array('name','file'), 'templateinfo');
		$templateinfo = $this->getDisplayGroup('templateinfo');	
		$templateinfo->setDecorators(array(        
	                'FormElements',	
	                array('HtmlTag',array('tag'=>'h4','placement' => 'prepend')),
					'Fieldset'
	    ));
			
		$this->addDisplayGroup(array('submit'), 'submitgroup');
		$submitgroup = $this->getDisplayGroup('submitgroup');		
		$submitgroup->setDecorators(array(        
	                'FormElements',	
					'Fieldset'
	    ));
	}
}