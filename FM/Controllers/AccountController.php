<?php
Zend_Loader::loadClass('FM_Controllers_BaseController');
Zend_Loader::loadClass('FM_Forms_Login');
Zend_Loader::loadClass('FM_Components_Member');
Zend_Loader::loadClass('FM_Components_Banner');
Zend_Loader::loadClass('FM_Components_OrgConfig');

class AccountController extends FM_Controllers_BaseController{

	public function init() {
		parent::init();
		$banners = FM_Components_Banner::getSortedRandomBanners(array());
		$this->view->layout()->banners = $this->view->partial('banner/bannerleftindex.phtml',
		array('banners'=>$banners));

		$spotlight = FM_Components_Organization::getRandom(6, false, array(), true);
		$this->view->layout()->spotlight = $this->view->partial('organization/spotlight.phtml',
		array('organizations'=>$spotlight));

		$featuredOrganization = FM_Components_Organization::getRandom(1, true, array(8));
		$this->view->layout()->featuredOrganization = $this->view->partial('organization/indexfeatured.phtml',
		array('organization'=>$featuredOrganization[1]));


		$this->view->layout()->header = $this->view->partial('headers/index.phtml');
	}

	public function indexAction() {
		$m =  $flashMessenger = $this->_helper->getHelper('FlashMessenger');
		if(count($mes = $m->getMessages())) {
			$this->view->message = $mes[0];
		}
		$auth = Zend_Auth::getInstance();
		if (!$auth->hasIdentity()) {
			$this->view->header = '<h4>Login</h4>';
			$this->view->content = new FM_Forms_Login();
		} else{
			$member = new FM_Components_Member(array('uname'=>$auth->getIdentity()));
			if($member->isRoot()) {$this->root = true;}
			$this->view->content = $this->view->partial('account/userdetail.phtml',
			array('member'=>$member, 'root'=>$root));
		}
	}

	public function ajaxupdatememberAction() {
		if(count($_POST)) {
			$auth = Zend_Auth::getInstance();
			$member = new FM_Components_Member(array('uname'=>$auth->getIdentity()));
			if(FM_Components_Member::update(array('uid'=>$member->getId()), $_POST)) {
				print '1';
				exit;
			} else {
				print '0';
				exit;
			}
		}
	}

	public function retrievepasswordAction() {
		$this->view->sent = false;
		$this->view->failed = false;
		if ($this->_request->isPost()) {
			if($_POST['email'] != '') {
				$members = FM_Components_Member::getMemberByEmail($_POST['email']);
				if(count($members)) {
					$creds = '';
					foreach ($members as $key=>$member) {
						$creds .= "<p>Username: {$member->getUserName()}<br />Pwd: {$member->getPassword()}</p>";
					}
					$message = "<p>Here are the usenames and associated passwords attached to this email address at <a href='http://4monmouth.com'>4Monmouth.com</a></p>";
					$message .= $creds;
					$message .= "<p>Click <a href='http://4monmouth.com/account'>here</a> to sign in.</p>";
					$headers  = "From: admin@4monmouth.com\r\n";
					$headers .= "Content-type: text/html\r\n";
					mail($_POST['email'], 'Your 4Monmouth.com password(s)', $message, $headers);
					$this->view->email = $_POST['email'];
					$this->view->sent = true;
				} else {
					$this->view->email = $_POST['email'];
					$this->view->failed = true;
				}
			}
		}
	}
}