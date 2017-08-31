<?php

/**
 * The file for the FieldsToolboxController class for the DynamicListModule
 *
 * @category    Toolbox
 * @package     DynamicListModule
 * @subpackage  Controllers
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace DynamicListModule\Controller;

use ListModule\Controller\ToolboxController as BaseToolboxController;
use Zend\View\Model\ViewModel;
use Zend\Form\Element;
use Zend\Form\Form;

/**
 * The FieldsToolboxController class for the DynamicListModule
 *
 * @category    Toolbox
 * @package     DynamicListModule
 * @subpackage  Controllers
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 */
class FieldsToolboxController extends BaseToolboxController {

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

    const MODULE_SERVICE_NAME = 'dynamiclistmodule-fields';

    /**
     * The template used for the editList action
     * @var string
     */
    protected $editListTemplate = 'dynamic-list-module/fields-toolbox/edit-list';

    /**
     * The title to use for the module associated with the field(s)
     * @var string
     */
    protected $moduleTitle = '';

    /**
     * The baseId name
     */
    protected $baseIdTitle = 'moduleId';

    /**
     * __construct
     *
     * Class Constructor
     */
    public function __construct() {
        parent::__construct();
        //Set the Toolbox Route. This has to be done here, because it is set dynamically in
        //the parent class constructor
        $this->toolboxRoute = 'dynamicListModule-fieldsToolbox';
        $this->socketsRoute = 'dynamicListModule-admin-sockets';          
    }

    /**
     * getTemplateName
     * 
     * @param  string $moduleName (this is used in this class, but it must maintain the structure of the parent)
     * @return string
     */
    protected function getTemplateName($moduleName) {
        return $this->editListTemplate;
    }

    /**
     * getModuleName
     * 
     * @return string
     */
    public function getModuleName() {
        return static::MODULE_SERVICE_NAME;
    }

    public function prepareEditList() {
        //Get the Module's Id from the route
        $moduleId = (int) $this->params()->fromRoute('moduleId');

        //Retrieve the module, so we can get the title of the module
        $moduleService = $this->getServiceLocator()->get('phoenix-dynamiclistmodule');
        $module = $moduleService->getItem($moduleId);
        $this->moduleTitle = $module->getName();
    }

    /**
     * editListAction
     * 
     * @return mixed
     */
    public function editlistAction() {
        $this->prepareEditList();

        //Run the action method
        $viewList = parent::editlistAction();

        $this->wrapEditList($viewList);

        //Return the result from the action method
        return $viewList;
    }

    public function wrapEditList($viewList) {
        //Get the Module's Id from the route
        $moduleId = (int) $this->params()->fromRoute('moduleId');

        //If the response is a view model, add the moduleRouteParams array to it so 
        //route building for urls will work correctly.
        if ($viewList instanceof ViewModel) {
            $viewList->moduleRouteParams = array('moduleId' => $moduleId);
        }
    }

    /**
     * getBaseId
     * 
     * @return integer
     */
    public function getBaseId() {
        return (int) $this->params()->fromRoute('moduleId');
    }

    /**
     * setBaseItemId
     * 
     * @param DynamicListModule\Service\Fields $moduleService
     */
    public function setBaseItemId($moduleService) {
        $moduleService->setModule($this->getBaseId());
    }

    public function setListTitle($taskView) {
        //If a ViewModel was returned, set the module title variable
        if ($taskView instanceof ViewModel) {
            $taskView->moduleTitle = $this->moduleTitle;
        }
    }

    /**
     * doEditList
     * 
     * @param  DynamicListModule\Service\Fields $moduleService
     * @param  mixed $viewModel
     * 
     * @return mixed $taskView
     */
    public function doEditList($moduleService, $viewModel = null) {
        $this->setBaseItemId($moduleService);

        //Do the actual editList logic
        $taskView = parent::doEditList($moduleService, $viewModel);

        $this->setListTitle($taskView);

        return $taskView;
    }

    /**
     * editItemAction
     * 
     * @return mixed
     */
    public function edititemAction() {
        $this->additionalRouteParams = array($this->baseIdTitle => $this->getBaseId());
        //Set the defaultNoItemRoute, so redirects will work correctly
        $this->defaultNoItemRoute = $this->toolboxRoute;

        //Run the editItem action method
        $viewList = parent::edititemAction();

        //If the response is a view model, add the moduleRouteParams array to it so 
        //route building for urls will work correctly.
        if ($viewList instanceof ViewModel) {
            $viewList->moduleRouteParams = $this->additionalRouteParams;
        }

        //Return the result
        return $viewList;
    }

    public function prepareDoEditItem($moduleService, $itemForm, $viewModel, $newItem) {
        //Get the Module's Id from the route
        $moduleId = (int) $this->params()->fromRoute('moduleId');

        //Get the module service, so we can inject the module into the field service, so it can be saved
        $fieldModuleService = $this->getServiceLocator()->get('phoenix-dynamiclistmodule');

        //Get the DynamicListModule\Model\Module object
        $module = $fieldModuleService->getItem($moduleId);


        //Set the module on the Fields service, so it can be injected into the model
        $moduleService->setModule($module);

        // code for displaying select fields
        $fieldIdVal = (int) $this->params()->fromRoute('itemId', 0);

        $selectValues = $moduleService->getSelectValueOptions($fieldIdVal);
       // var_dump($selectValues);
  
        foreach($selectValues as $key =>$value){
            //echo $key.'----'.$value.'<br/>';
                        $username = new Element\Text('updateValue' . $key);
            $username
                    ->setLabel('Values :' . $i)
                    ->setValue($value)
                    ->setAttributes(array(
                        'class' => 'updateValue' . $i,
            ));
            $itemForm->add($username);
            
        }
        //Set the module title
        $this->moduleTitle = $module->getName();

        $type = $itemForm->get('type');

        //Populate the list of options for the type field
        $moduleService->setTypeOptions($type);
    }

    /**
     * doEditItem
     * 
     * @param  DynamicListModule\Service\Fields  $moduleService
     * @param  DynamicListModule\Form\FieldForm  $itemForm
     * @param  mixed  $viewModel     [description]
     * @param  boolean $newItem       [description]
     * @return mixed
     */
    public function doEditItem($moduleService, $itemForm, $viewModel = null, $newItem = false) {
        $this->prepareDoEditItem($moduleService, $itemForm, $viewModel, $newItem);

        $type = $itemForm->get('type');

        //Populate the list of options for the type field
        $moduleService->setTypeOptions($type);

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
    protected function buildRoute($routeName, $routeParams) {
        $routeParams[$this->baseIdTitle] = $this->getBaseId();
        $redirect = $this->redirect()->toRoute($this->defaultNoItemRoute, $routeParams);
    }

    protected function getForm($service)
    {
        return $service->getForm("DynamicListModule\Form\FieldForm", $this->getServiceLocator());   
    }  

}
