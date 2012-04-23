<?php
Zend_Loader::loadClass('FM_Components_BaseComponent');
Zend_Loader::loadClass('FM_Models_BaseSearchModel');

class FM_Components_Search extends FM_Components_BaseComponent{


	protected $regions = array();
	protected $towns = array();
	protected $categories = array();
	protected $zipcode;
	protected $type = null;
	protected $keywords = array();
	private static $_instance = null;

	public static function getInstance() {
		if(!self::$_instance) {
			self::$_instance = new FM_Components_Search();
		}
		return self::$_instance;
	}

	public function doSearch() {
		$searchModel = new FM_Models_BaseSearchModel();
		return $results = $searchModel->buildFromSearchObj($this);
	}
	
	public function doNpSearch() {
		$searchModel = new FM_Models_BaseSearchModel();
		return $results = $searchModel->buildFromNpSearchObj($this);
	}

	public function addTown($town) {
		$this->towns[] = $town;
	}

	public function addRegion($region) {
		$this->regions[] = $region;
	}

	public function addCategory($cat) {
		$this->categories[] = $cat;
	}

	public function addKeyword($keyword) {
		$this->keywords[] = $keyword;
	}

	/**
	 * @param $keywords the $keywords to set
	 */
	public function setKeywords($keywords) {
		$this->keywords = $keywords;
	}

	/**
	 * @param $zipcode the $zipcode to set
	 */
	public function setZipcode($zipcode) {
		$this->zipcode = $zipcode;
	}

	/**
	 * @param $categories the $categories to set
	 */
	public function setCategories($categories) {
		$this->categories = $categories;
	}

	/**
	 * @param $towns the $towns to set
	 */
	public function setTowns($towns) {
		$this->towns = $towns;
	}
	
	public function cleanCats($cats) {
		$cleaned = array();
		foreach ($this->categories as $cat) {
			if(!in_array($cat, $cats)) {
				$cleaned[] = $cat;
			}
		}
		$this->categories = $cleaned;
	}

	/**
	 * @param $regions the $regions to set
	 */
	public function setRegions($regions) {
		$this->regions = $regions;
	}
	
	public function setType($type) {
		$this->type = $type;
	}
	
	public function getType() {
		return $this->type;
	}

	/**
	 * @return the $keywords
	 */
	public function getKeywords() {
		return $this->keywords;
	}

	/**
	 * @return the $zipcode
	 */
	public function getZipcode() {
		return $this->zipcode;
	}

	/**
	 * @return the $categories
	 */
	public function getCategories() {
		return $this->categories;
	}

	/**
	 * @return the $towns
	 */
	public function getTowns() {
		return $this->towns;
	}

	/**
	 * @return the $regions
	 */
	public function getRegions() {
		return $this->regions;
	}


	protected function __construct() {

	}
}

