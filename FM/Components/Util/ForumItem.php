<?php
Zend_Loader::loadClass ( 'FM_Models_FM_ForumItems' );
Zend_Loader::loadClass ( 'FM_Components_BaseComponent' );

class FM_Components_Util_ForumItem extends FM_Components_BaseComponent{

	protected $id;
	protected $orgId;
	protected $name;
	protected $email;
	protected $message;
	protected $timestamp;

	public function __construct($keys = null) {
		if(is_array($keys)) {
			$forumModel = new FM_Models_FM_ForumItems();
			$forum = $forumModel->getForumItemByKeys($keys);
			if(count($forum)){
				foreach ($forum as $key=>$value) {
					if(property_exists($this, $key)) {
							$this->{$key} = $value;
					}
				}
				return true;
			}
			return false;
		}
		return true;
	}
	
	/**
	 * @return the $active
	 */
	public function getActive() {
		return $this->active;
	}

	/**
	 * @return the $order
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return the $answer
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * @return the $question
	 */
	public function getMessage() {
		return $this->message;
	}

	/**
	 * @return the $orgId
	 */
	public function getOrgId() {
		return $this->orgId;
	}

	/**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}
	
	public function getTime() {
		return date('m-d-Y H:i', strtotime($this->timestamp));
	}

	public function edit($args) {
		foreach ($args as $key=>$value) {
			if(property_exists($this, $key)) {
					$this->{$key} = $value;
				}
		}
		$forumModel = new FM_Models_FM_ForumItems();
		if($forumModel->edit(array('id'=>$this->getId()), $this->toArray(array('id')))) {
			return true;
		}
		return false;
	}


	public static function insertForumItem($args) {
		$forumModel = new FM_Models_FM_ForumItems();
		if($id = $forumModel->insertForumItem($args)) {
			return $id;
		}
		return false;
	}

	public static function getForumItems($args) {
		$forumModel = new FM_Models_FM_ForumItems();
		$returnArray = array();
		if(count($forums = $forumModel->getForumItemsByKeys($args))) {
			foreach ($forums as $index=>$values) {
				$forum = new FM_Components_Util_ForumItem(array('id'=>$values['id']));
				$returnArray[] = $forum;
			}
		}
		return $returnArray;
	}

	public static function delete($keys) {
		$forumModel = new FM_Models_FM_ForumItems();
		if($forum = new FM_Components_Util_ForumItem($keys)) {
			if($forumModel->remove(array('id'=>$forum->getId()))) {
					return true;
			}
		}
		return false;
	}

}