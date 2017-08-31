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
//use ListModule\Controller\ToolboxController as BaseFieldsController;
use Zend\View\Model\ViewModel;

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
class ViewToolboxController extends BaseFieldsController
{
    /**
     * The base admin level for actions. Each action (or task within an action)
     * can require a higher adminLevel, but this is the lowest allowed.
     * 
     * @var string
     */    
    protected $baseAdminLevel = \Users\Service\Acl::PERMISSIONS_RESOURCE_WRITE;

    /*
     * The name of the primary service used by this controller
     */
    const MODULE_SERVICE_NAME = 'flexibleforms-view';

    /**
     * The template used for the editList action
     * @var string
     */
    protected $editListTemplate = 'flexible-forms/view-toolbox/view';

    /**
     * The title to use for the module associated with the field(s)
     * @var string
     */
    protected $moduleTitle = '';

    /**
     * The baseId name
     */
    protected $baseIdTitle = 'itemId';    
protected $tasksMenu = array('view'=>'View Forms');
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
        $this->toolboxRoute = 'flexibleForms-viewToolbox';
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
        $formId = (int) $this->params()->fromRoute('itemId');

        //Retrieve the module, so we can get the title of the module
        $formService = $this->getServiceLocator()->get('phoenix-flexibleforms');
        $form = $formService->getItem($formId);
     $this->formTitle = $form->getName();
     
    }
     public function viewAction() {

       // echo "I am in VTC viewAction()<br>";
        $viewModel = parent::editlistAction();
        
        $children = $viewModel->getChildren();
        $taskViewModel = $children[0];
   
        //Get the Module's Id from the route
        $formId = (int) $this->params()->fromRoute('itemId');
        //echo $formId;
        $viewFormService = $this->getServiceLocator()->get('phoenix-flexibleforms-view');
        //var_dump($viewFormService);
        //Get the list of properties and pass it to the viewModel
        $taskViewModel->items = $viewFormService->getFormItems($formId);
        $taskViewModel->field = $viewFormService->getFields($formId);
      // print_r($taskViewModel->field);
        //var_dump($viewModel->items);
        $viewModel->moduleRouteParams = array('formId' => $formId);
        //$taskViewModel->testvar = 'abc';
        //$taskViewModel->setVariable('testvar','abc');
        
        //$viewModel['testvar'] = 'abc';

        return $viewModel;
    }
   

    
      protected function buildRoute($routeName, $routeParams)
    {
        $routeParams['itemId'] = $this->params()->fromRoute('itemId');
        $redirect = $this->redirect()->toRoute($this->defaultNoItemRoute, $routeParams); 
    }  
       
}