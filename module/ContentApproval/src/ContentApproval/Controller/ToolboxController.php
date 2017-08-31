<?php

/**
 * PhoenixRates ToolboxController
 *
 * The ToolboxController for the PhoenixRates Module
 *
 * If it is a toolbox action for the phoenixRates module, it goes here.
 *
 * @category    Toolbox
 * @package     PhoenixRates
 * @subpackage  Controllers
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace ContentApproval\Controller;

/**
 * PhoenixRates ToolboxController
 *
 * The ToolboxController for the PhoenixRates Module
 *
 * If it is a toolbox action for the phoenixRates module, it goes here.
 *
 * This will need to have a way of deciding whether to show all rates, or just the property for the current site
 * depending upon the user.
 *
 * @category    Toolbox
 * @package     ContentApproval
 * @subpackage  Controllers
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 14.2
 * @since       
 * @author      Daniel Yang <dyang@travelclick.com>
 */
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
//use DoctrineORMModule\Form\Annotation\AnnotationBuilder;

class ToolboxController extends \ListModule\Controller\ToolboxController
{
    protected $editItemTemplate = "phoenix-contentapproval/toolbox/edit-item";
    protected $tasksMenu = array('editList'=>'Manage Items', "workflow"=>'Manage Workflow', 'settings' => 'CAS Settings');

    /**
     * The base admin level for actions. Each action (or task within an action)
     * can require a higher adminLevel, but this is the lowest allowed.
     * 
     * @var string
     */    
    protected $baseAdminLevel = \Users\Service\Acl::PERMISSIONS_RESOURCE_ADMIN;    

    public function settingsAction()
    {
        $serviceLocator = $this->getServiceLocator();
        $moduleName = $this->getModuleName($this->module);
        $service = $serviceLocator->get("phoenix-$moduleName");

        if (!$service->getCurrentUser()->isDeveloper())
        {
            $this->redirect()->toRoute('contentApproval-toolbox'); 
        }

        $request = $this->getRequest();
        $objectManager = $this
                ->getServiceLocator()
                ->get('Doctrine\ORM\EntityManager');

        $repository = $objectManager
                        ->getRepository('\ContentApproval\Entity\ContentApprovalSettings')->findAll();

        //echo 'count' . count($repository);

        if (count($repository) == 0) {
            $entry = new \ContentApproval\Entity\ContentApprovalSettings();
            $objectManager->persist($entry);
            $objectManager->flush();
        }

        $entry = $objectManager->find('\ContentApproval\Entity\ContentApprovalSettings', 1);

        /*$builder = new AnnotationBuilder($objectManager);
        $form = $builder->createForm($entry);
        $form->setHydrator(new DoctrineHydrator($objectManager, '\ContentApproval\Entity\ContentApprovalSettings'));
        $form->bind($entry);
        */
        $view = new ViewModel();
        $components = $objectManager->getRepository('\Toolbox\Entity\Components')->findBy(array('casAllowed' => 1));
        
        
        if ($request->isPost()) {
            //$entry->setEnabled($this->params()->fromPost('enabled'));
            $componentCas = $this->params()->fromPost('component');
            
            foreach ($components as $component)
            {
                $component->setCasEnabled(isset($componentCas[$component->getId()]));
                $objectManager->persist($component);
                $objectManager->flush();
            }
            
            $this->flashMessenger()->addSuccessMessage('Settings saved');
            $this->redirect()->toRoute('contentApproval-toolbox'); 
        }

        //$view->setVariable('form', $form);
        
        $view->setVariable('components', $components);
        
        return $view;
    }
    
    public function workflowAction()
    {
        $this->redirect()->toRoute('contentApproval_workflow-toolbox'); 
    }
    
    public function approvalsAction()
    {
        $this->redirect()->toRoute('contentApproval_approvals-toolbox'); 
    }
    
    public function approveAction()
    {
        
        $serviceLocator = $this->getServiceLocator();
        $moduleName = $this->getModuleName($this->module);
        $service = $serviceLocator->get("phoenix-$moduleName");
        
        $approvalCount = $service->approve($this->params()->fromQuery('id'));
        
        $casItem = $service->getItem($this->params()->fromQuery('id'));
                
        if ($approvalCount > 0) $this->redirect()->toRoute('contentApproval-toolbox'); 
        
    }
    
    public function rollbackAction()
    {
        $serviceLocator = $this->getServiceLocator();
        $moduleName = $this->getModuleName($this->module);
        $service = $serviceLocator->get("phoenix-$moduleName");
        
        $casItem = $service->getItem($this->params()->fromQuery('id'));
                
        $approvalCount = $service->getApprovalCount($this->params()->fromQuery('id'));
        
        if ($approvalCount > 0)  
        {
            $service->rollback($this->params()->fromQuery('id'));
            $this->redirect()->toRoute('contentApproval-toolbox'); 
        }
            
    }
    
    public function editListAction()
    {
                $serviceLocator = $this->getServiceLocator();
        $moduleName = $this->getModuleName($this->module);
        $viewManager = $serviceLocator->get('view-manager');
        $mergedConfig = $serviceLocator->get('MergedConfig');
        $service = $serviceLocator->get("phoenix-$moduleName");

        $taskViewModel = $this->doEditList($service);
        $taskViewModel->setTemplate($this->getTemplateName($moduleName));
        $taskViewModel->setVariables($viewManager->getViewModel()->getVariables());
        $taskViewModel->setVariable('itemsPerPage', $mergedConfig->get('items-per-page'));
        $taskViewModel->setVariable('editListOptions', $this->editListOptions);
        $taskViewModel->setVariable('title', $this->editListTitle);
        $taskViewModel->setVariable('users', $service->getServiceManager()->get('phoenix-users'));
        $taskViewModel->setVariable('user',$service->getCurrentUser());
        
        $this->setAdditionalVars($taskViewModel);

        if ( $serviceLocator->has("$moduleName-layout") )
        {
            $viewModel = $serviceLocator->get("$moduleName-layout");
        }
        else
        {
            $viewModel = $serviceLocator->get('listModule-layout');
        }

        $viewModel->setVariables($viewManager->getViewModel()->getVariables());
        $viewModel->setVariable('toolboxRoute', $this->toolboxRoute);
        $viewModel->setVariable('tasksMenu', $this->tasksMenu);
        $viewModel->setVariable('moduleName', $this->module);

        //Set this to empty by default
        $viewModel->moduleRouteParams = array();
        $viewModel->addChild($taskViewModel, 'taskContent');

        return $viewModel;
    }
    
     public function editItemAction()
    {
         
       $serviceLocator = $this->getServiceLocator();
        $moduleName = $this->getModuleName($this->module);
        $viewManager = $serviceLocator->get('view-manager');
        $service = $serviceLocator->get("phoenix-$moduleName");
        $mergedConfig = $serviceLocator->get('MergedConfig');

        if (!$service->getServiceManager()) {
            $service->setServiceManager($serviceLocator);
        }

        $item = $service->getItem($this->params('itemId'));
        
        /**
         * Get the form to use for this action
         */
        $form = $service->getForm("\\$this->module\\Form\\{$this->modsing}Form", $serviceLocator);

        /**
         * Lets get the edit item statues for this module
         */
        $this->editItemOptions = $service->getItemFormOptions($form, $this->editItemOptions, $mergedConfig);

        /**
         * If newItem then lets filter the edit item options for this module
         */
        if ($this->newItem && $this->newItemOptions && $this->editItemOptions)
        {
            $this->editItemOptions = array_intersect($this->newItemOptions, $this->editItemOptions);
        }

        $taskViewModel = $this->doEditItem($service, $form, null, $this->newItem);
        
        if (!$taskViewModel instanceof ViewModel) return $taskViewModel;

        $taskViewModel->setTemplate($this->editItemTemplate);
        $taskViewModel->setVariables($viewManager->getViewModel()->getVariables());
        $taskViewModel->setVariable('havePropertyList', $this->havePropertyList);
        $taskViewModel->setVariable('editItemOptions', $this->editItemOptions);
        $taskViewModel->setVariable('title', $this->editItemTitle);
        $taskViewModel->setVariable('moduleName', $this->module);
        $taskViewModel->setVariable('itemName', $this->modsing);
        $taskViewModel->setVariable('item', $item);
        $taskViewModel->setVariable('groups', $service->getAdminEntityManager()->getRepository('Users\Entity\Admin\Groups'));

        $viewModel = $serviceLocator->get('listModule-layout');
        $viewModel->setVariables($viewManager->getViewModel()->getVariables());
        $viewModel->setVariable('toolboxRoute', $this->toolboxRoute);
        $viewModel->setVariable('tasksMenu', $this->tasksMenu);
        $viewModel->setVariable('title', $this->editItemTitle);
        $viewModel->setVariable('moduleName', $this->module);
        //Setting this to empty by default
        $viewModel->moduleRouteParams = array();        
        $viewModel->addChild($taskViewModel, 'taskContent');

        return $viewModel;
     }
}
