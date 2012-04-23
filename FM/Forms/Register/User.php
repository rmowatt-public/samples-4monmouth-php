<?php
Zend_Loader::loadClass('Zend_Form');
Zend_Loader::loadClass('Zend_Form_Element_Select');
Zend_Loader::loadClass('FM_Components_Util_State');
Zend_Loader::loadClass('FM_Components_Util_Town');
//Zend_Loader::loadClass('FM_Forms_Element_Textarea');
//Zend_Loader::loadClass('FM_Forms_Element_Hidden');

class FM_Forms_Register_User extends Zend_Form {

	public function __construct($options = null, $users = array()) {
		parent::__construct();
		$states = FM_Components_Util_State::getAll();
		$regions = FM_Components_Util_Town::getAll();

		$this->addElement('text', 'uname', array('label'=>'user name :', 'name'=>'uname', 'required'=>1, 'onblur'=>'FM.UserRoot.checkuname(this.value)'));
		$this->addElement('password', 'pwd', array('label'=>'password :', 'name'=>'pwd', 'required'=>1));
		$this->addElement('text', 'firstname', array('label'=>'first name :', 'name'=>'firstname', 'required'=>1));
		$this->addElement('text', 'middlename', array('label'=>'Middle Name :', 'name'=>'middlename'));
		$this->addElement('text', 'lastname', array('label'=>'Last Name :', 'name'=>'lastname', 'required'=>1));
		$this->addElement('text', 'address1', array('label'=>'address 1 :', 'name'=>'address1', 'required'=>0));
		$this->addElement('text', 'address2', array('label'=>'address 2 :', 'name'=>'address2'));
		$this->addElement('text', 'city', array('label'=>'city :', 'name'=>'city', 'required'=>0));
		$state = new Zend_Form_Element_Select(array('label'=>'state :', 'name'=>'state', 'required'=>0));
		foreach($states as $key=>$stateObj) {
			$state->addMultiOption($stateObj->getAbbr(), $stateObj->getState());
		}
		$state->setValue('NJ');
		$this->addElement($state);
		$this->addElement('text', 'zip', array('label'=>'zip :', 'name'=>'zip', 'required'=>0));
		$this->addElement('text', 'phone', array('label'=>'phone :', 'name'=>'phone', 'required'=>1));
		$this->addElement('text', 'email', array('label'=>'email :', 'name'=>'email', 'required'=>1));
		$maillist = new Zend_Form_Element_Select(array('label'=>'mailing list? :', 'name'=>'maillist'));
		$maillist->addMultiOption('1', ' Yes! ');
		$maillist->addMultiOption('0', ' No ');
		$this->addElement($maillist);
		
		$this->addDisplayGroup(array('uname','pwd','email'), 'logininfo');
		$logininfo = $this->getDisplayGroup('logininfo');		
		$logininfo->setDecorators(array(        
                    'FormElements',	
                    array('HtmlTag',array('tag'=>'h4','placement' => 'prepend')),
					'Fieldset'
        ));
		$this->addDisplayGroup(array('firstname','middlename','lastname'), 'nameinfo');
		$nameinfo = $this->getDisplayGroup('nameinfo');		
		$nameinfo->setDecorators(array(        
                    'FormElements',	
                    array('HtmlTag',array('tag'=>'h4','placement' => 'prepend')),
					'Fieldset'
        ));
		$this->addDisplayGroup(array('address1','address2','city','state','zip','maillist','phone'), 'locationinfo');
		$locationinfo = $this->getDisplayGroup('locationinfo');		
		$locationinfo->setDecorators(array(        
                    'FormElements',	
                    array('HtmlTag',array('tag'=>'h4','placement' => 'prepend')),
					'Fieldset'
        ));
		
		$this->addElement('submit', 'submit', array('name'=>'submit', 'class'=>'sub'));
	}
}