<?php
Zend_Loader::loadClass('Zend_Form');
Zend_Loader::loadClass('Zend_Form_Element_Select');
Zend_Loader::loadClass('Zend_Form_Element_Checkbox');

class FM_Forms_Forum extends Zend_Form {

	public function __construct($options = null, $uname = false, $email = false) {
		$options['id'] = 'forumForm';
		$options['onsubmit'] = 'FM.Forum.submitComment(this);return false;';
		parent::__construct($options);
		if(!$uname){
			$this->addElement('text', 'name', array('label'=>'name:', 'name'=>'fname'));
		}
		if(!$email) {
			$this->addElement('text', 'email', array('label'=>'email:', 'name'=>'femail'));
		}
		$this->addElement('textarea', 'forumMessage', array('label'=>'Comment:', 'name'=>'forumMessage', 'rows'=>3, 'style'=>'width:300px;'));
		$this->addElement('submit', 'submit', array('value'=>'submit', 'class'=>'sub'));
		$this->addElement('hidden', 'orgId', array('value'=>$options['orgId']));
		if($uname) {
			$this->addElement('hidden', 'name', array('name'=>'name', 'value'=>$uname));
		} 
		if($email){
			$this->addElement('hidden', 'email', array('name'=>'femail', 'value'=>$email));
		}
		//	$this->addElement('hidden', 'id', array('value'=>''));
	}

}