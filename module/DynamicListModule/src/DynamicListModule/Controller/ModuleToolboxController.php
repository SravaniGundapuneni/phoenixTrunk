<?php
/**
 * The file for the ToolboxController class for the DynamicListModule
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

/**
 * The ModuleToolboxController class for the DynamicListModule
 *
 * This is the ToolboxController, but for use by the actual dynamic modules.
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
class ModuleToolboxController extends BaseToolboxController
{
    protected $tasksMenu = array('addItem'=>'New Item','editList'=>'Manage Items', 
                                 //'export' => 'Export to CSV', 'import' => 'Import CSV'
                                 );
    /**
     * The base admin level for actions. Each action (or task within an action)
     * can require a higher adminLevel, but this is the lowest allowed.
     * 
     * @var string
     */    
    protected $baseAdminLevel = \Users\Service\Acl::PERMISSIONS_RESOURCE_ADMIN;

    /*
     * The name of the primary service used by this controller
     */    
    const MODULE_SERVICE_NAME = 'dynamicmanager';

    /**
     * The template used for the editList action
     * @var string
     */
    protected $editListTemplate = 'dynamic-list-module/module-toolbox/edit-list';

    /**
     * The name of the dynamic module
     * @var string
     */
    protected $dynamicModuleName = '';

    protected $socketsRoute = "dynamicListModule-sockets";

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
        $this->toolboxRoute = 'dynamicListModule-moduleToolbox';
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
     * prepareEditList
     *
     * Abstracted this out into a function so this class can be extended
     * 
     * @return void
     */
    public function prepareEditList()
    {
        //Get the Module name from the route
        $moduleName = $this->params()->fromRoute('moduleName');

        //Get DynamicManager service
        $dynamicManager = $this->getServiceLocator()->get('phoenix-dynamicmanager');

        //Set the module name on the service
        $dynamicManager->setModuleName($moduleName);        
    }

    /**
     * editListAction
     * 
     * @return mixed
     */
    public function editlistAction()
    {
        $this->prepareEditList();

        //Run the editList action method
        $viewList = parent::editlistAction();

        $this->wrapEditList($viewList);

        //Return the ViewModel
        return $viewList;
    }

    public function wrapEditList($viewList)
    {
        $dynamicManager = $this->getServiceLocator()->get('phoenix-dynamicmanager');

        $moduleName = $dynamicManager->getModuleName();

        if ($viewList instanceof ViewModel) {
            //Set the module name on the view model
            $viewList->moduleName = $moduleName;
            //Add the module params to the view model
            $viewList->moduleRouteParams = array('module' => str_replace(' ','-',lcfirst($moduleName)));            
        }
    }    

    /**
     * doEditList
     * 
     * @param  DynamicListModule\Service\DynamicManager $moduleService
     * @param  mixed $viewModel
     * 
     * @return mixed
     */
    public function doEditList($moduleService, $viewModel = null)
    {
        //Do the actual editList logic        
        $viewList = parent::doEditList($moduleService, $viewModel);

        $modService = $this->getServiceLocator()->get('phoenix-dynamiclistmodule');
         $moduleName = $this->params()->fromRoute('moduleName');
         $module = $modService->getItemBy(array('name' => $moduleName));
         $categoryService = $this->getServiceLocator()->get('phoenix-listmodule-categories');
         
         
        //If a ViewModel was returned, set the module title variable and ModuleRouteName
        if ($viewList instanceof ViewModel) {
            $viewList->moduleRouteName = str_replace(' ','-',lcfirst($moduleService->getModuleName()));
            $viewList->fields = $moduleService->getFields(true);
            $viewList->categories = $module->getEntity()->getCategories();
            $viewList->categoryService = $categoryService;
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

        $this->additionalRouteParams = array('module' => str_replace(' ','-',lcfirst($moduleName)));

        $moduleService = $this->getServiceLocator()->get('phoenix-dynamiclistmodule');

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
            $viewEdit->moduleRouteParams = $this->additionalRouteParams;
            $viewEdit->setVariable('moduleName', $moduleName);
        }

        return $viewEdit;
    }

    /**
     * doEditItem
     * 
     * @param  DynamicListModule\Service\DynamicManager $moduleService
     * @param  DynamicListModule\Form\ModuleItemForm  $itemForm
     * @param  mixed  $viewModel
     * @param  boolean $newItem
     * 
     * @return mixed
     */
    public function doEditItem($moduleService, $itemForm, $viewModel = null, $newItem = false)
    {
        //This bit of code will set this correctly for binding media attachments, while not affecting use of the value elsewhere
        $module = $this->module;
      
        $this->module = $this->additionalRouteParams['module'];

        //Do the logic
        $viewItem = parent::doEditItem($moduleService, $itemForm, $viewModel, $newItem);

        $this->module = $module;

        //If result is ViewModel, set the module variable
        if ($viewItem instanceof ViewModel) {
            $viewItem->module = $this->additionalRouteParams['module'];
        }

        //Return the result
        return $viewItem;
    }

    public function exportAction()
    {
        $dynamicManager = $this->getService();
        $filename = preg_replace("([^\w\s\d\-_~,;:\[\]\(\]]|[\.]{2,})", '',  $dynamicManager->getModuleName()).'.csv';
        
        $exportCsvString = $dynamicManager->getExportCsvString();
        
        $response = $this->getResponse();
        $response->setContent($exportCsvString);

        $headers = $response->getHeaders();
        $headers->clearHeaders()
            ->addHeaderLine('Content-Type', 'application/csv')
            ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->addHeaderLine('Content-Length', strlen($exportCsvString));

        return $this->response;
        
    }
    
    public function importAction()
    {
        $moduleName = $this->params()->fromRoute('moduleName');
        $form = new \DynamicListModule\Form\UploadForm();
        $dynamicManager = $this->getService();
        $upload = 0;
        if ($this->request->isPost())
        {
            $dynamicManager = $this->getService();
            //$this->getRequest()->getFile('upload');
            $file = $this->params()->fromFiles('upload');
            
            $dynamicManager->import($file);
            $upload = 1;
        }
        
        return array('form' => $form, 'upload' => $upload, 'moduleName' => $moduleName);
        
    }
    
    protected function getService()
    {
        $moduleName = $this->params()->fromRoute('moduleName');

        //Set the dynamicModuleName property
        $this->dynamicModuleName = $moduleName;
        
        $moduleService = $this->getServiceLocator()->get('phoenix-dynamiclistmodule');
        $module = $moduleService->getItemBy(array('name' => $moduleName));
        $dynamicManager = $this->getServiceLocator()->get('phoenix-dynamicmanager');
        $dynamicManager->setModuleName($moduleName);

        $dynamicManager->setModule($module);
        
        return $dynamicManager;
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
        $routeParams['module'] = str_replace(' ','-',lcfirst($this->dynamicModuleName));
        $redirect = $this->redirect()->toRoute($this->defaultNoItemRoute, $routeParams); 
    }

}