<?php
Zend_Loader::loadClass('Zend_Form');

class FM_Forms_Widgets_Testimonials extends Zend_Form {

	public function __construct($options = null) {
		$options['onsubmit'] = 'FM.AskUs.submitRequest();return false;';
		parent::__construct($options);
		$this->addElement('textarea', 'testimonial', array('label'=>'testimonial :', 'name'=>'testimonial'));
		$this->addElement('submit', 'submit', array('name'=>'submit'));
	}
}