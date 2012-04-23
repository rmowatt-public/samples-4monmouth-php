<?php
Zend_Loader::loadClass ( 'FM_Models_FM_OrgdataIcons' );
Zend_Loader::loadClass ( 'FM_Components_BaseComponent' );

class FM_Components_Util_Icon extends FM_Components_BaseComponent{

	protected $id;
	protected $orgId;
	protected $fileName = false;
	protected $height;
	protected $width;
	protected $type;
	protected $created;
	protected $active;

	public function __construct($keys = null) {
		if(is_array($keys)) {
			$logoModel = new FM_Models_FM_OrgdataIcons ();
			$logo = $logoModel->getIconByKeys($keys);
			if(count($logo)){
				foreach ($logo as $key=>$value) {
					if(property_exists($this, $key)) {
						$this->{$key} = $value;
					}
				}
				return true;
			}
			return false;
		}
		return true;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function getFileName() {
		return ($this->fileName) ? $this->fileName : false;
	}

	public static function getActive() {
		$model = new FM_Models_FM_OrgdataIcons();
		$s = $model->getIconsByKeys(array('active'=>'1'));
		$sArray = array();
		foreach($s as $key=>$values) {
			$sArray[] = new FM_Components_Util_Icon(array('id'=>$values['id']));
		}
		return $sArray;
	}

	
	public static function getOrgActive($orgId) {
		$model = new FM_Models_FM_OrgdataIcons();
		$s = $model->getIconsByKeys(array('active'=>'1', 'orgId'=>$orgId));
		$sArray = array();
		foreach($s as $key=>$values) {
			$sArray[] = new FM_Components_Util_Icon(array('id'=>$values['id']));
		}
		return $sArray;
	}
	
	
	public static function deleteIcon($args) {
		$logoModel = new FM_Models_FM_OrgdataIcons();
		return $logoModel->remove($args);
	}

	public static function updateIcon($args =array(), $new = array()) {
		$logoModel = new FM_Models_FM_OrgdataIcons();
		$logoModel->edit($args, array('active'=>0));
		return $logoModel->edit($args, $new);
	}

	public static function insert($args) {
		$Model = new FM_Models_FM_OrgdataIcons();
		if($id = $Model->insertRecord($args)) {
			return $id;
		}
		return false;
	}
	
	public static function hasRow($id) {
		$model = new FM_Models_FM_OrgdataIcons();
		$icon = $model->getIconsByKeys(array('orgId'=>$id));
		return (count($icon) < 1) ? false : true;
	}
}