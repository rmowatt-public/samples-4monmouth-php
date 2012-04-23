<?php
Zend_Loader::loadClass('FM_Forms_Register_Base');
Zend_Loader::loadClass('FM_Models_FM_SearchPrimaryCategoriesOrgs');

class FM_Forms_Register_NonProfit extends FM_Forms_Register_Base {

	public function __construct($options = array(), $users = array()) {
		//$options['onsubmit'] = 'FM.AskUs.submitRequest();return false;';
		parent::__construct($options, $users);
		$this->setName('upload');
		$this->setAttrib('enctype', 'multipart/form-data');
		
		$pc = new FM_Models_FM_SearchPrimaryCategoriesOrgs ();
		$category = new Zend_Form_Element_Multiselect(array('label'=>'category :', 'name'=>'category', 'required'=>1));
		foreach($pc->getPrimaryCategories() as $index=>$values) {
			$category->addMultiOption($values['id'], html_entity_decode($values['name']));
		}
		$this->addElement('textarea', 'keywords', array('label'=>'keywords :', 'name'=>'keywords', 'rows'=>3));
		$this->addElement($category) ;
		$icon = new FM_Forms_Element_File('icon');
		$icon->setLabel('Icon :');
		//->setRequired(true)
		//->addValidator('NotEmpty');
		$this->addElement($icon);
		
		
		$file = new FM_Forms_Element_File('file');
		$file->setLabel('Logo :');
		//->setRequired(true)
		//->addValidator('NotEmpty');
	
		$banner = new FM_Forms_Element_File('banner');
		$banner->setLabel('Banner :');
		//->setRequired(true)
		//->addValidator('NotEmpty');

		$this->addElement($file);
		$this->addElement($banner);
		
		$this->addElement('submit', 'clear', array('value'=>'clear', 'class'=>'clear_org'));
		$this->addElement('submit', 'submit', array('name'=>'submit', 'class'=>'sub'));
		
		$this->addDisplayGroup(array('description','category','specialty','keywords','icon','file','banner'), 'detailinfo');
		$detailinfo = $this->getDisplayGroup('detailinfo');
		$detailinfo->setDecorators(array(        
                    'FormElements',					
                    array('HtmlTag',array('tag'=>'h4','placement' => 'prepend')),
                    'Fieldset'
        ));
		
		$this->addElement('hidden', 'orgId', array('name'=>'orgId', 'value'=>'0'));
		$this->addElement('hidden', 'type', array('name'=>'type', 'value'=>'3'));
		
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