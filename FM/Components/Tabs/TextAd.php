<?php
Zend_Loader::loadClass('FM_Components_Tabs_BaseTab');
Zend_Loader::loadClass('FM_Models_FM_Menu');

class FM_Components_Tabs_Menu extends FM_Components_Tabs_BaseTab {

	protected $_profile;
	protected $_db;

	public function __construct($orgId) {
		parent::__construct();
		$this->_db = new FM_Models_FM_Menu();
		$this->_orgId = $orgId;
	}

	public function setProfile($profile) {
		$this->_profile = $profile;
	}

	public function getProfile() {
		$m = $this->_db->getMenuByKeys(array('orgId'=>$this->_orgId));
		//print_r($m);exit;
		return (count($m) && array_key_exists('menu', $m)) ? $m['menu'] : '';
	}

	public function toHTML($id) {
		//print_r($this->_view);exit;
		return $this->_view->partial('tabs/menu.phtml',
		array('menu'=>$this->getProfile(), 'id'=>$id));
	}

	public static function editMenu($orgId, $content) {
		$model = new FM_Models_FM_Menu();
		if($model->getMenuByKeys(array('orgId'=>$orgId))) {
			if($model->update(array('orgId'=>$orgId), array('menu'=>$content))) {
				return true;
			}
		} else{
			if($model->insert(array('orgId'=>$orgId, 'menu'=>$content))) {
				return true;
			}
			return false;
		}
	}
}