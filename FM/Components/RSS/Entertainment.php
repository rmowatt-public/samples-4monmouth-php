<?php
Zend_Loader::loadClass('FM_Components_RSS_Base');

class  FM_Components_RSS_Entertainment extends FM_Components_RSS_Base {
	
	//protected $_link = 'http://feeds.feedburner.com/variety/news/frontpage';
	//protected $_link = 'http://rss.ew.com/web/ew/rss/todayslatest/index.xml';
	protected $_link = 'http://rss.news.yahoo.com/rss/entertainment';
	
	public function __construct() {
		parent::__construct($this->_link, 'entv');
	}
}