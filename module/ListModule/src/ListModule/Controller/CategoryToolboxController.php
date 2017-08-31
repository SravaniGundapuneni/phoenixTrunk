<?php

/**
 * The file for the FieldsToolboxController class for the ListModule
 *
 * @category    Toolbox
 * @package     ListModule
 * @subpackage  Controllers
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      Daniel Yang <dyang@travelclick.com>
 * @filesource
 */

namespace ListModule\Controller;

use ListModule\Controller\ToolboxController as BaseToolboxController;
use Zend\View\Model\ViewModel;

use Zend\Log\Logger;

/**
 * The FieldsToolboxController class for the ListModule
 *
 * @category    Toolbox
 * @package     ListModule
 * @subpackage  Controllers
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 */
class CategoryToolboxController extends BaseToolboxController
{

    /**
     * The base admin level for actions. Each action (or task within an action)
     * can require a higher adminLevel, but this is the lowest allowed.
     * 
     * @var string
     */
    protected $baseAdminLevel = \Users\Service\Acl::PERMISSIONS_RESOURCE_SUPER_ADMIN;

    /*
     * The name of the primary service used by this controller
     */

    const MODULE_SERVICE_NAME = 'listmodule-categories';

    /**
     * The template used for the editList action
     * @var string
     */
    protected $editListTemplate = 'list-module/category-toolbox/edit-list';

    /**
     * The title to use for the module associated with the field(s)
     * @var string
     */
    protected $moduleTitle = '';

    /**
     * __construct
     *
     * Class Constructor
     */
    public function __construct()
    {
        parent::__construct();
        //Set the Toolbox Route. This has to be done here, because it is set dynamically in
        //the parent class constructor
        $this->toolboxRoute = 'categories-toolbox';
    }

    /**
     * getTemplateName
     * 
     * @param  string $moduleName (this is used in this class, but it must maintain the structure of the parent)
     * @return string
     */
    protected function getTemplateName($moduleName)
    {
        return $this->editListTemplate;
    }

    /**
     * getModuleName
     * 
     * @return string
     */
    public function getModuleName()
    {
        return self::MODULE_SERVICE_NAME;
    }

    /**
     * editListAction
     * 
     * @return mixed
     */
    public function editlistAction()
    {
        //Get the Module's Id from the route
        $moduleId = $this->params()->fromRoute('moduleId');
        //Retrieve the module, so we can get the title of the module
        $moduleService = $this->getServiceLocator()->get('phoenix-dynamiclistmodule');
        $module = $moduleService->getItem($moduleId);
        $this->moduleTitle = $module->getName();
        //Run the action method
        $viewList = parent::editlistAction();

        //If the response is a view model, add the moduleRouteParams array to it so 
        //route building for urls will work correctly.
        if ($viewList instanceof ViewModel) {
            $viewList->moduleRouteParams = array('moduleId' => $moduleId);
        }

        //Return the result from the action method
        return $viewList;
    }

    /**
     * doEditList
     * 
     * @param  ListModule\Service\Fields $moduleService
     * @param  mixed $viewModel
     * 
     * @return mixed $taskView
     */
    public function doEditList($moduleService, $viewModel = null)
    {
        $moduleId = (int) $this->params()->fromRoute('moduleId');

        $this->additionalRouteParams = array('moduleId' => $moduleId);

        //Get the module service, so we can inject the module into the field service, so it can be saved
        $dynamicListModuleService = $this->getServiceLocator()->get('phoenix-dynamiclistmodule');

        //Get the ListModule\Model\Module object
        $module = $dynamicListModuleService->getItem($moduleId);

        $returnRoute = 'dynamicListModule-toolbox';

        if ($module->getDynamic() !== 1) {
            $returnRoute = lcfirst($module->getName() . '-toolbox');
        }

        $moduleService->setModuleName($module->getName());
        //Do the actual editList logic
        $taskView = parent::doEditList($moduleService, $viewModel);

        $taskView->returnRoute = $returnRoute;
        $taskView->returnName = $module->getName();

        //If a ViewModel was returned, set the module title variable
        if ($taskView instanceof ViewModel) {
            $taskView->moduleTitle = $this->moduleTitle;
            $taskView->moduleId = $moduleId;
        }

        return $taskView;
    }

    /**
     * editItemAction
     * 
     * @return mixed
     */
    public function edititemAction()
    {
        //Get the Module's Id from the route
        $moduleId = (int) $this->params()->fromRoute('moduleId');

        $this->additionalRouteParams = array('moduleId' => $moduleId);

        //Set the defaultNoItemRoute, so redirects will work correctly
        $this->defaultNoItemRoute = $this->toolboxRoute;

        //Run the editItem action method
        $viewList = parent::edititemAction();

        //If the response is a view model, add the moduleRouteParams array to it so 
        //route building for urls will work correctly.
        if ($viewList instanceof ViewModel) {
            $viewList->moduleRouteParams = array('moduleId' => $moduleId);
        }

        //Return the result
        return $viewList;
    }

    /**
     * doEditItem
     * 
     * @param  ListModule\Service\Fields  $moduleService
     * @param  ListModule\Form\FieldForm  $itemForm
     * @param  mixed  $viewModel     [description]
     * @param  boolean $newItem       [description]
     * @return mixed
     */
    public function doEditItem($moduleService, $itemForm, $viewModel = null, $newItem = false)
    {

        //Get the Module's Id from the route
        $moduleId = (int) $this->params()->fromRoute('moduleId');

        //Get the module service, so we can inject the module into the field service, so it can be saved
        $dynamicListModuleService = $this->getServiceLocator()->get('phoenix-dynamiclistmodule');

        //Get the ListModule\Model\Module object
        $module = $dynamicListModuleService->getItem($moduleId);

        //Set the module on the Fields service, so it can be injected into the model
        $moduleService->setModule($module);
        $moduleService->setModuleName($module->getName());

        //Set the module title
        $this->moduleTitle = $module->getName();

        //Run the actual logic
        return parent::doEditItem($moduleService, $itemForm, $viewModel, $newItem);
    }

    /**
     * buildRoute
     *
     * This allows us to add the Dynamic Module to the route parameters for a redirect
     * 
     * @param  string $routeName  
     * @param  array $routeParams 
     * @return \Zend\Http\Response $redirect
     */
    protected function buildRoute($routeName, $routeParams)
    {
        $routeParams['moduleId'] = $this->params()->fromRoute('moduleId');
        $redirect = $this->redirect()->toRoute($this->defaultNoItemRoute, $routeParams);
    }

}
