<?php
class FM_Components_BaseComponent {

	/**
	 *  
	 * A basic function for accessing a components variable
	 * pretty much mimics php magic method get
	 * @param string $key
	 * @return mixed
	 */
	
	public function get($key) {
		if(property_exists($this, $key)) {
			return $this->{$key};
		}
	}

	/**
	 * 
	 * Turns any component extending basecomponent to an array
	 * a list of included keys will filter out unwanted vars
	 * by default this will return objects by property key, use showObjects=false to overide
	 * 
	 * @param array $excludeKeys
	 * @param boolean $excludeKeys
	 * @return array $returnArray
	 */
	
	public function toArray($excludeKeys = array(), $showObjects = true) {
		$returnArray = array();
		foreach ($this as $key=>$value) {
			if(!in_array($key, $excludeKeys)) {
				if(is_object($value) && $showObjects) {
					$returnArray[$key] = ($value instanceof FM_Components_BaseComponent) ? $value->toArray() : $value;
				} else {
					$returnArray[$key] = $value;
				}
			}
		}
		return $returnArray;
	}

	/**
	 * 
	 * Creates a uniform fromat to urls
	 * @param string $url
	 * @return string $url
	 */
	
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