<?php
Zend_Loader::loadClass('Zend_Form');

class FM_Forms_Review extends Zend_Form {

	public function __construct($options = null) {
		parent::__construct($options);
		$this->addElement('text', 'reviewname', array('label'=>'name :', 'name'=>'reviewname', 'style'=>"width:325px;"));
		$this->addElement('text', 'email', array('label'=>'email :', 'name'=>'email', 'style'=>"width:325px;"));
		$this->addElement('textarea', 'feedback', array('label'=>'review :', 'name'=>'feedback', 'rows'=>5));
		$this->addElement('hidden', 'orgId', array('name'=>'orgId'));
		$this->addElement('submit', 'submit', array('name'=>'submit', 'class'=>'sub', 'onclick'=>"FM.AdminWidget.TestimonialsTab.add();return false;"));
	}
}