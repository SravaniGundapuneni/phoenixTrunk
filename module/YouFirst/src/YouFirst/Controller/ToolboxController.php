<?php
/**
 *  YouFirst Toolbox Controller
 * 
 *  The Toolbox Controller for the YouFirst Module
 *
 *  If it is a toolbox action for the youFirst Module.
 *
 * @category      Toolbox
 * @package       YouFirst
 * @subpackage    Controllers
 * @copyright     Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license       All Rights Reserved 
 * @version       Release 13.5
 * @since         File available since release 13.5
 * @author        Kevin Davis <kedavis@travelclick.com>
 * @filesource
 */
 
 namespace YouFirst\Controller;
 
 use ListModule\Controller\ToolboxController as ListModuleToolbox;
 
 use Zend\View\Model\ViewModel;
 use YouFirst\Form\YouFirstLogin;
 use YouFirst\Form\YouFirstForgetPwd;
 use YouFirst\Form\YouFirstAccount;
 use PhoenixProperties\Form\PropertyForm;

 /**
 *  YouFirst Toolbox Controller
 * 
 *  The Toolbox Controller for the YouFirst Module
 *
 *  If it is a toolbox action for the youFirst Module.
 *
 * @category      Toolbox
 * @package       YouFirst
 * @subpackage    Controllers
 * @copyright     Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license       All Rights Reserved 
 * @version       Release 13.5
 * @since         File available since release 13.5
 * @author        Kevin Davis <kedavis@travelclick.com>
 */
 
  class ToolboxController extends ListModuleToolBox
  {
   const DEFAULT_NOITEM_ROUTE = 'youFirst-toolbox';
   
   protected $baseAdminLevel = \Users\Service\Acl::PERMISSIONS_RESOURCE_WRITE;
   
   public function indexAction()
   {
     return $this->editlistAction();
   }
   
   public function reservationListing()
   {
   
   
   }
   
   
   public function editlistAction()
   {
     $viewManager =  $this->getServiceLocator()->get('view-manager');
	 $youfirstService = $this->getServiceLocator()->get('youfirst');
   }
   
   /**
    * loginuserAction
	*
	* Login for the user
	* @return ViewModel $viewModel
	*/
	public function loginUserAction()
	{
	  $acl = $this->getServiceLocator()->get('youfirst-users-acl');
	  $viewManager = $this->getServiceLocator()->get('view-manager');
	  $viewModel = $this->getServiceLocator()->get('users-layout');
	  
	  $taskViewModel = new ViewModel();
	  $taskViewModel->setVariables($viewManager->getViewModel()->getVariables());
	  $taskViewModel->setTemplate('youfirst/toolbox/user');
	  
	  $users = $this->getServiceLocator()->get('youfirst-users');
	  
	  $form = $acl->addGroupField(new YouFirstLogin());
	  
	  $request = $this->getRequest();
	  
	  if ($request->isPost()) {
	      $user = $this->getServiceLocator()->get('youfirst-users')->createUserModel();
		  $form->setInputFilter($acl->addGroupInputFilter($user->getInputFilter()));
		  $form->setData($request->getPost());		  
	  
	  }
	
	 $taskViewModel->form = $form;
	 
	 $viewModel->addChild($taskViewModel, 'taskContent');
	 $viewModel->setVariables($viewManager->getViewModel()->getVariables());
	 
	 return $viewModel;
	
	}
	
	
	/**
	 * addAccount
	 *
	 * Add a new account
	 * @return ViewModel $viewModel
	 */
	public function addAccount()
	{
	  $acl = $this->getServiceLocator()->get('youfirst-account-acl');
	  $viewManager = $this->getServiceLocator()->get('view-manager');
	  $viewModel = $this->getServiceLocator()->get('youfirst-layout');
	  
	  $taskViewModel = new ViewModel();
	  $taskViewModel->setVariables($viewManager->getViewModel()->getVariables());
	  $taskViewModel->setTemplate('youfirst/toolbox/new-acount');
	  
	  $accounts = $this->getServiceLocator()->get('youfirst-accounts');
	  
	  $form = $this->getRequest();
	  
	  if ($request->isPost()) {
	    $account = $this->getServiceLocator()->get('youfirst-accounts')->createUserModel();
		$form->setInputFiter($acl->addGroupInputFilter($user->getInputFilter()));
		$form->setData($request->getPost());
		
		if ($form->isValid()) {
		  $accounts->save($account, $form->getData());
		  
		  $taskViewModel->userAdded = true;
		  $form = $acl->addGroupField(new YouFirstAccount());
		  
		 }
		  
	     $taskViewModel->form = $form;
		 
		 $viewModel->addChild($taskViewModel, 'taskContent');
		 $viewModel->setVariables($viewManger->getViewModel()->getVariables());
		 
		 return $viewModel;
		
	  
	  
	  }
	
	}
	
	/**
	 * gettemppasswordAction()
	 *
	 * Get a temp password
	 * @return ViewModel $viewModel
	 */
	public function gettemppasswordAction()
	{
	  $acl = $this->getSerivceLocator()->get('youfirst-accounts-acl');
	  $accounts = $this->getServiceLocator()->get('youfirst-accounts');
	  $viewManager = $this->getServiceLocator()->get('view-manager');
	  $viewModel->setVariables($viewManager->getViewModel()->getVariables());
	  
	  $itemId = (int) $this->params()->fromRoute('itemId', $this->params()->fromPost('id',0));
	  
	  if (!$itemId) {
	   return $this->redirect()->toRoute('itemId', $this->params()->fromPost('id',0));
	  }
	  
	  try {
	   $account = $accounts->getAccount($itemId, false);
	  }
	  catch (\Exception $ex) 
	  {
	   return $this->redirect()->toRoute('youfirst-toolbox',array('action' => 'listAccounts'));
	  }	  
	  $taskViewModel->username = $user->getUserName();	  
	  $form = new YouFirstForgetPwd();
	  
	   $form->setData($request->getPost());
		 
	   if ($form->isValid()) {
		 $users->save($user, $form->getData());		   
		 $viewModel->userEdited = true;
		 $redirectUrl = $this->url()->fromRoute('youfirst-toolbox', array('action' => 'listUsers')) . '?changePasswordAccount=' . $user->getUsername();		   
		 return $this->redirect()->toUrl($redirectUrl);	 
		 
	   }
	  else	  {
		 $form->bind($user);
	  }
	  
	  $taskViewModel->form = $form;
		$viewModel->addChild($taskViewModel, 'taskContent');
		
		return $viewModel; 
	
	}
	
	
    
	/**
	 * changepasswordAction()
	 *
	 * Change a user's password
	 * @return ViewModel $viewModel
	 */
	 
    public function changepasswordAction()
    {
        $acl = $this->getServiceLocator()->get('youfirst-accounts-acl');
        $accounts = $this->getServiceLocator()->get('youfirst-accounts');
        $viewManager = $this->getServiceLocator()->get('view-manager');
        $viewModel->setVariables($viewManager->getViewModel()->getVariables());

        $itemId = (int) $this->params()->fromRoute('itemId', $this->params()->fromPost('id',0));

        if (!$itemId) {
            return $this->redirect()->toRoute('youfirst-toolbox', array('action' => 'listAccounts'));
        }
	   
        try {
            $account = $accounts->getAccount($itemId, false);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('youfirst-toolbox', array('action' => 'index' ));
        }
	   
        $taskViewModel->username = $user->getUserName();

        $form = new YouFirstLogin();
        $form->remove('username');
        $form->get('password')->setLabel('New Password');
	   
        $request = $this->getRequest();
	   
        if ($request->isPost()) {
            $form->getInputFilter($user->getInputFilter());
            $inputFilter->remove('username');

            $form->setData($request->getPost());
		 
            if ($form->isValid()) {
		        $users->save($user, $form->getData());
                $viewModel->userEdited = true;
                $redirectUrl = $this->url()->fromRoute('youfirst-toolbox', array('action' => 'listUsers')) . '?changePasswordAccount=' . $user->getUsername();
    		   
                return $this->redirect()->toUrl($redirectUrl);
            } else {
                $form->bind($user);
            }

            $taskViewModel->form = $form;
		    $viewModel->addChild($taskViewModel, 'taskContent');
		    return $viewModel; 
	    }
	}
}