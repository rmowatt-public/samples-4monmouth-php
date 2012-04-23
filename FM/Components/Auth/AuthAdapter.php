<?php

Zend_Loader::loadClass('Zend_Auth_Adapter_Interface');
Zend_Loader::loadClass('Zend_Auth_Result');
Zend_Loader::loadClass('Zend_Session_Namespace');
Zend_Loader::loadClass('FM_Models_FM_User');
Zend_Loader::loadClass('FM_Components_Member');

class FM_Components_Auth_AuthAdapter implements Zend_Auth_Adapter_Interface
{
	
	protected $_name;
	protected $_password;
    /**
     * Sets username and password for authentication
     *
     * @return void
     */
    public function __construct($username, $password)
    {
       $this->_name = $username;
       $this->_password = $password;
    }

    /**
     * Performs an authentication attempt
     *
     * @throws Zend_Auth_Adapter_Exception If authentication cannot
     *                                     be performed
     * @return Zend_Auth_Result
     */
    public function authenticate()
    {
    	
        $userTable = new FM_Models_FM_User();
        $code = Zend_Auth_Result::FAILURE;
		$identity = $this->_name;
		$message = 'Process failed';
		if ($uc = $userTable->authenticate($this->_name, $this->_password)) {
			$user = new FM_Components_Member(array('id'=>$uc['id']));
			$namespace = new Zend_Session_Namespace('client');
			$namespace->user = $user;
			$code = Zend_Auth_Result::SUCCESS;
			$message = 'Login succesful';
		} else {
			$code = Zend_Auth_Result::FAILURE;
			$message = 'Login failed';
		}
		return new Zend_Auth_Result($code, $identity, array($message));
    }
}