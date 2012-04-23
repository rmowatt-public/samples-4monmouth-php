<?php
Zend_Loader::loadClass('FM_Controllers_BaseController');
Zend_Loader::loadClass('FM_Components_Util_Category');
Zend_Loader::loadClass('FM_Components_Util_Town');
Zend_Loader::loadClass('FM_Components_Util_Region');
Zend_Loader::loadClass('FM_Components_Search');
Zend_Loader::loadClass('FM_Components_Business');
Zend_Loader::loadClass('FM_Components_Organization');
Zend_Loader::loadClass('FM_Components_Sports');
Zend_Loader::loadClass('FM_Components_Banner');
Zend_Loader::loadClass('Zend_Session_Namespace');


class SearchController extends FM_Controllers_BaseController{

	function indexAction() {
		$this->view->results = null;
		$this->view->orgsearch = false;
		if($_POST || ($_GET && array_key_exists('all', $_GET))) {
			$cats = array();
			//print_r($_POST);exit;
			$search = FM_Components_Search::getInstance();
			$search->setType(2);
			if($_POST) {
				$all = array();
				foreach($_POST as $key=>$value) {
					if(stristr($key, 'all')) {
						$regionId = explode('_', $key);
						$all[] = $regionId[2];
						continue;
					}
					if(stristr($key, 'region')) {
						$regionId = explode('_', $key);
						$search->addRegion($regionId[2]);
						continue;
					}
					if(stristr($key, 'town')) {
						$townId = explode('_', $key);
						if($townId[2] != 0){
							$search->addTown($townId[2]);
						}
						continue;
					}
					if(stristr($key, 'category')) {
						$categoryId = explode('_', $key);
						if($categoryId[2] != 0){
							$search->addCategory($categoryId[2]);
						}
						continue;
					}
					if(stristr($key, 'subcat')) {
						$categoryId = explode('_', $key);
						$search->addCategory($categoryId[2]);
						$cats[] = $categoryId[3];
						continue;
					}
					if($key == 'keyword') {
						$search->setKeywords(explode(',', $value));
						continue;
					}
					if($key == 'zip') {
						$search->setZipcode($value);
					}
				}

				$search->cleanCats($cats);
				foreach ($all as $value) {
					$search->addCategory($value);
				}
				$results = $search->doSearch();
			}
			$results = $search->doSearch();


			$objs = array();
			if($resultsTotal = count($results)) {
				//print_r($results);
				foreach($results as $index=>$values) {
					switch($values['type']) {
						case 1 :
							//$obj = new FM_Components_Organization(array('id'=>$values['id']));
							//$objs[] = $obj;
							break;
						case 2 :
							$obj = $values;
							$icon = new FM_Components_Util_Icon(array('orgId'=>$values['id']));
							$obj['icon'] = $icon->getFileName();
							$objs[] = $obj;
							break;
					}
				}

				//print_r($objs);exit;

				$banners = FM_Components_Banner::getSortedRandomBanners();
				$banners = $this->view->partial('banner/bannerleftindex.phtml',
				array('banners'=>$banners));
				$count = count($objs);
				$this->view->results = $this->view->partial('search/results.phtml',
				array('clients'=>$objs, 'total'=>$count, 'banners'=>$banners));

			}
			else {
				$this->view->noResults = true;
			}
		} elseif ($this->_request->getParam( 'region' ) != 0) {

			$search = FM_Components_Search::getInstance();
			$search->addRegion($this->_request->getParam( 'region' ));
			$results = $search->doSearch();

			$objs = array();
			if($resultsTotal = count($results)) {
				//print_r($results);
				foreach($results as $index=>$values) {
					switch($values['type']) {
						case 1 :
							//$obj = new FM_Components_Organization(array('id'=>$values['id']));
							//$objs[] = $obj;
							break;
						case 2 :
							$obj = new FM_Components_Business(array('id'=>$values['id']));
							$objs[] = $obj;
							break;
					}
				}

				$banners = FM_Components_Banner::getSortedRandomBanners();
				$banners = $this->view->partial('banner/bannerleftindex.phtml',
				array('banners'=>$banners));

				$this->view->results = $this->view->partial('search/results.phtml',
				array('clients'=>$objs, 'total'=>count($objs), 'banners'=>$banners, 'type'=>'merchant'));

			} else {
				$this->view->noResults = true;
			}
		}

		$this->_helper->layout->setLayout('singlecolumn');
		$this->view->layout()->cols = 1;

		$this->view->cats = $this->view->partial('search/categories.phtml',
		array('cats'=>FM_Components_Util_Category::getSortedSearchCategories()));

		$this->view->towns = $this->view->partial('search/towns.phtml',
		array('towns'=>FM_Components_Util_Town::getAll()));
	}

	function orgsAction() {
		$this->view->results = null;
		if($_POST || ($_GET && array_key_exists('all', $_GET))) {
			//print_r($_POST);exit;
			$search = FM_Components_Search::getInstance();
			$search->setType(3);
			if($_POST) {
				foreach($_POST as $key=>$value) {
					if(stristr($key, 'region')) {
						$regionId = explode('_', $key);
						$search->addRegion($regionId[2]);
						continue;
					}
					if(stristr($key, 'all')) {
						$regionId = explode('_', $key);
						$all[] = $regionId[2];
						continue;
					}
					if(stristr($key, 'town')) {
						$townId = explode('_', $key);
						if($townId[2] != 0){
							$search->addTown($townId[2]);
						}
						continue;
					}
					if(stristr($key, 'category')) {
						$categoryId = explode('_', $key);
						if($categoryId[2] != 0){
							$search->addCategory($categoryId[2]);
						}
						continue;
					}
					if($key == 'keyword') {
						$search->setKeywords(explode(',', $value));
						continue;
					}
					if($key == 'zip') {
						$search->setZipcode($value);
					}
				}
			}

			$results = $search->doNpSearch();

			$objs = array();
			if($resultsTotal = count($results)) {
				//print_r($results);
				foreach($results as $index=>$values) {
					switch($values['type']) {
						case 3 :
							$obj = $values;
							$icon = new FM_Components_Util_Icon(array('orgId'=>$values['id']));
							$obj['icon'] = $icon->getFileName();
							$objs[] = $obj;
							break;
						case 4 :
							$obj = $values;
							$icon = new FM_Components_Util_Icon(array('orgId'=>$values['id']));
							$obj['icon'] = $icon->getFileName();
							$obj['type'] = 4;
							$objs[] = $obj;
							break;
					}
				}

				$banners = FM_Components_Banner::getSortedRandomBanners();
				$banners = $this->view->partial('banner/bannerleftindex.phtml',
				array('banners'=>$banners));
				$this->view->results = $this->view->partial('search/results.phtml',
				array('clients'=>$objs, 'total'=>$resultsTotal, 'banners'=>$banners, 'type'=>'org'));

			} else {
				$this->view->noResults = true;
			}
		}




		$this->_helper->layout->setLayout('singlecolumn');
		$this->view->layout()->cols = 1;

		$this->view->cats = $this->view->partial('search/categories.phtml',
		array('cats'=>FM_Components_Util_Category::getSortedSearchCategories(true)));

		$this->view->towns = $this->view->partial('search/towns.phtml',
		array('towns'=>FM_Components_Util_Town::getAll()));
		$this->view->orgsearch = true;
	}

}