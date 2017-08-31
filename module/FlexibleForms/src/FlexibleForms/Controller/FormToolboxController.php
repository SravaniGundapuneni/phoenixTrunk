<?php
/**
 * The file for the ToolboxController class for the FlexibleForms
 *
 * @category    Toolbox
 * @package     FlexibleForms
 * @subpackage  Controllers
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace FlexibleForms\Controller;

use DynamicListModule\Controller\ModuleController as ModuleController;
use Zend\View\Model\ViewModel;

/**
 * The FormToolboxController class for the FlexibleForms
 *
 * This is the ToolboxController, but for use by the actual dynamic modules.
 * 
 * @category    Toolbox
 * @package     FlexibleForms
 * @subpackage  Controllers
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 */
class FormToolboxController extends ModuleController
{
    /**
     * The base admin level for actions. Each action (or task within an action)
     * can require a higher adminLevel, but this is the lowest allowed.
     * 
     * @var string
     */    
    protected $baseAdminLevel = \Users\Service\Acl::PERMISSIONS_RESOURCE_DEVELOP;

    /*
     * The name of the primary service used by this controller
     */    
    const MODULE_SERVICE_NAME = 'formmanager';

    /**
     * The template used for the editList action
     * @var string
     */
    protected $editListTemplate = 'flexible-forms/form-toolbox/edit-list';

    /**
     * The name of the dynamic module
     * @var string
     */
    protected $dynamicModuleName = '';

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
        $this->toolboxRoute = 'flexibleForms-formToolbox';
    }

    /**
     * prepareEditList
     *
     * Abstracted this out into a function so this class can be extended
     * 
     * @return void
     */
    public function prepareEditList()
    {
        //Get the Module name from the route
        $formName = $this->params()->fromRoute('formName');

        //Get DynamicManager service
        $dynamicManager = $this->getServiceLocator()->get('phoenix-formmanager');

        //Set the module name on the service
        $dynamicManager->setModuleName($formName);        
    }

    /**
     * wrapEditList
     *
     * Finish up the editList Action for the forms
     * 
     * @return [type] [description]
     */
    public function wrapEditList($viewList)
    {
        $formManager = $this->getServiceLocator()->get('phoenix-formmanager');
        
        if ($viewList instanceof ViewModel) {
            //Get the Form Name
            $formName = $formManager->getFormName();

            //Set the module name on the view model
            $viewList->formName = $formManager->getFormName();

            //Add the module params to the view model
            $viewList->formRouteParams = array('form' => str_replace(' ','-',lcfirst($formName)));            
        }
    }  

    /**
     * doEditList
     * 
     * @param  FlexibleForms\Service\DynamicManager $moduleService
     * @param  mixed $viewModel
     * 
     * @return mixed
     */
    public function doEditList($moduleService, $viewModel = null)
    {
        //Do the actual editList logic        
        $viewList = parent::doEditList($moduleService, $viewModel);

        //If a ViewModel was returned, set the module title variable and ModuleRouteName
        if ($viewList instanceof ViewModel) {
            $viewList->formRouteName = lcfirst($moduleService->getFormName());
            $viewList->fields = $moduleService->getFields(true);
        }

        //Return the Result
        return $viewList;
    }

    /**
     * edititemAction
     * 
     * @return mixed
     */
    public function edititemAction()
    {
        //Get the Module's Name from the route        
        $moduleName = $this->params()->fromRoute('moduleName');

        //Set the dynamicModuleName property
        $this->dynamicModuleName = $moduleName;

        $moduleService = $this->getServiceLocator()->get('phoenix-flexibleforms');

        //Set the default noItem route
        $this->defaultNoItemRoute = $this->toolboxRoute;

        //Set the module name and module on the service
        //@todo: Refactor so moduleName and Module come from same place.
        $module = $moduleService->getItemBy(array('name' => $moduleName));
        $dynamicManager = $this->getServiceLocator()->get('phoenix-dynamicmanager');

        $dynamicManager->setModuleName($moduleName);

        $dynamicManager->setModule($module);

        //Run the editItem action method
        $viewEdit = parent::edititemAction();

        //If the response is a view model, add the moduleRouteParams array to it so 
        //route building for urls will work correctly.
        if ($viewEdit instanceof ViewModel) {
            $viewEdit->moduleRouteParams = array('module' => lcfirst($moduleName));
        }

        return $viewEdit;
    }

    /**
     * doEditItem
     * 
     * @param  FlexibleForms\Service\DynamicManager $moduleService
     * @param  FlexibleForms\Form\ModuleItemForm  $itemForm
     * @param  mixed  $viewModel
     * @param  boolean $newItem
     * 
     * @return mixed
     */
    public function doEditItem($moduleService, $itemForm, $viewModel = null, $newItem = false)
    {
        //Do the logic
        $viewItem = parent::doEditItem($moduleService, $itemForm, $viewModel, $newItem);

        //If result is ViewModel, set the module variable
        if ($viewItem instanceof ViewModel) {
            $viewItem->module = $moduleService->getModuleName();
        }

        //Return the result
        return $viewItem;
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
        $routeParams['module'] = lcfirst($this->dynamicModuleName);
        $redirect = $this->redirect()->toRoute($this->defaultNoItemRoute, $routeParams); 
        echo $redirect;
    }
}