<?php
Zend_Loader::loadClass('FM_Components_Widgets_BaseWidget');
Zend_Loader::loadClass('FM_Components_Organization');

class FM_Components_Widgets_Profile extends FM_Components_Widgets_BaseWidget {

	public function __construct($view, $orgId, $id) {
		$org = new FM_Components_Organization(array('id'=>$orgId));
		$view->headScript()->appendFile(
		'/js/widgets/profile.js',
		'text/javascript'
		);
		$view->layout()->{$id} = $view->partial('widgets/profile/widget.phtml',
		array('org'=>$org));
	}
}