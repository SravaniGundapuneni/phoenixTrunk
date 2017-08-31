<?php
/**
 * IHotelier ToolboxController
 *
 * The ToolboxController for the IHotelier Module
 *
 * If it is a toolbox action for the phoenixProperties module, it goes here.
 *
 * @category    Toolbox
 * @package     PhoenixEvents
 * @subpackage  Controllers
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.5.5
 * @since       File available since release 13.5.5
 * @author      Kevin Davis <kedavis@travelclick.com>
 * @filesource
 */

namespace IHotelier\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use ListModule\Controller\ToolboxController as ListModuleToolbox;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class ToolboxController extends AbstractActionController
{
    const DEFAULT_NOITEM_ROUTE = 'IHotelier-toolbox';

    const JS_PATH = 'module/IHotelier/view/i-hotelier/toolbox/js/';

    protected $baseAdminLevel = \Users\Service\Acl::PERMISSIONS_RESOURCE_WRITE;

    public function indexAction()
    {
        $ihsService = $this->getServiceLocator()->get('phoenix-ihotelier-settings');
        $renderer   = $this->serviceLocator->get('Zend\View\Renderer\PhpRenderer');

        $toolboxIncludeUrl = $this->getToolboxIncludeUrl(); 
        $renderer->headScript()->appendFile($toolboxIncludeUrl . self::JS_PATH . 'index.js');

        $viewModel = new ViewModel();

        $viewModel->setVariables($ihsService->getSettings());

        // TODO: Use Zend Form
        return $viewModel;
    }

    private function getToolboxIncludeUrl()
    {
        $mergedConfig = $this->getServiceLocator('mergedConfig');
        $config = $mergedConfig->get('config');
        return $config['paths']['toolboxIncludeUrl'];
    }
}
