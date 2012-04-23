<?php
Zend_Loader::loadClass('FM_Components_RSS_Base');

class FM_Components_RSS_Rod extends FM_Components_RSS_Base {
	
	protected $_link = 'http://www.bettycrocker.com/recipes/HealthyRecipeOfTheDay.aspx?WT.mc_id=rss_HealthyRecipeOfTheDay_XML';
	
	public function __construct() {
		parent::__construct($this->_link, 'rod');
	}
}