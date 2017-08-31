<?php
/**
 * File for the Users GroupsController
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

use Zend\Mvc\MvcEvent;

use Zend\View\Model\ViewModel;

use Users\Form\GroupForm;

/**
 * The GroupsController for the Users module
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
class GroupsController extends BaseToolboxController
{
    /**
     * The default route to use if there is no itemId passed to a page.
     */
    const DEFAULT_NOITEM_ROUTE = 'users-groups-toolbox';

    /**
     * The base admin level for actions. Each action (or task within an action)
     * can require a higher adminLevel, but this is the lowest allowed.
     * 
     * @var string
     */
    protected $baseAdminLevel = \Users\Service\Acl::PERMISSIONS_RESOURCE_SUPER_ADMIN;

    public function __construct() {
        $this->modsing = 'Group';
        parent::__construct();
    }

    public function getModuleName($moduleName)
    {
        return 'users-groups';
    }

    protected function getTemplateName($moduleName)
    {
        return "users/groups/edit-list";
    }

    public function editlistAction()
    {
        $viewModel = parent::editlistAction();

        if ($viewModel instanceof ViewModel) {
            $viewModel->setTemplate('users/layout/module');
            $viewModel->title = 'Groups';
        }

        return $viewModel;
    }

    /**
     * The editItem Action
     * @return void
     */
    public function edititemAction()
    {
        $viewManager = $this->getServiceLocator()->get('view-manager');

        $groupsService = $this->getServiceLocator()->get('phoenix-users-groups');  
        $groupsService->setScope($this->params()->fromQuery('scope', 'site'));

        $groupForm = new GroupForm();

        $scope = $groupForm->get('scope');

        $scope->setAttributes(array('disabled' => 'disabled'));        

        $taskViewModel = $this->doEditItem($groupsService, $groupForm);

        if ($taskViewModel instanceof ViewModel) {
            $taskViewModel->setTemplate('edit-item');            
            $viewModel = $this->getServiceLocator()->get('users-layout');
            $viewModel->addChild($taskViewModel, 'taskContent');

            $taskViewModel->setVariables($viewManager->getViewModel()->getVariables());
            $taskViewModel->title = "Edit Group";
            $viewModel->setVariables($viewManager->getViewModel()->getVariables());

            return $viewModel;                
        }

        return $taskViewModel;
    }

    public function doEditItem($moduleService, $itemForm, $viewModel = null, $newItem = false, $redirectRoute = null, $redirectParams = null)
    {
        $taskViewModel = parent::doEditItem($moduleService, $itemForm, $viewModel, $newItem, $redirectRoute = null, $redirectParams = null);

        if ($taskViewModel instanceof ViewModel) {
            $taskViewModel->editItemOptions = array(
                'Save'    => 'publish',
                'Cancel'  => 'cancel',
            );
            $taskViewModel->setTemplate('list-module/toolbox/edit-item');

            $form = $taskViewModel->form;

            $action = ($newItem) ? 'addItem' : 'editItem';

            $subsite = $this->params()->fromRoute('subsite', '');

            if ($subsite) {
                $subsite = substr($subsite, 1);
            }

            $form->setAttribute('action', $this->url()->fromRoute('users-groups-toolbox', array('action' => $action, 'subsite' => $subsite)));
        }

        return $taskViewModel;
    }

    public function additemAction()
    {
        $viewManager = $this->getServiceLocator()->get('view-manager');

        $groupsService = $this->getServiceLocator()->get('phoenix-users-groups');  

        $groupForm = new GroupForm(); 

        $taskViewModel = $this->doAddItem($groupsService, $groupForm);

        if ($taskViewModel instanceof ViewModel) {
            $taskViewModel->title = "Add Group";
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
        $groupsService = $this->getServiceLocator()->get('phoenix-users-groups');

        return $this->doDeleteItem($groupsService);
    }

    public function setgrouppermsAction()
    {
        $viewManager = $this->getServiceLocator()->get('view-manager');

        $groupsService = $this->getServiceLocator()->get('phoenix-users-groups');
        $permissionsService = $this->getServiceLocator()->get('phoenix-users-permissions');
        $aclService = $this->getServiceLocator()->get('phoenix-users-acl');

        $itemId = (int) $this->params()->fromRoute('itemId', $this->params()->fromPost('id', 0));

        $subsite = $this->params()->fromRoute('subsite', '');

        if ($subsite) {
            $subsite = substr($subsite, 1);
        }

        if ($this->getRequest()->isPost()) {
            $params = $this->params()->fromPost();

            if ($action == 'cancel') {
                return $this->redirect()->toUrl($this->url()->fromRoute('users-groups-toolbox', array('subsite' => $subsite)));
            }
            $groupsService->setGroupPermissions($itemId, $params, $aclService->getAuthLevels());

            return $this->redirect()->toUrl($this->url()->fromRoute('users-groups-toolbox', array('subsite' => $subsite)));
        }

        $authLevels = $aclService->getAuthLevels();
        $permissions = $permissionsService->getItems();
       
        $groupModel = $groupsService->getItem($itemId);

        $groupPerms = $groupModel->getPermissions();

        $taskViewModel = new ViewModel();
        $taskViewModel->title = 'Set Group Permissions';
        $taskViewModel->setTemplate('users/groups/set-group-perms');
        $taskViewModel->authLevels = $authLevels;
        $taskViewModel->permissions = $permissions;
        $taskViewModel->groupPerms = $groupPerms;
        $taskViewModel->itemId = $itemId;

        $taskViewModel->editListOptions = array(
            'Save'    => 'publish',
            'Cancel'  => 'cancel',
        );

        $viewModel = $this->getServiceLocator()->get('users-layout');
        $viewModel->addChild($taskViewModel, 'taskContent');

        $taskViewModel->setVariables($viewManager->getViewModel()->getVariables());
        $viewModel->setVariables($viewManager->getViewModel()->getVariables());

        return $viewModel;                
    }

    public function onDispatch(MvcEvent $e)
    {
        $e->getViewModel()->setTemplate('toolbox-layout');

        return parent::onDispatch($e);
    }
}