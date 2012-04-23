<?php
Zend_Loader::loadClass ( 'FM_Models_FM_LimeCard' );
Zend_Loader::loadClass ( 'FM_Components_BaseComponent' );

class FM_Components_Util_LimeCard extends FM_Components_BaseComponent{

	protected $id;
	protected $name;
	protected $address = false;
	protected $city;
	protected $state;
	protected $phone;
	protected $website;
	protected $zip;
	protected $keywords;

	public function __construct($keys = null) {
		if(is_array($keys)) {
			$limeCardModel = new FM_Models_FM_LimeCard();
			$limeCard = $limeCardModel->getRecordByKeys($keys);
			if(count($limeCard)){
				foreach ($limeCard as $key=>$value) {
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

	public static function getRecord($keys) {
		$limeCardModel = new FM_Models_FM_LimeCard();
		return $limeCardModel->getRecordByKeys($keys);
	}

	public function getId() {
		return $this->id;
	}

	public function getFileName() {
		return ($this->fileName) ? $this->fileName : false;;
	}

	/**
	public static function getAll() {
		$model = new FM_Models_FM_LimeCard();
		return $model->getAll();
	}
	**/

	public static function delete($args) {
		$limeCardModel = new FM_Models_FM_LimeCard();
		return $limeCardModel->remove($args);
	}

	public static function update($args =array(), $new = array()) {
		$limeCardModel = new FM_Models_FM_LimeCard();
		return $limeCardModel->edit($args, $new);
	}

	public static function insert($args) {
		$Model = new FM_Models_FM_LimeCard();
		if($id = $Model->insertRecord($args)) {
			return $id;
		}
		return false;
	}

	public static function alphaSearch($searchTerm) {
		$model = new FM_Models_FM_LimeCard();
		$nonOrg = $model->alphaSearch($searchTerm);
		$orgDataModel = new FM_Models_FM_Orgdata();
		$orgs = $orgDataModel->alphabeticalSearch($searchTerm);
		$orgArray = array();
		foreach (array_merge($orgs, $nonOrg) as $index=>$org) {
			if(array_key_exists($org['name'], $orgArray)) {
				$orgArray[$org['name']]['categories'][] = $org['catName'];
			} else {
				$orgArray[$org['name']]['record'] = $org;
				$orgArray[$org['name']]['categories'][] = $org['catName'];
			}
		}
		return $orgArray;
	}
	
	public static function getAll() {
		$model = new FM_Models_FM_LimeCard();
		$nonOrg = $model->getAll();
		$orgDataModel = new FM_Models_FM_Orgdata();
		$orgs = $orgDataModel->getOrgsByKeys(array('limeCard'=>1));
		$orgArray = array();
		foreach (array_merge($orgs, $nonOrg) as $index=>$org) {
			if(array_key_exists($org['name'], $orgArray)) {
				$orgArray[$org['name']]['categories'][] = $org['catName'];
			} else {
				$orgArray[$org['name']]['record'] = $org;
				$orgArray[$org['name']]['categories'][] = $org['catName'];
			}
		}
		return $orgArray;
	}
	
	public static function getNonMember() {
		$model = new FM_Models_FM_LimeCard();
		$nonOrg = $model->getAll();
		//$orgDataModel = new FM_Models_FM_Orgdata();
		//$orgs = $orgDataModel->getOrgsByKeys(array('limeCard'=>1));
		$orgArray = array();
		foreach ($nonOrg as $index=>$org) {
			if(array_key_exists($org['name'], $orgArray)) {
				$orgArray[$org['name']]['categories'][] = $org['catName'];
			} else {
				$orgArray[$org['name']]['record'] = $org;
				$orgArray[$org['name']]['categories'][] = $org['catName'];
			}
		}
		return $orgArray;
	}

	public static function catSearch($searchTerm) {
		$model = new FM_Models_FM_LimeCard();
		$nonOrg = $model->catSearch($searchTerm);
		$orgDataModel = new FM_Models_FM_Orgdata();
		$orgs = $orgDataModel->catSearch($searchTerm);
		$orgArray = array();
		foreach (array_merge($orgs, $nonOrg) as $index=>$org) {
			if(array_key_exists($org['name'], $orgArray)) {
				$orgArray[$org['name']]['categories'][] = $org['catName'];
			} else {
				$orgArray[$org['name']]['record'] = $org;
				$orgArray[$org['name']]['categories'][] = $org['catName'];
			}
		}
		return $orgArray;
	}


	public static function regionSearch($searchTerm) {
		if ($searchTerm == 25) {
			return FM_Components_Util_LimeCard::statenIslandSearch();
		}
		if ($searchTerm == 26) {
			return FM_Components_Util_LimeCard::oceanCountySearch();
		}
		$regions = array();
		$towns = FM_Components_Util_Region::getTownIdsByRegion($searchTerm);
		foreach ($towns as $index=>$town) {
			$regions[] = $town['id'];
		}
		$model = new FM_Models_FM_LimeCard();
		$nonOrg = $model->regionSearch($searchTerm);
		$orgDataModel = new FM_Models_FM_Orgdata();
		$orgs = $orgDataModel->regionSearch($regions);
		$orgArray = array();
		foreach (array_merge($orgs, $nonOrg) as $index=>$org) {
			if(array_key_exists($org['name'], $orgArray)) {
				$orgArray[$org['name']]['categories'][] = $org['catName'];
			} else {
				$orgArray[$org['name']]['record'] = $org;
				$orgArray[$org['name']]['categories'][] = $org['catName'];
			}
		}
		return $orgArray;
	}
	
	private static function statenIslandSearch() {
		$model = new FM_Models_FM_LimeCard();
		$nonOrg = $model->regionSearch(25);
		$orgArray = array();
		foreach ($nonOrg as $index=>$org) {
			if(array_key_exists($org['name'], $orgArray)) {
				$orgArray[$org['name']]['categories'][] = $org['catName'];
			} else {
				$orgArray[$org['name']]['record'] = $org;
				$orgArray[$org['name']]['categories'][] = $org['catName'];
			}
		}
		return $orgArray;
	}
	
		private static function oceanCountySearch() {
		$model = new FM_Models_FM_LimeCard();
		$nonOrg = $model->regionSearch(26);
		$orgArray = array();
		foreach ($nonOrg as $index=>$org) {
			if(array_key_exists($org['name'], $orgArray)) {
				$orgArray[$org['name']]['categories'][] = $org['catName'];
			} else {
				$orgArray[$org['name']]['record'] = $org;
				$orgArray[$org['name']]['categories'][] = $org['catName'];
			}
		}
		return $orgArray;
	}


	public static function search($searchTerm) {
		$model = new FM_Models_FM_LimeCard();
		return  $model->search($searchTerm);
	}

	public static function sort($array, $key) {
		$sortedArray = array();
		foreach ($array as $index=>$values) {
			$newKey = strtolower($values['record'][$key]);
			$sortedArray[$newKey] = $newKey;
		}
		sort($sortedArray, SORT_STRING);
		$returnArray = array();
		foreach ($sortedArray as $index=>$value) {
			foreach ($array as $key2=>$values2) {
				if(strtolower($values2['record']['name']) == $value) {
					//if($values2['record']['website'] != '') {
					$values2['record']['website'] = FM_Components_BaseComponent::cleanURL($values2['record']['website']);
					//}
					$returnArray[] = $values2;
					continue;
				}
			}
		}
		return $returnArray;
	}
}