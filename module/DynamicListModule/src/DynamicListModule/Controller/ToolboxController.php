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
use Zend\Mvc\MvcEvent;

/**
 * The ToolboxController class for the DynamicListModule
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
class ToolboxController extends BaseToolboxController
{
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
    protected $editListTemplate = 'dynamic-list-module/toolbox/edit-list';

    protected $tasksMenu = array('addItem'=>'New Item','editList'=>'Manage Items', 'install' => 'Install Modules');

    protected $socketsRoute = 'dynamicListModule-admin-sockets';

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

    public function installAction()
    {
        $actualModule = $this->module;
        $this->module = 'dynamiclistmodule-install';
        $this->editListTemplate = 'dynamic-list-module/toolbox/install';
        $this->toolboxEditListOptions = array();

        $viewModel = self::editlistAction();

        if ($viewModel instanceof ViewModel) {
            $viewModel->moduleName = $actualModule;
        }

        return $viewModel;
    }

    public function onDispatch(MvcEvent $e)
    {
        $mergedConfig = $e->getApplication()->getServiceManager()->get('MergedConfig');

        $development = $mergedConfig->get('development');

        if (!$development) {
            unset($this->tasksMenu['install']);
        }

        parent::onDispatch($e);
    }

    public function __construct()
    {
        parent::__construct();
        $this->socketsRoute = 'dynamicListModule-admin-sockets';        
    }
}