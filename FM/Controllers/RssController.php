<?php
Zend_Loader::loadClass('Zend_Controller_Action');
Zend_Loader::loadClass('FM_Models_FM_Rss');

class RssController extends Zend_Controller_Action{

	public function getfeedAction() {
		if($id = $this->_request->getParam ( 'id' ))	{
			$model = new FM_Models_FM_Rss();
			$feeds = $model->getRssByKeys(array('id'=>$id));
			if(count($feeds)) {
				print $feeds['rss'];
				exit;
			}else {
				print 'pleasse provide a feed id' . __LINE__;
				exit;
			}
		} else {
			print 'pleasse provide a feed id' . __LINE__;
			exit;
		}
	}

}