<?php
/**
 * File for the Users PermissionsController
 *
 * @category    Toolbox
 * @package     Users
 * @subpackage  Controller
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */
namespace Users\Controller;

use ListModule\Controller\ToolboxController as ListModuleToolbox;

use Zend\Mvc\MvcEvent;

use Zend\View\Model\ViewModel;

use Users\Form\GroupForm;

/**
 * The PermissionsController for the Users module
 *
 * This is a second toolbox controller that exists so the main toolbox controller won't get cluttered.
 * All of the group actions will go here. 
 *
 * @category    Toolbox
 * @package     Users
 * @subpackage  Controller
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 */
class PermissionsController extends BaseToolboxController
{
    /**
     * The default route to use if there is no itemId passed to a page.
     */
    const DEFAULT_NOITEM_ROUTE = 'users-permissions-legacypath-toolbox';

    /**
     * The base admin level for actions. Each action (or task within an action)
     * can require a higher adminLevel, but this is the lowest allowed.
     * 
     * @var string
     */
    protected $baseAdminLevel = \Users\Service\Acl::PERMISSIONS_RESOURCE_SUPER_ADMIN;

    /**
     * The Index Action. 
     * @return void
     */
    public function indexAction()
    {
        return $this->editlistAction();
    }

    /**
     * The editList Action
     * @return void
     */
    public function editlistAction()
    {
        $viewManager = $this->getServiceLocator()->get('view-manager');
        //Get the Properties Service
        $permissionsService = $this->getServiceLocator()->get('phoenix-users-permissions'); //Dependency Injected

        //Return the viewModel
        $taskViewModel = $this->doEditList($permissionsService); 
        $taskViewModel->setTemplate('users/permissions/edit-list');
        $viewModel = $this->getServiceLocator()->get('users-layout');
        $viewModel->addChild($taskViewModel, 'taskContent');

        $itemName = $this->params()->fromQuery('itemName', '');

        if ($itemName) {
            $taskViewModel->itemName = $itemName;
        }

        $taskViewModel->setVariables($viewManager->getViewModel()->getVariables());
        $viewModel->setVariables($viewManager->getViewModel()->getVariables());

        return $viewModel;        
    }

    /**
     * The editItem Action
     * @return void
     */
    public function edititemAction()
    {
        $viewManager = $this->getServiceLocator()->get('view-manager');

        $permissionsService = $this->getServiceLocator()->get('phoenix-users-permissions');  

        $groupForm = new GroupForm(); 

        $taskViewModel = $this->doEditItem($permissionsService, $groupForm);

        if ($taskViewModel instanceof ViewModel) {
            $taskViewModel->setTemplate('users/permissions/edit-item');            
            $viewModel = $this->getServiceLocator()->get('users-layout');
            $viewModel->addChild($taskViewModel, 'taskContent');

            $taskViewModel->setVariables($viewManager->getViewModel()->getVariables());
            $viewModel->setVariables($viewManager->getViewModel()->getVariables());

            return $viewModel;                
        }

        return $taskViewModel;
    }

    public function additemAction()
    {
        $viewManager = $this->getServiceLocator()->get('view-manager');

        $permissionsService = $this->getServiceLocator()->get('phoenix-users-permissions');  

        $groupForm = new GroupForm(); 

        $taskViewModel = $this->doAddItem($permissionsService, $groupForm);

        if ($taskViewModel instanceof ViewModel) {
            $taskViewModel->setTemplate('users/permissions/add-item');
            $viewModel = $this->getServiceLocator()->get('users-layout');
            $viewModel->addChild($taskViewModel, 'taskContent');

            $taskViewModel->setVariables($viewManager->getViewModel()->getVariables());
            $viewModel->setVariables($viewManager->getViewModel()->getVariables());

            return $viewModel;                 
        }

        return $taskViewModel;
    }

    public function deleteitemAction()
    {
        $permissionsService = $this->getServiceLocator()->get('phoenix-users-permissions');

        return $this->doDeleteItem($permissionsService);
    }

    public function onDispatch(MvcEvent $e)
    {
        $e->getViewModel()->setTemplate('toolbox-layout');

        return parent::onDispatch($e);
    }
}