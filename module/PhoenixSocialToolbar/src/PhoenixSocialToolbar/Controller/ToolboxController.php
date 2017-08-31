<?php
/**
 * SocialToolbar Toolbox Controller
 * 
 * The ToolboxController for the SocialToolbar Module
 * 
 * If it is a toolbox action for the socialToolbar module.
 *
 * @category      Toolbox
 * @package       PhoenixSocialToolbar
 * @subpackage    Controllers
 * @copyright     Copyright (c) 2013 TravelClick, Inc (http://travelclick.com)
 * @license       All Rights Reserved
 * @version       Release 13.5
 * @since         File available since release 13.5
 * @author        Kevin Davis
 * @filesource
 */
namespace PhoenixSocialToolbar\Controller;

use Toolbox\Mvc\Controller\AbstractToolboxController;
use Zend\View\Model\ViewModel;
use PhoenixSocialToolbar\Model\PhoenixSocialToolbar;
use ListModule\Controller\ToolboxController as ListModuleToolbox;

class ToolboxController extends ListModuleToolbox
{
    protected $baseAdminLevel = \Users\Service\Acl::PERMISSIONS_RESOURCE_ADMIN;
    protected $module = 'home';
    protected $modsing;
    protected $toolboxRoute = '/phoenixtng/toolbox/tools/phoenixSocialToolbar/index';
    //protected $editItemTemplate = "toolbox/edit-item";
    protected $newItem = false;

    const DEFAULT_NOITEM_ROUTE = 'phoenix-socialtoolbar';

    public function indexAction()
    {
       return $this->viewItemAction();
    }

    public function viewItemAction()
    {
        $serviceLocator = $this->getServiceLocator();
        $templateResolver = $this->serviceLocator->get('Zend\View\Resolver\TemplatePathStack');
        $viewManager = $this->getServiceLocator()->get('view-manager');

        $service = $this->getServiceLocator()->get("phoenix-socialtoolbar");
        
        $fname = "\PhoenixSocialToolbar\Form\PhoenixSocialToolbarForm";
        $form = $service->getForm($fname);          
        $moduleName = "phoenixSocialToolbar";
        $taskViewModel = $this->doEditItem($service, $form, null, $this->newItem);
        if (!$taskViewModel instanceof ViewModel){
            return $taskViewModel;
        }

        $taskViewModel->setTemplate('social-toolbar/toolbox/index');
        $taskViewModel->setVariables($viewManager->getViewModel()->getVariables());
        $taskViewModel->setVariable('hiddenButtons', array('draft','trash','archive'));
        $taskViewModel->itemName = $this->modsing;
        $taskViewModel->title = "Social Media Toolbar";
        $taskViewModel->toolboxRoute = $this->toolboxRoute;  
       
        $viewModel = $serviceLocator->get('listModule-layout');
        $viewModel->setVariables($viewManager->getViewModel()->getVariables());
        $viewModel->setVariable('toolboxRoute', $this->toolboxRoute);
        $viewModel->setVariable('tasksMenu', $this->tasksMenu);
        $viewModel->setVariable('title', $this->editItemTitle);
        $viewModel->setVariable('moduleName', $this->module);
        $viewModel->addChild($taskViewModel, 'taskContent');
 $viewModel->moduleRouteParams = array('module' => lcfirst($moduleName));
        return $viewModel;
    }

    public function addItemAction()
    {
        $this->newItem = true;
        return $this->doEditItem();
    }

    public function doEditItem($moduleService, $itemForm, $viewModel = null, $newItem = false)
    {
        //Instantiate the ViewModel, if one isn't provided
        if (!$viewModel instanceof ViewModel) {
            $viewModel = new ViewModel();
        }

        //Remove any fields that should not be enabled
      $moduleService->removeDisabledFields($itemForm);

        //Get the ViewManager
        $viewManager = $this->getServiceLocator()->get('view-manager');

        //Pass the variables from the base viewModel to our action's viewModel
        $viewModel->setVariables($viewManager->getViewModel()->getVariables());       
  
        //Get the Request Object
        $request = $this->getRequest();         
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        
        $temp = $moduleService->getItems();
        $itemId = $temp ? $temp[0]->getId() : 1;
      
        if (!$newItem) {
            $viewModel->itemModel = $itemModel = $itemId == ''
                ? $moduleService->getItems()
                : $moduleService->getItems($itemId);
        } elseif(is_callable(array($moduleService, 'createModel'))) {
            $itemModel = $moduleService->createSocialToolbarModel();
        } 

        $request = $this->getRequest();
       
        if ($request->isPost())
        {

            //Add the Validators and InputFilters to the form  
            $itemForm->setInputFilter($itemModel[0]->getInputFilter());
            $itemForm->setData($request->getPost());
        
            //Check to see if the form is valid
            if ($itemForm->isValid())
            {                
                $this->save($itemModel[0], $itemForm->getData());               
               
                //Redirect to the main page for PhoenixRates, and include the itemId so the page will show that the user was edited.
                return $this->doRedirect('saved', $itemModel[0]->getId());
           }
        } elseif (!$newItem) {         
           //Bind the property's values to the form, so they will be shown in the correct fields.
          
           $itemForm->bind($itemModel[0]);
        } 
        
        //Add the form to the viewModel        
        $viewModel->form = $itemForm;

            
        //Return the ViewModel for rendering
        return $viewModel;
    }

    public function doRedirect($redirectType, $itemId = 0)
    {         
          switch($redirectType) {
          case 'saved':
                $redirect = $this->redirect()->toRoute(DEFAULT_NOITEM_ROUTE, array(
                        'action' => 'index',
                        'itemId' => $itemId
                    ));
                break;             
    }
        
        return $redirect;
    }
   
   public function save($model, $data)
   { 
        if (!$model) {
            $model = $this->createModel();
        }
        $model->loadFromArray($data);

        //////////// This piece of code is only needed if showStream and showFaces fields are used /////////////
        // they are replaced by a single field showStreamOrFaces wich has 0 or 1 value [IS 2013-11-27]
        $entity = $model->getEntity();
        $showStreamOrFaces = $entity->getShowStreamOrFaces();
        $entity->setShowStream($showStreamOrFaces?0:1);
        $entity->setShowFaces($showStreamOrFaces?1:0);
        ///////////////////////////////////////////////////////////////////////////////////////////////

        $model->save();
   }
}