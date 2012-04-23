<?php
$frontEnd = Zend_Controller_Front::getInstance();
$router   = $frontEnd->getRouter();

$router->addRoute('ajaxphotogallery', new Zend_Controller_Router_Route('/ajaxphotogallery', array(
	'controller' => 'sports',
	'action' => 'ajaxphotogallery',
)));

$router->addRoute('ajaxsportssignin', new Zend_Controller_Router_Route('/ajaxsportssignin', array(
	'controller' => 'sports',
	'action' => 'ajaxsportssignin',
)));

$router->addRoute('ajaxsportssignout', new Zend_Controller_Router_Route('/ajaxsportssignout', array(
	'controller' => 'sports',
	'action' => 'ajaxsportssignout',
)));

$router->addRoute('ajaxsportsrequestaccount', new Zend_Controller_Router_Route('/ajaxsportsrequestaccount', array(
	'controller' => 'sports',
	'action' => 'ajaxsportsrequestaccount',
)));

$router->addRoute('ajaxdeletesportsemail', new Zend_Controller_Router_Route('/ajaxdeletesportsemail', array(
	'controller' => 'sports',
	'action' => 'ajaxdeletesportsemail',
)));

$router->addRoute('ajaxsportschangepwd', new Zend_Controller_Router_Route('/ajaxsportschangepwd', array(
	'controller' => 'sports',
	'action' => 'ajaxsportschangepwd',
)));

$router->addRoute('sportstoggleprotected', new Zend_Controller_Router_Route('/ajaxsportstoggleprotected', array(
	'controller' => 'sports',
	'action' => 'ajaxsportstoggleprotected',
)));

$router->addRoute('ajaxsportsretrievepwd', new Zend_Controller_Router_Route('/ajaxsportsretrievepwd', array(
	'controller' => 'sports',
	'action' => 'ajaxsportsretrievepwd',
)));

$router->addRoute('ajaxaddsportsuser', new Zend_Controller_Router_Route('/ajaxaddsportsuser', array(
	'controller' => 'sports',
	'action' => 'ajaxaddsportsuser',
)));

$router->addRoute('ajaxdeletesportsuser', new Zend_Controller_Router_Route('/ajaxdeletesportsuser', array(
	'controller' => 'sports',
	'action' => 'ajaxdeletesportsuser',
)));

$router->addRoute('ajaxsendpassword', new Zend_Controller_Router_Route('/ajaxsendpassword', array(
	'controller' => 'sports',
	'action' => 'ajaxsendpassword',
)));

$router->addRoute('ajaxsendemails', new Zend_Controller_Router_Route('/ajaxsendemails', array(
	'controller' => 'sports',
	'action' => 'ajaxsendemails',
)));

/**
$router->addRoute('search', new Zend_Controller_Router_Route('/search/region/:region', array(
	'controller' => 'search',
	'action' => 'index',
	'region' => 0
)));
**/

$router->addRoute('ajaxuploadcouponlogo', new Zend_Controller_Router_Route('/ajaxuploadcouponlogo', array(
	'controller' => 'index',
	'action' => 'ajaxuploadcouponlogo'
)));

$router->addRoute('slug', new Zend_Controller_Router_Route('/client/:slug', array(
	'controller' => 'index',
	'action' => 'slug',
	'slug'=>0
)));

$router->addRoute('ajaxupdatepwd', new Zend_Controller_Router_Route('/ajaxupdatepwd', array(
	'controller' => 'index',
	'action' => 'ajaxupdatepwd',
)));

$router->addRoute('ajaxuploadimagegallery', new Zend_Controller_Router_Route('/ajaxuploadimagegallery', array(
	'controller' => 'index',
	'action' => 'ajaxuploadimagegallery'
)));
$router->addRoute('ajaxuploadproductimage', new Zend_Controller_Router_Route('/ajaxuploadproductimage', array(
	'controller' => 'index',
	'action' => 'ajaxuploadproductimage'
)));
$router->addRoute('ajaxaddproduct', new Zend_Controller_Router_Route('/ajaxaddproduct', array(
	'controller' => 'index',
	'action' => 'ajaxaddproduct'
)));
$router->addRoute('ajaxaddrelisting', new Zend_Controller_Router_Route('/ajaxaddrelisting', array(
	'controller' => 'index',
	'action' => 'ajaxaddrelisting'
)));

$router->addRoute('ajaxdeletelisting', new Zend_Controller_Router_Route('/ajaxdeletelisting', array(
	'controller' => 'index',
	'action' => 'ajaxdeletelisting'
)));

$router->addRoute('ajaxdeleteproduct', new Zend_Controller_Router_Route('/ajaxdeleteproduct', array(
	'controller' => 'index',
	'action' => 'ajaxdeleteproduct'
)));

$router->addRoute('ajaxupdateprofile', new Zend_Controller_Router_Route('/ajaxupdateprofile', array(
	'controller' => 'index',
	'action' => 'ajaxupdateprofile'
)));

$router->addRoute('ajaxupdateorgdata', new Zend_Controller_Router_Route('/ajaxupdateorgdata', array(
	'controller' => 'index',
	'action' => 'ajaxupdateorgdata'
)));

$router->addRoute('ajaxuploadbannerlogo', new Zend_Controller_Router_Route('/ajaxuploadbannerlogo', array(
	'controller' => 'index',
	'action' => 'ajaxuploadbannerlogo'
)));

$router->addRoute('ajaxuploadtopbanner', new Zend_Controller_Router_Route('/ajaxuploadtopbanner', array(
	'controller' => 'index',
	'action' => 'ajaxuploadtopbanner'
)));


$router->addRoute('ajaxgetbannerconfig', new Zend_Controller_Router_Route('/ajaxgetbannerconfig', array(
	'controller' => 'index',
	'action' => 'ajaxgetbannerconfig'
)));

$router->addRoute('ajaxforumadd', new Zend_Controller_Router_Route('/ajaxforumadd', array(
	'controller' => 'index',
	'action' => 'ajaxforumadd'
)));

$router->addRoute('ajaxforumdelete', new Zend_Controller_Router_Route('/ajaxforumdelete', array(
	'controller' => 'index',
	'action' => 'ajaxforumdelete'
)));

$router->addRoute('bannerpreview', new Zend_Controller_Router_Route('/bannerpreview/:bannerid', array(
	'controller' => 'index',
	'action' => 'bannerpreview',
	'bannerid'=>0
)));

$router->addRoute('couponpreview', new Zend_Controller_Router_Route('/couponpreview/:couponid', array(
	'controller' => 'index',
	'action' => 'couponpreview',
	'couponid'=>0
)));

$router->addRoute('sitemap', new Zend_Controller_Router_Route('/sitemap', array(
	'controller' => 'utilities',
	'action' => 'sitemap'
)));

$router->addRoute('displayphotos', new Zend_Controller_Router_Route('/displayphotos/:orgId/:picId', array(
	'controller' => 'utilities',
	'action' => 'displayphotos',
	'orgId'=>0,
	'picId'=>0
)));

$router->addRoute('helppopup', new Zend_Controller_Router_Route('/helppopup/:code', array(
	'controller' => 'utilities',
	'action' => 'helppopup',
	'code'=>0
)));

$router->addRoute('ourservices', new Zend_Controller_Router_Route('/ourservices', array(
	'controller' => 'utilities',
	'action' => 'ourservices'
)));

$router->addRoute('notfound', new Zend_Controller_Router_Route('/notfound/:slug', array(
	'controller' => 'utilities',
	'action' => 'noorg',
	'slug'=>0
)));


$router->addRoute('affiliates', new Zend_Controller_Router_Route('/affiliates', array(
	'controller' => 'utilities',
	'action' => 'affiliates'
)));

$router->addRoute('foradvertisers', new Zend_Controller_Router_Route('/foradvertisers', array(
	'controller' => 'utilities',
	'action' => 'foradvertisers'
)));


$router->addRoute('aboutus', new Zend_Controller_Router_Route('/aboutus', array(
	'controller' => 'utilities',
	'action' => 'aboutus'
)));


$router->addRoute('privacypolicy', new Zend_Controller_Router_Route('/privacy', array(
	'controller' => 'utilities',
	'action' => 'privacypolicy'
)));

$router->addRoute('legal', new Zend_Controller_Router_Route('/legal', array(
	'controller' => 'utilities',
	'action' => 'legal'
)));

$router->addRoute('feedback', new Zend_Controller_Router_Route('/feedback', array(
	'controller' => 'utilities',
	'action' => 'feedback'
)));

$router->addRoute('forusers', new Zend_Controller_Router_Route('/forusers', array(
	'controller' => 'utilities',
	'action' => 'forusers'
)));

$router->addRoute('fororgs', new Zend_Controller_Router_Route('/fororganizations', array(
	'controller' => 'utilities',
	'action' => 'fororganizations'
)));

$router->addRoute('limecard_front', new Zend_Controller_Router_Route('/limecard/:do/:var', array(
	'controller' => 'utilities',
	'action' => 'limecard',
	'do'=>0,
	'var'=>0
)));


$router->addRoute('getfeed', new Zend_Controller_Router_Route('/rss/getfeed/:id', array(
	'controller' => 'rss',
	'action' => 'getfeed',
	'id' => '0'
)));

$router->addRoute('banner_delete', new Zend_Controller_Router_Route('/admin/banner/delete/:id', array(
	'controller' => 'banner',
	'action' => 'delete',
	'id' => ''
)));

$router->addRoute('banner_edit', new Zend_Controller_Router_Route('/admin/banner/edit/', array(
	'controller' => 'banner',
	'action' => 'edit',
)));

$router->addRoute('orgs_index', new Zend_Controller_Router_Route('/org/:id', array(
	'controller' => 'orgs',
	'action' => 'index',
	'id'=>0
)));

$router->addRoute('b2b_index', new Zend_Controller_Router_Route('/merchant/:id', array(
	'controller' => 'b2b',
	'action' => 'index',
	'id'=>0
)));


$router->addRoute('sports_index', new Zend_Controller_Router_Route('/sports/:id', array(
	'controller' => 'sports',
	'action' => 'index',
	'id'=>0
)));

/**
$router->addRoute('addcoupon', new Zend_Controller_Router_Route('/utilities/ajaxaddcoupon', array(
	'controller' => 'b2b',
	'action' => 'ajaxaddcoupon'
)));
**/
$router->addRoute('faqs_front', new Zend_Controller_Router_Route('/faqs/:orgId', array(
	'controller' => 'utilities',
	'action' => 'faqs',
	'orgId'=>0
)));

$router->addRoute('contactt', new Zend_Controller_Router_Route('/contact/:orgId', array(
	'controller' => 'utilities',
	'action' => 'contactus',
	'orgId'=>0
)));

$router->addRoute('mission', new Zend_Controller_Router_Route('/mission/:orgId', array(
	'controller' => 'utilities',
	'action' => 'missionstatement',
	'orgId'=>0
)));

$router->addRoute('check_root_email', new Zend_Controller_Router_Route('/root/email/:dept', array(
	'controller' => 'root',
	'action' => 'checkemail',
	'dept'=>0
)));

$router->addRoute('managesiteemails', new Zend_Controller_Router_Route('/root/managesiteemails/:todo/:id', array(
	'controller' => 'root',
	'action' => 'managesiteemails',
	'todo'=>'',
	'id'=>0
)));

/**
$router->addRoute('manageusers', new Zend_Controller_Router_Route('/root/manageusers/:id', array(
	'controller' => 'root',
	'action' => 'manageusers',
	'uid'=>0
)));
**/

$router->addRoute('approve_feedback', new Zend_Controller_Router_Route('/root/feedback', array(
	'controller' => 'root',
	'action' => 'feedback'
)));

$router->addRoute('root_manage_coupons', new Zend_Controller_Router_Route('/root/managecoupons/:orgid', array(
	'controller' => 'root',
	'action' => 'managecoupons',
	'orgid'=>0
)));

$router->addRoute('root_manage_banners', new Zend_Controller_Router_Route('/root/managebanners/:orgid', array(
	'controller' => 'root',
	'action' => 'managebanners',
	'orgid'=>0
)));

$router->addRoute('root_manage_users', new Zend_Controller_Router_Route('/root/manageusers/:type/:search', array(
	'controller' => 'root',
	'action' => 'manageusers',
	'type'=>0,
	'search'=>''
)));

$router->addRoute('root_user_details', new Zend_Controller_Router_Route('/root/userdetails/:id', array(
	'controller' => 'root',
	'action' => 'userdetails',
	'id'=>0
)));

$router->addRoute('add_bsines', new Zend_Controller_Router_Route('/root/addbusiness/:category', array(
	'controller' => 'root',
	'action' => 'addbusiness',
	'category'=>0
)));

$router->addRoute('add_np', new Zend_Controller_Router_Route('/root/addnp/:category', array(
	'controller' => 'root',
	'action' => 'addnp',
	'category'=>0
)));

$router->addRoute('addsport', new Zend_Controller_Router_Route('/root/addsport/:category', array(
	'controller' => 'root',
	'action' => 'addsport',
	'category'=>0
)));

$router->addRoute('limecard', new Zend_Controller_Router_Route('/root/limecard/:do/:id', array(
	'controller' => 'root',
	'action' => 'limecard',
	'do'=>0,
	'id'=>0
)));


$router->addRoute('viewevent', new Zend_Controller_Router_Route('/viewevent/:id', array(
	'controller' => 'utilities',
	'action' => 'viewevent',
	'id'=>0
)));