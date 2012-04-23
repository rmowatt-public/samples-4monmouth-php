<?php
Zend_Loader::loadClass('FM_Controllers_BaseController');
Zend_Loader::loadClass('FM_Forms_ContactUs');
Zend_Loader::loadClass('FM_Forms_Feedback');


Zend_Loader::loadClass('FM_Components_Banner');
Zend_Loader::loadClass('FM_Components_Coupon');
Zend_Loader::loadClass('FM_Components_Organization');
Zend_Loader::loadClass ('FM_Components_Email');
Zend_Loader::loadClass('FM_Components_Util_Faq');
Zend_Loader::loadClass('FM_Components_Util_Legal');
Zend_Loader::loadClass('FM_Components_Util_PrivacyPolicy');
Zend_Loader::loadClass('FM_Components_Util_AboutUs');
Zend_Loader::loadClass('FM_Components_Util_ForUsers');
Zend_Loader::loadClass('FM_Components_Util_ForOrgs');
Zend_Loader::loadClass ('FM_Components_Util_Testimonial');
Zend_Loader::loadClass ('FM_Components_Util_MissionStatement');
Zend_Loader::loadClass ('FM_Components_Util_ForAdvertisers');
Zend_Loader::loadClass ('FM_Components_Util_Feedback');
Zend_Loader::loadClass('FM_Components_Calendar');
Zend_Loader::loadClass('FM_Components_Member');
Zend_Loader::loadClass('FM_Components_Calendar_Month');
Zend_Loader::loadClass('FM_Components_Events');
Zend_Loader::loadClass('FM_Components_Widgets_PhotoGallery');
Zend_Loader::loadClass('FM_Components_Events');
Zend_Loader::loadClass('FM_Models_FM_OrgTabs');
Zend_Loader::loadClass('FM_Components_Util_Affiliates');
Zend_Loader::loadClass('FM_Components_EmailFormatter');
Zend_Loader::loadClass('FM_Components_SportsUser');
Zend_Loader::loadClass('FM_Components_Util_OurServices');
Zend_Loader::loadClass('FM_Components_Util_FaqPage');
Zend_Loader::loadClass('FM_Components_Util_FeedbackPage');
Zend_Loader::loadClass('FM_Components_Util_HelpPopup');
Zend_Loader::loadClass('FM_Components_Util_SiteEmail');
Zend_Loader::loadClass('FM_Components_Widgets_VideoGallery');
Zend_Loader::loadClass('FM_Components_Util_VideoAlbum');
Zend_Loader::loadClass('FM_Components_Util_Video');
Zend_Loader::loadClass('FM_Forms_LimeCard');
Zend_Loader::loadClass('FM_Models_FM_SearchPrimaryCategories');
Zend_Loader::loadClass('FM_Models_FM_SearchPrimaryCategoriesOrgs');
Zend_Loader::loadClass('FM_Models_FM_Orgdata');
Zend_Loader::loadClass('FM_Components_Util_LimeCard');
Zend_Loader::loadClass('FM_Components_Util_Category');
Zend_Loader::loadClass('FM_Forms_LimeCardRegion');
Zend_Loader::loadClass('FM_Components_Util_Region');

class UtilitiesController extends FM_Controllers_BaseController{

	public function init() {
		parent::init();
		if(!stristr($_SERVER['REQUEST_URI'], 'ajax')) {
			$banners = FM_Components_Banner::getSortedRandomBanners(array());
			$this->view->layout()->banners = $this->view->partial('banner/bannerleftindex.phtml',
			array('banners'=>$banners));

			$this->view->layout()->astrology = $this->view->partial('widgets/horoscope/full.phtml',
			array('signs'=>FM_Components_RSS_Horoscope::getAll()));

			$spotlight = FM_Components_Organization::getRandom(6, false, array(), true);
			$this->view->layout()->spotlight = $this->view->partial('organization/spotlight.phtml',
			array('organizations'=>$spotlight));

			$featuredOrganization = FM_Components_Organization::getRandom(1, true, array(8));
			$this->view->layout()->featuredOrganization = $this->view->partial('organization/indexfeatured.phtml',
			array('organization'=>$featuredOrganization[1]));
		}
	}

	public function faqsAction() {
		//print_r(FM_Components_Util_Faq::getFaqs(array('orgId'=>'0')));exit;
		$page = new FM_Components_Util_FaqPage(array('active'=>1));
		$this->view->layout()->header = $this->view->partial('headers/mission.phtml', array('sel'=>1, 'header'=>$page->getCarousel()));
		$this->view->headline = $page->getTitle();
		$this->view->headerImg = $page->getHeader();
		$this->view->faqs = $this->view->partial('utilities/parts/faq.phtml',
		array('faqs'=>FM_Components_Util_Faq::getFaqs(array('orgId'=>$this->_request->getParam('orgId')))));
	}

	public function noorgAction() {
		$orgs = FM_Components_Organization::findSlugsLike($this->_request->getParam('slug'));
		$this->view->orgs = $orgs;
	}

	public function limecardAction() {

		$this->view->form = new FM_Forms_LimeCard();
		$this->view->regionsForm = new FM_Forms_LimeCardRegion();
		$this->view->results = false;
		if($this->_getParam('var') === '0') {
			$this->view->searchTerm = 'ALL';
			$orgs =  FM_Components_Util_LimeCard::alphaSearch('');
			//print_r($orgs);
			$this->view->selected = '0';
			$this->view->results = $final = FM_Components_Util_LimeCard::sort($orgs, 'name');
		}
		elseif($this->_getParam('do') == 'alph'){
			$this->view->searchTerm = $this->_getParam('var');
			$orgs =  FM_Components_Util_LimeCard::alphaSearch($this->_getParam('var'));
			$this->view->selected = $this->_getParam('var');
			$this->view->results = $final = FM_Components_Util_LimeCard::sort($orgs, 'name');
		}
		elseif($this->_getParam('do') == 'cat'){
			$orgs =  FM_Components_Util_LimeCard::catSearch($this->_getParam('var'));
			$this->view->results = $final = FM_Components_Util_LimeCard::sort($orgs, 'name');
			$this->view->searchTerm = ucwords(strtolower(FM_Components_Util_Category::getCategoryName($this->_getParam('var'), false)));
		}
		elseif($this->_getParam('do') == 'region'){
			$orgs =  FM_Components_Util_LimeCard::regionSearch($this->_getParam('var'));
			$this->view->results = $final = FM_Components_Util_LimeCard::sort($orgs, 'name');
			$region = new FM_Components_Util_Region(array('id'=>$this->_getParam('var')));
			$this->view->searchTerm = ucwords(strtolower($region->getName()));
			if ($this->_getParam('var') == 25) {
				$this->view->searchTerm = 'Staten Island';
			}
			if ($this->_getParam('var') == 26) {
				$this->view->searchTerm = 'Ocean County';
			}
		}
		else{
			if($_POST['limecardsearch']) {
				$this->view->searchTerm = $_POST['limecardsearch'];
				$bzOrgsModel = new FM_Models_FM_SearchPrimaryCategories();
				$npOrgsModel = new FM_Models_FM_SearchPrimaryCategoriesOrgs();

				$bzResults = $bzOrgsModel->searchByCat($_POST['limecardsearch']);
				$npResults = $npOrgsModel->searchByCat($_POST['limecardsearch']);

				$orgIds = array();
				foreach(array_merge($bzResults, $npResults) as $key=>$value) {
					$orgIds[] = $value['orgId'];
				}
				$orgDataModel = new FM_Models_FM_Orgdata();
				$orgs = $orgDataModel->limecardSearch(array_values($orgIds), $_POST['limecardsearch']);
				$orgDataModel->alphabeticalSearch();
				$nonOrgs = FM_Components_Util_LimeCard::search($_POST['limecardsearch']);
				$this->view->results = $final = FM_Components_Util_LimeCard::sort(array_merge($orgs, $nonOrgs), 'name');
			}
		}

	}

	public function contactusAction() {
		$contactUsForm = new FM_Forms_ContactUs();
		if ($this->_request->isPost ()) {
			$formData = $this->_request->getPost();
			if ($contactUsForm ->isValid ( $formData )) {
				if($id = FM_Components_Email::insertEmail($formData) && FM_Components_Util_SiteEmail::send($formData)) {
					$contactUsForm->reset();
					$this->view->message = 'Your email has been sent. You should expect a response within 24 - 48 hours.';
				} else{
					$this->view->message = 'Your email failed to send. Please try again';

				}
			} else {
				$this->view->message = 'Please complete the fields below and try again';
			}

		}
		$this->view->form = $contactUsForm;
	}

	public function helppopupAction() {
		if($code = $this->_request->getParam('code')) {
			$this->view->popup = $popup = new FM_Components_Util_HelpPopup(array('code'=>$code));
		}
		$this->_helper->layout->setLayout('cssonly');
	}

	public function ajaxhelppopupAction() {
		if($code = $_POST['code']) {
			$popup = new FM_Components_Util_HelpPopup(array('code'=>$code));
			print $popup->getText();
			exit;
		}
		print '0';
		exit;

	}

	public function feedbackAction() {
		$this->view->form = $form = new FM_Forms_Feedback();
		$page = new FM_Components_Util_FeedbackPage(array('active'=>1));
		$this->view->layout()->header = $this->view->partial('headers/mission.phtml', array('sel'=>1, 'header'=>$page->getCarousel()));
		$this->view->headline = $page->getTitle();
		$this->view->headerImg = $page->getHeader();
		if ($this->_request->isPost ()) {
			$formData = $this->_request->getPost();
			if ($form->isValid ( $formData )) {
				if($id = FM_Components_Util_Feedback::insertFeedback($formData)) {
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					$headers .= 'From:feedback@4Monmouth.com' . "\r\n" .
					'Reply-To: nobody@4monmouth.com' . "\r\n" .
					'X-Mailer: PHP/' . phpversion();
					mail('admin@4monmouth.com', 'You recieved feedback', FM_Components_EmailFormatter::feedback($_POST), $headers);
					mail($_POST['email'], 'Your feedback has been submitted', FM_Components_EmailFormatter::feedback($_POST), $headers);
					$form->reset();
					$this->view->message = 'Thank you! Your feedback has been submitted.';
				} else{
					$this->view->message = 'Your email failed to send. Please try again';

				}
			} else {
				$this->view->message = 'Please complete the fields below and try again';
			}

		}

		$this->view->feedback = $allFeedback = FM_Components_Util_Feedback::getAll(true);
	}

	public function legalAction() {
		//$banners = FM_Components_Banner::getRandomBanners();
		//$this->view->layout()->banners = $this->view->partial('banner/bannerleftindex.phtml',
		//array('banners'=>$banners));
		$statement = new FM_Components_Util_Legal(array('active'=>'1'));
		$this->view->layout()->header = $this->view->partial('headers/mission.phtml', array('sel'=>1, 'header'=>$statement->getCarousel()));
		$this->view->headline = $statement->getTitle();
		$this->view->statement = htmlspecialchars_decode($statement->getStatement());
		$this->view->headerImg = $statement->getHeader();
	}

	public function missionstatementAction() {
		$statement = new FM_Components_Util_MissionStatement(array('active'=>'1'));
		$this->view->layout()->header = $this->view->partial('headers/mission.phtml', array('sel'=>1, 'header'=>$statement->getCarousel()));
		$this->view->headline = $statement->getTitle();
		$this->view->statement = htmlspecialchars_decode($statement->getStatement());
		$this->view->headerImg = $statement->getHeader();
	}

	function aboutusAction() {

		$statement = new FM_Components_Util_AboutUs(array('active'=>'1'));
		$this->view->layout()->header = $this->view->partial('headers/mission.phtml', array('sel'=>1, 'header'=>$statement->getCarousel()));
		$this->view->headline = $statement->getTitle();
		$this->view->statement = htmlspecialchars_decode($statement->getStatement());
		$this->view->headerImg = $statement->getHeader();
	}

	function affiliatesAction() {
		$statement = new FM_Components_Util_Affiliates(array('active'=>'1'));
		$this->view->layout()->header = $this->view->partial('headers/mission.phtml', array('sel'=>1, 'header'=>$statement->getCarousel()));
		//$this->view->headline = $statement->getTitle();
		$this->view->content = htmlspecialchars_decode($statement->getContent());
		$this->view->headerImg = $statement->getHeader();
	}

	function privacypolicyAction() {
		$statement = new FM_Components_Util_PrivacyPolicy(array('active'=>'1'));
		$this->view->layout()->header = $this->view->partial('headers/mission.phtml', array('sel'=>1, 'header'=>$statement->getCarousel()));
		$this->view->headline = $statement->getTitle();
		$this->view->statement = htmlspecialchars_decode($statement->getStatement());
		$this->view->headerImg = $statement->getHeader();
	}

	function forusersAction() {
		$statement = new FM_Components_Util_ForUsers(array('active'=>'1'));
		$this->view->layout()->header = $this->view->partial('headers/mission.phtml', array('sel'=>2, 'header'=>$statement->getCarousel()));
		$this->view->headline = $statement->getTitle();
		$this->view->statement = htmlspecialchars_decode($statement->getStatement());
		$this->view->headerImg = $statement->getHeader();
	}

	function ourservicesAction() {
		$statement = new FM_Components_Util_OurServices(array('active'=>'1'));
		$this->view->layout()->header = $this->view->partial('headers/mission.phtml', array('sel'=>2, 'header'=>$statement->getCarousel()));
		$this->view->headline = $statement->getTitle();
		$this->view->statement = htmlspecialchars_decode($statement->getStatement());
		$this->view->headerImg = $statement->getHeader();
	}

	function fororganizationsAction() {
		$statement = new FM_Components_Util_ForOrgs(array('active'=>'1'));
		$this->view->layout()->header = $this->view->partial('headers/mission.phtml', array('sel'=>3, 'header'=>$statement->getCarousel()));
		$this->view->headline = $statement->getTitle();
		$this->view->statement = htmlspecialchars_decode($statement->getStatement());
		$this->view->headerImg = $statement->getHeader();
	}

	function foradvertisersAction() {
		$statement = new FM_Components_Util_ForAdvertisers(array('active'=>'1'));
		$this->view->layout()->header = $this->view->partial('headers/mission.phtml', array('sel'=>4, 'header'=>$statement->getCarousel()));
		$this->view->headline = $statement->getTitle();
		$this->view->statement = htmlspecialchars_decode($statement->getStatement());
		$this->view->headerImg = $statement->getHeader();
	}

	public function ajaxcontactusAction() {
		if($_POST) {
			if($id = FM_Components_Email::insertEmail($_POST)) {
				$org = new FM_Components_Organization(array('id'=>$_POST['orgId']));
				$user = new FM_Components_Member(array('id'=>$org->getAdminId()));
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From:' . $org->getName() . '  @ 4Monmouth.com' . "\r\n" .
				'Reply-To: nobody@4monmouth.com' . "\r\n" .
				'X-Mailer: PHP/' . phpversion();
				//print $user->getEmail();
				mail($user->getEmail(), 'You recieved an email from your 4monmouth miniweb', FM_Components_EmailFormatter::contactUs($_POST), $headers);
				mail($_POST['email'], 'Your question has been submitted', FM_Components_EmailFormatter::contactUs($_POST), $headers);
				print 'Your email has been sent. You should expect a response within 24 - 48 hours.';
			} else{
				print'Your email failed to send. Please try again';
			}
		}
		exit;

	}

	public function ajaxgetbannerinfoAction() {
		if($_POST) {
			if($banner = new FM_Components_Banner(array('id'=>$_POST['id']))) {
				print Zend_Json::encode($banner->toArray());
				exit;
			} else {
				print '0';
				exit;
			}
		}
	}

	public function ajaxgetcouponinfoAction() {
		if($_POST) {
			if($coupon = new FM_Components_Coupon(array('id'=>$_POST['id']))) {
				print Zend_Json::encode($coupon->toArray());
				exit;
			} else {
				print '0';
				exit;
			}
		}
	}

	public function populateorgtabsAction() {
		$i = 987;
		$ot = new FM_Models_FM_OrgTabs();
		while($i < 986) {
			$ot->insertRecord(array('orgId'=>$i));
			$i++;
		}
	}

	public function ajaxgetcalendarAction() {
		$c = new FM_Components_Calendar_Month($_POST['month'], $_POST['year'], $_POST['orgId']);
		$nextMonth = ($_POST['month'] >= 12) ? 0 : $_POST['month'];
		$nextYear = ($_POST['month'] >= 12) ? $_POST['year'] + 1 : $_POST['year'];
		$nextMonth++;

		$prevMonth = ($_POST['month'] <= 1) ? 13 : $_POST['month'];
		$prevYear = ($_POST['month'] <= 1) ? $_POST['year'] - 1 : $_POST['year'];
		$prevMonth--;

		print $this->view->partial(
		'tabs/ajaxcalendar.phtml',
		array('id' => 'cal', 'events' => $c->getDaysWithEvents(), 'obj'=>$c, 'nextMonth'=>$nextMonth, 'nextYear'=>$nextYear, 'previousMonth'=>$prevMonth, 'previousYear'=>$prevYear)
		);
		exit;
	}

	public function ajaxdeleteeventAction() {
		if(FM_Components_Events::deleteEvent($_POST['id'])) {
			print '1';
			exit;
		}
		print '0';
		exit;
	}

	public function ajaxcheckslugAction() {
		print  (($s = FM_Components_Organization::findBySlug($_POST['slug'])) && ($s['id'] != $_POST['orgId'])) ? '0' : '1';
		//print_r($s);
		exit;
	}

	public function vieweventAction() {
		//$this->_helper->layout->setLayout('utilities/event');
		$this->view->event = $event = new FM_Components_Events(array('eventId'=>$this->_getParam('id')));
		$this->view->layout()->event = $event;
	}

	public function ajaxaddtestimonialAction() {
		if($_POST) {
			if($_POST['edit'] > 0) {
				if(FM_Components_Util_Testimonial::editTestimonial(array('id'=>$_POST['edit']), $_POST)) {
					print '1';

				} else {
					print '0';
				}
			} else {
				if(FM_Components_Util_Testimonial::insertTestimonial($_POST)) {
					$org = new FM_Components_Organization(array('id'=>$_POST['orgId']));
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					$headers .= 'From:' . $org->getName() . '  @ 4Monmouth.com' . "\r\n" .
					'Reply-To: nobody@4monmouth.com' . "\r\n" .
					'X-Mailer: PHP/' . phpversion();
					mail($_POST['from'], 'Your review has been submitted' , FM_Components_EmailFormatter::reviewEmail($_POST), $headers);
					mail($org->getEmail(), 'A review has been submitted' , FM_Components_EmailFormatter::reviewEmailAdmin($_POST), $headers);
					print '1';

				} else {
					print '0';
				}
			}
		} else {
			print '0';
		}
		exit;
	}


	public function ajaxdeletetestimonialAction() {
		if($_POST) {
			if(FM_Components_Util_Testimonial::deleteTestimonial($_POST)) {
				print $_POST['id'];

			} else {
				print '0';
			}

		} else {
			print '0';
		}
		exit;
	}

	public function ajaxedittestimonialAction() {
		if($_POST) {
			if($t = new FM_Components_Util_Testimonial(array('id'=>$_POST['id']))) {
				$rv = array();
				$rv['from'] = $t->getFrom();
				$rv['name'] = $t->getName();
				$rv['testimonial'] = $t->getTestimonial();
				$rv['id'] = $t->getId();
				print Zend_Json::encode($rv);

			} else {
				print '0';
			}

		} else {
			print '0';
		}
		exit;
	}

	public function ajaxaddcouponAction() {
		if($this->_user && ($this->_user->getOrgId() || $this->_user->isRoot())){//user is admin for this group, show edit
			if($_POST['sponsor']) {
				if($_POST['cid'] != 0) {
					$id = $_POST['cid'];
					unset($_POST['cid']);
					if(FM_Components_Coupon::updateCoupon(array('id'=>$id), $_POST))  {
						$cp = new FM_Components_Coupon(array('id'=>$id));
						print Zend_Json::encode($cp->toArray());
					} else{
						print '0';
					}
				}
				else {
					if($id = FM_Components_Coupon::insertCoupon($_POST)) {
						$cp = new FM_Components_Coupon(array('id'=>$id));
						print Zend_Json::encode($cp->toArray());
					} else{
						print '0';
					}
				}
			}

		}		exit;
	}

	public function ajaxtogglecouponAction() {
		if($this->_user && ($this->_user->getOrgId() || $this->_user->isRoot())){//user is admin for this group, show edit
			if($_POST['id']) {
				if($id = FM_Components_Coupon::updateCoupon(array('id'=>$_POST['id']), array('active'=>$_POST['active']))) {
					print '1';
				} else{
					print '0';
				}
			}
		}else {
			print '0';
		}
		exit;
	}

	public function ajaxtoggleb2bcouponAction() {
		if($this->_user && ($this->_user->getOrgId() || $this->_user->isRoot())){//user is admin for this group, show edit
			if($_POST['id']) {
				if($id = FM_Components_Coupon::updateCoupon(array('id'=>$_POST['id']), array('b2b'=>$_POST['b2b']))) {
					print '1';
				} else{
					print '0';
				}
			}
		}else {
			print '0';
		}
		exit;
	}

	public function ajaxtogglebannerAction() {
		if($this->_user && ($this->_user->getOrgId() || $this->_user->isRoot())){//user is admin for this group, show edit
			if($_POST['id']) {
				if($id = FM_Components_Banner::updateBanner(array('id'=>$_POST['id']), array('active'=>$_POST['active']))) {
					print '1';
				} else{
					print '0';
				}
			}
		}else {
			print '0';
		}
		exit;
	}

	public function ajaxtogglereviewAction() {
		if($this->_user && ($this->_user->getOrgId() || $this->_user->isRoot())){//user is admin for this group, show edit
			if($_POST['id']) {
				if($id = FM_Components_Util_Testimonial::editTestimonial(array('id'=>$_POST['id']), array('active'=>$_POST['active']))) {
					print '1';
				} else{
					print '0';
				}
			}
		}else {
			print '0';
		}
		exit;
	}

	public function ajaxaddphotoinfoAction() {
		if($this->_user &&($this->_user->getOrgId() || $this->_user->isRoot())){//user is admin for this group, show edit
			if($_POST['orgId']) {
				if($id = FM_Components_Widgets_PhotoGallery::addPhoto($_POST)) {
					$albums = FM_Components_Util_PhotoAlbum::getActive($_POST['orgId']);
					foreach ($albums as $key=>$value) {
						$ajson = Zend_Json::encode($value->getImages());
						$album_array[$value->getId()] =$value->getImages();
					}
					print Zend_Json::encode($album_array);
				} else{
					print '0';
				}
			}
		}
		exit;
	}

	public function ajaxaddvideoinfoAction() {
		if($this->_user &&($this->_user->getOrgId() || $this->_user->isRoot())){//user is admin for this group, show edit
			if($_POST['orgId']) {
				if($id = FM_Components_Widgets_VideoGallery::addVideo($_POST)) {
					$match = explode('v=', $_POST['video']);
					$match = explode('&', $match[1]);
					$video = $match[0];
					print Zend_Json::encode(array('id'=>$id, 'video'=>$video, 'description'=>$_POST['description'], 'videoAlbum'=>$_POST['videoAlbum']));

				} else{
					print '0';
				}
			}
		}
		exit;
	}

	public function ajaxaddphotoalbumAction() {
		if($this->_user &&($this->_user->getOrgId() || $this->_user->isRoot())){//user is admin for this group, show edit
			if($_POST['orgId']) {
				$_POST['name'] = $_POST['albumName'];
				if($id = FM_Components_Util_PhotoAlbum::insert($_POST)) {
					print $id;
				} else{
					print '0';
				}
			}
		}
		exit;
	}

	public function ajaxaddvideoalbumAction() {
		if($this->_user &&($this->_user->getOrgId() || $this->_user->isRoot())){//user is admin for this group, show edit
			if($_POST['orgId']) {
				$_POST['name'] = $_POST['albumName'];
				if($id = FM_Components_Util_VideoAlbum::insert($_POST)) {
					$_POST['id'] = $id;
					print Zend_Json::encode($_POST);
				} else{
					print '0';
				}
			}
		}
		exit;
	}

	public function ajaxdeletevideoAction() {
		if($this->_user &&($this->_user->getOrgId() || $this->_user->isRoot())){//user is admin for this group, show edit
			if($_POST['orgId']) {
				if($id = FM_Components_Util_Video::deleteVideo($_POST)) {
					print Zend_Json::encode($_POST);
				} else{
					print '0';
				}
			}
		}
		exit;
	}

	public function ajaxdeletecouponAction() {
		if($_POST && array_key_exists('couponId', $_POST) && $_POST['couponId'] != '') {
			if(FM_Components_Coupon::deleteCoupon(array('id'=>$_POST['couponId'], 'orgId'=>$_POST['orgId']))) {
				print $_POST['couponId'];
			} else {
				print '0';
			}
		} else {
			print '0';
		}
		exit;
	}

	public function ajaxaddbannerAction() {
		if($this->_user && ($this->_user->getOrgId() || $this->_user->isRoot())){//user is admin for this group, show edit
			if($_POST['name']) {
				if($_POST['id'] == ''){
					$_POST['path'] = '/media/image/bannerlogos';
					if($id = FM_Components_Banner::insertBanner($_POST)) {
						$banner = new FM_Components_Banner(array('id'=>$id));
						print Zend_Json::encode($banner->toArray());
					} else{
						print '0';
					}
				} else {
					$id = $_POST['id'];
					if(FM_Components_Banner::updateBanner(array('id'=>$id), $_POST)) {
						$banner = new FM_Components_Banner(array('id'=>$id));
						print Zend_Json::encode($banner->toArray());
					}
					else {
						unset($_POST['oid']);
						$_POST['id'] = $id;
						if(($banner = new FM_Components_Banner($_POST)) && $banner->getId()) {
							print Zend_Json::encode($banner->toArray());
						} else {
							print '0';
						}
					}
				}
			}
		}
		else {
			print '0';
		}
		exit;
	}

	public function ajaxdeletebannerAction() {
		if($_POST && array_key_exists('bannerId', $_POST) && $_POST['bannerId'] != '') {
			if(FM_Components_Banner::deleteBanner(array('id'=>$_POST['bannerId'], 'orgId'=>$_POST['orgId']))) {
				print $_POST['bannerId'];
			} else {
				print '0';
			}
		} else {
			print '0';
		}
		exit;
	}

	public function ajaxaddeventAction() {
		if($this->_user && ($this->_user->getOrgId() || $this->_user->isRoot())){//user is admin for this group, show edit
			if($_POST['datetag']) {
				$_POST['starttime'] = $_POST['stimeh']. ':' . $_POST['stimem'];
				$_POST['endtime'] = $_POST['etimeh']. ':' . $_POST['etimem'];
				$_POST['id'] = $_POST['ceid'];
				$_POST['frontPage'] = (array_key_exists('frontPage', $_POST)) ? 1 : 0;
				if($id = FM_Components_Events::saveNewEvent($_POST)) {
					$c = new FM_Components_Events(array('eventId'=>$id));
					$rv = $c->toArray();
					$rv['time'] = $c->getFormattedTime();
					if(FM_Components_Organization::getOrgType($_POST['orgId']) == 4) {
						$sport = new FM_Components_Organization(array('id'=>$_POST['orgId']));
						$users = FM_Components_SportsUser::getAll($_POST['orgId']);
						foreach ($users as $index=>$user) {
							$headers  = 'MIME-Version: 1.0' . "\r\n";
							$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
							$headers .= 'From: '. $sport->getName() . ' administrator @ 4Monmouth.com' . "\r\n" .
							'Reply-To: nobody@4monmouth.com' . "\r\n" .
							'X-Mailer: PHP/' . phpversion();
							$ad = FM_Components_Util_TextAd::getRandom(6);
							$content = $_POST['content'] . "\n" . $ad;
							mail($user->getEmail(), 'A New Event Has Been Posted' , FM_Components_EmailFormatter::sendSportsEvent($_POST, $c->getFormattedTime(), $sport),  $headers);
						}
					}
					$rv['date'] = date('m-d-Y', strtotime($_POST['datetag']));
					print Zend_Json::encode($rv);
					exit;
				} else{
					print '0';
				}
			}
		}
		exit;
	}

	public function sitemapAction() {

	}

	public function printcouponsAction() {
		$this->_helper->layout->setLayout('cssonly');
		if($_GET['coupons']) {
			$coupons = explode('_', $_GET['coupons']);
			$c = FM_Components_Coupon::getSelectedCoupons($coupons);
			$this->view->coupons = $c;
		}
	}

	public function ajaxremovemediaAction() {
		if($_POST && array_key_exists('id', $_POST)) {
			if(FM_Components_Widgets_PhotoGallery::removeMedia($_POST['id'])){
				$albums = FM_Components_Util_PhotoAlbum::getActive($_POST['orgId']);
				foreach ($albums as $key=>$value) {
					$ajson = Zend_Json::encode($value->getImages());
					$album_array[$value->getId()] =$value->getImages();
				}
				print Zend_Json::encode($album_array);
				exit;
			}
			print '0';
			exit;
		}
		print '0';
		exit;
	}

	public function displayphotosAction() {
		$this->_helper->layout->setLayout('cssonly');
		$this->view->photo = $photog = FM_Components_Widgets_PhotoGallery::getPhoto($this->_getParam('picId'));
		//print_r($this->_getParam('orgId'),);exit;
		$this->view->orgId = $this->_getParam('orgId');
		$pg = new FM_Components_Widgets_PhotoGallery($this->view, $this->_getParam('orgId'), 0, false);
		$photos = array();
		$prevId = 0;
		//print_r($pg);exit;
		foreach ($pg->getIndexedPhotos() as $index=>$photo) {
			if($photo['photoAlbum'] == $photog['photoAlbum']) {
				if($prevId != 0) {
					$photo['prev'] = 'p_' . $prevId;
				}
				if($prevId != 0) {
					$photos['p_' . $prevId]['next'] = 'p_' . $photo['id'];
				}
				$photos[$index] = $photo;
				$prevId = $photo['id'];
			}
		}
		$photoindexes = $photos;
		$start = array_shift($photoindexes);
		$last = array_pop($photoindexes);
		$this->view->first = ($start['id'] == $photog['id']);
		$this->view->last = ($last['id'] == $photog['id']);
		//print_r(array_pop($photos));exit;
		$this->view->pg = Zend_Json::encode($photos);
	}

	public function ajaxdeletephotoalbumAction() {
		if(array_key_exists('id', $_POST)) {
			FM_Components_Util_PhotoAlbum::deleteAlbum($_POST['id']);
			$albums = FM_Components_Util_PhotoAlbum::getActive($_POST['orgId']);
			foreach ($albums as $key=>$value) {
				$ajson = Zend_Json::encode($value->getImages());
				$album_array[$value->getId()] =$value->getImages();
			}
			print Zend_Json::encode($album_array);
			exit;
		}
	}

	public function ajaxdeletevideoalbumAction() {
		if(array_key_exists('id', $_POST)) {
			FM_Components_Util_VideoAlbum::deleteAlbum($_POST['id']);
			print Zend_Json::encode($_POST);
			exit;
		}
	}

	public function ajaxgeteventAction() {
		$e = FM_Components_Calendar_Month::getEventAsArray($_POST['id']);
		print count($e) ? Zend_Json::encode($e) : '0';
		exit;
	}

	public function ajaxusealbumAction() {
		print FM_Components_OrgConfig::updateConfig('options', $_POST['orgId'], $_POST);
		exit;
	}
}












