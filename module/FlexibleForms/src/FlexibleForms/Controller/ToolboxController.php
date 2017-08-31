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

use ListModule\Controller\ToolboxController as BaseToolboxController;
use Zend\View\Model\ViewModel;

/**
 * The ToolboxController class for the FlexibleForms
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
class ToolboxController extends BaseToolboxController {

    /**
     * The base admin level for actions. Each action (or task within an action)
     * can require a higher adminLevel, but this is the lowest allowed.
     * 
     * @var string
     */
    protected $baseAdminLevel = \Users\Service\Acl::PERMISSIONS_RESOURCE_DEVELOP;

    /**
     * The template used for the editList action
     * @var string
     */
    protected $editListTemplate = 'flexible-forms/toolbox/edit-list';
    protected $actionDefaultAdminLevel = array('index' => \Users\Service\Acl::PERMISSIONS_RESOURCE_ADMIN,
        'editList' => \Users\Service\Acl::PERMISSIONS_RESOURCE_ADMIN,
            'view' => \Users\Service\Acl::PERMISSIONS_RESOURCE_ADMIN );
 
    /**
     * getTemplateName
     * 
     * @param  string $moduleName (this is used in this class, but it must maintain the structure of the parent)
     * @return string
     */
    protected function getTemplateName($moduleName) {
        return $this->editListTemplate;
    }

    public function viewAction() {
        

        $this->tasksMenu = array('editList' => 'View Forms'); 
        $viewModel = parent::editlistAction();
        $children = $viewModel->getChildren();
        $taskViewModel = $children[0];
        //Get the Module's Id from the route
        $formId = (int) $this->params()->fromRoute('itemId');
        $viewFormService = $this->getServiceLocator()->get('phoenix-flexibleforms-view');

        //Get the list of properties and pass it to the viewModel
        $taskViewModel->items = $viewFormService->getFormItems($formId);
        $taskViewModel->field = $viewFormService->getFields($formId);
        $taskViewModel->userValue = $viewFormService->userProperty();
      
        $viewModel->moduleRouteParams = array('formId' => $formId);
        $taskViewModel->setTemplate('flexible-forms/toolbox/view');
        return $viewModel;
    }

    protected function buildRoute($routeName, $routeParams) {
        $routeParams['itemId'] = $this->params()->fromRoute('itemId');
        $redirect = $this->redirect()->toRoute($this->defaultNoItemRoute, $routeParams);
    }

}
