<?php
Zend_Loader::loadClass('FM_Models_FM_TextAd');
Zend_Loader::loadClass('FM_Components_BaseComponent');

class FM_Components_Util_TextAd extends FM_Components_BaseComponent {

	protected $id;
	protected $orgId;
	protected $content;
	protected $active;
	protected $created;
	protected $org;

	private $_testModel;

	public function __construct($keys) {
		$this->_testModel = new FM_Models_FM_TextAd();
		if($test = $this->_testModel->getTextAdByKeys($keys)) {
			if(count($test)){
				foreach ($test as $key=>$value) {
					if(property_exists($this, $key)) {
						$this->{$key} = $value;
					}
				}
				$this->org = new FM_Components_Organization(array('id'=>$this->orgId));
				return true;
			}
		}
		return false;
	}

	public function getFormattedContent() {
		$content = '<table><tr><td height="80" valign="top"><a href="http://4monmouth.com' . $this->org->getLink() . '"><img src="http://4monmouth.com/media/image/icons/' . $this->org->getIconImage() .  '" /></a></td></tr><tr><td>' . $this->content . '</td></tr></table>';
		return $content;
	}

	public function getContent() {
		return $this->content;
	}

	public function getOrg(){
		return $this->org;
	}

	public function isActive() {
		return $this->active;
	}

	public function getId() {
		return $this->id;
	}

	public static function insertTextAd($args) {
		$model = new FM_Models_FM_TextAd();
		return $model->insert($args);
	}

	public static function deleteTextAd($args) {
		$model = new FM_Models_FM_TextAd();
		return $model->remove($args);
	}

	public static function getTextAd($args) {
		$model = new FM_Models_FM_TextAd();
		$tests = $model->getTextAdByKeys($args);
		$TextAd = array();
		if(count($tests)) {
			foreach($tests as $index=>$record) {
				$TextAd[] = new FM_Components_Util_TextAd(array('id'=>$record['id']));
			}
		}
		return $TextAd;
	}

	public static function updateTextAd($keys, $args) {
		$model = new FM_Models_FM_TextAd();
		return $model->update($keys, $args);
	}

	public static function getOrgAds($orgId) {
		$model = new FM_Models_FM_TextAd();
		$tests = $model->getTextAdsByKeys(array('orgId'=>$orgId));
		$TextAd = array();
		if(count($tests)) {
			foreach($tests as $index=>$record) {
				$TextAd[] = new FM_Components_Util_TextAd(array('id'=>$record['id']));
			}
		}
		return $TextAd;
	}
	
	/**

	public static function getRandom($total = 1) {
		$model = new FM_Models_FM_TextAd();
		$adr = $model->getRandom(array(), $total);
		$rs = '<table width="60%" style="margin-top:1em;"><tr>';
		$i = 0;
		if(count($adr)) {
			foreach ($adr as $index=>$ad) {
				if($i == 3){$rs .= '</tr><tr>';$i = 0;}
				$text = new FM_Components_Util_TextAd(array('id'=>$ad['id']));
				$rs .= '<td border="1" padding="3" valign="top" width="33%" >' . $text->getFormattedContent() . '</td>';
				$i++;
			}
		}
		if($i < 3){
			while($i < 3) {
				$rs .= '<td >&nbsp</td>';
				$i++;
			}
			$rs .= '</tr>';
		}
		$rs .= '</tr></table>';
		return $rs;
	}
	**/
	
		public static function getRandom($total = 1) {
		$model = new FM_Models_FM_TextAd();
		$adr = $model->getRandom(array(), $total);
		$rs = '<table width="80%" style="margin-top:1em;"><tr><td colspan="4" style="padding:5px;color:white;background:green;font-size:14pt;">Support Our Local Businesses. Thank You.</td><tr>';
		$i = 0;
		if(count($adr)) {
			foreach ($adr as $index=>$ad) {
				
				$text = new FM_Components_Util_TextAd(array('id'=>$ad['id']));
				$rs .= '<td border="1" padding="3" valign="top" width="25%" >' . $text->getFormattedContent() . '</td>';
			
			}
			$rs .= '</tr>';
		}
		$rs .= '</tr></table>';
		return $rs;
	}



}