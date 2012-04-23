<?php
Zend_Loader::loadClass ( 'FM_Models_FM_SearchPrimaryCategories' );
Zend_Loader::loadClass ( 'FM_Models_FM_SearchPrimaryCategoriesOrgs' );
Zend_Loader::loadClass ( 'FM_Components_BaseComponent' );
Zend_Loader::loadClass ( 'FM_Models_FM_BzorgCat' );
Zend_Loader::loadClass ( 'FM_Models_FM_NporgCat' );

class FM_Components_Util_Category extends FM_Components_BaseComponent{

	protected $id;
	protected $name;
	protected $description;
	protected $keywords = array();
	protected $parent = 0;
	protected $selected = false;

	public function __construct($keys = null, $orgs = false) {
		if(is_array($keys)) {
			$catModel = ($orgs) ? new FM_Models_FM_SearchPrimaryCategoriesOrgs() : new FM_Models_FM_SearchPrimaryCategories();
			$category = $catModel->getCategoryByKeys($keys);
			if(count($category)){
				foreach ($category as $key=>$value) {
					if(property_exists($this, $key)) {
						if($key == 'keywords'){
							$this->_keywords = explode(',', $value);
						} else{
							$this->{$key} = $value;
						}
					}
				}
				return true;
			}
			return false;
		}
		return true;
	}
	/**
	 * @param $keywords the $keywords to set
	 */
	public function setKeywords($keywords) {
		$this->keywords = $keywords;
	}

	/**
	 * @param $description the $description to set
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * @param $name the $name to set
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @param $id the $id to set
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @return the $keywords
	 */
	public function getKeywords() {
		return $this->keywords;
	}

	/**
	 * @return the $description
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @return the $name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}

	public function getParent() {
		return ($this->parent == 0) ? false : $this->parent;
	}
	
	public static function getCategoryName($id, $orgs) {
		$catModel = ($orgs) ? new FM_Models_FM_SearchPrimaryCategoriesOrgs() : new FM_Models_FM_SearchPrimaryCategories();
		$r = $catModel->getCategoryByKeys(array('id'=>$id));
		return $r['name'];
	}
	
	public function isSelected($orgId, $org = false, $id = false) {
		if(!$id){$id = $this->getId();}
		$catModel = ($org) ? new FM_Models_FM_NporgCat() : new FM_Models_FM_BzorgCat() ;
		$cats = $catModel->getRecordByKeys(array('orgId'=>$orgId, 'catId'=>$id));
		return (is_array($cats) && array_key_exists('id', $cats));
	}


	public static function getPrimarySearchCategories($orgs = false) {
		$catModel = ($orgs) ? new FM_Models_FM_SearchPrimaryCategoriesOrgs() : new FM_Models_FM_SearchPrimaryCategories();
		$categories = $catModel->getPrimaryCategories();
		$catArray = array();
		//print_r($categories);exit;
		if(is_array($categories)) {
			foreach($categories as $key=>$values) {
				$catArray[] = new FM_Components_Util_Category(array('id'=>$values['id']), $orgs);
			}
		}
		return $catArray;
	}

	public static function getSortedSearchCategories($orgs = false) {
		$allCats = self::getPrimarySearchCategories($orgs);
		$sort = array();
		
			
		if(is_array($allCats)) {
			foreach($allCats as $key=>$cat) {
				if(!$cat->getParent()) {
					$sort['i' . $cat->getId()]['main'] = $cat;
				} else {
					$sort['i' . $cat->getParent()]['subs'][] = $cat;
				}
			}
		}
		//print_r($sort);exit;
		return $sort;
	}

	public static function getRootCategories($orgs = false) {
		$allCats = self::getPrimarySearchCategories($orgs);
		$sort = array();
		if(is_array($allCats)) {
			foreach($allCats as $key=>$cat) {
				if(!$cat->getParent()) {
					$sort[]= $cat;
				}
			}
		}
		return $sort;
	}

	public static function insertCategory($args, $orgs = false) {
		$catModel = ($orgs) ? new FM_Models_FM_SearchPrimaryCategoriesOrgs() : new FM_Models_FM_SearchPrimaryCategories();
		if($catModel->insertRecord($args)) {
			return true;
		}
		return false;
	}
	
	public static function editCategory($keys, $values, $orgs = false) {
		$catModel = ($orgs) ? new FM_Models_FM_SearchPrimaryCategoriesOrgs() : new FM_Models_FM_SearchPrimaryCategories();
		if($catModel->edit($keys, $values)) {
			return true;
		}
		return false;
	}

	public static function deleteCategory($args, $orgs = false) {
		$catModel = ($orgs) ? new FM_Models_FM_SearchPrimaryCategoriesOrgs() : new FM_Models_FM_SearchPrimaryCategories();
		if($catModel->remove($args)) {
			return true;
		}
		return false;
	}
	
	public function getSubcats($orgs = false, $orgId = false) {
		$catModel = ($orgs) ? new FM_Models_FM_SearchPrimaryCategoriesOrgs() : new FM_Models_FM_SearchPrimaryCategories();
		$rv =  $catModel->getCategoriesByKeys(array('parent'=> $this->getId()));
		$keys = array();
		if($orgId) {
			foreach ($rv as $key=>$values) {
				$val = $values;
				if($this->isSelected($orgId, $orgs, $values['id'])) {
					$val['selected'] = 1;
				} else {
					$val['selected'] = 0;
				}
				$keys[] = $val;
			}
			
		}
		else {
			$keys = $rv;
		}
		return $keys;
	}


}