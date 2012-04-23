<?php
class FM_Components_BaseComponent {

	public function get($key) {
		if(property_exists($this, $key)) {
			return $this->{$key};
		}
	}

	public function toArray($excludeKeys = array()) {
		$returnArray = array();
		foreach ($this as $key=>$value) {
			if(!in_array($key, $excludeKeys)) {
				if(is_object($value)) {
					$returnArray[$key] = $value->toArray();
				} else {
					$returnArray[$key] = $value;
				}
			}
		}
		return $returnArray;
	}
	
	public static function cleanURL($url) {
		if(stristr($url, 'http://www.')){
			return $url;
		}
		if(stristr($url, 'http://')){
		 $str = explode('://', $url);
		 return 'http://' . $str[1];
		}
		if(stristr($url, 'http//')){
		 $str = explode('//', $url);
		 return 'http://' . $str[1];
		}
		if(stristr($url, 'www.')){
			return 'http://' . $url;
		}
		if(!stristr($url, 'www.') && stristr($url, '.com')){
			return 'http://www.' . $url;
		}
		if(stristr($url, 'www.') && !stristr($url, '.com')){
			$str = explode('www.', $url);
		 	return 'http://www.' . $str[1] . '.com';
		}
		return $url;
	}
}