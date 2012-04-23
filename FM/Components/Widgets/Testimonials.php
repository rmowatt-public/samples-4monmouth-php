<?php
Zend_Loader::loadClass('FM_Components_Widgets_BaseWidget');
Zend_Loader::loadClass('FM_Forms_Widgets_Testimonials');

class FM_Components_Widgets_Testimonials extends FM_Components_Widgets_BaseWidget {

	public function __construct($view, $orgId, $id) {
		$form = new FM_Forms_Widgets_Testimonials();
		$view->headScript()->appendFile(
		'/js/widgets/testimonials.js',
		'text/javascript'
		);
		$view->layout()->{$id} = $view->partial('widgets/testimonials/widget.phtml',
		array('form'=>$form));
	}
}