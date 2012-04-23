<?php
Zend_Loader::loadClass('FM_Components_Tabs_BaseTab');

class FM_Components_Tabs_TabGroup extends FM_Components_Tabs_BaseTab {

	protected $_tabs = array();
	
	public function __construct() {
		parent::__construct();
		$this->_view->headScript()->appendFile(
		'/js/widgets/events.js',
		'text/javascript'
		);
	}
	
	public function addTab($tab, $label, $id, $selected = false) {
		$a = array(
			'tab'=>$tab,
			'label'=>$label,
			'id'=>$id,
			'selected'=>$selected
		);
		//print_r($a);
		$this->_tabs[] = $a;
	}
	
	public function toHTML() {
		return $this->_view->partial('tabs/group.phtml',
											array('tabs'=>$this->_tabs));
	}
}