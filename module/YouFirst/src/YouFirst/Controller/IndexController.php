<?php

namespace YouFirst\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbtractActionController
{
   /**
    * The index action. This is the default action for the controller.
	* Right now, it's just being used for debug output for the time being.
	* @return \Zend\View\Model\ViewModel $viewModel;
    */
	
	public function indexAction()
	{
	   $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_admin');
	   
	   $viewModel = new ViewModel();
	   $viewModel->params = $this->params()->fromRoute();
	   $viewModel->result = $result;
	   
	   $errorHandling = $this->getServiceLocator()->get('phoenix-errorhandler');
	   $viewModel->erros = $errorHandling->getErrors();
	   
       return $viewModel;	   
	
	}
	
	public function viewlistAction()
	{
	  $viewModel = new ViewModel();
	  $viewModel->test = 'This is the viewList for action for youfirst!';
	  
	  return $viewModel;
	
	}



}