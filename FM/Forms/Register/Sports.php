<?php
Zend_Loader::loadClass('FM_Forms_Register_Base');
Zend_Loader::loadClass('FM_Models_FM_SportOptions');

class FM_Forms_Register_Sports extends FM_Forms_Register_Base {

	public function __construct($options = array(), $users = array()) {
		parent::__construct($options, $users);
		$sportOptions = new FM_Models_FM_SportOptions();
		$this->setName('upload');
		$this->setAttrib('enctype', 'multipart/form-data');
		$category = new Zend_Form_Element_Select(array('label'=>'sport type :', 'name'=>'category', 'required'=>1));
		foreach($sportOptions->getAll() as $index=>$values) {
			$category->addMultiOption($values['id'], $values['name']);
		}
		$this->addElement($category);
		
		$this->addElement('checkbox', 'protected' , array('label'=>'Password Protect'));
		
		$icon = new FM_Forms_Element_File('icon');
		$icon->setLabel('Icon :');
		//->setRequired(true)
		//->addValidator('NotEmpty');
		$this->addElement($icon);
		
		
		$file = new FM_Forms_Element_File('file', array('value'=>'www'));
		$file->setLabel('Logo :');
		//->setRequired(true)
		//->addValidator('NotEmpty');
		$this->addElement($file);

		$banner = new FM_Forms_Element_File('banner');
		$banner->setLabel('Banner :');
		//->setRequired(true)
		//->addValidator('NotEmpty');
		$this->addElement($banner);
		
		$this->addElement('submit', 'clear', array('value'=>'clear', 'class'=>'clear_org'));
		$this->addElement('submit', 'submit', array('name'=>'submit', 'class'=>'sub'));
				
		$this->addDisplayGroup(array('protected', 'description','category','icon','file','banner'), 'detailinfo');
		$detailinfo = $this->getDisplayGroup('detailinfo');
		$detailinfo->setDecorators(array(        
                    'FormElements',
                    array('HtmlTag',array('tag'=>'h4','placement' => 'prepend')),
                    'Fieldset'
        ));
		
		$this->addElement('hidden', 'orgId', array('name'=>'orgId', 'value'=>'0'));
		$this->addElement('hidden', 'type', array('name'=>'type', 'value'=>'4'));
		
		$this->addDisplayGroup(array('type','orgId'), 'hidden');
		$hidden = $this->getDisplayGroup('hidden');
		$hidden->setDecorators(array(        
                    'FormElements',
                    'Fieldset'
        ));		
		
		$this->addDisplayGroup(array('submit','clear'), 'submitgroup');
		$submitgroup = $this->getDisplayGroup('submitgroup');		
		$submitgroup->setDecorators(array(        
                    'FormElements',	
					'Fieldset'
        ));
	}
}