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

namespace FlexibleForms\Controller;

use DynamicListModule\Controller\FieldsToolboxController as BaseFieldsController;
use Zend\View\Model\ViewModel;
use Zend\Form\Element;
use Zend\Form\Form;
use FlexibleForms\Model\Field;
use FlexibleForms\Form\FieldForm;

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
class FieldsToolboxController extends BaseFieldsController
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

    const MODULE_SERVICE_NAME = 'flexibleforms-fields';

    /**
     * The template used for the editList action
     * @var string
     */
    protected $editListTemplate = 'flexible-forms/fields-toolbox/edit-list';

    /**
     * The title to use for the module associated with the field(s)
     * @var string
     */
    protected $moduleTitle = '';

    /**
     * The baseId name
     */
    protected $baseIdTitle = 'formId';

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
        $this->toolboxRoute = 'flexibleForms-fieldsToolbox';
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
        //Get the Module's Id from the route
        $formId = (int) $this->params()->fromRoute('formId');

        //Retrieve the module, so we can get the title of the module
        $formService = $this->getServiceLocator()->get('phoenix-flexibleforms');
        $form = $formService->getItem($formId);
        $this->formTitle = $form->getName();
    }

    /**
     * wrapEditList
     *
     * Finish up the editList Action for the forms
     * 
     * @return void
     */
    public function wrapEditList($viewList)
    {
        $formId = (int) $this->params()->fromRoute('formId');

        //If the response is a view model, add the moduleRouteParams array to it so 
        //route building for urls will work correctly.
        if ($viewList instanceof ViewModel) {
            $viewList->moduleRouteParams = array('formId' => $formId);
        }
    }

    /**
     * getBaseId
     * 
     * @return integer
     */
    public function getBaseId()
    {
        return (int) $this->params()->fromRoute('formId');
    }

    /**
     * setBaseItemId
     * 
     * @param DynamicListModule\Service\Fields $moduleService
     */
    public function setBaseItemId($moduleService)
    {
        $moduleService->setFlexibleForm($this->getBaseId());
    }

    public function setListTitle($taskView)
    {
        //If a ViewModel was returned, set the module title variable
        if ($taskView instanceof ViewModel) {
            $taskView->formTitle = $this->formTitle;
        }
    }

    public function prepareDoEditItem($moduleService, $itemForm, $viewModel, $newItem)
    {
        //Get the Module's Id from the route
        $formId = $this->getBaseId();

        //Get the module service, so we can inject the module into the field service, so it can be saved
        $fieldModuleService = $this->getServiceLocator()->get('phoenix-flexibleforms');

        //Get the DynamicListModule\Model\Module object
        $flexibleForm = $fieldModuleService->getItem($formId);

        //Set the module on the Fields service, so it can be injected into the model
        $moduleService->setFlexibleForm($flexibleForm);

        //If a ViewModel was returned, set the module title variable
        if ($taskView instanceof ViewModel) {
            $taskView->moduleTitle = $this->moduleTitle;
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
        //echo "Fields toolbox edititemAction<br>";
        //Get the Module's Id from the route
        $formId = (int) $this->params()->fromRoute('formId');

        //Set the defaultNoItemRoute, so redirects will work correctly
        $this->defaultNoItemRoute = $this->toolboxRoute;


//        
//        
//        $request = $this->getRequest();
//    if ($request->isPost()) {
//        // Make certain to merge the files info!
//        $post = array_merge_recursive(
//            $request->getPost()->toArray(),
//            $request->getFiles()->toArray()
//        );
//
//        $form->setData($post);
//        if ($form->isValid()) {
//            $data = $form->getData();
//            // Form is valid, save the form!
//            echo 'Valid';
//          //  return $this->redirect()->toRoute('upload-form/success');
//        }else{
//            echo 'Not Vald';
//        }
//        exit;
//    }
        //Run the editItem action method
        $viewList = parent::edititemAction();

        //If the response is a view model, add the moduleRouteParams array to it so 
        //route building for urls will work correctly.
        if ($viewList instanceof ViewModel) {
            $viewList->moduleRouteParams = array('formId' => $formId);
        }

        //Return the result
        return $viewList;
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
    public function doEditItem($moduleService, $itemForm, $viewModel = null, $newItem = false)
    {

        //Get the type field from the form
        $type = $itemForm->get('type');

        //Get the Module's Id from the route
        $formId = (int) $this->params()->fromRoute('formId');

        //Get the module service, so we can inject the module into the field service, so it can be saved
        $fieldModuleService = $this->getServiceLocator()->get('phoenix-flexibleforms');

        //Get the DynamicListModule\Model\Module object
        $module = $fieldModuleService->getItem($formId);

        //Set the module on the Fields service, so it can be injected into the model
        $moduleService->setModule($module);
// Attachments
        $request = $this->getRequest();

        if ($request->isPost()) {
  $itemForm->setInputFilter($profile->getInputFilter());
    
            $fieldModuleService = $this->getServiceLocator()->get('phoenix-flexibleforms-fields');
            $fieldModuleService->saveFile($this->getRequest()->getFiles()->toArray(), $this->params()->fromRoute('itemId'));
        
        }

        // code for displaying select fields
        $fieldIdVal = (int) $this->params()->fromRoute('itemId', 0);

        $selectValues = $moduleService->getSelectValueOptions($fieldIdVal);
       

        foreach ($selectValues as $key => $value) {
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

        //Display Attachments
        $selectValues = $moduleService->getAttachments($fieldIdVal);

        foreach ($selectValues as $key => $value) {

            $username = new Element\Text('updateValue' . $key);
            $username
                    ->setLabel('Values :' . $i)
                    ->setValue($value)
                    ->setAttributes(array(
                        'class' => 'updateValue' . $i,
            ));
            $itemForm->add($username);
        }

        //
        //
        //Set the module title
        //$this->moduleTitle = $module->getName();
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
    protected function buildRoute($routeName, $routeParams)
    {
        $routeParams['formId'] = $this->params()->fromRoute('formId');
        $redirect = $this->redirect()->toRoute($this->defaultNoItemRoute, $routeParams);
    }

    protected function getForm($service)
    {
        return $service->getForm("FlexibleForms\Form\FieldForm", $this->getServiceLocator());
    }

}
