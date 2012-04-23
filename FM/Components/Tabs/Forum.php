<?php
Zend_Loader::loadClass('FM_Components_Tabs_BaseTab');
Zend_Loader::loadClass('FM_Forms_Forum');
Zend_Loader::loadClass('FM_Components_Util_ForumItem');

class FM_Components_Tabs_Forum extends FM_Components_Tabs_BaseTab {
	
	protected $_items;
	protected $_profile;
	protected $_orgId;
	protected $_admin;
	protected $_user;
	
	public function __construct($orgId, $admin = false, $user = false) {
		//print_r($admin);exit;
		parent::__construct();
		$this->_orgId = $orgId;
		$this->_admin = $admin;
		$this->_user = $user;
		$this->_items = FM_Components_Util_ForumItem::getForumItems(array('orgId'=>$orgId));
		//$form = new FM_Forms_Forum(array('orgId'=>$orgId));
		$this->setProfile($this->getProfile());
		$this->_view->headScript()->appendFile(
		'/js/widgets/forum.js',
		'text/javascript'
		);
	}
	
	public function setProfile($profile) {
		$this->_profile = $profile;
	}
	
	public function getProfile() {
		if($this->_admin)	{
			$form =  new FM_Forms_Forum(array('display'=>'inline'), $this->_admin->getUserName(), $this->_admin->getEmail());
			$form->orgId->setValue($this->_orgId);
		}
		else if($this->_user)	{
			$form =  new FM_Forms_Forum(array('display'=>'inline'), $this->_user->getUserName(), $this->_user->getEmail());
			$form->orgId->setValue($this->_orgId);
		} else {
			$form =  new FM_Forms_Forum(array('display'=>'inline'));
			$form->orgId->setValue($this->_orgId);
		}
		
		//$form->orgId->setValue($this->_orgId);
		//return $form;
		return $form;
	}
	
	public function toHTML($id) {
		//print_r($this->_view);exit;
		return $this->_view->partial('tabs/forum.phtml',
											array('profile'=>$this->getProfile(), 'id'=>$id, 'items'=>$this->_items, 'admin'=>$this->_admin, 'user'=>$this->_user));
	}
}