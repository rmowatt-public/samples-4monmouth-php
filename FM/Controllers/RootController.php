<?php
Zend_Loader::loadClass('FM_Controllers_BaseController');
Zend_Loader::loadClass('FM_Forms_Root_SiteEmail');
Zend_Loader::loadClass('FM_Components_Banner');
Zend_Loader::loadClass('FM_Components_Util_UploadHandler');
Zend_Loader::loadClass('FM_Components_Util_MiniwebBanner');
Zend_Loader::loadClass('FM_Components_Organization');
Zend_Loader::loadClass('FM_Components_Business');
Zend_Loader::loadClass('FM_Components_NonProfit');
Zend_Loader::loadClass('FM_Components_Sports');
Zend_Loader::loadClass('FM_Components_Member');
Zend_Loader::loadClass('FM_Forms_Register_Business');
Zend_Loader::loadClass('FM_Forms_Register_NonProfit');
Zend_Loader::loadClass('FM_Forms_Register_Sports');
Zend_Loader::loadClass('FM_Forms_Faq');
Zend_Loader::loadClass('FM_Components_Util_Faq');
Zend_Loader::loadClass('FM_Forms_Root_MissionStatement');
Zend_Loader::loadClass('FM_Forms_Root_AboutUs');
Zend_Loader::loadClass('FM_Forms_Root_ForAdvertisers');
Zend_Loader::loadClass('FM_Forms_Root_ForOrgs');
Zend_Loader::loadClass('FM_Forms_Root_ForUsers');
Zend_Loader::loadClass('FM_Forms_Root_AddBannerTemplate');
Zend_Loader::loadClass('FM_Components_Util_MissionStatement');
Zend_Loader::loadClass('FM_Components_Util_AboutUs');
Zend_Loader::loadClass('FM_Components_Util_ForUsers');
Zend_Loader::loadClass('FM_Components_Util_ForOrgs');
Zend_Loader::loadClass('FM_Components_Util_ForAdvertisers');
Zend_Loader::loadClass('FM_Components_Util_Feedback');
Zend_Loader::loadClass('FM_Components_Util_OurServices');
Zend_Loader::loadClass('FM_Components_Util_BannerTemplate');
Zend_Loader::loadClass('FM_Forms_Root_AddCouponTemplate');
Zend_Loader::loadClass('FM_Components_Util_CouponTemplate');
Zend_Loader::loadClass('FM_Components_Coupon');
Zend_Loader::loadClass('FM_Forms_Register_UserAjax');
Zend_Loader::loadClass('FM_Forms_Register_User');
Zend_Loader::loadClass('FM_Forms_Root_AddPayBanner');
Zend_Loader::loadClass('FM_Components_Util_PayBanner');
Zend_Loader::loadClass('FM_Components_Util_Town');
Zend_Loader::loadClass('FM_Components_SiteConfig');
Zend_Loader::loadClass('FM_Components_Util_FullBanner');
Zend_Loader::loadClass('FM_Forms_Root_AddFullBanner');
Zend_Loader::loadClass('FM_Components_Util_Category');
Zend_Loader::loadClass('FM_Components_Util_Icon');
Zend_Loader::loadClass('FM_Models_FM_SearchPrimaryCategories');
Zend_Loader::loadClass('Zend_Form_Element_Select');
Zend_Loader::loadClass('FM_Forms_Register_CatJumper');
Zend_Loader::loadClass('FM_Forms_Register_SportsCatJumper');
Zend_Loader::loadClass('FM_Forms_Register_NpCatJumper');
Zend_Loader::loadClass('FM_Forms_Register_Search');
Zend_Loader::loadClass('FM_Components_Util_Affiliates');
Zend_Loader::loadClass('FM_Forms_Root_Affiliates');
Zend_Loader::loadClass('FM_Forms_Root_PrivacyPolicy');
Zend_Loader::loadClass('FM_Forms_Root_OurServices');
Zend_Loader::loadClass('FM_Forms_Root_Legal');
Zend_Loader::loadClass('FM_Components_Util_Legal');
Zend_Loader::loadClass('FM_Components_Util_PrivacyPolicy');
Zend_Loader::loadClass('FM_Forms_Root_Users');
Zend_Loader::loadClass('FM_Forms_Root_MediaKit');
Zend_Loader::loadClass('FM_Components_Util_MediaKit');
Zend_Loader::loadClass('FM_Forms_Root_FaqPage');
Zend_Loader::loadClass('FM_Forms_Root_FeedbackPage');
Zend_Loader::loadClass('FM_Components_Util_FaqPage');
Zend_Loader::loadClass('FM_Components_Util_FeedbackPage');
Zend_Loader::loadClass('FM_Components_Util_SiteEmail');
Zend_Loader::loadClass('FM_Forms_Root_LimeCard');
Zend_Loader::loadClass('FM_Components_Util_LimeCard');


class RootController extends FM_Controllers_BaseController{

	protected $_siteConfig;

	public function init() {
		parent::init();
		if(!$this->_user || !$this->_user->isRoot()){
			$this->_redirect('/account');
		}

		$this->_helper->layout->setLayout('rootadmin');
		$this->_siteConfig = new FM_Components_SiteConfig();
	}

	public function adminAction() {
		$this->view->home = true;
		$this->view->tiny = true;
		$this->view->npbanners = $this->_siteConfig->npBannersEnabled();
		if($_POST['message']) {
			$orgs = array();
			foreach($_POST as $key=>$value) {
				if(stristr($key, 'target')) {
					$p = explode('_', $key);
					$orgs[] = $p[1];
				}
			}
			$emails = FM_Components_Organization::getMaillistOrgs($orgs);
			$allEmails = array();
			$badChars = array('@', '.com', '.', '_', '-', ' ', ';', ':');
			foreach ($emails as $key=>$values) {
				if($values['email'] != '') {
					$allEmails[strtolower(str_replace($badChars, '', $values['email']))]['email'] = $values['email'];
				}
				if($values['uid'] != '') {
					$record = FM_Components_Member::getMemberRecord($values['uid']);
					$allEmails[strtolower(str_replace($badChars, '', $record['email']))]['email'] = $record['email'];
				}
			}

			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: administrator @ 4Monmouth.com' . "\r\n" .
			'Reply-To: nobody@4monmouth.com' . "\r\n" .
			'X-Mailer: PHP/' . phpversion();

			$adminMessage = $_POST['message'] . "<br />The following users have been emailed : <pre>" . print_r(array_values($allEmails), true) . "</pre>";

			mail('admin@4monmouth.com', 'COPY OF: 4Monmouth.com : ' . $_POST['subject'], $adminMessage, $headers);

			foreach ($emails as $key=>$value) {
				mail($value['email'], '4Monmouth.com : ' . $_POST['subject'], $_POST['message'], $headers);
			}

		}
	}

	public function colorpickerAction() {
		$this->view->siteconfig = $this->_siteConfig;
	}

	public function ajaxdeleteutilmediaAction() {
		if($_POST && array_key_exists('type', $_POST)) {
			switch($_POST['tool']) {
				case 'mission' :
					FM_Components_Util_MissionStatement::updateStatement(array($_POST['type']=>$_POST['media']), array($_POST['type']=>''));
					print '1';
					exit;
				case 'forusers' :
					FM_Components_Util_ForUsers::updateStatement(array($_POST['type']=>$_POST['media']), array($_POST['type']=>''));
					print '1';
					exit;
				case 'fororgs' :
					FM_Components_Util_ForOrgs::updateStatement(array($_POST['type']=>$_POST['media']), array($_POST['type']=>''));
					print '1';
					exit;
				case 'foradv' :
					FM_Components_Util_ForAdvertisers::updateStatement(array($_POST['type']=>$_POST['media']), array($_POST['type']=>''));
					print '1';
					exit;
				case 'aff' :
					FM_Components_Util_Affiliates::updateStatement(array($_POST['type']=>$_POST['media']), array($_POST['type']=>''));
					print '1';
					exit;
				case 'pri' :
					FM_Components_Util_PrivacyPolicy::updateStatement(array($_POST['type']=>$_POST['media']), array($_POST['type']=>''));
					print '1';
					exit;
				case 'legal' :
					FM_Components_Util_Legal::updateStatement(array($_POST['type']=>$_POST['media']), array($_POST['type']=>''));
					print '1';
					exit;
				case 'svc' :
					FM_Components_Util_OurServices::updateStatement(array($_POST['type']=>$_POST['media']), array($_POST['type']=>''));
					print '1';
					exit;
				case 'faq' :
					FM_Components_Util_FaqPage::updateStatement(array($_POST['type']=>$_POST['media']), array($_POST['type']=>''));
					print '1';
					exit;
				case 'fbk' :
					FM_Components_Util_FeedbackPage::updateStatement(array($_POST['type']=>$_POST['media']), array($_POST['type']=>''));
					print '1';
					exit;
				case 'aus' :
					FM_Components_Util_AboutUs::updateStatement(array($_POST['type']=>$_POST['media']), array($_POST['type']=>''));
					print '1';
					exit;
				default :
					print '0';
					exit;
			}
		}
	}

	public function ajaxupdatebackgroundAction() {
		if($_POST && array_key_exists('color', $_POST)) {
			if(FM_Components_SiteConfig::update(array('id'=>1), array('background'=>$_POST['color']))){
				print '1';
				exit;
			}
			print '0';
			exit;
		}
	}

	public function ajaxtogglenpbannersAction() {
		if($_POST && array_key_exists('active', $_POST)) {
			if(FM_Components_SiteConfig::update(array('id'=>1), array('npbanners'=>$_POST['active']))){
				print '1';
				exit;
			}
			print '0';
			exit;
		}
	}

	public function managecategoriesAction() {
		$this->view->sidenav = $this->view->partial('root/parts/sidenavs/cats.phtml', array('selected'=>'b2b'));
		$this->view->cat = true;
		$this->view->roots = FM_Components_Util_Category::getRootCategories();
		$this->view->sorted = FM_Components_Util_Category::getSortedSearchCategories();

		if ($this->_request->isPost()) {
			$this->view->success = false;
			if(array_key_exists('delete', $_POST) && $_POST['delete'] == 1) {
				if(FM_Components_Util_Category::deleteCategory(array('id'=>$_POST['deleteCat']))) {
					$this->view->success = true;
				}
			} else if(array_key_exists('edit', $_POST) && $_POST['edit'] == 1 && $_POST['name'] != '') {
				if(FM_Components_Util_Category::editCategory(array('id'=>$_POST['editCat']), array('name'=>$_POST['name']))) {
					$this->view->success = true;
				}
			}
			else {
				$formData = $this->_request->getPost();
				if(FM_Components_Util_Category::insertCategory($formData)) {
					$this->view->success = true;
				}
			}
			$this->_redirect('/root/managecategories');
		}
	}

	public function ajaxcheckslugAction() {
		print  FM_Components_Organization::findBySlug($_POST['slug']) ? '1' : '0';
		exit;
	}

	public function managecategoriesorgAction() {
		$this->view->sidenav = $this->view->partial('root/parts/sidenavs/cats.phtml', array('selected'=>'np'));
		$this->view->roots = FM_Components_Util_Category::getRootCategories(true);
		$this->view->sorted = FM_Components_Util_Category::getSortedSearchCategories(true);
		$this->view->cat = true;

		if ($this->_request->isPost()) {
			$this->view->success = false;
			if(array_key_exists('delete', $_POST) && $_POST['delete'] == 1) {
				if(FM_Components_Util_Category::deleteCategory(array('id'=>$_POST['deleteCat']), true)) {
					$this->view->success = true;
				}
			} else if(array_key_exists('edit', $_POST) && $_POST['edit'] == 1 && $_POST['name'] != '') {
				if(FM_Components_Util_Category::editCategory(array('id'=>$_POST['editCat']), array('name'=>$_POST['name']), true)) {
					$this->view->success = true;
				}
			}
			else {
				$formData = $this->_request->getPost();
				if(FM_Components_Util_Category::insertCategory($formData, true)) {
					$this->view->success = true;
				}
			}
			$this->_redirect('/root/managecategoriesorg');
		}
	}

	public function managepaybannersAction() {
		$this->view->search = new FM_Forms_Register_Search();
		$this->view->sidenav = $this->view->partial('root/parts/sidenavs/banner.phtml', array('selected'=>'managepay'));
		$this->view->addForm = $form = new FM_Forms_Root_AddPayBanner();
		if(array_key_exists('search', $_POST)) {
			$this->view->banners = FM_Components_Util_PayBanner::getLike($_POST['search']);
		} else {
			$this->view->banners = FM_Components_Util_PayBanner::getAll();

			$this->view->enabled = $this->_siteConfig->usePayBanners();

			$this->view->borgs = FM_Components_Business::getActiveForRoot();
			$this->view->nporgs = FM_Components_NonProfit::getActiveForRoot();
			$this->view->sportsorgs = FM_Components_Sports::getAllForRoot();

			if ($this->_request->isPost()) {
				$formData = $this->_request->getPost();
				if ($form->isValid($formData)) {
					$uploadedData = $form->getValues();
				}

				if($uploadedData['file']['name'] != '') {
					$name = $uploadedData['file']['name'] = 'pb' . '_' . time() . str_ireplace(array(' ', '_', '-', ','), '', strtolower($uploadedData['file']['name']));
					$uploadedData['file']['type'] = 'image';
					$fileHandler = new FM_Components_Util_UploadHandler($uploadedData['file']);
					$folder = $fileHandler->setFolder('paybanners');
					if($fileHandler->move()){
						list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $folder . '/' . $name);
						if($width != 514 || $height != 50 && !stristr($uploadedData['file']['name'], '.swf')) {
							$this->view->sizeError = true;
						} else {
							$this->view->sizeError = false;
							$uploadedData['file'] = $name;
							$uploadedData['width'] = $width;
							$uploadedData['height'] = $height;
							FM_Components_Util_PayBanner::insert($uploadedData);
							$this->_redirect('/root/managepaybanners');
						}
					}
				}
			}
		}}

		public function managemediakitAction() {
			$this->view->form = $form = new FM_Forms_Root_MediaKit(array('src'=>FM_Components_Util_MediaKit::getLink()));
			//$this->view->link = FM_Components_Util_MediaKit::getLink();
			if ($this->_request->isPost()) {
				$formData = $this->_request->getPost();
				if ($form->isValid($formData)) {
					$uploadedData = $form->getValues();
				}
				if($uploadedData['file']['name'] != '') {
					$name = $uploadedData['file']['name'] = '4M' . '_' . time() . str_ireplace(array(' ', '_', '-', ','), '', strtolower($uploadedData['file']['name']));
					$uploadedData['file']['type'] = 'pdf';
					$uploadedData['documentname'] = $name;
					$fileHandler = new FM_Components_Util_UploadHandler($uploadedData['file']);
					$folder = $fileHandler->setFolder('mediakit');
					if($fileHandler->move()){
						FM_Components_Util_MediaKit::insert($uploadedData);
						$this->_redirect('/root/managemediakit');
					}
				}
			}
		}

		public function managesiteemailsAction() {

			if($this->_getParam('todo') == 'delete') {
				FM_Components_Util_SiteEmail::delete(array('id'=>$this->_getParam('id')));
				$this->_redirect('/root/managesiteemails');
			};
			$this->view->form = $form = new FM_Forms_Root_SiteEmail();
			$this->view->emails =  FM_Components_Util_SiteEmail::getAll();

			if ($this->_request->isPost()) {
				$formData = $this->_request->getPost();
				if ($form->isValid($formData)) {
					$uploadedData = $form->getValues();
					if($formData['id'] != ''){
						$id = $formData['id'];
						unset($formData['id']);
						$i = FM_Components_Util_SiteEmail::update(array('id'=>$id), $formData);

					} else {
						$i = FM_Components_Util_SiteEmail::insert($formData);
					}
					if($i) {
						$this->_redirect('/root/managesiteemails');
					}

				}
			}
		}

		public function ajaxdeletemediaAction() {
			//print_r($_POST);
			if($_POST) {
				switch ($_POST['mediatype']) {
					case 'logo' :
						$r = FM_Components_Util_Logo::deleteBanner(array('id'=>$_POST['id']));
						break;
					case 'icon' :
						$r =FM_Components_Util_Icon::deleteIcon(array('id'=>$_POST['id']));
						break;
					case 'banner' :
						$r =FM_Components_Util_MiniwebBanner::deleteBanner(array('id'=>$_POST['id']));
						break;
				}
				print ($r) ? '1' : '0';
				exit;
			}
		}



		public function managefullbannersAction() {
			$this->view->search = new FM_Forms_Register_Search();
			$this->view->sidenav = $this->view->partial('root/parts/sidenavs/banner.phtml', array('selected'=>'managefull'));
			$this->view->addForm = $form = new FM_Forms_Root_AddFullBanner();
			if(array_key_exists('search', $_POST)) {
				$this->view->banners = FM_Components_Util_FullBanner::getLike($_POST['search']);
			} else {
				$this->view->banners = FM_Components_Util_FullBanner::getAll();
				$this->view->enabled = $this->_siteConfig->useFullBanners();

				if ($this->_request->isPost()) {
					$formData = $this->_request->getPost();
					if ($form->isValid($formData)) {
						$uploadedData = $form->getValues();
					}

					if($uploadedData['file']['name'] != '') {
						$name = $uploadedData['file']['name'] = 'pb' . '_' . time() . str_ireplace(array(' ', '_', '-', ','), '', strtolower($uploadedData['file']['name']));
						$uploadedData['file']['type'] = 'image';
						$fileHandler = new FM_Components_Util_UploadHandler($uploadedData['file']);
						$folder = $fileHandler->setFolder('fullbanners');
						if($fileHandler->move()){
							list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $folder . '/' . $name);
							if($width != 950 || $height > 130) {
								$this->view->sizeError = true;
							} else {
								$this->view->sizeError = false;
								$uploadedData['file'] = $name;
								$uploadedData['width'] = $width;
								$uploadedData['height'] = $height;
								FM_Components_Util_FullBanner::insert($uploadedData);
								$this->_redirect('/root/managefullbanners');
							}
						}
					}
				}
			}}

			public function limecardAction() {
				$this->view->util = true;
				$this->view->sidenav = $this->view->partial('root/parts/sidenavs/utils.phtml', array('selected'=>'limecard'));
				$form = new FM_Forms_Root_LimeCard();
				if($this->_getParam('id') != 0 && $this->_getParam('do') == 'delete'  ) {
					FM_Components_Util_LimeCard::delete(array('id'=>$this->_getParam('id')));
					$this->_redirect('/root/limecard');
				}
				if($this->_getParam('id') != 0 && $this->_getParam('do') == 'edit'  ) {
					$limecard = FM_Components_Util_LimeCard::getRecord(array('id'=>$this->_getParam('id')));
					//print_r($limecard);exit;
					foreach ($limecard as $index=>$value) {
						if(is_object($form->getElement($index))){
							$form->getElement($index)->setValue($value);
						}
					}
				}
				if ($this->_request->isPost()) {
					$formData = $this->_request->getPost();
					if ($form->isValid($formData)) {
						$uploadedData = $form->getValues();
						if($uploadedData['id'] == 0) {
						FM_Components_Util_LimeCard::insert($uploadedData );
						} else {
							$id = $uploadedData['id'];
							unset($uploadedData['id']);
							FM_Components_Util_LimeCard::update(array(id=>$id), $uploadedData);
						}
						$this->_redirect('/root/limecard');
					}
				}
				$this->view->form = $form;
				$this->view->current = $current = FM_Components_Util_LimeCard::getNonMember();
			}

			public function missionstatementAction() {
				$this->view->util = true;
				$this->view->sidenav = $this->view->partial('root/parts/sidenavs/utils.phtml', array('selected'=>'mission'));
				$statement = new FM_Components_Util_MissionStatement(array('active'=>1));
				$this->view->form = $form = new FM_Forms_Root_MissionStatement(array('src'=>$statement->getHeader(), 'src2'=>$statement->getCarousel()));
				//print $statement->getTitle();exit;
				$form->title->setValue($statement->getTitle());
				$form->file->setValue($statement->getHeader());
				$form->statement->setValue($statement->getStatement());
				$this->view->tiny = true;
				if ($this->_request->isPost()) {
					$formData = $this->_request->getPost();
					if ($form->isValid($formData)) {
						$uploadedData = $form->getValues();
						$this->view->tiny = true;
						if($uploadedData['file']['name'] != '') {
							$name = $uploadedData['file']['name'] = '1' . '_' . time() . str_ireplace(array(' ', '_', '-', ','), '', strtolower($uploadedData['file']['name']));
							$uploadedData['file']['type'] = 'image';
							$fileHandler = new FM_Components_Util_UploadHandler($uploadedData['file']);
							$folder = $fileHandler->setFolder('auxpage_headers');
							if($fileHandler->move()){
								$_POST['medianame'] = $name;
								list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $folder . '/' . $name);
								$_POST['width'] = $width;
								$_POST['height'] = $height;
							}
						}
						if($uploadedData['head']['name'] != '') {
							$name = $uploadedData['head']['name'] = '1' . '_' . time() . str_ireplace(array(' ', '_', '-', ','), '', strtolower($uploadedData['head']['name']));
							$uploadedData['head']['type'] = 'image';
							$fileHandler = new FM_Components_Util_UploadHandler($uploadedData['head']);
							$folder = $fileHandler->setFolder('auxpage_headers');
							if($fileHandler->move()){
								$_POST['header'] = $name;
							}
						}
						FM_Components_Util_MissionStatement::updateStatement(array('active'=>1), $_POST);
						$this->_redirect('/root/missionstatement');
					}
				}
			}

			public function affiliatesAction() {
				$this->view->afi = true;
				$this->view->sidenav = $this->view->partial('root/parts/sidenavs/utils.phtml', array('selected'=>'afi'));
				$affiliates = new FM_Components_Util_Affiliates(array('active'=>1));
				//print_r($affiliates);exit;
				$this->view->form = $form = new FM_Forms_Root_Affiliates(array('src'=>$affiliates->getHeader(), 'src2'=>$affiliates->getCarousel()));
				$form->statement->setValue($affiliates->getContent());
				$this->view->tiny = true;

				if ($this->_request->isPost()) {
					$formData = $this->_request->getPost();
					if ($form->isValid($formData)) {
						$uploadedData = $form->getValues();
						$this->view->tiny = true;
						if($uploadedData['file']['name'] != '') {
							$name = $uploadedData['file']['name'] = '1' . '_' . time() . str_ireplace(array(' ', '_', '-', ','), '', strtolower($uploadedData['file']['name']));
							$uploadedData['file']['type'] = 'image';
							$fileHandler = new FM_Components_Util_UploadHandler($uploadedData['file']);
							$folder = $fileHandler->setFolder('auxpage_headers');
							if($fileHandler->move()){
								$_POST['medianame'] = $name;
								list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $folder . '/' . $name);
								$_POST['width'] = $width;
								$_POST['height'] = $height;
							}
						}
						if($uploadedData['head']['name'] != '') {
							$name = $uploadedData['head']['name'] = '1' . '_' . time() . str_ireplace(array(' ', '_', '-', ','), '', strtolower($uploadedData['head']['name']));
							$uploadedData['head']['type'] = 'image';
							$fileHandler = new FM_Components_Util_UploadHandler($uploadedData['head']);
							$folder = $fileHandler->setFolder('auxpage_headers');
							if($fileHandler->move()){
								$_POST['header'] = $name;
							}
						}
						FM_Components_Util_Affiliates::updateStatement(array('active'=>1), $_POST);
						$this->_redirect('/root/affiliates');
					}

				}
			}

			public function aboutusAction() {
				$this->view->util = true;
				$this->view->sidenav = $this->view->partial('root/parts/sidenavs/utils.phtml', array('selected'=>'about'));
				$statement = new FM_Components_Util_AboutUs(array('active'=>1));
				$this->view->form = $form = new FM_Forms_Root_AboutUs(array('src'=>$statement->getHeader(), 'src2'=>$statement->getCarousel()));
				$form->title->setValue($statement->getTitle());
				$form->file->setValue($statement->getHeader());
				$form->statement->setValue($statement->getStatement());
				$this->view->tiny = true;
				if ($this->_request->isPost()) {
					$formData = $this->_request->getPost();
					if ($form->isValid($formData)) {
						$uploadedData = $form->getValues();
						$this->view->tiny = true;
						if($uploadedData['file']['name'] != '') {
							$name = $uploadedData['file']['name'] = '1' . '_' . time() . str_ireplace(array(' ', '_', '-', ','), '', strtolower($uploadedData['file']['name']));
							$uploadedData['file']['type'] = 'image';
							$fileHandler = new FM_Components_Util_UploadHandler($uploadedData['file']);
							$folder = $fileHandler->setFolder('auxpage_headers');
							if($fileHandler->move()){
								$_POST['medianame'] = $name;
								list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $folder . '/' . $name);
								$_POST['width'] = $width;
								$_POST['height'] = $height;
							}
						}
						if($uploadedData['head']['name'] != '') {
							$name = $uploadedData['head']['name'] = '1' . '_' . time() . str_ireplace(array(' ', '_', '-', ','), '', strtolower($uploadedData['head']['name']));
							$uploadedData['head']['type'] = 'image';
							$fileHandler = new FM_Components_Util_UploadHandler($uploadedData['head']);
							$folder = $fileHandler->setFolder('auxpage_headers');
							if($fileHandler->move()){
								$_POST['header'] = $name;
							}
						}
						FM_Components_Util_AboutUs::updateStatement(array('active'=>1), $_POST);
						$this->_redirect('/root/aboutus');
					}
				}
			}


			public function ourservicesAction() {
				$this->view->util = true;
				$this->view->sidenav = $this->view->partial('root/parts/sidenavs/utils.phtml', array('selected'=>'services'));
				$statement = new FM_Components_Util_OurServices(array('active'=>1));
				$this->view->form = $form = new FM_Forms_Root_OurServices(array('src'=>$statement->getHeader(), 'src2'=>$statement->getCarousel()));
				$form->title->setValue($statement->getTitle());
				$form->file->setValue($statement->getHeader());
				$form->statement->setValue($statement->getStatement());
				$this->view->tiny = true;
				if ($this->_request->isPost()) {
					$formData = $this->_request->getPost();
					if ($form->isValid($formData)) {
						$uploadedData = $form->getValues();
						$this->view->tiny = true;
						if($uploadedData['file']['name'] != '') {
							$name = $uploadedData['file']['name'] = '1' . '_' . time() . str_ireplace(array(' ', '_', '-', ','), '', strtolower($uploadedData['file']['name']));
							$uploadedData['file']['type'] = 'image';
							$fileHandler = new FM_Components_Util_UploadHandler($uploadedData['file']);
							$folder = $fileHandler->setFolder('auxpage_headers');
							if($fileHandler->move()){
								$_POST['medianame'] = $name;
								list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $folder . '/' . $name);
								$_POST['width'] = $width;
								$_POST['height'] = $height;
							}
						}
						if($uploadedData['head']['name'] != '') {
							$name = $uploadedData['head']['name'] = '1' . '_' . time() . str_ireplace(array(' ', '_', '-', ','), '', strtolower($uploadedData['head']['name']));
							$uploadedData['head']['type'] = 'image';
							$fileHandler = new FM_Components_Util_UploadHandler($uploadedData['head']);
							$folder = $fileHandler->setFolder('auxpage_headers');
							if($fileHandler->move()){
								$_POST['header'] = $name;
							}
						}
						FM_Components_Util_OurServices::updateStatement(array('active'=>1), $_POST);
						$this->_redirect('/root/ourservices');
					}
				}
			}

			public function forusersAction() {

				$this->view->util = true;
				$this->view->sidenav = $this->view->partial('root/parts/sidenavs/utils.phtml', array('selected'=>'users'));
				$statement = new FM_Components_Util_ForUsers(array('active'=>1));
				$this->view->form = $form = new FM_Forms_Root_ForUsers(array('src'=>$statement->getHeader(), 'src2'=>$statement->getCarousel()));
				//print $statement->getTitle();exit;
				$form->title->setValue($statement->getTitle());
				$form->file->setValue($statement->getHeader());
				$form->statement->setValue($statement->getStatement());
				$this->view->tiny = true;
				if ($this->_request->isPost()) {
					$formData = $this->_request->getPost();
					if ($form->isValid($formData)) {
						$uploadedData = $form->getValues();
						$this->view->tiny = true;
						if($uploadedData['file']['name'] != '') {
							$name = $uploadedData['file']['name'] = '1' . '_' . time() . str_ireplace(array(' ', '_', '-', ','), '', strtolower($uploadedData['file']['name']));
							$uploadedData['file']['type'] = 'image';
							$fileHandler = new FM_Components_Util_UploadHandler($uploadedData['file']);
							$folder = $fileHandler->setFolder('auxpage_headers');
							if($fileHandler->move()){
								$_POST['medianame'] = $name;
								list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $folder . '/' . $name);
								$_POST['width'] = $width;
								$_POST['height'] = $height;
							}
						}
						if($uploadedData['head']['name'] != '') {
							$name = $uploadedData['head']['name'] = '1' . '_' . time() . str_ireplace(array(' ', '_', '-', ','), '', strtolower($uploadedData['head']['name']));
							$uploadedData['head']['type'] = 'image';
							$fileHandler = new FM_Components_Util_UploadHandler($uploadedData['head']);
							$folder = $fileHandler->setFolder('auxpage_headers');
							if($fileHandler->move()){
								$_POST['header'] = $name;
							}
						}
						FM_Components_Util_ForUsers::updateStatement(array('active'=>1), $_POST);
						$this->_redirect('/root/forusers');
					}
				}
			}

			public function fororganizationsAction() {
				$this->view->util = true;
				$this->view->sidenav = $this->view->partial('root/parts/sidenavs/utils.phtml', array('selected'=>'orgs'));
				$statement = new FM_Components_Util_ForOrgs(array('active'=>1));
				$this->view->form = $form = new FM_Forms_Root_ForOrgs(array('src'=>$statement->getHeader(), 'src2'=>$statement->getCarousel()));
				//print $statement->getTitle();exit;
				$form->title->setValue($statement->getTitle());
				$form->file->setValue($statement->getHeader());
				$form->statement->setValue($statement->getStatement());
				$this->view->tiny = true;

				if ($this->_request->isPost()) {
					$formData = $this->_request->getPost();
					if ($form->isValid($formData)) {
						$uploadedData = $form->getValues();
						$this->view->tiny = true;
						if($uploadedData['file']['name'] != '') {
							$name = $uploadedData['file']['name'] = '1' . '_' . time() . str_ireplace(array(' ', '_', '-', ','), '', strtolower($uploadedData['file']['name']));
							$uploadedData['file']['type'] = 'image';
							$fileHandler = new FM_Components_Util_UploadHandler($uploadedData['file']);
							$folder = $fileHandler->setFolder('auxpage_headers');
							if($fileHandler->move()){
								$_POST['medianame'] = $name;
								list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $folder . '/' . $name);
								$_POST['width'] = $width;
								$_POST['height'] = $height;
							}
						}
						if($uploadedData['head']['name'] != '') {
							$name = $uploadedData['head']['name'] = '1' . '_' . time() . str_ireplace(array(' ', '_', '-', ','), '', strtolower($uploadedData['head']['name']));
							$uploadedData['head']['type'] = 'image';
							$fileHandler = new FM_Components_Util_UploadHandler($uploadedData['head']);
							$folder = $fileHandler->setFolder('auxpage_headers');
							if($fileHandler->move()){
								$_POST['header'] = $name;
							}
						}
						FM_Components_Util_ForOrgs::updateStatement(array('active'=>1), $_POST);
						$this->_redirect('/root/fororganizations');
					}
				}
			}

			public function foradvertisersAction() {
				$this->view->util = true;
				$this->view->sidenav = $this->view->partial('root/parts/sidenavs/utils.phtml', array('selected'=>'adv'));
				$statement = new FM_Components_Util_ForAdvertisers(array('active'=>1));
				$this->view->form = $form = new FM_Forms_Root_ForAdvertisers(array('src'=>$statement->getHeader(), 'src2'=>$statement->getCarousel()));
				//print $statement->getTitle();exit;
				$form->title->setValue($statement->getTitle());
				$form->file->setValue($statement->getHeader());
				$form->statement->setValue($statement->getStatement());
				$this->view->tiny = true;

				if ($this->_request->isPost()) {
					$formData = $this->_request->getPost();
					if ($form->isValid($formData)) {
						$uploadedData = $form->getValues();
						$this->view->tiny = true;
						if($uploadedData['file']['name'] != '') {
							$name = $uploadedData['file']['name'] = '1' . '_' . time() . str_ireplace(array(' ', '_', '-', ','), '', strtolower($uploadedData['file']['name']));
							$uploadedData['file']['type'] = 'image';
							$fileHandler = new FM_Components_Util_UploadHandler($uploadedData['file']);
							$folder = $fileHandler->setFolder('auxpage_headers');
							if($fileHandler->move()){
								$_POST['medianame'] = $name;
								list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $folder . '/' . $name);
								$_POST['width'] = $width;
								$_POST['height'] = $height;
							}
						}
						if($uploadedData['head']['name'] != '') {
							$name = $uploadedData['head']['name'] = '1' . '_' . time() . str_ireplace(array(' ', '_', '-', ','), '', strtolower($uploadedData['head']['name']));
							$uploadedData['head']['type'] = 'image';
							$fileHandler = new FM_Components_Util_UploadHandler($uploadedData['head']);
							$folder = $fileHandler->setFolder('auxpage_headers');
							if($fileHandler->move()){
								$_POST['header'] = $name;
							}
						}
						FM_Components_Util_ForAdvertisers::updateStatement(array('active'=>1), $_POST);
						$this->_redirect('/root/foradvertisers');
					}
				}
			}

			public function legalAction() {
				$this->view->util = true;
				$this->view->sidenav = $this->view->partial('root/parts/sidenavs/utils.phtml', array('selected'=>'leg'));
				$statement = new FM_Components_Util_Legal(array('active'=>1));
				$this->view->form = $form = new FM_Forms_Root_Legal(array('src'=>$statement->getHeader(), 'src2'=>$statement->getCarousel()));
				//print $statement->getTitle();exit;
				$form->title->setValue($statement->getTitle());
				$form->head->setValue($statement->getCarousel());
				$form->statement->setValue($statement->getStatement());
				$this->view->tiny = true;

				if ($this->_request->isPost()) {
					$formData = $this->_request->getPost();
					if ($form->isValid($formData)) {
						$uploadedData = $form->getValues();
						$this->view->tiny = true;
						if($uploadedData['file']['name'] != '') {
							$name = $uploadedData['file']['name'] = '1' . '_' . time() . str_ireplace(array(' ', '_', '-', ','), '', strtolower($uploadedData['file']['name']));
							$uploadedData['file']['type'] = 'image';
							$fileHandler = new FM_Components_Util_UploadHandler($uploadedData['file']);
							$folder = $fileHandler->setFolder('auxpage_headers');
							if($fileHandler->move()){
								$_POST['medianame'] = $name;
								list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $folder . '/' . $name);
								$_POST['width'] = $width;
								$_POST['height'] = $height;
							}
						}
						if($uploadedData['head']['name'] != '') {
							$name = $uploadedData['head']['name'] = '1' . '_' . time() . str_ireplace(array(' ', '_', '-', ','), '', strtolower($uploadedData['head']['name']));
							$uploadedData['head']['type'] = 'image';
							$fileHandler = new FM_Components_Util_UploadHandler($uploadedData['head']);
							$folder = $fileHandler->setFolder('auxpage_headers');
							if($fileHandler->move()){
								$_POST['header'] = $name;
							}
						}
						FM_Components_Util_Legal::updateStatement(array('active'=>1), $_POST);
						$this->_redirect('/root/legal');
					}
				}
			}

			public function privacyAction() {
				$this->view->util = true;
				$this->view->sidenav = $this->view->partial('root/parts/sidenavs/utils.phtml', array('selected'=>'pri'));
				$statement = new FM_Components_Util_PrivacyPolicy(array('active'=>1));
				$this->view->form = $form = new FM_Forms_Root_PrivacyPolicy(array('src'=>$statement->getHeader(), 'src2'=>$statement->getCarousel()));
				//print $statement->getTitle();exit;
				$form->title->setValue($statement->getTitle());
				$form->head->setValue($statement->getCarousel());
				$form->statement->setValue($statement->getStatement());
				$this->view->tiny = true;
				if ($this->_request->isPost()) {
					$formData = $this->_request->getPost();
					if ($form->isValid($formData)) {
						$uploadedData = $form->getValues();
						$this->view->tiny = true;
						if($uploadedData['file']['name'] != '') {
							$name = $uploadedData['file']['name'] = '1' . '_' . time() . str_ireplace(array(' ', '_', '-', ','), '', strtolower($uploadedData['file']['name']));
							$uploadedData['file']['type'] = 'image';
							$fileHandler = new FM_Components_Util_UploadHandler($uploadedData['file']);
							$folder = $fileHandler->setFolder('auxpage_headers');
							if($fileHandler->move()){
								$_POST['medianame'] = $name;
								list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $folder . '/' . $name);
								$_POST['width'] = $width;
								$_POST['height'] = $height;
							}
						}
						if($uploadedData['head']['name'] != '') {
							$name = $uploadedData['head']['name'] = '1' . '_' . time() . str_ireplace(array(' ', '_', '-', ','), '', strtolower($uploadedData['head']['name']));
							$uploadedData['head']['type'] = 'image';
							$fileHandler = new FM_Components_Util_UploadHandler($uploadedData['head']);
							$folder = $fileHandler->setFolder('auxpage_headers');
							if($fileHandler->move()){
								$_POST['header'] = $name;
							}
						}
						FM_Components_Util_PrivacyPolicy::updateStatement(array('active'=>1), $_POST);
						$this->_redirect('/root/privacy');
					}
				}
			}

			public function managefaqAction() {
				$this->view->faq = true;
				$this->view->form = new FM_Forms_Faq();
				$faqs = FM_Components_Util_Faq::getFaqs(array('orgId'=>0));
				$statement = new FM_Components_Util_FaqPage(array('active'=>1));
				$this->view->form = $form = new FM_Forms_Root_FaqPage(array('src'=>$statement->getHeader(), 'src2'=>$statement->getCarousel()));
				$this->view->sidenav = $this->view->partial('root/parts/sidenavs/utils.phtml', array('selected'=>'faq'));
				$this->view->faqs = $this->view->partial('root/parts/faqdisplay.phtml', array('faqs'=>$faqs));
				$this->view->form2 = $form2 =  new FM_Forms_Faq();


				if ($this->_request->isPost() && array_key_exists('new', $_POST)) {
					$formData = $this->_request->getPost();
					if ($form2->isValid($formData)) {
						if($_POST['id'] == '' || $_POST['id'] == 0) {
							FM_Components_Util_Faq::insertFaq($_POST);
						} else {
							$faq = new FM_Components_Util_Faq(array('id'=>$_POST['id']));
							$faq->edit($_POST);
						}
						$this->_redirect('/root/managefaq');
					}
				}


				if ($this->_request->isPost() && !array_key_exists('new', $_POST)) {
					$formData = $this->_request->getPost();
					if ($form->isValid($formData)) {
						$uploadedData = $form->getValues();
						$this->view->tiny = true;
						if($uploadedData['file']['name'] != '') {
							$name = $uploadedData['file']['name'] = '1' . '_' . time() . str_ireplace(array(' ', '_', '-', ','), '', strtolower($uploadedData['file']['name']));
							$uploadedData['file']['type'] = 'image';
							$fileHandler = new FM_Components_Util_UploadHandler($uploadedData['file']);
							$folder = $fileHandler->setFolder('auxpage_headers');
							if($fileHandler->move()){
								$_POST['medianame'] = $name;
								list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $folder . '/' . $name);
								$_POST['width'] = $width;
								$_POST['height'] = $height;
							}
						}
						if($uploadedData['head']['name'] != '') {
							$name = $uploadedData['head']['name'] = '1' . '_' . time() . str_ireplace(array(' ', '_', '-', ','), '', strtolower($uploadedData['head']['name']));
							$uploadedData['head']['type'] = 'image';
							$fileHandler = new FM_Components_Util_UploadHandler($uploadedData['head']);
							$folder = $fileHandler->setFolder('auxpage_headers');
							if($fileHandler->move()){
								$_POST['header'] = $name;
							}
						}
						FM_Components_Util_FaqPage::updateStatement(array('active'=>1), $_POST);
						$this->_redirect('/root/managefaq');
					}
				}

				//	$this->view->tiny = true;
			}

			public function ajaxupdatefaqAction() {
				switch( $_POST['action']) {
					case 'popedit' :
						$faq = new FM_Components_Util_Faq(array('id'=>$_POST['id']));
						print Zend_Json::encode($faq->toArray());
						exit;
					case 'delete' :
						if(FM_Components_Util_Faq::delete(array('id'=>$_POST['id']))) {
							print $_POST['id'];
							exit;

						} else {
							print 'false';
							exit;
						}
				}
			}

			public function addsportAction() {

				$this->view->org = $this->subcat = 'sport';
				$this->view->search = new FM_Forms_Register_Search();
				$this->view->catJumper = new FM_Forms_Register_SportsCatJumper();
				$this->view->tiny = true;
				if($_GET['delete']) {
					FM_Components_Sports::delete(array('id'=>$_GET['delete']));
					$this->_redirect('/root/addsport');
				} else {
					$this->view->sidenav = $this->view->partial('root/parts/sidenavs/organizations.phtml', array('org'=>'sports'));
					if($_POST['search']){
						$businesses = FM_Components_Organization::getOrgsLike($_POST['search'], 4);
					} elseif($this->_getParam('category') > 0 && $this->_getParam('category') != '19' ) {
						$catname = FM_Components_Sports::getCategoryName($this->_getParam('category'));
						$businesses = FM_Components_Sports::getByCategoryForRoot($this->_getParam('category'));
					}else {
						$businesses = FM_Components_Sports::getAllForRoot();
						$catname = 'all';
					}
					$this->view->clients = $this->view->partial('root/sports/clientindex.phtml', array('clients'=>$businesses,  'catname'=>$catname));
					$this->view->form = $businessForm =  new FM_Forms_Register_Sports(array(), FM_Components_Member::getAll());

					if ($this->_request->isPost() && !array_key_exists('search', $_POST)) {
						$insert = false;
						$update = false;
						$id;

						if($_POST['orgId'] != 0){$id = $_POST['orgId']; $update = true;}

						$formData = $this->_request->getPost();
						if ($businessForm->isValid($formData)) {
							$uploadedData = $businessForm->getValues();
							foreach ($_POST  as $value=>$t) {
								if(stristr($value, 'region')) {
									$uploadedData[$value] = 1;
								}
							}
							($update) ? FM_Components_Sports::update($uploadedData) : $id = FM_Components_Sports::insertSports($uploadedData);


							if($uploadedData['file']['name'] != '') {
								$name = $uploadedData['file']['name'] = '1' . '_' . time() . str_ireplace(array(' ', '_', '-', ','), '', strtolower($uploadedData['file']['name']));
								$uploadedData['file']['type'] = 'image';
								$fileHandler = new FM_Components_Util_UploadHandler($uploadedData['file']);
								$folder = $fileHandler->setFolder('logos');
								if($fileHandler->move()){
									//exit;
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
									//print (FM_Components_Util_Logo::hasRow($id)) ? 'yes' : 'no';
									if(!FM_Components_Util_Logo::hasRow($id)){$insertData['orgId'] = $id;}
									(FM_Components_Util_Logo::hasRow($id)) ? FM_Components_Util_Logo::updateBanner(array('orgId'=>$id), $insertData) : FM_Components_Util_Logo::insert($insertData);
								}
							} if($uploadedData['banner']['name'] != '') {
								$name = $uploadedData['banner']['name'] = '1' . '_' . time() . str_ireplace(array(' ', '_', '-', ','), '', strtolower($uploadedData['banner']['name']));
								$uploadedData['file']['type'] = 'image';
								$fileHandler = new FM_Components_Util_UploadHandler($uploadedData['banner']);
								$folder = $fileHandler->setFolder('logos');
								if($fileHandler->move()){
									list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $folder . '/' . $name);
									$insertData['fileName'] = $name;
									$insertData['width'] = $width;
									$insertData['height'] = $height;
									$insertData['type'] = 'LG';
									$insertData['active'] = '1';
									if(!FM_Components_Util_MiniwebBanner::hasRow($id)){$insertData['orgId'] = $id;}
									(FM_Components_Util_MiniwebBanner::hasRow($id)) ? FM_Components_Util_MiniwebBanner::updateBanner(array('orgId'=>$id), $insertData) : FM_Components_Util_MiniwebBanner::insert($insertData);
								}
							}
							if($uploadedData['icon']['name'] != '') {
								$name = $uploadedData['icon']['name'] = '1' . '_' . time() . str_ireplace(array(' ', '_', '-', ','), '', strtolower($uploadedData['icon']['name']));
								$uploadedData['file']['type'] = 'image';
								$fileHandler = new FM_Components_Util_UploadHandler($uploadedData['icon']);
								$folder = $fileHandler->setFolder('icons');
								if($fileHandler->move()){
									list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $folder . '/' . $name);
									$insertData['fileName'] = $name;
									$insertData['width'] = $width;
									$insertData['height'] = $height;
									$insertData['type'] = 'LG';
									$insertData['active'] = '1';
									if(!FM_Components_Util_Icon::hasRow($id)){$insertData['orgId'] = $id;}
									(FM_Components_Util_Icon::hasRow($id)) ? FM_Components_Util_Icon::updateIcon(array('orgId'=>$id), $insertData) : FM_Components_Util_Icon::insert($insertData);
								}
							}
							$this->_redirect('/root/addsport');
						}elseif(!array_key_exists('search', $_POST)) {
							$this->view->error = true;
						}
					}

				}

			}

			public function addnpAction() {

				$this->view->org = $this->subcat = 'np';
				$this->view->search = new FM_Forms_Register_Search();
				$this->view->catJumper = new FM_Forms_Register_NpCatJumper();
				$this->view->tiny = true;
				if($_GET['delete']) {
					FM_Components_NonProfit::delete(array('id'=>$_GET['delete']));
					$this->_redirect('/root/addnp');
				} else {
					if($_POST['search']){
						$businesses = FM_Components_Organization::getOrgsLike($_POST['search'], 3);
					}
					elseif($this->_getParam('category') > 0 && $this->_getParam('category') != '117' ) {
						$catname = FM_Components_Util_Category::getCategoryName($this->_getParam('category'), true);
						$businesses = FM_Components_NonProfit::getByCategoryForRoot($this->_getParam('category'));
					} else{
						$businesses = FM_Components_NonProfit::getActiveForRoot();
						$catname = 'all';
					}
					$this->view->sidenav = $this->view->partial('root/parts/sidenavs/organizations.phtml', array('org'=>'np'));
					$this->view->clients = $this->view->partial('root/nonprofit/clientindex.phtml', array('clients'=>$businesses, 'catname'=>$catname));
					$this->view->form = $businessForm =  new FM_Forms_Register_NonProfit(array(), FM_Components_Member::getAll());

					if ($this->_request->isPost() && !array_key_exists('search', $_POST)) {
						$insert = false;
						$update = false;
						$id;

						if($_POST['orgId'] != 0){$id = $_POST['orgId']; $update = true;}

						$formData = $this->_request->getPost();
						if ($businessForm->isValid($formData)) {
							$uploadedData = $businessForm->getValues();
							//	print_r($uploadedData);exit;
							foreach ($_POST  as $value=>$t) {
								if(stristr($value, 'region')) {
									$uploadedData[$value] = 1;
								}
							}
							$updateResult = ($update) ? FM_Components_NonProfit::update($uploadedData) : $id = FM_Components_NonProfit::insertNonProfit($uploadedData);
						} else {
							$updateResult = false;
						}

						if($uploadedData['file']['name'] != '') {
							$name = $uploadedData['file']['name'] = '1' . '_' . time() . str_ireplace(array(' ', '_', '-', ','), '', strtolower($uploadedData['file']['name']));
							$uploadedData['file']['type'] = 'image';
							$fileHandler = new FM_Components_Util_UploadHandler($uploadedData['file']);
							$folder = $fileHandler->setFolder('logos');
							if($fileHandler->move()){
								//exit;
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
								//print (FM_Components_Util_Logo::hasRow($id)) ? 'yes' : 'no';
								if(!FM_Components_Util_Logo::hasRow($id)){$insertData['orgId'] = $id;}
								(FM_Components_Util_Logo::hasRow($id)) ? FM_Components_Util_Logo::updateBanner(array('orgId'=>$id), $insertData) : FM_Components_Util_Logo::insert($insertData);
							}
						} if($uploadedData['banner']['name'] != '') {
							$name = $uploadedData['banner']['name'] = '1' . '_' . time() . str_ireplace(array(' ', '_', '-', ','), '', strtolower($uploadedData['banner']['name']));
							$uploadedData['file']['type'] = 'image';
							$fileHandler = new FM_Components_Util_UploadHandler($uploadedData['banner']);
							$folder = $fileHandler->setFolder('logos');
							if($fileHandler->move()){
								list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $folder . '/' . $name);
								$insertData['fileName'] = $name;
								$insertData['width'] = $width;
								$insertData['height'] = $height;
								$insertData['type'] = 'LG';
								$insertData['active'] = '1';
								if(!FM_Components_Util_MiniwebBanner::hasRow($id)){$insertData['orgId'] = $id;}
								(FM_Components_Util_MiniwebBanner::hasRow($id)) ? FM_Components_Util_MiniwebBanner::updateBanner(array('orgId'=>$id), $insertData) : FM_Components_Util_MiniwebBanner::insert($insertData);
							}
						}
						if($uploadedData['icon']['name'] != '') {
							$name = $uploadedData['icon']['name'] = '1' . '_' . time() . str_ireplace(array(' ', '_', '-', ','), '', strtolower($uploadedData['icon']['name']));
							$uploadedData['file']['type'] = 'image';
							$fileHandler = new FM_Components_Util_UploadHandler($uploadedData['icon']);
							$folder = $fileHandler->setFolder('icons');
							if($fileHandler->move()){
								list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $folder . '/' . $name);
								$insertData['fileName'] = $name;
								$insertData['width'] = $width;
								$insertData['height'] = $height;
								$insertData['type'] = 'LG';
								$insertData['active'] = '1';
								if(!FM_Components_Util_Icon::hasRow($id)){$insertData['orgId'] = $id;}
								(FM_Components_Util_Icon::hasRow($id)) ? FM_Components_Util_Icon::updateIcon(array('orgId'=>$id), $insertData) : FM_Components_Util_Icon::insert($insertData);
							}
						}
						if($updateResult) {
							$this->_redirect('/root/addnp/' . $this->_getParam('category'));
						} else {
							$this->view->error = true;
						}
					}

				}

			}

			public function addbusinessAction() {

				$this->view->org = 'b2b';
				$this->view->search = new FM_Forms_Register_Search();
				$this->view->catJumper = new FM_Forms_Register_CatJumper();
				$this->subcat = 'business';

				$this->view->tiny = true;
				if($_GET['delete']) {
					FM_Components_Business::delete(array('id'=>$_GET['delete']));
					$this->_redirect('/root/addbusiness');
				} else {
					$this->view->sidenav = $this->view->partial('root/parts/sidenavs/organizations.phtml', array('org'=>'b2b'));

					$pc = new FM_Models_FM_SearchPrimaryCategories ();
					$this->view->catSelect = $category = new Zend_Form_Element_Multiselect(array('label'=>'category :', 'name'=>'category', 'required'=>1));
					foreach($pc->getPrimaryCategories() as $index=>$values) {
						$category->addMultiOption($values['id'], $values['name']);
					}
					if($_POST['search']){
						$businesses = FM_Components_Organization::getOrgsLike($_POST['search'], 2);
					}
					elseif($this->_getParam('category') > 0 && $this->_getParam('category') != '117' ) {
						$catname = FM_Components_Util_Category::getCategoryName($this->_getParam('category'), false);
						$businesses = FM_Components_Business::getByCategoryForRoot($this->_getParam('category'));
					} elseif($this->_getParam('category') == '117'){
						$businesses = FM_Components_Business::getActiveForRoot();
						$catname = 'all';
					}else {
						$businesses = array();
					}
					$this->view->clients = $this->view->partial('root/business/clientindex.phtml', array('clients'=>$businesses, 'catname'=>$catname));
					$this->view->form = $businessForm =  new FM_Forms_Register_Business(array(), FM_Components_Member::getAll());
					//print __LINE__;exit;
					if ($this->_request->isPost() && !array_key_exists('search', $_POST)) {
						$insert = false;
						$update = false;
						$id;


						//	print_r($this->_request->getPost());exit;
						if($_POST['orgId'] != 0){$id = $_POST['orgId']; $update = true;}

						$formData = $this->_request->getPost();
						if ($businessForm->isValid($formData)) {
							$uploadedData = $businessForm->getValues();
							//print_r($uploadedData);exit;
							foreach ($_POST  as $value=>$t) {
								if(stristr($value, 'region')) {
									$uploadedData[$value] = 1;
								}
								if(stristr($value, 'subcat_')) {
									$uploadedData['category'][] = $t;
								}
							}


							($update) ? FM_Components_Business::updateBusiness($uploadedData) : $id = FM_Components_Business::insertBusiness($uploadedData);


							if($uploadedData['file']['name'] != '') {
								$name = $uploadedData['file']['name'] = '1' . '_' . time() . str_ireplace(array(' ', '_', '-', ','), '', strtolower($uploadedData['file']['name']));
								$uploadedData['file']['type'] = 'image';
								$fileHandler = new FM_Components_Util_UploadHandler($uploadedData['file']);
								$folder = $fileHandler->setFolder('logos');
								if($fileHandler->move()){
									//exit;
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
									//print (FM_Components_Util_Logo::hasRow($id)) ? 'yes' : 'no';
									if(!FM_Components_Util_Logo::hasRow($id)){$insertData['orgId'] = $id;}
									(FM_Components_Util_Logo::hasRow($id)) ? FM_Components_Util_Logo::updateBanner(array('orgId'=>$id), $insertData) : FM_Components_Util_Logo::insert($insertData);
								}
							} if($uploadedData['banner']['name'] != '') {
								$name = $uploadedData['banner']['name'] = '1' . '_' . time() . str_ireplace(array(' ', '_', '-', ','), '', strtolower($uploadedData['banner']['name']));
								$uploadedData['file']['type'] = 'image';
								$fileHandler = new FM_Components_Util_UploadHandler($uploadedData['banner']);
								$folder = $fileHandler->setFolder('logos');
								if($fileHandler->move()){
									list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $folder . '/' . $name);
									$insertData['fileName'] = $name;
									$insertData['width'] = $width;
									$insertData['height'] = $height;
									$insertData['type'] = 'LG';
									$insertData['active'] = '1';
									if(!FM_Components_Util_MiniwebBanner::hasRow($id)){$insertData['orgId'] = $id;}
									(FM_Components_Util_MiniwebBanner::hasRow($id)) ? FM_Components_Util_MiniwebBanner::updateBanner(array('orgId'=>$id), $insertData) : FM_Components_Util_MiniwebBanner::insert($insertData);
								}
							}

							if($uploadedData['icon']['name'] != '') {
								$name = $uploadedData['icon']['name'] = '1' . '_' . time() . str_ireplace(array(' ', '_', '-', ','), '', strtolower($uploadedData['icon']['name']));
								$uploadedData['file']['type'] = 'image';
								$fileHandler = new FM_Components_Util_UploadHandler($uploadedData['icon']);
								$folder = $fileHandler->setFolder('icons');
								if($fileHandler->move()){
									list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $folder . '/' . $name);
									$insertData['fileName'] = $name;
									$insertData['width'] = $width;
									$insertData['height'] = $height;
									$insertData['type'] = 'LG';
									$insertData['active'] = '1';
									if(!FM_Components_Util_Icon::hasRow($id)){$insertData['orgId'] = $id;}
									(FM_Components_Util_Icon::hasRow($id)) ? FM_Components_Util_Icon::updateIcon(array('orgId'=>$id), $insertData) : FM_Components_Util_Icon::insert($insertData);
								}
							}
							$this->_redirect('/root/addbusiness/' . $this->_getParam('category'));
						} else {
							$this->view->error = true;
						}
					}

				}

			}

			public function manageusersAction() {
				$orgType = $this->_getParam('type');
				$this->view->userform = new FM_Forms_Root_Users(array(), FM_Components_Member::getAllForDropdown());
				$this->view->sidenav = $this->view->partial('root/parts/sidenavs/users.phtml', array('selected'=>$orgType));
				if($_POST['search'] && $_POST['search'] != ''){
					$users = FM_Components_Member::getMemberRecordsLike($_POST['search']);
				} else if($_POST['orgsearch']) {
					$users = array();
					$orgs = FM_Components_Organization::searchOrgs($_POST['orgsearch']);
					//print_r($orgs);exit;
					foreach ($orgs as $org) {
						$members = FM_Components_Organization::getOrgMembers($org['id']);
						foreach ($members as $member) {
							$user =  FM_Components_Member::getMemberRecord($member['uid']);
							if($user['id']) {
								$users[$user['uname']] = $user;
							}
						}
					}
					asort($users);
				}
				else if($this->_request->getParam ( 'search' )) {
					$users = array(FM_Components_Member::getMemberRecord($this->_request->getParam ( 'search' )));
				}
				else if($orgType !== 0) {
					$users = FM_Components_Member::getMemberRecordsByOrgType($orgType);
				} else {
					$users = FM_Components_Member::getAllMemberRecords();
				}
				//print count($users);exit;
				$this->view->users = $users;
			}

			public function userdetailsAction() {
				if ($id = $this->_request->getParam ( 'id' )) {
					$user = new FM_Components_Member(array('id'=>$id));
					//print_r($user);exit;
					$this->view->member = $user;
					$this->view->orgs = $user->getOrgs();
					$this->view->borgs = FM_Components_Business::getActiveForRoot();
					$this->view->nporgs = FM_Components_NonProfit::getActiveForRoot();
					$this->view->sportsorgs = FM_Components_Sports::getAllForRoot();
					//$this->view->allOrgs = FM_Components_Organization::getActiveOrgs();
				}
			}


			public function ajaxgetclientAction() {
				if($_POST) {
					switch ($_POST['clienttype']) {
						case 2 :
							$client = new FM_Components_Business(array('id'=>$_POST['id']));
							print Zend_Json::encode($client->toArray());
							break;
						case 3 :
							$client = new FM_Components_NonProfit(array('id'=>$_POST['id']));
							print Zend_Json::encode($client->toArray());
							break;
						case 4 :
							$client = new FM_Components_Sports(array('id'=>$_POST['id']));
							print Zend_Json::encode($client->toArray());
							break;
					}
				}
				exit;
			}

			public function managebannersAction() {
				$this->view->sidenav = $this->view->partial('root/parts/sidenavs/banner.phtml', array('selected'=>'manage'));
				$this->view->search = new FM_Forms_Register_Search();
				$this->view->orglist = FM_Components_Organization::getActiveOrgRecords();
				if(array_key_exists('search', $_POST)) {
					$coupons = array();
					$orgs = FM_Components_Organization::searchOrgs($_POST['search']);
					$banners = array();
					foreach ($orgs as $org) {
						$banners[] =  FM_Components_Banner::getOrgBanners($org['id']);
					}
					$rc = array();
					foreach ($banners as $banner){
						foreach ($banner as $c)	{
							$rc[] = $c->toArray();
						}
					}
					$this->view->banners = $rc;
				} else {
					$this->view->banners = FM_Components_Banner::getOrgBannerRecords($this->_getParam('orgid'));
				}
			}

			public function addbannertemplateAction() {
				$this->view->sidenav = $this->view->partial('root/parts/sidenavs/banner.phtml', array('selected'=>'addtemplate'));
				$this->view->form = $form = new FM_Forms_Root_AddBannerTemplate();

				if ($this->_request->isPost()) {
					$formData = $this->_request->getPost();
					if ($form->isValid($formData)) {
						$uploadedData = $form->getValues();
						if($uploadedData['file']['name'] != '') {
							$name = $uploadedData['file']['name'] = '1' . '_' . time() . str_ireplace(array(' ', '_', '-', ','), '', strtolower($uploadedData['file']['name']));
							$uploadedData['file']['type'] = 'image';
							$fileHandler = new FM_Components_Util_UploadHandler($uploadedData['file']);
							$folder = $fileHandler->setFolder('banner_templates');
							if($fileHandler->move()){
								$formData['image'] = $name;
								FM_Components_Util_BannerTemplate::insertTemplate($formData);
								$this->_redirect('/root/managebannertemplates');
							}
						}
					}
				}
			}

			public function managecouponsAction() {
				$this->view->coupon = 'managecoupon';
				$this->view->search = new FM_Forms_Register_Search();
				//print_r($_POST);exit;
				$this->view->sidenav = $this->view->partial('root/parts/sidenavs/coupon.phtml', array('selected'=>'managecoupon'));
				if(array_key_exists('search', $_POST)) {
					$coupons = array();
					$orgs = FM_Components_Organization::searchOrgs($_POST['search']);
					foreach ($orgs as $org) {
						$coupons[] =  FM_Components_Coupon::getAllOrgCoupons($org['id']);
					}
					foreach ($coupons as $coupon){
						foreach ($coupon as $c)	{
							$rc[] = $c;
						}
					}
					$this->view->coupons = $rc;
				}
				else if($this->_getParam('orgid') == 999999999) {
					$this->view->coupons = $coupons =  FM_Components_Coupon::getAllCoupons();
				}
				else {
					$this->view->coupons = $coupons =  FM_Components_Coupon::getAllOrgCoupons($this->_getParam('orgid'));
				}
				$this->view->orglist = FM_Components_Business::getActiveRecords();
			}

			public function managecoupontemplatesAction() {
				$this->view->coupon = 'managetemplate';
				$this->view->sidenav = $this->view->partial('root/parts/sidenavs/coupon.phtml', array('selected'=>'managetemplate'));
				$this->view->templates = FM_Components_Util_CouponTemplate::getAll();
			}

			public function addcoupontemplateAction() {
				$this->view->coupon = 'add';
				$this->view->sidenav = $this->view->partial('root/parts/sidenavs/coupon.phtml', array('selected'=>'addtemplate'));
				$this->view->form = $form =  new FM_Forms_Root_AddCouponTemplate();

				if ($this->_request->isPost()) {
					$formData = $this->_request->getPost();
					if ($form->isValid($formData)) {
						$uploadedData = $form->getValues();
						if($uploadedData['file']['name'] != '') {
							$name = $uploadedData['file']['name'] = '1' . '_' . time() . str_ireplace(array(' ', '_', '-', ','), '', strtolower($uploadedData['file']['name']));
							$uploadedData['file']['type'] = 'image';
							$fileHandler = new FM_Components_Util_UploadHandler($uploadedData['file']);
							$folder = $fileHandler->setFolder('coupon_templates');
							if($fileHandler->move()){
								$formData['image'] = $name;
								FM_Components_Util_CouponTemplate::insertTemplate($formData);
								$this->_redirect('/root/managecoupontemplates');
							}
						}
					}
				}
			}

			public function ajaxtogglecoupontemplateAction() {
				if($_POST['id']) {
					if(FM_Components_Util_CouponTemplate::updateCouponTemplate(array('id'=>$_POST['id']), array('active'=>$_POST['active']))) {
						print '1';
						exit;
					} else {
						print '0';
						exit;
					}
				}
			}

			public function ajaxdeletecoupontemplateAction() {
				if($_POST['id']) {
					if(FM_Components_Util_CouponTemplate::deleteCouponTemplate(array('id'=>$_POST['id']))) {
						print $_POST['id'];
						exit;
					} else {
						print '0';
						exit;
					}
				}
			}

			public function managebannertemplatesAction() {
				$this->view->sidenav = $this->view->partial('root/parts/sidenavs/banner.phtml', array('selected'=>'managebannertemplates'));
				$this->view->templates = $banners = FM_Components_Util_BannerTemplate::getAll();
			}

			public function ajaxdeletebannertemplateAction() {
				if($_POST['id']) {
					if(FM_Components_Util_BannerTemplate::deleteBannerTemplate(array('id'=>$_POST['id']))) {
						print $_POST['id'];
						exit;
					} else {
						print '0';
						exit;
					}
				}
			}

			public function ajaxdeletepaybannerAction() {
				if($_POST['id']) {
					if(FM_Components_Util_PayBanner::deletePayBanner(array('id'=>$_POST['id']))) {
						print $_POST['id'];
						exit;
					} else {
						print '0';
						exit;
					}
				}
			}

			public function ajaxdeletefullbannerAction() {
				if($_POST['id']) {
					if(FM_Components_Util_FullBanner::deleteFullBanner(array('id'=>$_POST['id']))) {
						print $_POST['id'];
						exit;
					} else {
						print '0';
						exit;
					}
				}
			}


			public function ajaxtogglebannertemplateAction() {
				if($_POST['id']) {
					if(FM_Components_Util_BannerTemplate::updateBannerTemplate(array('id'=>$_POST['id']), array('active'=>$_POST['active']))) {
						print '1';
						exit;
					} else {
						print '0';
						exit;
					}
				}
			}

			public function ajaxtogglebannerAction() {
				if($_POST['id']) {
					if(FM_Components_Banner::updateBanner(array('id'=>$_POST['id']), array('active'=>$_POST['active']))) {
						print '1';
						exit;
					} else {
						print '0';
						exit;
					}
				}
			}

			public function ajaxtogglepaybannerAction() {
				if($_POST['id']) {
					if(FM_Components_Util_PayBanner::updatePayBanner(array('id'=>$_POST['id']), array('active'=>$_POST['active']))) {
						print '1';
						exit;
					} else {
						print '0';
						exit;
					}
				}
			}

			public function ajaxtogglefullbannerAction() {
				if($_POST['id']) {
					if(FM_Components_Util_FullBanner::updateFullBanner(array('id'=>$_POST['id']), array('active'=>$_POST['active']))) {
						print '1';
						exit;
					} else {
						print '0';
						exit;
					}
				}
			}



			public function ajaxtoggleusepaybannerAction() {
				if(array_key_exists('active', $_POST)) {
					if(FM_Components_SiteConfig::update(array('id'=>1), array('paybanners'=>$_POST['active']))) {
						print '1';
						exit;
					} else {
						print '0';
						exit;
					}
				}
			}

			public function ajaxtoggleusefullbannerAction() {
				if(array_key_exists('active', $_POST)) {
					if(FM_Components_SiteConfig::update(array('id'=>1), array('fullbanners'=>$_POST['active']))) {
						print '1';
						exit;
					} else {
						print '0';
						exit;
					}
				}
			}

			public function feedbackAction() {
				$this->view->sidenav = $this->view->partial('root/parts/sidenavs/utils.phtml', array('selected'=>'fbk'));
				$this->view->feedback = FM_Components_Util_Feedback::getAll();
				$statement = new FM_Components_Util_FeedbackPage(array('active'=>1));
				$this->view->form = $form = new FM_Forms_Root_FeedbackPage(array('src'=>$statement->getHeader(), 'src2'=>$statement->getCarousel()));

				if ($this->_request->isPost() && !array_key_exists('new', $_POST)) {
					$formData = $this->_request->getPost();
					if ($form->isValid($formData)) {
						$uploadedData = $form->getValues();
						$this->view->tiny = true;
						if($uploadedData['file']['name'] != '') {
							$name = $uploadedData['file']['name'] = '1' . '_' . time() . str_ireplace(array(' ', '_', '-', ','), '', strtolower($uploadedData['file']['name']));
							$uploadedData['file']['type'] = 'image';
							$fileHandler = new FM_Components_Util_UploadHandler($uploadedData['file']);
							$folder = $fileHandler->setFolder('auxpage_headers');
							if($fileHandler->move()){
								$_POST['medianame'] = $name;
								list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $folder . '/' . $name);
								$_POST['width'] = $width;
								$_POST['height'] = $height;
							}
						}
						if($uploadedData['head']['name'] != '') {
							$name = $uploadedData['head']['name'] = '1' . '_' . time() . str_ireplace(array(' ', '_', '-', ','), '', strtolower($uploadedData['head']['name']));
							$uploadedData['head']['type'] = 'image';
							$fileHandler = new FM_Components_Util_UploadHandler($uploadedData['head']);
							$folder = $fileHandler->setFolder('auxpage_headers');
							if($fileHandler->move()){
								$_POST['header'] = $name;
							}
						}
						FM_Components_Util_FeedbackPage::updateStatement(array('active'=>1), $_POST);
						$this->_redirect('/root/feedback');
					}
				}
			}

			public function ajaxtogglefeedbackAction() {
				if($_POST['id']) {
					$newValue = ($_POST['active'] == 'true') ? 1 : 0;
					if(FM_Components_Util_Feedback::updateFeedback(array('id'=>$_POST['id']), array('approved'=>$newValue))) {
						print '1';
						exit;
					} else {
						print '0';
						exit;
					}
				}
			}

			public function ajaxclientactivateAction() {
				if($_POST['id']) {
					if(FM_Components_Organization::updateActive(array($_POST))) {
						print '1';
					} else {
						print '0';
					}
				}
				exit;
			}

			public function ajaxremoveuserfromorgAction() {
				if(FM_Components_Organization::removeMember($_POST['oid'],$_POST['uid'])) {
					print '1';
					exit;
				} else {
					print '0';
					exit;
				}
			}

			public function ajaxdeleteuserAction() {
				if($_POST['id']) {
					if(FM_Components_Member::deleteMember(array('id'=>$_POST['id']))) {
						print $_POST['id'];
						exit;
					} else {
						print '0';
						exit;
					}
				}
			}

			public function ajaxaddusertoorgAction() {
				if($_POST['oid']) {

					if($member = new FM_Components_Member(array('id'=>$_POST['uid']))) {
						if($member->inOrg($_POST['oid'])) {
							print '2';
							exit;
						}
					}
					if(FM_Components_Member::addUserOrg($_POST)) {
						print '1';
						exit;
					} else {
						print '0';
						exit;
					}
				}
			}

			public function ajaxupdateuserinfoAction() {
				if($_POST['uid']) {
					if(FM_Components_Member::update(array('uid'=>$_POST['uid']), array($_POST['key']=>$_POST['value']))) {
						print '1';
						exit;
					} else {
						print '0';
						exit;
					}
				}
			}

			public function ajaxcheckunameAction() {
				if($_POST['uname']) {
					print (FM_Components_Member::userNameExists($_POST['uname']))? '1' : '0';
					exit;
				}
				print '0';
				exit;
			}

			public function quickadduserAction() {
				$this->_helper->layout->setLayout('root/quickadduser');
				$this->view->form = $form = new FM_Forms_Register_UserAjax();
			}

			public function adduserAction() {
				$this->view->users = true;
				$this->view->sidenav = $this->view->partial('root/parts/sidenavs/users.phtml', array('selected'=>5));
				$this->view->form = $form = new FM_Forms_Register_User();
				if ($this->_request->isPost()) {
					$formData = $this->_request->getPost();
					if ($form->isValid($formData)) {
						$uploadedData = $form->getValues();
						if($id = FM_Components_Member::addMember($uploadedData)) {
							$this->_redirect('/root/adduser');
						} else {

						}

					}

				}

			}

			public function ajaxadduserAction() {
				if($_POST['uname']) {
					if($id = FM_Components_Member::addMember($_POST)) {
						print $id;
						exit;
					} else {
						print '0';
						exit;
					}

				}
			}

			public function ajaxgetregionAction() {
				if($_POST['id']) {
					if($town = new FM_Components_Util_Town(array('id'=>$_POST['id']))) {
						print $town->getRegion();
						exit;
					} else {
						print '0';
						exit;
					}

				}
			}

			public function ajaxgetsubcatsAction() {
				if($_POST['id']) {
					if($cat = new FM_Components_Util_Category(array('id'=>$_POST['id']))) {
						if($_POST['orgId']){
							print Zend_Json::encode(array('subs'=>$cat->getSubcats(false, $_POST['orgId'])));
						} else {
							print Zend_Json::encode(array('subs'=>$cat->getSubcats()));
						}
						exit;
					} else {
						print Zend_Json::encode(array('subs'=>array()));
						exit;
					}

				}
			}

			public function ajaxdeletecouponAction() {
				if($_POST && array_key_exists('id', $_POST) && $_POST['id'] != '') {
					if(FM_Components_Coupon::deleteCoupon(array('id'=>$_POST['id']))) {
						print $_POST['id'];
					} else {
						print '0';
					}
				} else {
					print '0';
				}
				exit;
			}

			public function ajaxdeletebannerAction() {
				if($_POST && array_key_exists('id', $_POST) && $_POST['id'] != '') {
					if(FM_Components_Banner::deleteBanner(array('id'=>$_POST['id']))) {
						print $_POST['id'];
					} else {
						print '0';
					}
				} else {
					print '0';
				}
				exit;
			}
}