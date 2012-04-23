<?php  
class FM_Controllers_ErrorController extends Zend_Controller_Action

{

	public function errorAction()

	{

		$errors = $this->_getParam('error_handler');

		switch ($errors->type) {

			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:

			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
				print 'ddd';exit;

			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:

				// 404 error -- controller or action not found
				$this->getResponse()->setRawHeader('HTTP/1.1 404 Not Found');
				$content = '';
                      break;

              }

       

              // Clear previous content

              $this->getResponse()->clearBody();
              $this->view->content = $content;

          }

      }