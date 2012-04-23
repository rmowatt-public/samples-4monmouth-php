<?php
Zend_Loader::loadClass('Zend_Form');
Zend_Loader::loadClass('Zend_Form_Element_Select');

Zend_Loader::loadClass('Zend_Form_Element_Checkbox');
//Zend_Loader::loadClass('FM_Forms_Element_Hidden');

class FM_Forms_Faq extends Zend_Form {

	public function __construct($options = array()) {
		if(!array_key_exists('id', $options)){
			$options['id'] = 'faqForm';
		}
		parent::__construct($options);
		$this->addElement('textarea', 'question', array('label'=>'question:', 'name'=>'question', 'rows'=>3, 'required'=>1));
		$this->addElement('textarea', 'answer', array('label'=>'answer:', 'name'=>'answer', 'rows'=>3, 'required'=>1));
		//$order = new Zend_Form_Element_Select(array('label'=>'order :', 'name'=>'order', 'required'=>0));
		//foreach(range(0,100) as $i) {
		//	$order->addMultiOption($i, $i);
		//}
		//$this->addElement($order);
		$active = new Zend_Form_Element_Checkbox('active', array('label'=>'active:', 'class'=>'chkbx'));
		$this->addElement($active);
		$this->addElement('hidden', 'id', array('value'=>''));		
		$this->addElement('hidden', 'new', array('value'=>1));	
		$this->addElement('submit', 'clear', array('value'=>'clear','class'=>'clear_faq'));
		$this->addElement('submit', 'submit', array('value'=>'submit', 'class'=>'sub'));
		
		$this->addDisplayGroup(array('question','answer'), 'questioninfo');
		$questioninfo = $this->getDisplayGroup('questioninfo');		
		$questioninfo->setDecorators(array(        
                    'FormElements',	
                    array('HtmlTag',array('tag'=>'h4','placement' => 'prepend')),
					'Fieldset'
        ));
		$this->addDisplayGroup(array('order','active','new'), 'questiondetails');
		$questiondetails = $this->getDisplayGroup('questiondetails');		
		$questiondetails->setDecorators(array(        
                    'FormElements',	
                    array('HtmlTag',array('tag'=>'h4','placement' => 'prepend')),
					'Fieldset'
        ));
		$this->addDisplayGroup(array('id'), 'hidden');
		$hidden = $this->getDisplayGroup('hidden');		
		$hidden->setDecorators(array(        
                    'FormElements',	
                    array('HtmlTag',array('tag'=>'h4','placement' => 'prepend')),
					'Fieldset'
        ));
		
		$this->addDisplayGroup(array('submit','clear'), 'submitgroup');
		$submitgroup = $this->getDisplayGroup('submitgroup');		
		$submitgroup->setDecorators(array(        
                    'FormElements',	
					'Fieldset'
        ));

	}
	//$options['onsubmit'] = 'FM.AskUs.submitRequest();return false;';
}