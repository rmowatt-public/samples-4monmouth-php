<?php
Zend_Loader::loadClass('Zend_Form');
Zend_Loader::loadClass('Zend_Form_Element_Select');
Zend_Loader::loadClass('Zend_Form_Element_Checkbox');
Zend_Loader::loadClass('FM_Forms_Element_File');


class FM_Forms_Root_FeedbackPage extends Zend_Form {

	public function __construct($options = null) {
		//print_r($options);exit;
		parent::__construct($options);
		$options['src'] = array_key_exists('src', $options) ? $options['src'] : '';
		$this->addElementPrefixPath('FM', 'FM/');
		$this->setName('upload');
		//$this->addElement('text', 'title', array('label'=>'headline :', 'name'=>'title', 'required'=>0));
		$file = new FM_Forms_Element_File('file');
		$file->setLabel('Header Image');
		$this->addElement($file);
		$file = new FM_Forms_Element_File('head');
		$file->setLabel('Page Header');
		$this->addElement($file);
		$this->setAttrib('enctype', 'multipart/form-data');
		$preview = new Zend_Form_Element_Text(array('label'=>'Click To View', 'name'=>'preview'));
		$preview->setDecorators(array(array('ViewScript', array(
		'viewScript' => 'form/preview2.phtml',
		'class'      => 'form element',
		'tool' =>'fbk',
		'type'=>'header',
		'src'=>'/media/image/auxpage_headers/' . $options['src2']
		))));
		$preview2 = new Zend_Form_Element_Text(array('label'=>'Click To View', 'name'=>'preview2'));
		$preview2->setDecorators(array(array('ViewScript', array(
		'viewScript' => 'form/preview2.phtml',
		'class'      => 'form element',
		'tool' =>'fbk',
		'type'=>'medianame',
			'src'=> $options['src']
		))));
		$this->addElement($preview);
		$this->addElement($preview2);
		//$this->addElement('textarea', 'statement', array('label'=>'statement:', 'name'=>'statement', 'rows'=>6, 'cols'=>50, 'style'=>'width:400px;','required'=>1));
		$this->addElement('submit', 'submit', array('value'=>'submit', 'class'=>'sub'));
		//$this->addElement('hidden', 'id', array('value'=>''));
		
		$this->addDisplayGroup(array('head','preview', 'file', 'preview2'), 'titleinfo');
		$titleinfo = $this->getDisplayGroup('titleinfo');		
		$titleinfo->setDecorators(array(        
                    'FormElements',	
                    array('HtmlTag',array('tag'=>'h4','placement' => 'prepend')),
					'Fieldset'
        ));
		
		//$this->addDisplayGroup(array('id'), 'hidden');
		//$hidden = $this->getDisplayGroup('hidden');		
		//$hidden->setDecorators(array(        
       //             'FormElements',	
		//			'Fieldset'
       // ));
		
		$this->addDisplayGroup(array('submit','clear'), 'submitgroup');
		$submitgroup = $this->getDisplayGroup('submitgroup');		
		$submitgroup->setDecorators(array(        
                    'FormElements',	
					'Fieldset'
        ));
	}
}