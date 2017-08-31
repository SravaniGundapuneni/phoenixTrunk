<?php

/**
 * MediaManager ToolboxController
 * 
 * The ToolboxController for the MediaManager Module
 * 
 * If it is a toolbox action for the mediaManager module.
 *
 * @category           Toolbox
 * @package            MediaManager
 * @subpackage         Controllers
 * @copyright          Copyright (c) 2013 TravelClick, Inc (http://travelclick.com)
 * @license            All Rights Reserved
 * @version            Release 13.5
 * @since              File available since release 13.5
 * @author             Kevin Davis <kedavis@travelclick.com>
 * @author             Andrew Tate <atate@travelclick.com>
 * @filesource
 */

namespace MediaManager\Controller;

use ListModule\Controller\ToolboxController as ListModuleToolbox;
use Zend\View\Model\ViewModel;

/**
 * MediaManager ToolboxController
 * 
 * The ToolboxController for the MediaManager Module
 * 
 * If it is a toolbox action for the mediaManager module.
 *
 * @category           Toolbox
 * @package            MediaManager
 * @subpackage         Controllers
 * @copyright          Copyright (c) 2013 TravelClick, Inc (http://travelclick.com)
 * @license            All Rights Reserved
 * @version            Release 13.5
 * @since              File available since release 13.5
 * @author             Kevin Davis <kedavis@travelclick.com>
 * @author             Andrew Tate <atate@travelclick.com>
 */
class ToolboxController extends ListModuleToolbox
{
    const DEFAULT_NOITEM_ROUTE = 'mediaManager-toolbox';

    protected $baseAdminLevel = \Users\Service\Acl::PERMISSIONS_RESOURCE_WRITE;

    public function indexAction()
    {
        $viewModel   = new ViewModel();
        $viewManager = $this->getServiceLocator()->get('view-manager');

        $viewModel->isAttachments            = 'false';
        $this->layout()->attachmentsDisabled = true;

        $viewModel->setVariables($viewManager->getViewModel()->getVariables());

        return $viewModel;
    }
}
