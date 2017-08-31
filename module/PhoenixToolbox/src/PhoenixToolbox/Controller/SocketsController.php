<?php

namespace PhoenixToolbox\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Modul\ViewModel;

class SocketsController extends AbstractActionController
{

  public function indexAction()
  {
   return new ViewModel();
  }

  public function saveAction()
  {
   return new ViewModel();
  }
  
  public function retrieveAction()
  { 
    return new ViewModel();  
  }
  
}