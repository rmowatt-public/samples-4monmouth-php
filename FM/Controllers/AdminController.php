<?php
Zend_Loader::loadClass('FM_Controllers_BaseController');
Zend_Loader::loadClass('FM_Forms_Admin_Banner');
Zend_Loader::loadClass('FM_Components_Util_UploadHandler');
Zend_Loader::loadClass('FM_Components_Banner');
Zend_Loader::loadClass('FM_Components_Coupon');
Zend_Loader::loadClass('FM_Components_Tabs_Services');
Zend_Loader::loadClass('FM_Components_Tabs_Menu');
Zend_Loader::loadClass('FM_Components_Util_TextAd');
Zend_Loader::loadClass('FM_Components_Util_SportsSchedule');

class AdminController extends FM_Controllers_BaseController {



	public function bannerAction() {
		$this->_helper->layout->setLayout('admin');
		if ($this->_request->isPost()) {
			$bannerForm = new FM_Forms_Admin_Banner();
			$formData = $this->_request->getPost();
			if ($bannerForm->isValid($formData)) {
				$uploadedData = $bannerForm->getValues();
				//Zend_Debug::dump($uploadedData, '$uploadedData');
				$fileHandler = new FM_Components_Util_UploadHandler($uploadedData['file']);
				$folder = $fileHandler->setFolder('banner');
				if($fileHandler->move()){
					list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $folder . '/' . $uploadedData['file']['name']);
					$uploadedData['width'] = $width;
					$uploadedData['height'] = $height;
					$uploadedData['path'] = $folder;
					$uploadedData['medianame'] = $uploadedData['file']['name'];

					FM_Components_Banner::insertBanner($uploadedData);
					$bannerForm = new FM_Forms_Admin_Banner();
					$this->view->form = $bannerForm;
				}
			} else {
				$bannerForm->populate($formData);
				$this->view->form = $bannerForm;
			}
		} else{
			$bannerForm = new FM_Forms_Admin_Banner();
			$this->view->form = $bannerForm;
		}

		$banners = FM_Components_Banner::getOrgBanners(1);
		$this->view->currentBanners = $this->view->partial('admin/partials/bannerdisplay.phtml',
		array('banners'=>$banners));

	}

	public function uploadcouponAction() {
		if ($this->_request->isPost()) {
			$_FILES['userfile']['name'] = $_POST['id'] . '_' . date('YmdHis', time()) . str_ireplace(array(' ', '_', '-'), '', strtolower($_FILES['userfile']['name']));
			$fileHandler = new FM_Components_Util_UploadHandler($_FILES['userfile']);
			$folder = $fileHandler->setFolder('coupons');
			if($fileHandler->move()){
				FM_Components_Coupon::updateCoupon(array('id'=>$_POST['id']), array('file'=>$_FILES['userfile']['name']));
				print $_FILES['userfile']['name'];
			} else {
				print '0';
			}
		} else {
			print '0';
		}
		exit;
	}

	public function uploadbannerAction() {
		if ($this->_request->isPost()) {
			$_FILES['userfile']['name'] = $_POST['id'] . '_' . date('YmdHis', time()) . str_ireplace(array(' ', '_', '-'), '', strtolower($_FILES['userfile']['name']));
			$fileHandler = new FM_Components_Util_UploadHandler($_FILES['userfile']);
			$folder = $fileHandler->setFolder('banner');
			if($fileHandler->move()){
				list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $folder . '/' . $_FILES['userfile']['name']);
				$uploadedData['width'] = $width;
				$uploadedData['height'] = $height;
				$uploadedData['path'] = $folder;
				$uploadedData['medianame'] = $_FILES['userfile']['name'];
				FM_Components_Banner::updateBanner(array('id'=>$_POST['id']), $uploadedData);
				print $_FILES['userfile']['name'];
			} else {
				print '0';
			}
		} else {
			print '0';
		}
		exit;
	}

	public function ajaxeditservicesAction() {
		if(FM_Components_Tabs_Services::editService($_POST['orgId'], $_POST['content'])) {
			print '1';
			exit;
		}
		print '0';
		exit;
	}


	public function ajaxeditsportsscheduleAction() {
		$ss = new FM_Components_Util_SportsSchedule(array('orgId'=>$_POST['orgId']));
		if($ss->getId()) {
			print FM_Components_Util_SportsSchedule::updateStatement(array('orgId'=>$_POST['orgId']), array('schedule'=>$_POST['schedule']));
		} else {
			print FM_Components_Util_SportsSchedule::insert($_POST);
		}
		exit;

	}

	public function ajaxeditmenuAction() {
		if(FM_Components_Tabs_Menu::editMenu($_POST['orgId'], $_POST['content'])) {
			print '1';
			exit;
		}
		print '0';
		exit;
	}


	public function ajaxaddtextadAction() {
		$ad = new FM_Components_Util_TextAd(array('id'=>$_POST['id']));
		if($id = $ad->getId()) {
			unset($_POST['id']);
			FM_Components_Util_TextAd::updateTextAd(array('id'=>$id), $_POST);
			print $id;
			exit;
		}else {
			if($id = FM_Components_Util_TextAd::insertTextAd(array('orgId'=>$_POST['orgId'], 'content'=>$_POST['content']))) {
				print $id;
				exit;
			}
		}
		print '0';
		exit;
	}

	public function ajaxedittextadAction() {
		if($id = FM_Components_Util_TextAd::insertTextAd(array('orgId'=>$_POST['orgId'], 'content'=>$_POST['content']))) {
			$ad = new FM_Components_Util_TextAd(array('id'=>$_POST['id']));
			if($ad->getId()){
				print Zend_Json::encode($ad->toArray());
				exit;
			}
		}
		print '0';
		exit;
	}

	public function ajaxtoggletextadAction() {
		if(FM_Components_Util_TextAd::updateTextAd(array('id'=>$_POST['id']),array( 'active'=>$_POST['active']))) {
			print '1';
			exit;
		}
		print '0';
		exit;
	}

	public function ajaxremovetextadAction() {
		if(FM_Components_Util_TextAd::deleteTextAd(array('id'=>$_POST['id']))) {
			print $_POST['id'];
			exit;
		}
		print '0';
		exit;
	}

}