<?php
Zend_Loader::loadClass('FM_Components_RSS_Base');

class FM_Components_RSS_Horoscope extends FM_Components_RSS_Base {

	protected $_sign;
	
	public static  $signs = array(
	'ARIES',
	'TAURUS',
	'GEMINI',
	'CANCER',
	'LEO',
	'VIRGO',
	'LIBRA',
	'SCORPIO',
	'SAGITTARIUS',
	'CAPRICORN',
	'AQUARIUS',
	'PISCES'
	);

	public function __construct($sign) {
		$this->_sign = $sign;
		switch ($sign) {
			case 'ARIES' :
			$link = 'http://www.astrocenter.com/us/Feeds/RSS/getDaily.aspx?sign=Aries';
			break;
			case 'TAURUS' :
			$link = 'http://www.astrocenter.com/us/Feeds/RSS/getDaily.aspx?sign=Taurus';
			break;
			case 'GEMINI' :
			$link = 'http://www.astrocenter.com/us/Feeds/RSS/getDaily.aspx?sign=Gemini';
			break;
			case 'CANCER' :
			$link = 'http://www.astrocenter.com/us/Feeds/RSS/getDaily.aspx?sign=Cancer';
			break;
			case 'LEO' :
			$link = 'http://www.astrocenter.com/us/Feeds/RSS/getDaily.aspx?sign=Leo';
			break;
			case 'VIRGO' :
			$link = 'http://www.astrocenter.com/us/Feeds/RSS/getDaily.aspx?sign=Virgo';
			break;
			case 'LIBRA' :
			$link = 'http://www.astrocenter.com/us/Feeds/RSS/getDaily.aspx?sign=Libra';
			break;
			case 'SCORPIO' :
			$link = 'http://www.astrocenter.com/us/Feeds/RSS/getDaily.aspx?sign=Scorpio';
			break;
			case 'SAGITTARIUS' :
			$link = 'http://www.astrocenter.com/us/Feeds/RSS/getDaily.aspx?sign=Sagittarius';
			break;
			case 'CAPRICORN' :
			$link = 'http://www.astrocenter.com/us/Feeds/RSS/getDaily.aspx?sign=Capricorn';
			break;
			case 'AQUARIUS' :
			$link = 'http://www.astrocenter.com/us/Feeds/RSS/getDaily.aspx?sign=Aquarius';
			break;
			case 'PISCES' ;
			$link = 'http://www.astrocenter.com/us/Feeds/RSS/getDaily.aspx?sign=Pisces';
			break;
		}
		if(isset($link)) {
			parent::__construct($link, $sign);
		}
	}

	public static function getAll() {
		$allSigns = array();
		foreach (FM_Components_RSS_Horoscope::$signs as $sign) {
			$allSigns[$sign] = new FM_Components_RSS_Horoscope($sign);
		}
		return $allSigns;
	}
	
	public function getLink() {
		$keys = array_flip(FM_Components_RSS_Horoscope::$signs);
		return $this->link() . '/us/Default.aspx?sign=' . $keys[$this->_sign];
	}

}