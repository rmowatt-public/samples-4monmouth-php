<?php
Zend_Loader::loadClass('FM_Components_RSS_Base');

class FM_Components_RSS_FlashNews extends FM_Components_RSS_Base {
	
	//protected $_link = 'http://hosted.ap.org/lineups/TOPHEADS-rss_2.0.xml?SITE=AZTUS&SECTION=HOME';
	protected $_link = 'http://rss.news.yahoo.com/rss/us';
	
	public function __construct() {
		parent::__construct($this->_link, 'fne');
	}
}