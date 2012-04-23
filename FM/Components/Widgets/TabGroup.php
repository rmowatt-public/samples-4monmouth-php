<?php
Zend_Loader::loadClass('FM_Components_Widgets_BaseWidget');
Zend_Loader::loadClass('FM_Forms_Widgets_Testimonials');

class FM_Components_Widgets_TabGroup extends FM_Components_Widgets_BaseWidget {

	protected $_tabs = array();
	
	public function __construct() {
		parent::__construct();		
	}
	
	public function addTab($tab, $label, $selected = false) {
		$a = array(
			'tab'=>$tab,
			'label'=>$label,
			'selected'=>$selected
		);
		$this->_tabs[] = $a;
	}
	
	public function toHTML() {
		$html = array();
		foreach ($this->_tabs as $t) {
			if($t['selected']) {
				$html[] = $t['tab']->toHTML();
			}
		}
		//$this->_view->headScript()->appendFile(
		//'/js/widgets/admin.js',
		//'text/javascript'
		//);
		return $this->_view->partial('tabs/group.phtml',
											array('tabs'=>$html));
	}
}