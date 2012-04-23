<?php
Zend_Loader::loadClass('FM_Components_BaseComponent');
Zend_Loader::loadClass('FM_Models_FM_Coupon');
Zend_Loader::loadClass('FM_Models_FM_CouponTemplates');


class FM_Components_Coupon extends FM_Components_BaseComponent {

	protected $id;
	protected $type;
	protected $sponsor;
	protected $offer;
	protected $code;
	protected $copy;
	protected $orgId;
	protected $file;
	protected $created;
	protected $active;
	protected $b2b;
	protected $meta = array();
	protected $orgname = '';
	protected $valid;

	public function __construct($keys = null) {
		if(is_array($keys)) {
			$couponModel = new FM_Models_FM_Coupon();
			$templateModel = new FM_Models_FM_CouponTemplates();
			$user = $couponModel->getCouponByKeys($keys);
			if(count($user)){
				foreach ($user as $key=>$value) {
					if(property_exists($this, $key)) {
						$this->{$key} = $value;
					}
				}
				$this->meta = $templateModel->getTemplateByKeys(array('id'=>$this->getType()));
				return true;
			}
			return false;
		}
		return true;
	}

	public static function insertCoupon($args) {
		if(!is_array($args)){return false;}
		$couponModel = new FM_Models_FM_Coupon();
		$Coupon = new FM_Components_Coupon();
		$cleansedArgs = array();
		foreach ($args as $key=>$value) {
			if(property_exists($Coupon, $key)) {
				$cleansedArgs[$key] = $value;
			}
		}
		if(!count($cleansedArgs)){return false;}
		return $couponModel->insertRecord($cleansedArgs);
	}

	public static function deleteCoupon($args) {
		$couponModel = new FM_Models_FM_Coupon();
		return $couponModel->remove($args);
	}

	public static function updateCoupon($keys, $args) {
		$couponModel = new FM_Models_FM_Coupon();
		return $couponModel->edit($keys, $args);
	}

	public static function getOrgCoupons($oid, $b2b = false, $activeOnly = false) {
		$Coupon = array();
		$couponModel = new FM_Models_FM_Coupon();
		if($b2b) {
			$Coupons = $couponModel->getCouponsByKeys(array('orgId'=>$oid, 'b2b'=>1), $activeOnly);
		} else {
			$Coupons = $couponModel->getCouponsByKeys(array('orgId'=>$oid, 'b2b'=>0), $activeOnly);
		}
		if(count($Coupons)) {
			foreach ($Coupons as $record) {
				if($b= new FM_Components_Coupon(array('id'=>$record['id']))){
					$Coupon[] = $b;
				}
			}
		}
		return $Coupon;
	}


	public static function getAllOrgCoupons($oid) {
		$Coupon = array();
		$couponModel = new FM_Models_FM_Coupon();
		$Coupons = $couponModel->getCouponsByKeys(array('orgId'=>$oid));
		if(count($Coupons)) {
			foreach ($Coupons as $record) {
				if($b= new FM_Components_Coupon(array('id'=>$record['id']))){
					$Coupon[] = $b;
				}
			}
		}
		return $Coupon;
	}
	
	
	public static function getAllCoupons() {
		$Coupon = array();
		$couponModel = new FM_Models_FM_Coupon();
		$Coupons = $couponModel->getAll();
		if(count($Coupons)) {
			foreach ($Coupons as $record) {
				if($b= new FM_Components_Coupon(array('id'=>$record['id']))){
					$Coupon[] = $b;
				}
			}
		}
		return $Coupon;
	}


	public static function getSelectedCoupons(array $ids) {
		$Coupon = array();
		$couponModel = new FM_Models_FM_Coupon();
		$Coupons = $couponModel->getCouponsIn($ids);
		if(count($Coupons)) {
			foreach ($Coupons as $record) {
				if($b= new FM_Components_Coupon(array('id'=>$record['id']))){
					$Coupon[] = $b;
				}
			}
		}
		return $Coupon;
	}

	public static function getNewCoupons($orgId) {
		$Coupon = array();
		$couponModel = new FM_Models_FM_Coupon();
		$args = array('created'=>0);
		if($orgId && $orgId != 0 ) {
			$args['orgId'] = $orgId;
		}
		$Coupons = $couponModel->getCouponsByKeys($args);

		if(count($Coupons)) {
			foreach ($Coupons as $record) {
				if($b= new FM_Components_Coupon(array('id'=>$record['id']))){
					$Coupon[] = $b;
				}
			}
		}
		return $Coupon;
	}

	public function getId() {
		return $this->id;
	}

	public function getSponsor() {
		return $this->sponsor;
	}

	public function getOrgId() {
		return $this->orgId;
	}
	
	public function getOrgName() {
		return $this->orgname;
	}

	public function getCode() {
		return $this->code;
	}

	public function getType() {
		return $this->type;
	}

	public function getOffer() {
		return $this->offer;
	}

	public function getCopy() {
		return $this->copy;
	}
	
	public function getFormattedValid() {
		return date('M d,Y', strtotime($this->getValid()));
	}
	
	public function getValid() {
		return $this->valid;
	}

	public function isCreated() {
		return ($this->created == 1) ? true : false;
	}

	public function isActive() {
		return ($this->active == 1) ? true : false;
	}

	public function getFile() {
		return ($this->file && $this->file != '') ? $this->file : false;
	}

	public function getClass() {
		return $this->meta['class'];
	}

	public function isB2b() {
		return $this->b2b;
	}
}