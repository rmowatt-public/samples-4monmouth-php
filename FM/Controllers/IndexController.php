<?php
Zend_Loader::loadClass('FM_Controllers_BaseController');
Zend_Loader::loadClass('FM_Components_RSS_FlashNews');
Zend_Loader::loadClass('FM_Components_RSS_MonmouthNews');
Zend_Loader::loadClass('FM_Components_RSS_Finance');
Zend_Loader::loadClass('FM_Components_RSS_Entertainment');
Zend_Loader::loadClass('FM_Components_RSS_Rod');
Zend_Loader::loadClass('FM_Components_Banner');
Zend_Loader::loadClass('FM_Components_Coupon');
Zend_Loader::loadClass('FM_Components_Widgets_BannerLayout');
Zend_Loader::loadClass('FM_Components_Business');
Zend_Loader::loadClass('FM_Components_NonProfit');
Zend_Loader::loadClass('FM_Components_Sports');
Zend_Loader::loadClass('FM_Components_Organization');
Zend_Loader::loadClass('FM_Components_Util_UploadHandler');
Zend_Loader::loadClass('FM_Components_Util_BannerTemplate');
Zend_Loader::loadClass('FM_Components_Util_PayBanner');
Zend_Loader::loadClass('FM_Components_Util_ProductImage');
Zend_Loader::loadClass('FM_Components_Util_Product');
Zend_Loader::loadClass('FM_Components_Util_RealEstateListing');
Zend_Loader::loadClass('FM_Components_Util_ForumItem');
Zend_Loader::loadClass('FM_Components_Widgets_PhotoGallery');
Zend_Loader::loadClass('FM_Components_Calendar_Month');
Zend_Loader::loadClass('FM_Components_EmailFormatter');
Zend_Loader::loadClass('FM_Components_RSS_Sports');

class IndexController extends FM_Controllers_BaseController{

	function indexAction() {


		$id = Zend_Auth::getInstance()->hasIdentity();
		$this->view->layout()->maps = false;
		//if(!$id) {
			//echo '<body><div style="width:1000px;margin:0px auto;"><div style="margin:0px auto;"><img
			//		src="/images/UnderConstruction.jpeg" /></div></div></body>';
			//exit;
		//} else {

			$fn = new FM_Components_RSS_FlashNews();
			$this->view->topstories = $this->view->partial('widgets/rss/news.phtml',
			array('news'=>$fn, 'class'=>'rss', 'id'=>"fnrss", 'limit'=>30, 'title'=>'flash news', 'icon'=>'flashnew-icon.png'));

			$mn = new FM_Components_RSS_MonmouthNews();
			$this->view->monmouthnews = $this->view->partial('widgets/rss/news.phtml',
			array('news'=>$mn, 'class'=>'rss', 'id'=>"mnrss", 'limit'=>30, 'title'=>'mounmouth news 24', 'icon'=>'info_icon.png'));

			$bz = new FM_Components_RSS_Finance();
			$this->view->financenews = $this->view->partial('widgets/rss/news.phtml',
			array('news'=>$bz, 'class'=>'rss', 'id'=>"bzrss", 'limit'=>30, 'title'=>'finance', 'icon'=>'finance-icon.png'));

			$en = new FM_Components_RSS_Entertainment();
			$this->view->entertainmentnews = $this->view->partial('widgets/rss/news.phtml',
			array('news'=>$en, 'class'=>'rss', 'id'=>"enrss", 'limit'=>30, 'title'=>'entertainment', 'icon'=>'entert-icon.png'));

			$sports = new FM_Components_RSS_Sports();
			$this->view->sportsnews = $this->view->partial('widgets/rss/news.phtml',
			array('news'=>$sports, 'class'=>'rss', 'id'=>"sprss", 'limit'=>30, 'title'=>'sports', 'icon'=>'sport-icon.png'));

			$banners = FM_Components_Banner::getSortedRandomBanners(array(), 9);
			$this->view->layout()->banners = $this->view->partial('banner/bannerleftindex.phtml',
			array('banners'=>$banners));

			$featuredOrganization = FM_Components_Organization::getRandom(1, true, array(8));
			$this->view->layout()->featuredOrganization = $this->view->partial('organization/indexfeatured.phtml',
			array('organization'=>$featuredOrganization[1]));

			$this->view->layout()->weather = $this->view->partial('widgets/weather/widget.phtml');

			$spotlight = FM_Components_Organization::getRandom(6, false, array(), true);
			//print_r(count($spotlight));exit;
			$this->view->layout()->spotlight = $this->view->partial('organization/spotlight.phtml',
			array('organizations'=>$spotlight));

			//$recipeOfTheDay = new FM_Components_RSS_Rod();
			//$this->view->layout()->rod = $this->view->partial('widgets/rss/rod.phtml',
			//array('rod'=>$recipeOfTheDay, 'class'=>'rodrss', 'id'=>"rssrod", 'limit'=>1, 'title'=>'RECIPE OF THE DAY', 'icon'=>''));

			$this->view->entertainment = $this->view->partial('widgets/rss/entertainment.phtml');

			//	print_r($recipeOfTheDay);exit;

			$this->view->layout()->astrology = $this->view->partial('widgets/horoscope/full.phtml',
			array('signs'=>FM_Components_RSS_Horoscope::getAll()));

			$headerBanner = new FM_Components_Widgets_BannerLayout($this->view);
			$this->view->layout()->header = $this->view->partial('headers/index.phtml', array('banner'=>$headerBanner->toHTML()));

			$c = new FM_Components_Calendar_Month(0, 0, 0);
			$date1 = (date('n', time()) == 12) ? date('Y', strtotime("+1 year")) : date('Y', time());
			$date2 = (date('n', time()) == 12 || date('n', time()) == 11) ? date('Y', strtotime("+1 year")) : date('Y', time());
			$d = new FM_Components_Calendar_Month(date('n', strtotime("first day of next month")), $date1, 0);
			$e = new FM_Components_Calendar_Month(date('n', strtotime("first day of next month +1 month")), $date2, 0);
			//echo date('n', strtotime("+1 month"));exit;
			$ae[] = $c->getActiveEvents(date('d', time()));
			$ae[] = $d->getActiveEvents(date('d', time()), true);
			$ae[] = $e->getActiveEvents(date('d', time()), true);
			
			$this->view->calendarrss = $this->view->partial('widgets/rss/calendar.phtml',
			array('events'=>$ae, 'class'=>'rss', 'id'=>"evrss", 'title'=>'Events', 'icon'=>'event-icon.png'));


	//	}
	}

	public function slugAction() {
		if($this->_request->getParam('slug')) {
			$org = FM_Components_Organization::findBySlug($this->_request->getParam('slug'));
			if($org) {
				switch($org['type']) {
					case 2 :
						$this->_redirect('/merchant/'. $org['id']);
						break;
					case 3 :
						$this->_redirect('/org/'. $org['id']);
						break;
					case 4 :
						$this->_redirect('/sports/'. $org['id']);
						break;
					default :
						$this->_redirect('/');
						break;
				}
			} else {
				$this->_redirect('/notfound/' . $this->_request->getParam('slug'));
			}
		} else {
			$this->_redirect('/notfound/' . $this->_request->getParam('slug'));
		}
	}

	public function ajaxuploadcouponlogoAction() {
		$name = $_FILES['Filedata']['name'] = '1' . '_' . time() . str_ireplace(array(' ', '_', '-', ','), '', strtolower($_FILES['Filedata']['name']));
		$_FILES['Filedata']['type'] = 'image';
		$fileHandler = new FM_Components_Util_UploadHandler($_FILES['Filedata']);
		$folder = $fileHandler->setFolder('couponlogos');
		if($fileHandler->move()){
			list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $folder . '/' . $name);
			$insertData['fileName'] = $name;
			$insertData['width'] = $width;
			$insertData['height'] = $height;
			$insertData['type'] = 'LG';
			$insertData['active'] = '1';
			$insertData['orgId'] = $id;
			print $folder . '/' . $name; exit;
			FM_Components_Util_Logo::insert($insertData);
		}
	}

	public function ajaxuploadbannerlogoAction() {
		//print_r($_FILES);exit;
		$name = $_FILES['Filedata']['name'] = '1' . '_' . time() . str_ireplace(array(' ', '_', '-', ','), '', strtolower($_FILES['Filedata']['name']));
		$_FILES['Filedata']['type'] = 'image';
		$fileHandler = new FM_Components_Util_UploadHandler($_FILES['Filedata']);
		$folder = $fileHandler->setFolder('bannerlogos');
		if($fileHandler->move()){
			list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $folder . '/' . $name);
			//if($width > $_POST['width'] || $height > $_POST['height']) {
			//	print '0';
			//	exit;
			//}
			$insertData['fileName'] = $name;
			$insertData['width'] = $width;
			$insertData['height'] = $height;
			$insertData['type'] = 'LG';
			$insertData['active'] = '1';
			$insertData['orgId'] = $id;
			$returns = array('src'=>$folder . '/' . $name, 'height'=>$height, 'width'=>$width);
			$returns['templatewidth'] = $_POST['width'];
			$returns['templateheight'] = $_POST['height'];
			print Zend_Json::encode($returns);
			FM_Components_Util_Logo::insert($insertData);
			exit;
		}
	}

	public function ajaxuploadtopbannerAction() {
		//print_r($_FILES);exit;
		$name = $_FILES['Filedata']['name'] = '1' . '_' . time() . str_ireplace(array(' ', '_', '-', ','), '', strtolower($_FILES['Filedata']['name']));
		$_FILES['Filedata']['type'] = 'image';
		$fileHandler = new FM_Components_Util_UploadHandler($_FILES['Filedata']);
		$mediaFolder = ($_POST['type'] == 'icon') ? 'icons' : 'logos';
		$folder = $fileHandler->setFolder($mediaFolder);
		if($fileHandler->move()){
			list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $folder . '/' . $name);
			//if($width > $_POST['width'] || $height > $_POST['height']) {
			//	print '0';
			//	exit;
			//}
			$insertData['fileName'] = $name;
			$insertData['width'] = $width;
			$insertData['height'] = $height;
			$insertData['type'] = 'LG';
			$insertData['active'] = '1';
			//$insertData['orgId'] = $id;
			$returns = array('src'=>$folder . '/' . $name, 'height'=>$height, 'width'=>$width);
			print Zend_Json::encode($returns);

			if(($_POST['type'] == 'left')){
				$banner = new FM_Components_Util_Logo(array('orgId'=>$_POST['orgId']));
				if(trim($banner->getId()) != '') {
					FM_Components_Util_Logo::updateBanner(array('orgId'=>$_POST['orgId']), $insertData);
				} else {
					$insertData['orgId'] = $_POST['orgId'];
					FM_Components_Util_Logo::insert($insertData);
				}
			}
			if(($_POST['type'] == 'right')){
				$banner = new FM_Components_Util_MiniwebBanner(array('orgId'=>$_POST['orgId']));
				if(trim($banner->getId()) != '') {
					FM_Components_Util_MiniwebBanner::updateBanner(array('orgId'=>$_POST['orgId']), $insertData);
				} else {
					$insertData['orgId'] = $_POST['orgId'];
					FM_Components_Util_MiniwebBanner::insert($insertData);
				}
			}
			if(($_POST['type'] == 'icon')){
				$icon = new FM_Components_Util_Icon(array('orgId'=>$_POST['orgId']));
				//print_r($icon);
				if(trim($icon->getId()) != '') {
					FM_Components_Util_Icon::updateIcon(array('orgId'=>$_POST['orgId']), $insertData);
				} else {
					$insertData['orgId'] = $_POST['orgId'];
					FM_Components_Util_Icon::insert($insertData);
				}
			}
			//($_POST['type'] == 'left') ? FM_Components_Util_Logo::insert(array($insertData)) : FM_Components_Util_MiniwebBanner::insert(array($insertData));
			exit;
		}
	}

	public function ajaxuploadproductimageAction() {
		//print_r($_POST);exit;
		$id = $_POST['orgId'];
		$name = $_FILES['Filedata']['name'] = $id . '_' . time() . str_ireplace(array(' ', '_', '-', ','), '', strtolower($_FILES['Filedata']['name']));
		$_FILES['Filedata']['type'] = 'image';
		$fileHandler = new FM_Components_Util_UploadHandler($_FILES['Filedata']);
		$folder = $fileHandler->setFolder('productimages');
		if($fileHandler->move()){
			list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $folder . '/' . $name);
			if($width > $_POST['width'] || $height > $_POST['height']) {
				print '0';
				exit;
			}
			$insertData['fileName'] = $name;
			$insertData['width'] = $width;
			$insertData['height'] = $height;
			$insertData['type'] = 'LG';
			$insertData['active'] = '1';
			$insertData['orgId'] = $id;
			$returns = array('src'=>$folder . '/' . $name, 'height'=>$height, 'width'=>$width, 'name'=>$name);
			print Zend_Json::encode($returns);
			FM_Components_Util_ProductImage::insert($insertData);
			exit;
		}
	}

	public function ajaxuploadimagegalleryAction() {
		//print_r($_POST);exit;
		$id = $_POST['orgId'];
		$name = $_FILES['Filedata']['name'] = $id . '_' . time() . str_ireplace(array(' ', '_', '-', ','), '', strtolower($_FILES['Filedata']['name']));
		$_FILES['Filedata']['type'] = 'image';
		$fileHandler = new FM_Components_Util_UploadHandler($_FILES['Filedata']);
		$folder = $fileHandler->setFolder('photogallery');
		if($fileHandler->move()){
			list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $folder . '/' . $name);
			//if($width > $_POST['width'] || $height > $_POST['height']) {
			//print '0';
			//exit;
			//}
			$insertData['fileName'] = $name;
			$insertData['width'] = $width;
			$insertData['height'] = $height;
			$insertData['type'] = 'LG';
			$insertData['active'] = '1';
			$insertData['orgId'] = $id;
			$returns = array('src'=>$folder . '/' . $name, 'height'=>$height, 'width'=>$width, 'name'=>$name);
			print Zend_Json::encode($returns);
			//FM_Components_Widgets_PhotoGallery::addPhoto($insertData);
			exit;
		}
	}

	public function ajaxgetbannerconfigAction() {
		if($_POST) {
			$bannerId = $_POST['bannerId'];
			$bannerTemplate = new FM_Components_Util_BannerTemplate(array('id'=>$bannerId));
			print Zend_Json::encode($bannerTemplate->toArray());
			exit;
		}
	}

	public function ajaxaddproductAction() {
		if($_POST) {
			$result = FM_Components_Util_Product::insertProduct($_POST);
			print ($result) ? '1' : '0';
			exit;
		}
	}

	public function ajaxforumaddAction() {
		if($_POST) {
			$result = FM_Components_Util_ForumItem::insertForumItem($_POST);
			$_POST['date'] = date('m-d-Y H:i', time());
			if( $id = $result) {
				$org = new FM_Components_Organization(array('id'=>$_POST['orgId']));
				$admin = new FM_Components_Member(array('id'=>$org->getAdminId()));
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: Your Site @ 4Monmouth.com' . "\r\n" .
				'Reply-To: nobody@4monmouth.com' . "\r\n" .
				'X-Mailer: PHP/' . phpversion();
				mail($admin->getEmail(), 'A Comment Has Been Posted To your Forum @ 4Monmouth.com', FM_Components_EmailFormatter::forumComment($_POST, $org), $headers);
				//print $admin->getEmail();
			}
			$siteAdmin = false;
			if($this->_user && ($this->_user->inOrg($this->_request->getParam ( 'id' ))|| $this->_user->isRoot())){//user is admin for this group, show edit
				$siteAdmin = true;
			}
			$_POST['admin'] = $siteAdmin;
			$_POST['id'] = $id;
			print ($result) ? Zend_Json::encode($_POST) : '0';
			exit;
		}
	}

	public function ajaxforumdeleteAction() {
		if($_POST) {
			$result = FM_Components_Util_ForumItem::delete(array('id'=>$_POST['id']));
			print ($result) ? $_POST['id'] : '0';
			exit;
		}else{
			print '0';
			exit;
		}
	}

	public function ajaxupdatepwdAction() {
		if($_POST)	{
			$member = new FM_Components_Member(array('pwd'=>$_POST['old']));
			if($member && $member->getId()) {
				if(FM_Components_Member::update(array('id'=>$member->getId()), array('pwd'=>$_POST['newPwd']))) {
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					$headers .= 'From: Password Admin @ 4Monmouth.com' . "\r\n" .
					'Reply-To: nobody@4monmouth.com' . "\r\n" .
					'X-Mailer: PHP/' . phpversion();
					mail($member->getEmail(), 'Password Change @ 4Monmouth.com', FM_Components_EmailFormatter::updatePasswordNoOrg($_POST), $headers);
					print '1';
					exit;
				}
			}
			print '2';
			exit;
		}
		print '0';
		exit;
	}

	public function ajaxaddrelistingAction() {
		if($_POST) {
			$result = FM_Components_Util_RealEstateListing::insertRealEstateListing($_POST);
			print ($result) ? '1' : '0';
			exit;
		}
	}

	public function ajaxupdateprofileAction() {
		if($_POST['orgId'] && $_POST['ot']) {
			$result = false;
			switch ($_POST['ot']) {
				case 2:
					$result = FM_Components_Business::updateBusiness($_POST);
					break;
				case 3:
					$result = FM_Components_NonProfit::update($_POST);
					break;
				case 4:
					$result = FM_Components_Sports::update($_POST);
					break;
			}

			print ($result) ? '1' : '0';
			exit;
		}
	}

	public function ajaxupdateorgdataAction() {
		if($_POST['orgId']) {
			$result = FM_Components_Organization::update($_POST);
			print ($result) ? '1' : '0';
			exit;
		}
	}

	public function ajaxdeleteproductAction() {
		if($_POST['orgId'] && $_POST['id']) {
			$result = false;
			$result = FM_Components_Util_Product::deleteProduct(array('orgId'=>$_POST['orgId'], 'id'=>$_POST['id']));
			print ($result) ? '1' : '0';
			exit;
		}
	}

	public function ajaxdeletelistingAction() {
		if($_POST['orgId'] && $_POST['id']) {
			$result = false;
			$result = FM_Components_Util_RealEstateListing::deleteRealEstateListing(array('orgId'=>$_POST['orgId'], 'id'=>$_POST['id']));
			print ($result) ? '1' : '0';
			exit;
		}
	}

	public function bannerpreviewAction() {
		$id = $this->_request->getParam ( 'bannerid' );
		$banner = new FM_Components_Banner(array('id'=>$id));
		$this->view->banners = array($banner);
		$this->_helper->layout->setLayout('cssonly');
	}

	public function couponpreviewAction() {
		$id = $this->_request->getParam ( 'couponid' );
		$banner = new FM_Components_Coupon(array('id'=>$id));
		//print_r($banner);exit;
		$this->view->coupons = array($banner);
		$this->_helper->layout->setLayout('cssonly');
	}

	public function sandboxAction()
	{
		Zend_Loader::loadClass('FM_Forms_Events');
		$form = new FM_Forms_Events(array(), array());
		$this->view->form = $form;
	}

}