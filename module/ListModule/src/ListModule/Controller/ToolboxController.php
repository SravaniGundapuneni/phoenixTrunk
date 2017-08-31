<?php

/**
 * The ListModule ToolboxController File
 *
 * @category    Toolbox
 * @package     ListModule
 * @subpackage  Controller
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace ListModule\Controller;

use Zend\View\Model\ViewModel;
use Zend\Mvc\MvcEvent;

/**
 * The ListModule ToolboxController Class
 *
 * THIS IS AN ABSTRACT CLASS
 *
 * In other words, do not try to set routes to this controller. It is meant as an abstract controller
 * for use by modules that build off of this.
 *
 * @category    Toolbox
 * @package     ListModule
 * @subpackage  Controller
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */
abstract class ToolboxController extends \Toolbox\Mvc\Controller\AbstractToolboxController
{
    /**
     * Module name and signature
     * @var string
     */
    protected $module = 'home';
    protected $modsing;
    protected $newItem = false;
    protected $havePropertyList = true;
    protected $tasksMenu = array();
    protected $editListOptions = array(
        'Toggle Re-Order' => 'toggleReorder',
        'Save Item Order' => 'orderListItems',
        'Save' => 'publish',
        'Archive' => 'archive',
        'Trash' => 'trash',
    );
    protected $newItemOptions = array(
        'Save' => 'publish',
        'Draft' => 'draft',
        'Cancel' => 'cancel',
    );
    protected $editItemOptions = array(
        'Save' => 'publish',
        'Draft' => 'draft',
        'Archive' => 'archive',
        'Trash' => 'trash',
        'Cancel' => 'cancel',
    );

    /**
     * The proper title to use for the different templates
     * @var string
     */
    protected $editItemTitle = 'Edit Item';
    protected $editListTitle = 'Select Item to edit';

    /**
     * The proper template to use for the editItem screen
     * @var string
     */
    protected $editItemTemplate = "list-module/toolbox/edit-item";
    protected $editListTemplate = "list-module/toolbox/edit-list";

    /**
     * The base admin level for actions. Each action (or task within an action)
     * can require a higher adminLevel, but this is the lowest allowed.
     *
     * @var string
     */
    protected $baseAdminLevel = \Users\Service\Acl::PERMISSIONS_RESOURCE_WRITE;

    /**
     * The route to use in setting up the the module task menu (the left hand menu)
     * @var string
     */
    protected $toolboxRoute = 'home/toolbox-root';
    protected $additionalRouteParams = array();
    protected $socketsRoute = 'toolbox-sockets';

    /**
     * __construct
     *
     * @access public
     *
     */
    public function __construct()
    {
        preg_match("/(.+?)\\\/", get_class($this), $moduleNamespace);
        $this->module = $moduleNamespace = array_pop($moduleNamespace);
        //define("DEFAULT_NOITEM_ROUTE", $this->toolboxRoute = lcfirst("$moduleNamespace-toolbox"));
        $this->defaultNoItemRoute = $this->toolboxRoute = lcfirst("$moduleNamespace-toolbox");
        $this->editListTitle = $this->editListHeader = "Select $this->modsing to edit";
        $this->newItemOptions = array_reverse($this->newItemOptions);
        $this->editItemOptions = array_reverse($this->editItemOptions);
        $this->editItemTitle = "Edit $this->modsing";

        $this->socketsRoute = lcfirst($this->module) . '-sockets';
    }

    public function onDispatch(MvcEvent $e)
    {
        $e->getViewModel()->socketsRoute = $this->socketsRoute;

        parent::onDispatch($e);
    }

    /**
     * getModulename
     *
     * Strip Phoenix from our module name
     *
     * @access public
     * @param  string $moduleName
     * @return string str_ireplace('phoenix', '', strtolower($moduleName))
     */
    public function getModuleName($moduleName)
    {
        return str_ireplace('phoenix', '', strtolower($moduleName));
    }

    private function getModuleNameForAttachment($moduleName)
    {
        return str_replace(' ', '-', lcfirst($moduleName));
    }
    
    /**
     * getTemplateName
     *
     * Strip Phoenix from our module name
     *
     * @access protected
     * @param  string $moduleName
     * @return string str_ireplace('phoenix', '', strtolower($moduleName))
     */
    protected function getTemplateName($moduleName)
    {
        return "phoenix-$moduleName/toolbox/edit-list";
    }

    /**
     * indexAction
     *
     * @access public
     * The default action for the Controller
     * @return mixed $this->editlistAction()
     */
    public function indexAction()
    {
        return $this->editlistAction();
    }

    // a dummy method to satisfy the call
    // for a virtual function if does not exist
    protected function setAdditionalVars($view)
    {
        
    }

    /**
     * editlistAction
     *
     * @access public
     * The action that handles the list of Module Items
     * @return mixed $viewModel
     *
     */
    public function editlistAction()
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

        $this->setAdditionalVars($taskViewModel);

        if ($serviceLocator->has("$moduleName-layout")) {
            $viewModel = $serviceLocator->get("$moduleName-layout");
        } else {
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

    /**
     * editItemAction function
     *
     * @access public
     * The action that handles editing a Module Item
     * @return mixed $viewModel
     *
     */
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

        $service->getTranslateFields();
        /**
         * Get the form to use for this action
         */
        
        $form = $this->getForm($service);

        /**
         * Lets get the edit item statues for this module
         */
        $this->editItemOptions = $service->getItemFormOptions($form, $this->editItemOptions, $mergedConfig);

        /**
         * If newItem then lets filter the edit item options for this module
         */
        if ($this->newItem && $this->newItemOptions && $this->editItemOptions) {
            $this->editItemOptions = array_intersect($this->newItemOptions, $this->editItemOptions);
        }

        $taskViewModel = $this->doEditItem($service, $form, null, $this->newItem);

        if (!$taskViewModel instanceof ViewModel)
            return $taskViewModel;

        $taskViewModel->setTemplate($this->editItemTemplate);
        $taskViewModel->setVariables($viewManager->getViewModel()->getVariables());
        $taskViewModel->setVariable('havePropertyList', $this->havePropertyList);
        $taskViewModel->setVariable('editItemOptions', $this->editItemOptions);
        $taskViewModel->setVariable('title', $this->editItemTitle);
        $taskViewModel->setVariable('moduleName', $this->module);
        $taskViewModel->setVariable('itemName', $this->modsing);

        $taskViewModel->setVariable('translateFields', $service->getTranslateFields());

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

    /**
     * addItemAction
     *
     * @access public
     * The additem Action method
     * @return mixed $this->editItemAction()
     *
     */
    public function addItemAction()
    {
        $this->newItem = true;
        $viewModel = $this->editItemAction();

        return $viewModel;
    }

    /**
     * addItemAction function
     *
     * The additem Action method
     * @return mixed $viewModel
     *
     */
    public function addcategoryAction()
    {
        $this->newCategory = true;
        return $this->editcategoryAction();
    }

    /**
     * editcategoryAction function
     *
     * @access public
     *
     */
    public function editcategoryAction()
    {
        echo 'This is the edit category action.';
        die('FILE: ' . __FILE__ . ' LINE: ' . __LINE__);
    }

    /**
     * editcategoryAction function
     *
     * @access public
     */
    public function editcategorylistAction()
    {
        echo 'This is the edit categories list ';
        die('FILE: ' . __FILE__ . ' LINE: ' . __LINE__);
    }

    /**
     * editcategoryAction function
     *
     * @access public
     * @param  mixed $moduleService
     * @param  mixed $viewModel
     * @return mixed $viewModel
     */
    public function doEditList($moduleService, $viewModel = null)
    {
        if (!$viewModel instanceof ViewModel) {
            $viewModel = new ViewModel();
        }

        // Checks to see if we've been given back an itemId for an edited item.
        $editedId = (int) $this->params()->fromRoute('itemId', 0);

        $request = $this->getRequest();

        if ($request->isPost()) {
            $params = $this->params()->fromPost();

            if (isset($params['action']) && isset($params['items']) && is_array($params['items'])) {


                switch ($params['action']) {
                    case 'trash':
                        $moduleService->trash($params['items']);
                        break;
                    case 'archive':
                        $moduleService->archive($params['items']);
                        break;
                    case 'publish':
                        $moduleService->publish($params['items']);
                        break;
                    case 'orderListItems':
                        $moduleService->orderListItems($params['items'], $params['itemsList'], true);
                        break;
                    case 'toggleReorder': break;
                }
            }
        }

        //If we have an editedId, we want the template to output a message saying the user was edited.
        if ($editedId) {
            //Gets the property from the editedId
            $editedItem = $moduleService->getItem($editedId);

            if ($editedItem) {
                //Set's the propertyEdited variable in the template to the edited Property's hotelName
                $viewModel->editedItem = $editedItem;
            }
        } else {
            $viewModel->editedItem = false;
        }

       
        if (isset($moduleService->pagination))
        {
            $moduleService->listSearch = $this->params()->fromQuery('s');
            $moduleService->currentPage = $this->params()->fromQuery('page');
            $viewModel->items = $moduleService->getItems();
            $viewModel->paginator = $moduleService->paginator;
            $viewModel->search = $this->params()->fromQuery('s');
        }
        else
        {
             //Get the list of properties and pass it to the viewModel
            $viewModel->items = $moduleService->getItems();
        }
        
        
        return $viewModel;
    }

    /**
     * doEditItem function
     *
     * @access public
     * @param  mixed $moduleService
     * @param  mixed $itemForm
     * @param  mixed $viewModel
     * @param  mixed $newItem
     * @return mixed $viewModel
     */
    public function doEditItem($moduleService, $itemForm, $viewModel = null, $newItem = false, $redirectRoute = null, $redirectParams = null)
    {
        //Instantiate the ViewModel, if one isn't provided
        if (!$viewModel instanceof ViewModel) {
            $viewModel = new ViewModel();
        }

        //Get the Module Name
        $moduleName = $this->getModuleName($this->module);

        //This needs to be here, as we need the $module variable scoped so it can be used with binding
        $module = $this->module;

        //Remove any fields that should not be enabled
        $moduleService->removeDisabledFields($itemForm);

        //Get the ViewManager
        $viewManager = $this->getServiceLocator()->get('view-manager');

        //Pass the variables from the base viewModel to our action's viewModel
        $viewModel->setVariables($viewManager->getViewModel()->getVariables());
        $viewModel->setVariable('moduleName', lcfirst($moduleName));
        $viewModel->setVariable('module', lcfirst($module));

        $subsite = $this->params()->fromRoute('subsite', '');

        if ($subsite) {
            $subsite = substr($subsite, 1);
        } else {
            $subsite = null;
        }

        if (!$newItem) {
            //Retrieve the itemId from either the route or the post, depending on what is available.
            $itemId = (int) $this->params()->fromRoute('itemId', $this->params()->fromPost('id', 0));
            $itemForm->setAttribute('action', $this->url()->fromRoute($this->defaultNoItemRoute, array_merge(array('action' => 'editItem', 'subsite' => $subsite, 'itemId' => $itemId), $this->additionalRouteParams)));

            //Return to the main screen of the module if no itemId
            if (!$itemId) {
                return $this->doRedirect('noItem');
            }

            //Get the itemModel associated with the given itemId
            try {
                if (!($itemModel = $moduleService->getItem($itemId))) {
                    return $this->doRedirect('noItem');
                }
            } catch (\Exception $ex) {
                return $this->doRedirect('noItem');
            }

            //Pass the itemModel directly into the viewModel, so its methods will be available there.
            $viewModel->itemModel = $itemModel;
        } elseif (is_callable(array($moduleService, 'createModel'))) {
            $itemModel = $moduleService->createModel();
            $action = ($newItem) ? 'addItem' : 'editItem';

            $itemForm->setAttribute('action', $this->url()->fromRoute($this->defaultNoItemRoute, array_merge(array('action' => $action, 'subsite' => $subsite), $this->additionalRouteParams)));
        }

        $currentProperty = $this->getServiceLocator()->get('currentProperty');

        //Attach the current user model to our item's model
        $itemModel->setCurrentUser($this->getServiceLocator()->get('phoenix-users-current'));

        //Get the Request Object
        $request = $this->getRequest();

        //Get all of the variables called in this scope and pass them to the bind o matic
        $scopedVariables = get_defined_vars();

        //Attach Special Calls for Binding (Like MediaAttachments, Polytext, etc...)
        $moduleService->attachBindingCalls($itemForm, $scopedVariables);

        if ($request->isPost()) {
            // Add the Validators and InputFilters to the form
            $itemForm->setInputFilter($itemModel->getInputFilter());

            // Populate the Form object with our submitted Post data
            $itemForm->setData($request->getPost());

            // Check to see if the form is valid
            // Disabled temporarily to make all forms work [IS 2013/11/13]
            $action = $request->getPost()->get('action', '');
            if ($action == 'cancel') {
                return $this->doRedirect($redirectionAction, $itemModel->getId());
            }
            if ($itemForm->isValid()) {
                $redirectionAction = null;
                switch ($action) {
                    case 'draft':
                    case 'publish':
                    case 'archive':
                    case 'trash':
                        $this->flashMessenger()->addSuccessMessage('Item saved or pending approval');
                        $redirectionAction = 'saved';
                        // disabled temporarily to make all forms work [IS 2013/11/13]
                        // $moduleService->save($itemModel, $itemForm->getData());
                        $moduleService->$action(array($itemModel->getId()));
                        $moduleService->save($itemModel, $request->getPost());
                        $formData = $itemForm->getData();

                        $this->getEventManager()->trigger('attachMediaForNewItem', $this, array(
                            'parentModule' => $this->getModuleNameForAttachment($this->module),
                            'mediaAttachments' => $formData['mediaAttachments'],
                            'parentItemId' => $itemModel->getId()
                        ));
                        break;
                    case 'addItem':
                    case 'editItem':
                        $this->flashMessenger()->addSuccessMessage('Item saved or pending approval');
                        $moduleService->save($itemModel, $request->getPost());
                        break;
                    case 'orderListItems':
                        $this->flashMessenger()->addSuccessMessage('The items have been reordered.');
                        break;
                    default:
                        throw new \Exception('Undefined form action');
                        break;
                }

                //Redirect to the main page for PhoenixRates, and include the itemId so the page will show that the user was edited.
                if ($redirectRoute) {
                    return $this->redirect()->toRoute($redirectRoute, $redirectParams);
                } else {
                    return $this->doRedirect($redirectionAction, $itemModel->getId());
                }
            } else {
                $this->flashMessenger()->addErrorMessage('There are errors with the form.');
            }
        } elseif (!$newItem) {
            //Bind the property's values to the form, so they will be shown in the correct fields.
            $itemForm->bind($itemModel);
        }

        //Add the form to the viewModel
        $viewModel->form = $itemForm;

        //Return the ViewModel for rendering
        return $viewModel;
    }

    /**
     * doEditItem function
     *
     * @access public
     * @param  mixed $moduleService
     * @param  mixed $itemForm
     * @param  mixed $viewModel
     * @param  mixed $newItem
     * @return mixed $this->doEditItem($moduleService, $itemForm, $viewModel, true)
     */
    public function doAddItem($moduleService, $itemForm, $viewModel = null)
    {
        $taskViewModel = $this->doEditItem($moduleService, $itemForm, $viewModel, true);

        return $taskViewModel;
    }

    /**
     * doEditItem function
     *
     * @access public
     * @param  mixed $redirectType
     * @param  mixed $itemId
     * @return mixed $redirect
     */
    public function doRedirect($redirectType, $itemId = 0)
    {
        $subsite = $this->params()->fromRoute('subsite', '');

        if ($subsite) {
            $subsite = substr($subsite, 1);
        } else {
            $subsite = null;
        }

        switch ($redirectType) {
            case 'saved':
                $redirect = $this->buildRoute($this->defaultNoItemRoute, array(
                    'action' => 'editList',
                    'itemId' => (!empty($itemId)) ? $itemId : null,
                    'subsite' => $subsite,
                ));
                break;
            default:
                $redirect = $this->buildRoute($this->defaultNoItemRoute, array(
                    'action' => 'editList',
                    'subsite' => $subsite
                ));
                break;
        }

        return $redirect;
    }

    /**
     * buildRoute
     *
     * This allows classes that inherit from this to build their own route parameters
     * @param  string $routeName  
     * @param  array $routeParams 
     * @return \Zend\Http\Response $redirect
     */
    protected function buildRoute($routeName, $routeParams)
    {
        $redirect = $this->redirect()->toRoute($routeName, $routeParams);

        return $redirect;
    }

    /**
     * doDeleteItem function
     *
     * @access public
     * @param  mixed $moduleService
     * @return mixed $this->doRedirect('noItem')
     */
    public function doDeleteItem($moduleService)
    {
        $itemId = (int) $this->params()->fromRoute('itemId', 0);

        if (!$itemId) {
            return $this->doRedirect('noItem');
        }

        try {
            $itemModel = $moduleService->getItem($itemId);

            if ($itemModel) {
                $name = $itemModel->getName();
                $itemModel->delete();
                return $this->doRedirect('deleted', $name);
            }
        } catch (\Exception $ex) {
            return $this->doRedirect('noItem');
        }

        return $this->doRedirect('noItem');
    }

    protected function getForm($service)
    {
        return $service->getForm("\\$this->module\\Form\\{$this->modsing}Form", $this->getServiceLocator());   
    }

}
