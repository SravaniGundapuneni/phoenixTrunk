<?php

namespace Toolbox\Controller;

use ListModule\Controller\ToolboxController as ListModuleController;
use Zend\View\Model\ViewModel;
use Zend\Mvc\MvcEvent;

class ToolboxController extends ListModuleController 
{

    /**
     * The base admin level for actions. Each action (or task within an action)
     * can require a higher adminLevel, but this is the lowest allowed.
     * 
     * @var string
     */
    protected $baseAdminLevel = \Users\Service\Acl::PERMISSIONS_RESOURCE_DEVELOP;
}
