<?php

/**
 * The SiteMap ToolboxController File
 *
 * @category    Toolbox
 * @package     SiteMap
 * @subpackage  Controller
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      Alex Kotsores <akotsores@travelclick.com>
 * @filesource
 */

namespace SiteMap\Controller;

use Zend\View\Model\ViewModel;
use SiteMap\Service\SiteMaps;

class ToolboxController extends \ListModule\Controller\ToolboxController
{
    protected $tasksMenu = array('addItem'=>'Scan Site','editList'=>'Edit Page List');
    /**
     * Module name and signature
     * @var string
     */
    protected $module = 'siteMap';
    protected $modsing;

    protected $newItem = false;
    protected $havePropertyList = true;

    protected $editListOptions = array(
        'Toggle Re-Order'    => 'toggleReorder',
        'Save Item Order'    => 'orderListItems',
        'Save'    => 'publish',
        'Archive' => 'archive',
        'Trash'   => 'trash',
    );

    protected $editItemOptions = array(
        'Save'    => 'publish',
        'Archive' => 'archive',
        'Trash'   => 'trash',
        'Cancel'  => 'cancel',
    );

    /**
     * The proper title to use for the different templates
     * @var string
     */
    protected $editItemTitle = 'Edit Item';
    protected $editListTitle = 'Select Item to edit';

    /**
     * The proper template to use for the editItem screen
     * @var string
     */
    protected $editItemTemplate = "edit-item";
    protected $editListTemplate = "edit-list";

    /**
     * The base admin level for actions. Each action (or task within an action)
     * can require a higher adminLevel, but this is the lowest allowed.
     *
     * @var string
     */
    protected $baseAdminLevel = \Users\Service\Acl::PERMISSIONS_RESOURCE_ADMIN;

    /**
     * The route to use in setting up the the module task menu (the left hand menu)
     * @var string
     */
    protected $toolboxRoute = 'home/toolbox-root';

    /**
     * __construct
     *
     * @access public
     *
     */

    public function __construct()
    {
        preg_match("/(.+?)\\\/",get_class($this),$moduleNamespace);
        $this->module = $moduleNamespace = array_pop($moduleNamespace);
        //define("DEFAULT_NOITEM_ROUTE", $this->toolboxRoute = lcfirst("$moduleNamespace-toolbox"));
        $this->defaultNoItemRoute = $this->toolboxRoute = lcfirst("$moduleNamespace-toolbox");
        $this->editListTitle = $this->editListHeader = "Select $this->modsing to edit";
        //$this->newItemOptions = array_reverse($this->newItemOptions);
        $this->editItemOptions = array_reverse($this->editItemOptions);
        $this->editItemTitle = "Edit $this->modsing";
    }

    /**
     * getModulename
     *
     * Strip Phoenix from our module name
     *
     * @access public
     * @param  string $moduleName
     * @return string str_ireplace('phoenix', '', strtolower($moduleName))
     */
    public function getModuleName($moduleName)
    {
        return str_ireplace('phoenix', '', strtolower($moduleName));
    }

    /**
     * getTemplateName
     *
     * Strip Phoenix from our module name
     *
     * @access protected
     * @param  string $moduleName
     * @return string str_ireplace('phoenix', '', strtolower($moduleName))
     */

    protected function getTemplateName($moduleName)
    {
        return "phoenix-$moduleName/toolbox/edit-list";
    }

    /**
     * indexAction
     *
     * @access public
     * The default action for the Controller
     * @return mixed $this->editlistAction()
     */
    public function indexAction()
    {
        return $this->editlistAction();
    }

    /**
     * addItemAction
     *
     * @access public
     * The default action for the Controller
     * @return mixed $this->editlistAction()
     */
    public function addItemAction()
    {
        $scanService = $this->getServiceLocator();
        $moduleName = $this->getModuleName($this->module);
        $viewManager = $scanService->get('view-manager');
        $mergedConfig = $scanService->get('MergedConfig');
        $service = $scanService->get("phoenix-$moduleName");
        $service->scanFolder();
        return $this->redirect()->toRoute('siteMap-toolbox');
    }

    /**
     * editlistAction
     *
     * @access public
     * The action that handles the list of Module Items
     * @return mixed $viewModel
     *
     */
    public function editlistAction()
    {
        
        $serviceLocator = $this->getServiceLocator();
        $moduleName = $this->getModuleName($this->module);
        $viewManager = $serviceLocator->get('view-manager');
        $mergedConfig = $serviceLocator->get('MergedConfig');
        $service = $serviceLocator->get("phoenix-$moduleName");
        
        
        $taskViewModel = $this->doEditList($service);
        $taskViewModel->setTemplate($this->getTemplateName($moduleName));
        $taskViewModel->setVariables($viewManager->getViewModel()->getVariables());
        $taskViewModel->setVariable('itemsPerPage', $mergedConfig->get('items-per-page'));
        $taskViewModel->setVariable('editListOptions', $this->editListOptions);
        $taskViewModel->setVariable('title', $this->editListTitle);

        $this->setAdditionalVars($taskViewModel);

        if ( $serviceLocator->has("$moduleName-layout") )
        {
            $viewModel = $serviceLocator->get("$moduleName-layout");
        }
        else
        {
            $viewModel = $serviceLocator->get('listModule-layout');
        }
        
        $viewModel->setVariables($viewManager->getViewModel()->getVariables());
        
        $viewModel->setVariable('toolboxRoute', $this->toolboxRoute);
        $viewModel->setVariable('tasksMenu', $this->tasksMenu);
        $viewModel->setVariable('moduleName', $this->module);
        //Set this to empty by default
        $viewModel->moduleRouteParams = array();
        $viewModel->addChild($taskViewModel, 'taskContent');

        return $viewModel;
    }
}
