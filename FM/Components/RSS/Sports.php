<?php
Zend_Loader::loadClass('FM_Components_RSS_Base');

class FM_Components_RSS_Sports extends FM_Components_RSS_Base {
	
	protected $_link = 'http://sports.yahoo.com/top/rss.xml';
	
	public function __construct() {
		parent::__construct($this->_link, 'spo');
	}
}