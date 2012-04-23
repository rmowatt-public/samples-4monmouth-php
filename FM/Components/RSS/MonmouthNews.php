<?php
Zend_Loader::loadClass('FM_Components_RSS_Base');

class FM_Components_RSS_MonmouthNews extends FM_Components_RSS_Base {
	
	//protected $_link = 'http://blog.nj.com/ledgerupdates_impact/monmouth_county/rss.xml';
	//protected $_link = 'http://www.app.com/section/NEWS01&template=rss_app&mime=xml';
	protected $_link = 'http://www.topix.com/rss/county/monmouth-nj';
	
	public function __construct() {
		parent::__construct($this->_link, 'mne');
	}
}