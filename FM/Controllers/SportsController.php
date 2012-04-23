<?php
Zend_Loader::loadClass ('FM_Controllers_BaseController');
Zend_Loader::loadClass ('FM_Components_Sports');
Zend_Loader::loadClass ('FM_Forms_Login');
Zend_Loader::loadClass('FM_Components_Widgets_AskUs');
Zend_Loader::loadClass('FM_Components_Widgets_Profile');
Zend_Loader::loadClass('FM_Components_Widgets_Testimonials');
Zend_Loader::loadClass('FM_Components_Tabs_TabGroup');
Zend_Loader::loadClass('FM_Components_Tabs_Profile');
Zend_Loader::loadClass('FM_Components_Tabs_Directions');
Zend_Loader::loadClass('FM_Components_Tabs_Admin');
Zend_Loader::loadClass('FM_Components_Widgets_Admin');
Zend_Loader::loadClass('FM_Components_Widgets_PhotoGallery');
Zend_Loader::loadClass('FM_Components_Banner');
Zend_Loader::loadClass('FM_Components_Coupon');
Zend_Loader::loadClass('FM_Components_OrgConfig');
Zend_Loader::loadClass('FM_Components_Tabs_Contact');
Zend_Loader::loadClass('FM_Components_Tabs_SportsSignIn');
Zend_Loader::loadClass('FM_Components_Tabs_Testimonials');
Zend_Loader::loadClass('FM_Components_Tabs_Media');
Zend_Loader::loadClass('FM_Components_Tabs_Calendar');
Zend_Loader::loadClass('FM_Components_SportsUser');
Zend_Loader::loadClass('FM_Components_Member');
Zend_Loader::loadClass('FM_Components_Util_Email');
Zend_Loader::loadClass('FM_Components_Tabs_Forum');
Zend_Loader::loadClass('FM_Components_Util_TextAd');
Zend_Loader::loadClass('FM_Components_EmailFormatter');
Zend_Loader::loadClass('FM_Components_Tabs_SportsSchedule');
Zend_Loader::loadClass('FM_Components_Widgets_VideoGallery');


class SportsController extends FM_Controllers_BaseController{

	function indexAction() {

		$this->_helper->layout->setLayout('layout2col');
		$this->view->layout()->cols = 2;
		if ($this->_request->getParam ( 'id' ) == 0 || !$this->_business = new FM_Components_Sports( array ('id' => $this->_request->getParam ( 'id' ) ) )) {
			$this->_redirect ( '/' );
		}
		//print_r($this->_business);exit;
		if(!$this->_business->getId()){
			$this->_redirect ( '/' );
		}
		if($this->_business->getType() == 2) {
			$this->_redirect ( '/merchant/' . $this->_request->getParam('id') );
		}
		if($this->_business->getType() == 3) {
			$this->_redirect ( '/org/' . $this->_request->getParam('id') );
		}
		if(!$this->_user && !$this->_business->getActive() ) {
			$this->_redirect ( '/' );
		}

		if(!$this->_business->getActive() &&  !($this->_user->isRoot() || $this->_user->frontEndAdmin())) {
			$this->_redirect ( '/' );
		}


		$this->view->orgId = $this->_request->getParam ( 'id' );

		$myNamespace = new Zend_Session_Namespace('sportsuser');
		$sportsuser = false;
		if(isset($myNamespace->auth) && $myNamespace->auth == true) {
			//print_r(unserialize($myNamespace->auth));exit;
			$sportsuser = unserialize($myNamespace->auth);
			$this->view->sportsuser =  unserialize($myNamespace->auth);
		}

		$orgConfig = $this->_business->getOrgConfig();
		$common = $orgConfig->getTabs();

		$siteAdmin = false;
		if($this->_user && ($this->_user->inOrg($this->_request->getParam ( 'id' ))|| $this->_user->isRoot())){//user is admin for this group, show edit
			$siteAdmin = $this->_user;
		}

		$banners = FM_Components_Banner::getSortedRandomBanners();
		$this->view->layout()->banners = $this->view->partial('banner/bannerleftindex.phtml',
		array('banners'=>$banners));

		$this->view->layout()->leftcol = $this->view->partial('b2b/leftcol.phtml',
		array('orgdata'=>$this->view->partial('widgets/profile/synop.phtml', array('org'=>$this->_business)),
		'banners'=>$banners)
		);

		$tg = new FM_Components_Tabs_TabGroup();
		if($sportsuser || !$this->_business->isProtected() || $siteAdmin) {
			$tab = new FM_Components_Tabs_Profile();
			$tab->setProfile($this->_business->get('description'));
			$tg->addTab($tab, 'profile', 'profile', true);
			$gallery = new FM_Components_Widgets_PhotoGallery($this->view, $this->_request->getParam ( 'id' ), 'photogallery', $siteAdmin);
			$media = new FM_Components_Tabs_Media($this->_request->getParam ( 'id' ), true);
			$media->setProfile('This is the profile tab');
			$tg->addTab($media, 'media', 'media', false);
			$calendarTab = new FM_Components_Tabs_Calendar($this->_request->getParam ('id'));
			$tg->addTab($calendarTab, 'events', 'calendar', false);
			if($common['forum']) {
				$forum = new FM_Components_Tabs_Forum($this->_request->getParam ( 'id' ),  $siteAdmin, $sportsuser);
				$tg->addTab($forum, 'forum', 'forum', false);
			}
			if($common['directions']) {
				$dir = new FM_Components_Tabs_Directions($this->_business);
				$dir->setProfile('these are directions');
				$tg->addTab($dir, 'directions', 'directions', false);
			}

			$spsch = new FM_Components_Tabs_SportsSchedule($this->_business);
			$tg->addTab($spsch, 'schedule', 'schedule', false);

			if($common['contact']) {
				$dir = new FM_Components_Tabs_Contact($this->_request->getParam ( 'id' ));
				$dir->setProfile('these are directions');
				$tg->addTab($dir, 'contact us', 'contact', false);
			}

			$this->view->video = false;
			$this->view->photo = false;
			if($common['media']) {
				$this->view->layout()->photo = true;
				$gallery = new FM_Components_Widgets_PhotoGallery($this->view, $this->_request->getParam ( 'id' ), 'photogallery', $siteAdmin);
			}
			if($common['video']) {
				$this->view->layout()->video = true;
				$videoGallery = new FM_Components_Widgets_VideoGallery($this->view, $this->_request->getParam ( 'id' ), 'videogallery', $siteAdmin);
			}

		}
		if(!$sportsuser) {
			$signIn = new FM_Components_Tabs_SportsSignIn($this->_request->getParam ( 'id' ), ($this->_business->isProtected() && !$this->_user));
			$tg->addTab($signIn, 'signin', 'signin', ($this->_business->isProtected() && !$this->_user));
		}
		if($siteAdmin) {
			$adminTab = new FM_Components_Tabs_Admin();
			$admin = new FM_Components_Widgets_Admin($this->view, $this->_business->getOrgConfig(), $this->_business);
			$adminTab->setProfile($admin->toHTML());
			$tg->addTab($adminTab, 'admin', 'admin');
		}


		$this->view->layout()->profiletab = $tg->toHTML();
		$this->view->crumb = 'home >> sports >> ' . $this->_business->getName();

		$this->view->content = new FM_Forms_Login ( );

		$this->view->layout()->logo = $this->_business->getLogoObj()->getFileName();
		$this->view->layout()->miniwebBanner = $mw = $this->_business->getMiniWebBannerObj()->getFileName();

		$this->view->coupons = $coupons = FM_Components_Coupon::getOrgCoupons($this->_request->getParam ( 'id' ));
		$this->view->pageId = 'sports';
	}

	function ajaxphotogalleryAction() {
		$myNamespace = new Zend_Session_Namespace('sportsuser');
		if(isset($myNamespace->auth) && $myNamespace->auth == true) {
			print 'im good';
		}
		exit;
		print_r($_GET);
		$this->_helper->layout->setLayout('cssonly');
		$gallery = new FM_Components_Widgets_PhotoGallery($this->view, $_GET['pid'], 'photogallery', false);
		$media = new FM_Components_Tabs_Media($_GET['pid'], true);
		$this->view->gallery = $media->toHTML('photogallery');
	}

	function ajaxdeletesportsemailAction() {
		if($_POST && array_key_exists('id', $_POST)){
			if(FM_Components_Util_Email::delete(array('id'=>$_POST['id']))){
				print '1';
				exit;
			}
		}
		print '0';
		exit;
	}

	function ajaxsendemailsAction() {
		if(!$sport = new FM_Components_Sports( array ('id' => $_POST['orgId'] ) )) {
			print '0';
			exit;
		}

		$users = FM_Components_SportsUser::getAll($_POST['orgId']);
		FM_Components_Util_Email::insertEmail($_POST);

		foreach ($users as $index=>$user) {
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: '. $sport->getName() . ' administrator @ 4Monmouth.com' . "\r\n" .
			'Reply-To: nobody@4monmouth.com' . "\r\n" .
			'X-Mailer: PHP/' . phpversion();
			mail($user->getEmail(), $_POST['subject'] , FM_Components_EmailFormatter::sendSportEmail($_POST['content'],$sport), $headers);
		}
		print '1';
		exit;

		print '0';
		exit;
	}

	public function ajaxsportstoggleprotectedAction() {
		if($_POST && array_key_exists('isProtected', $_POST)) {
			$_POST['protected'] = $_POST['isProtected'];

			if(FM_Components_Sports::updateProtected($_POST)) {
				print '1';
				exit;
			}
			print '0';
			exit;
		}
		print '0';
		exit;
	}

	function ajaxsportssigninAction() {
		$myNamespace = new Zend_Session_Namespace('sportsuser');
		if($u = FM_Components_SportsUser::authenticate($_POST['uname'], $_POST['pwd'], $_POST['orgId'])){
			$user = new FM_Components_SportsUser(array('id'=>$u['id']));
			$myNamespace->auth = serialize($user);
			print '1';
			exit;
		}
		print '0';
		exit;
	}

	function ajaxsportsretrievepwdAction() {
		if(!$sport = new FM_Components_Sports( array ('id' => $_POST['orgId'] ) )) {
			print '0';
			exit;
		}
		$user = new FM_Components_SportsUser(array('id'=>$_POST['id']));
		if($user->getId()) {
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: '. $sport->getName() . ' administrator @ 4Monmouth.com' . "\r\n" .
			'Reply-To: nobody@4monmouth.com' . "\r\n" .
			'X-Mailer: PHP/' . phpversion();
			mail($user->getEmail(), 'Your username for  ' . $sport->getName() . ' @ 4Monmouth.com' , FM_Components_EmailFormatter::createPasswordLetter($user, $sport), $headers);
			print $user->getEmail();
			exit;
		}
		print '0';
		exit;
	}

	function ajaxsportsrequestaccountAction() {
		if(!$sport = new FM_Components_Sports( array ('id' => $_POST['orgId'] ) )) {
			print '0';
			exit;
		}
		//print_r($sport);
		$user = new FM_Components_Member(array('id'=>$sport->getAdminId()));
		if($user->getId()) {
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: '. $sport->getName() . ' administrator @ 4Monmouth.com' . "\r\n" .
			'Reply-To: nobody@4monmouth.com' . "\r\n" .
			'X-Mailer: PHP/' . phpversion();
			mail($_POST['email'], 'Account Request For  ' . $sport->getName() . ' @ 4Monmouth.com' , FM_Components_EmailFormatter::createAccountRequestLetter($_POST, $sport), $headers);
			mail($user->getEmail(), 'Account Request For  ' . $sport->getName() . ' @ 4Monmouth.com' , FM_Components_EmailFormatter::createAdminAccountRequestLetter($_POST, $sport), $headers);
			//print_r($user->getEmail());
			print '1';
			exit;
		}
		print '0';
		exit;
	}

	function ajaxsportschangepwdAction() {
		if($_POST)	{
			if(!$sport = new FM_Components_Sports( array ('id' => $_POST['orgId'] ) )) {
				print '0';
				exit;
			}
			$member = new FM_Components_SportsUser(array('id'=>$_POST['id']));
			if($member && $member->getId()) {
				if(FM_Components_SportsUser::update(array('id'=>$member->getId()), array('pwd'=>$_POST['newPwd']))) {
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					$headers .= 'From: Password Admin @ 4Monmouth.com' . "\r\n" .
					'Reply-To: nobody@4monmouth.com' . "\r\n" .
					'X-Mailer: PHP/' . phpversion();
					mail($member->getEmail(), 'Password Change @ 4Monmouth.com', FM_Components_EmailFormatter::updatePassword($_POST, $sport), $headers);
					print '1';
					exit;
				}
			}
			print '2';
			exit;
		}
		print '0';
		exit;
		print __FILE__ . ' ' . __LINE__;exit;
	}

	function ajaxsendpasswordAction() {
		if(!$sport = new FM_Components_Sports( array ('id' => $_POST['orgId'] ) )) {
			print '0';
			exit;
		}
		$user = new FM_Components_SportsUser(array('id'=>$_POST['id']));
		if($user->getId()) {
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: '. $sport->getName() . ' administrator @ 4Monmouth.com' . "\r\n" .
			'Reply-To: nobody@4monmouth.com' . "\r\n" .
			'X-Mailer: PHP/' . phpversion();
			mail($user->getEmail(), 'Your username for  ' . $sport->getName() . ' @ 4Monmouth.com' , FM_Components_EmailFormatter::createPasswordLetter($user, $sport), $headers);
			print '1';
			exit;
		}
		print '0';
		exit;
	}

	function ajaxaddsportsuserAction() {
		if(!$sport = new FM_Components_Sports( array ('id' => $_POST['orgId'] ) )) {
			print '0';
			exit;
		}
		$user = new FM_Components_SportsUser(array('uname'=>$_POST['uname']));
		if($user->getId()) {
			print '2';
			exit;
		}
		$pwd = FM_Components_SportsUser::generatePassword();
		$_POST['pwd'] = $pwd;
		if($id = FM_Components_SportsUser::addUser($_POST)){
			$user = new FM_Components_SportsUser(array('id'=>$id));
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: '. $sport->getName() . ' administrator @ 4Monmouth.com' . "\r\n" .
			'Reply-To: nobody@4monmouth.com' . "\r\n" .
			'X-Mailer: PHP/' . phpversion();
			mail($_POST['email'], 'You have been added as a user of ' . $sport->getName() . ' @ 4Monmouth.com'  , FM_Components_EmailFormatter::createConfirmLetter($_POST, $sport), $headers);
			print Zend_Json::encode($user->toArray());
			exit;
		}
		print '0';
		exit;
	}

	function ajaxdeletesportsuserAction() {
		if(FM_Components_SportsUser::deleteMember(array('id'=>$_POST['id'], 'orgId'=>$_POST['orgId']))){
			print '1';
			exit;
		}
		print '0';
		exit;
	}

	function ajaxsportssignoutAction() {
		$myNamespace = new Zend_Session_Namespace('sportsuser');
		if($myNamespace->auth) {
			unset($myNamespace->auth);
			print '1';
			exit;
		}
		print '0';
		exit;
	}

}