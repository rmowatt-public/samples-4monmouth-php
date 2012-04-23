<?php
Zend_Loader::loadClass('FM_Components_BaseComponent');
Zend_Loader::loadClass('FM_Models_FM_Orgdata');
Zend_Loader::loadClass('FM_Models_FM_UserOrg');
Zend_Loader::loadClass('FM_Models_FM_OrgTabs');
Zend_Loader::loadClass('FM_Models_FM_OrgWidgets');
Zend_Loader::loadClass('FM_Models_FM_OrgOptions');
Zend_Loader::loadClass('FM_Models_FM_NpOrgCat');
Zend_Loader::loadClass('FM_Models_FM_BzOrgCat');

class FM_Components_OrgConfig extends FM_Components_BaseComponent{


	protected $tabs = array();
	protected $widgets = array();
	protected $options = array();
	protected $orgId;

	private $_tabsTable;
	private $_widgetsTable;
	private $_optionsTable;
	private $_categories;


	public function __construct($keys = null, $orgId) {
		$this->orgId = $orgId;
		if(is_array($keys)) {
			$this->_tabsTable = new FM_Models_FM_OrgTabs();
			$this->_widgetsTable = new FM_Models_FM_OrgWidgets();
			$this->_optionsTable = new FM_Models_FM_OrgOptions();

			$tabs = $this->_tabsTable->getTabByKeys($keys);
			if(!is_array($tabs) || !count($tabs)) {
				if($this->_tabsTable->insertRecord(array('orgId'=>$orgId))) {
					$tabs = $this->_tabsTable->getTabByKeys($keys);
				}
			}
			$this->tabs = $tabs;
			//$this->widgets = $this->_widgetsTable->getWidgetByKeys($keys);
			$options = $this->_optionsTable->getOptionByKeys($keys);
			if(!is_array($options ) || !count($options )) {
				if($this->_optionsTable->insertRecord(array('orgId'=>$orgId))) {
					$options = $this->_optionsTable->getOptionsByKeys($keys);
				}
			}
			$this->options = $options;
			
			if(array_key_exists('type', $keys)) {
				$this->_categories = $this->setCats($keys['type']);
			}
		}
		return true;
	}
	
	private function setCats($type) {
		$model = null;
		switch($type) {
			case 2 :
				$model = new FM_Models_FM_BzorgCat();
				break;
			case 3 :
				$model = new FM_Models_FM_NporgCat();
				break;
		}
		
		$keys = null;
		$rv = null;
		if ($model){$keys = $model->getIdsByKeys(array('orgId'=>$this->orgId));}
		if($keys) {
			foreach ($keys as $key=>$values) {
				$rv[] = $values['catId'];			}
		}
		return ($rv) ? $rv : array();
	}
	
	public function getCats() {
		return $this->_categories;
	}

	public function getOrgId() {
		return $this->orgId;
	}

	public function getCommon() {
		return array(
		'tabs'=>$this->getTabs(),
		'widgets'=>$this->getWidgets(),
		'options'=>$this->getOptions());
	}

	public function getTabs() {
		return (count($this->tabs)) ? $this->tabs : false;
	}

	public function getWidgets() {
		return (count($this->tabs)) ? $this->widgets : false;
	}

	public function getOptions() {
		return (count($this->options)) ? $this->options : false;
	}

	public static function updateConfig($type, $orgId, $values) {
		switch($type) {
			case 'tabs' :
				$table = new FM_Models_FM_OrgTabs();
				if(isset($values['orgId'])){unset($values['orgId']);}
				if($table->edit(array('orgId'=>$orgId), $values)) {
					return true;
				}
				return false;
			case 'widgets' :
				$table = new FM_Models_FM_OrgWidgets();
				if(isset($values['orgId'])){unset($values['orgId']);}
				if($table->edit(array('orgId'=>$orgId), $values)) {
					return true;
				}
				return false;
			case 'options' :
				$table = new FM_Models_FM_OrgOptions();
				if(isset($values['orgId'])){unset($values['orgId']);}
				if($table->edit(array('orgId'=>$orgId), $values)) {
					return true;
				}
				return false;
			default :
				return false;
		}
	}

	public static function populate() {
		$table[] = new FM_Models_FM_OrgTabs();
		$table[] = new FM_Models_FM_OrgWidgets();
		$table[] = new FM_Models_FM_OrgOptions();

		$orgsTable = new FM_Models_FM_Orgdata();
		$allOrgs = $orgsTable->getAll();
		foreach ($allOrgs as $all=>$org) {
			foreach ($table as $model) {
				$model->insert(array('orgId'=>$org['id']));
			}
		}

	}
	
}


