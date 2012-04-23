<?php
Zend_Loader::loadClass('Zend_Feed_Rss');
Zend_Loader::loadClass('FM_Models_FM_Rss');
Zend_Loader::loadClass('Zend_Cache');

class FM_Components_RSS_Base extends Zend_Feed_Rss {

	protected $_base;
	protected $_rssTable;

	public function __construct($feed, $id) {
		$cache_f = array(
		'lifetime' => 60 * 60,
		'automatic_serialization' => true
		);
		$cache_b = array(
		'cache_dir' => '/tmp'
		);
		$cache = Zend_Cache::factory('Core', 'File', $cache_f, $cache_b);
		if(($c = $cache->load($id . '_FM')) ) {
			parent::__construct(null, $c->saveXML());
			$this->_base = $c;
		} else {
			try{
				parent::__construct($feed);
				$this->_base = new Zend_Feed_Rss($feed);
				$cache->save($this->_base , $id . '_FM');
				$this->_base->__wakeup();
			} catch (Zend_Feed_Exception $e){

			}
		}
	}

	public  function getLink() {
		return (is_object($this->_base)) ? $this->_base->link() : '';
	}

	public function getTitle() {
		return (is_object($this->_base)) ?  $this->_base->title() : '';
	}

	public function getDate() {
		return (is_object($this->_base)) ?  $this->_base->pubDate() : '';
	}
}