<?php
Zend_Loader::loadClass('FM_Components_Widgets_BaseWidget');
Zend_Loader::loadClass('FM_Forms_Widgets_AskUs');

class FM_Components_Widgets_AskUs extends FM_Components_Widgets_BaseWidget {

	public function __construct($view, $orgId, $id) {
		$form = new FM_Forms_Widgets_AskUs();
		$view->headScript()->appendFile(
		'/js/widgets/askus.js',
		'text/javascript'
		);
		$view->layout()->{$id} = $view->partial('widgets/askus/widget.phtml',
		array('form'=>$form));
	}
}