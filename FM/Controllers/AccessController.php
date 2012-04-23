<?php
Zend_Loader::loadClass('FM_Controllers_BaseController');
Zend_Loader::loadClass('FM_Components_Auth_AuthAdapter');

class AccessController extends FM_Controllers_BaseController {

	public function loginAction() {
		$p = $this->_request->getParams();
		$auth = Zend_Auth::getInstance();
		if($auth->hasIdentity()){
			$this->_redirect($_SERVER['HTTP_REFERER']);
		}
		$authAdapter = new FM_Components_Auth_AuthAdapter($p['uname'], $p['password']);
		// Attempt authentication, saving the result
		$result = $auth->authenticate($authAdapter);

		if (!$result->isValid()) {
			// Authentication failed; print the reasons why
			foreach ($result->getMessages() as $message) {
				echo "$message\n";
			}
			$flashMessenger = $this->_helper->getHelper('FlashMessenger');
			$flashMessenger->addMessage('Your credentials wre incorrect. Please try again.');
			$this->_redirect('/account');
		} else {
			$this->_redirect($_SERVER['HTTP_REFERER']);
		}
	}

	public function logoutAction() {
		if(Zend_Auth::getInstance()->hasIdentity()){
			Zend_Auth::getInstance()->clearIdentity();
			$namespace = new Zend_Session_Namespace('client');
			$namespace->unsetAll();
			$this->_redirect('/account');
		}
		print __LINE__;exit;
		$this->_redirect('/account');
	}
}