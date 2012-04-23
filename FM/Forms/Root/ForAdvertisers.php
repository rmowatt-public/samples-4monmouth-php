<?php
Zend_Loader::loadClass('Zend_Form');
Zend_Loader::loadClass('Zend_Form_Element_Select');
Zend_Loader::loadClass('Zend_Form_Element_Checkbox');
Zend_Loader::loadClass('FM_Forms_Element_File');


class FM_Forms_Root_ForAdvertisers extends Zend_Form {

	public function __construct($options = null) {
		parent::__construct($options);
		$options['src'] = array_key_exists('src', $options) ? $options['src'] : '';
		$options['src2'] = array_key_exists('src2', $options) ? $options['src2'] : '';
		$this->addElementPrefixPath('FM', 'FM/');
		$this->setName('upload');
		$this->addElement('text', 'title', array('label'=>'headline :', 'name'=>'title', 'required'=>1));
		$file = new FM_Forms_Element_File('file');
		$file->setLabel('Header Image');
		$this->addElement($file);
		$file2 = new FM_Forms_Element_File('head');
		$file2->setLabel('Page Header');
		$this->addElement($file2);
		$this->setAttrib('enctype', 'multipart/form-data');
		$preview = new Zend_Form_Element_Text(array('label'=>'Click To View', 'name'=>'preview'));
		$preview->setDecorators(array(array('ViewScript', array(
		'viewScript' => 'form/preview2.phtml',
		'class'      => 'form element',
		'tool' =>'foradv',
		'type'=>'medianame',
		'src'=>$options['src']
		))));
		$this->addElement($preview);
		$preview2 = new Zend_Form_Element_Text(array('label'=>'Click To View', 'name'=>'preview2'));
		$preview2->setDecorators(array(array('ViewScript', array(
		'viewScript' => 'form/preview2.phtml',
		'class'      => 'form element',
		'tool' =>'foradv',
		'type'=>'header',
		'src'=>'/media/image/auxpage_headers/' . $options['src2']
		))));
		$this->addElement($preview2);
		$this->addElement('textarea', 'statement', array('label'=>'mission statement:', 'name'=>'statement', 'rows'=>6, 'cols'=>50, 'style'=>'width:400px;','required'=>1));
		$this->addElement('submit', 'submit', array('value'=>'submit', 'class'=>'sub'));
		$this->addElement('hidden', 'id', array('value'=>''));
		
		$this->addDisplayGroup(array('title','head','preview2','file', 'preview', 'statement'), 'titleinfo');
		$titleinfo = $this->getDisplayGroup('titleinfo');		
		$titleinfo->setDecorators(array(        
                    'FormElements',	
                    array('HtmlTag',array('tag'=>'h4','placement' => 'prepend')),
					'Fieldset'
        ));
		
		$this->addDisplayGroup(array('id'), 'hidden');
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