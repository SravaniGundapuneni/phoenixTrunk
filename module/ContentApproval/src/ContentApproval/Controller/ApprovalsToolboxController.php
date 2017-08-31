<?php

/**
 * PhoenixRates ToolboxController
 *
 * The ToolboxController for the PhoenixRates Module
 *
 * If it is a toolbox action for the phoenixRates module, it goes here.
 *
 * @category    Toolbox
 * @package     PhoenixRates
 * @subpackage  Controllers
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace ContentApproval\Controller;

/**
 * PhoenixRates ToolboxController
 *
 * The ToolboxController for the PhoenixRates Module
 *
 * If it is a toolbox action for the phoenixRates module, it goes here.
 *
 * This will need to have a way of deciding whether to show all rates, or just the property for the current site
 * depending upon the user.
 *
 * @category    Toolbox
 * @package     ContentApproval
 * @subpackage  Controllers
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 14.2
 * @since       
 * @author      Daniel Yang <dyang@travelclick.com>
 */
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use DoctrineORMModule\Form\Annotation\AnnotationBuilder;

class ApprovalsToolboxController extends \ListModule\Controller\ToolboxController
{
    const MODULE_SERVICE_NAME = 'approvals';
    
        public function __construct()
    {
        parent::__construct();
        //Set the Toolbox Route. This has to be done here, because it is set dynamically in
        //the parent class constructor
        $this->toolboxRoute = 'contentApproval_approvals-toolbox';
    }
    
    public function getModuleName()
    {
        return static::MODULE_SERVICE_NAME;
    }
    public function edititemAction()
    {
        //Set the defaultNoItemRoute, so redirects will work correctly
        $this->defaultNoItemRoute = $this->toolboxRoute;

        //Run the editItem action method
        $viewList = parent::edititemAction();

        //Return the result
        return $viewList;
    }
}
