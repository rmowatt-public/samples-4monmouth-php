<?php
Zend_Loader::loadClass('FM_Components_RSS_Base');

class FM_Components_RSS_Finance extends FM_Components_RSS_Base {
	
	protected $_link = 'http://rss.cnn.com/rss/money_markets.rss';
	
	public function __construct() {
		parent::__construct($this->_link, 'fin');
	}
}