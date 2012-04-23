<?php
Zend_Loader::loadClass('FM_Controllers_BaseController');
Zend_Loader::loadClass('FM_Forms_Admin_Banner');
Zend_Loader::loadClass('FM_Components_Util_UploadHandler');
Zend_Loader::loadClass('FM_Components_Banner');
Zend_Loader::loadClass('Zend_Paginator');
Zend_Loader::loadClass('Zend_View_Helper_PaginationControl');

class BannerController extends FM_Controllers_BaseController {

	public function indexAction() {
		$this->_helper->layout->setLayout('admin');
		if ($this->_request->isPost() && !$this->_request->getParam('editid')) {
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
					$uploadedData['oid'] = $this->_user->getOrgId();

					FM_Components_Banner::insertBanner($uploadedData);
					$bannerForm = new FM_Forms_Admin_Banner();
					$this->view->form = $bannerForm;
				}
			} else {
				$bannerForm->populate($formData);
				$this->view->form = $bannerForm;
			}
		} elseif($this->_request->isPost()  && $this->_request->getParam('editid')){
			$bannerForm = new FM_Forms_Admin_Banner();
			$bannerForm->populate($this->_request->getPost());
			$uploadedData = $bannerForm->getValues();
			if(FM_Components_Banner::updateBanner(array('id'=>$uploadedData['editid']), $uploadedData)){
				if(array_key_exists('HTTP_REFERER', $_SERVER) && stristr($_SERVER['HTTP_REFERER'], 'admin/banner')){
					$this->_redirect($_SERVER['HTTP_REFERER']);
				} else{
					$this->_redirect('/admin/banner');
				}
			}
		}else{

			$bannerForm = new FM_Forms_Admin_Banner();
			$this->view->form = $bannerForm;
		}

		$banners = FM_Components_Banner::getOrgBanners($this->_user->getOrgId());
		$paginator = Zend_Paginator::factory(array_reverse($banners));
		$paginator->setCurrentPageNumber($this->_request->getParam('page'));
		$paginator->setItemCountPerPage(5);
		$paginator->setView($this->view);
		Zend_Paginator::setDefaultScrollingStyle('Sliding');
		Zend_View_Helper_PaginationControl::setDefaultViewPartial(
		'pagination/default.phtml'
		);

		$this->view->currentBanners = $this->view->partial('admin/partials/bannerdisplay.phtml',
		array('banners'=>$paginator));
	}

	public function deleteAction() {
		$id = $this->_request->getParam('id');
		if($id) {
			//**todo check to make sure user is in org**/
			if(FM_Components_Banner::deleteBanner(array('id'=>$id))) {
				if(array_key_exists('HTTP_REFERER', $_SERVER) && stristr($_SERVER['HTTP_REFERER'], 'admin/banner')){
					$this->_redirect($_SERVER['HTTP_REFERER']);
				} else{
					$this->_redirect('/admin/banner');
				}
			} else {
				exit;
			}
		}
		exit;
	}

	public function editAction() {
		$id = $this->_request->getParam('id');
		if($id)
		{
			$banner = new FM_Components_Banner(array('id'=>$id));
			if($banner) {
				print Zend_Json::encode($banner->toArray());
				exit;
			}
		}
	}
}