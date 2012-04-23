<?php
Zend_Loader::loadClass('Zend_Form');
Zend_Loader::loadClass('Zend_Form_Element_Select');
Zend_Loader::loadClass('Zend_Form_Element_Checkbox');
Zend_Loader::loadClass('FM_Forms_Element_File');
Zend_Loader::loadClass('Zend_Form_Element_Text');


class FM_Forms_Root_Users extends Zend_Form {

	public function __construct($options = null, $users) {
		parent::__construct($options);
		//print_r($users);exit;
		$category = new Zend_Form_Element_Select(array('label'=>'search', 'name'=>'categoryjumper', 'required'=>1,  'onChange'=>"MM_jumpMenu('parent',this,1)"));
		$category->addMultiOption('/root/manageusers/0', 'select a user *');
		//$category->addMultiOption('/root/addbusiness/117', 'All');
		foreach($users as $index=>$values) {
			//$values = $value->toArray();
			//print_r($values);exit;
			$category->addMultiOption('/root/manageusers/0/'.$values['id'], strtolower($values['uname']) . ' (' . $values['firstname'] . ' ' . $values['lastname'] . ')');
		}
		$this->addElement($category) ;
		$this->addElement('text', 'search', array('label'=>'search by name :', 'name'=>'search', 'required'=>1));
		$this->addElement('text', 'orgsearch', array('label'=>'search by Org :', 'name'=>'orgsearch', 'required'=>1));
		$this->addElement('submit', 'submit', array('value'=>'submit', 'class'=>'sub'));
		
	}
}