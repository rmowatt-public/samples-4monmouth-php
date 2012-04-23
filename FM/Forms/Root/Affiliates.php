<?php
Zend_Loader::loadClass('Zend_Form');
Zend_Loader::loadClass('Zend_Form_Element_Select');
Zend_Loader::loadClass('Zend_Form_Element_Checkbox');
Zend_Loader::loadClass('FM_Forms_Element_File');
Zend_Loader::loadClass('Zend_Form_Element_Text');


class FM_Forms_Root_Affiliates extends Zend_Form {

	public function __construct($options = array()) {
		parent::__construct($options);
		//print_r($options);exit;
		$options['src'] = array_key_exists('src', $options) ? $options['src'] : '';
		$options['src2'] = array_key_exists('src2', $options) ? $options['src2'] : '';
		$this->addElementPrefixPath('FM', 'FM/');
		//$this->addElement('text', 'title', array('label'=>'headline :', 'name'=>'title', 'required'=>1));
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
		'tool' =>'aff',
		'type'=>'medianame',
		'src'=>$options['src']
		))));
		$preview2 = new Zend_Form_Element_Text(array('label'=>'Click To View', 'name'=>'preview2'));
		$preview2->setDecorators(array(array('ViewScript', array(
		'viewScript' => 'form/preview2.phtml',
		'class'      => 'form element',
		'tool' =>'aff',
		'type'=>'header',
		'src'=>'/media/image/auxpage_headers/' . $options['src2']
		))));
		$this->addElement($preview);
		$this->addElement($preview2);
		$this->addElement('textarea', 'statement', array('label'=>'affiliates:', 'name'=>'statement', 'rows'=>6, 'cols'=>50, 'style'=>'width:400px;','required'=>1));
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
