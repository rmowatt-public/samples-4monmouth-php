<?php
Zend_Loader::loadClass('FM_Models_BaseModel');
Zend_Loader::loadClass('FM_Components_Util_Region');
class FM_Models_BaseSearchModel extends FM_Models_BaseModel {

	protected $_tableName = 'orgdata';

	public function __construct() {
		parent::__construct('localhost', 'root', '', 'fm');
	}

	public function searchCategory($catId) {

		//$query = ""
	}

	public function buildFromSearchObj($searchComponent) {
		if(count($regions = $searchComponent->getRegions())) {
			foreach ($regions as $key=>$regionId) {
				$towns = FM_Components_Util_Region::getTownIdsByRegion($regionId);
				foreach ($towns as $index=>$town) {
					$searchComponent->addTown($town);
				}
			}
		}
		switch($searchComponent->getType()) {
			case 2:
				$table = ' FM.orgdata_business ';
				break;
		}

		$query = "SELECT o.* FROM FM.orgdata o JOIN {$table} t ON t.orgId = o.id ";
		if(count($towns = $searchComponent->getTowns())) {
			$query .= " JOIN FM.org_town ot ON ot.orgId = o.id ";
		}
		if(count($categories = $searchComponent->getCategories())) {
			$query .= " JOIN FM.bzorg_cat bc ON bc.orgId = o.id ";
		}
		$query .= "WHERE ";
		$i = 0;
		if(count($towns)) {
			$i++;
			$query .= " ot.townId IN (". implode(' , ' , $towns ) . ") AND ";
		}
		if(count($categories)) {
			$i++;
			$query .= " bc.catId IN (". implode(' , ' , $categories) . ") AND ";
		}
		if(count($keywords = $searchComponent->getkeywords())) {
			$i++;
			$query .= '(';
			foreach($keywords as $index=>$value) {
				$query .= " o.description LIKE ('%". $value . "%') OR o.keywords LIKE ('%". $value . "%') OR o.name LIKE ('%". $value . "%') OR ";
			}
			$query = substr($query, 0 , -4) . ') AND ';
		}
		if($zip = $searchComponent->getZipcode()) {
			$i++;
			$query .= " o.zip = {$zip} AND ";
		}
		if($type = $searchComponent->getType()) {
			$i++;
			$query .= " o.type = {$type} AND ";
		}
		if($i>0) {$query = substr($query, 0 , -4);} else {
			$query = substr($query, 0 , -6);
		}
		$query .= "GROUP BY o.id ORDER BY o.active DESC, o.name ASC ";
		//print $query;exit;
		return $this->getMultipleRows($query);
	}

	/**public function buildFromNpSearchObj($searchComponent) {
	if(count($regions = $searchComponent->getRegions())) {
	foreach ($regions as $key=>$regionId) {
	$towns = FM_Components_Util_Region::getTownIdsByRegion($regionId);
	foreach ($towns as $index=>$town) {
	$searchComponent->addTown($town);
	}
	}
	}
	switch($searchComponent->getType()) {
	case 3:
	$table = ' FM.orgdata ';
	break;
	}

	$query = "SELECT o.* FROM FM.orgdata o ";
	if(count($towns = $searchComponent->getTowns())) {
	$query .= " JOIN FM.org_town ot ON ot.orgId = o.id ";
	}
	if(count($categories = $searchComponent->getCategories())) {
	$query .= " JOIN FM.nporg_cat bc ON bc.orgId = o.id ";
	}

	$query .= "WHERE ";
	$i = 0;
	if(count($towns)) {
	$i++;
	$query .= " ot.townId IN (". implode(' , ' , $towns ) . ") AND ";
	}
	if(count($categories)) {
	$i++;
	$query .= " bc.catId IN (". implode(' , ' , $categories) . ") AND ";
	}
	if(count($keywords = $searchComponent->getkeywords())) {
	$i++;
	$query .= '(';
	foreach($keywords as $index=>$value) {
	$query .= " o.description LIKE ('%". $value . "%') OR  o.keywords LIKE ('%". $value . "%') OR o.name LIKE ('%". $value . "%') OR ";
	}
	$query = substr($query, 0 , -4) . ') AND ';
	}
	if($zip = $searchComponent->getZipcode()) {
	$i++;
	$query .= " o.zip = {$zip} AND ";
	}
	if($type = $searchComponent->getType()) {
	$i++;
	$query .= " o.type = {$type} AND ";
	}
	if($i>0) {$query = substr($query, 0 , -4);} else {
	$query = substr($query, 0 , -6);
	}
	$query .= "GROUP BY o.id ORDER BY o.active DESC, o.name ASC ";
	//print $query;exit;
	return $this->getMultipleRows($query);
	}**/

	public function buildFromNpSearchObj($searchComponent) {
		if(count($regions = $searchComponent->getRegions())) {
			foreach ($regions as $key=>$regionId) {
				$towns = FM_Components_Util_Region::getTownIdsByRegion($regionId);
				foreach ($towns as $index=>$town) {
					$searchComponent->addTown($town);
				}
			}
		}
		switch($searchComponent->getType()) {
			case 3:
				$table = ' FM.orgdata ';
				break;
		}

		$query = "SELECT o.* FROM FM.orgdata o ";
		if(count($towns = $searchComponent->getTowns())) {
			$query .= " JOIN FM.org_town ot ON ot.orgId = o.id ";
		}
		if(count($categories = $searchComponent->getCategories())) {
			$query .= " LEFT OUTER JOIN FM.nporg_cat bc ON bc.orgId = o.id ";
		}

		if($sports = (count($categories = $searchComponent->getCategories()) && in_array(12, $categories)) ) {
			$query .= " LEFT OUTER JOIN FM.orgdata_sports sc ON sc.orgId = o.id ";
		}


		$query .= "WHERE ";
		$i = 0;
		if(count($towns)) {
			$i++;
			$query .= " ot.townId IN (". implode(' , ' , $towns ) . ") AND ";
		}
		if(count($categories) && !$sports) {
			$i++;
			$query .= " bc.catId IN (". implode(' , ' , $categories) . ") AND ";
		} else if(count($categories)) {
			$i++;
			$query .= " (bc.catId IN (". implode(' , ' , $categories) . ") OR sc.category > 0 ) AND ";
		}
		
		if(count($keywords = $searchComponent->getkeywords())) {
			$i++;
			$query .= '(';
			foreach($keywords as $index=>$value) {
				$query .= " o.description LIKE ('%". $value . "%') OR  o.keywords LIKE ('%". $value . "%') OR o.name LIKE ('%". $value . "%') OR ";
			}
			$query = substr($query, 0 , -4) . ') AND ';
		}
		if($zip = $searchComponent->getZipcode()) {
			$i++;
			$query .= " o.zip = {$zip} AND ";
		}
		if(($type = $searchComponent->getType()) && !$sports && count($categories)) {
			$i++;
			$query .= " o.type = 3 AND ";
		} else if((($type = $searchComponent->getType()) && $sports) || !count($categories)){
			$i++;
			$query .= " o.type IN (3 , 4)  AND ";
		}
		if($i>0) {$query = substr($query, 0 , -4);} else {
			$query = substr($query, 0 , -6);
		}
		$query .= "GROUP BY o.id ORDER BY o.active DESC, o.name ASC ";
		//print $query;exit;
		return $this->getMultipleRows($query);
	}

	public function searchKeywords() {

	}

	public function searchLocation($towns = array(), $zip = array()) {

	}

	public function multiSearch($catIds = array(), $keywords = array(), $towns = array(), $zip = array()) {

	}
}