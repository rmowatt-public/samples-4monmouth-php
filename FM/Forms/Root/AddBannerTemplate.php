<?php
Zend_Loader::loadClass('Zend_Form');
Zend_Loader::loadClass('Zend_Form_Element_Select');
Zend_Loader::loadClass('Zend_Form_Element_Checkbox');
Zend_Loader::loadClass('FM_Forms_Element_File');


class FM_Forms_Root_AddBannerTemplate extends Zend_Form {

	public function __construct($options = null) {
		$this->addElementPrefixPath('FM', 'FM/');
		parent::__construct($options);
		$this->setAttrib('enctype', 'multipart/form-data');
		$this->addElement('text', 'name', array('label'=>'template name :', 'name'=>'name', 'required'=>1));
		$this->addElement('text', 'class', array('label'=>'style name: ',  'required'=>1));
		$file = new FM_Forms_Element_File('file');
		$file->setLabel('Template :')
		->setRequired(true)
		->addValidator('NotEmpty');
		$this->addElement($file);
		$this->addElement('checkbox', 'headline', array('label'=>'use headline', 'class'=>'ckbx'));
		$this->addElement('checkbox', 'logo', array('label'=>'use image :', 'class'=>'ckbx'));
		$this->addElement('text', 'height', array('label'=>'maxheight (px) :','class'=>'heightwidth', 'required'=>1));
		$this->addElement('text', 'width', array('label'=>'maxwidth (px) :', 'class'=>'heightwidth', 'required'=>1));
		$this->addElement('submit', 'submit', array('value'=>'submit', 'class'=>'sub'));
		
		
		$this->addDisplayGroup(array('name','file','class'), 'banner');
		$banner = $this->getDisplayGroup('banner');		
		$banner->setDecorators(array(        
                    'FormElements',	
                    array('HtmlTag',array('tag'=>'h4','placement' => 'prepend')),
					'Fieldset'
        ));
		$this->addDisplayGroup(array('headline','logo'), 'options');
		$options = $this->getDisplayGroup('options');		
		$options->setDecorators(array(        
                    'FormElements',	
                    array('HtmlTag',array('tag'=>'h4','placement' => 'prepend')),
					'Fieldset'
        ));
		$this->addDisplayGroup(array('width', 'height'), 'dimensions');
		$dimensions = $this->getDisplayGroup('dimensions');		
		$dimensions->setDecorators(array(        
                    'FormElements',	
                    array('HtmlTag',array('tag'=>'h4','placement' => 'prepend')),
					'Fieldset'
        ));
		
		$this->addDisplayGroup(array('submit', 'clear'), 'submitgroup');
		$submitgroup = $this->getDisplayGroup('submitgroup');		
		$submitgroup->setDecorators(array(        
                    'FormElements',	
					'Fieldset'
        ));
	}
}