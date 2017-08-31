<?php

namespace Toolbox\Controller;

use Toolbox\Mvc\Controller\AbstractToolboxController;
use Zend\View\Model\ViewModel;
use Zend\Mvc\MvcEvent;

class ToolboxController extends AbstractToolboxController {

    /**
     * The base admin level for actions. Each action (or task within an action)
     * can require a higher adminLevel, but this is the lowest allowed.
     * 
     * @var string
     */
    protected $baseAdminLevel = \Users\Service\Acl::PERMISSIONS_RESOURCE_READ;

    /**
     * indexAction
     * 
     * The Index Action
     * @return ViewModel $viewModel
     */
    public function indexAction() {
        $viewModel = new ViewModel();

        $viewManager = $this->getServiceLocator()->get('view-manager');
        
        //Pass the variables from the base viewModel to our action's viewModel
        $viewModel->setVariables($viewManager->getViewModel()->getVariables());
        
        $viewModel->setTemplate('toolbox/toolbox/index.phtml');
        return $viewModel;
    }

    // /**
    //  * onDispatch
    //  * 
    //  * @param  MvcEvent $e
    //  * @return mixed
    //  */
    // public function onDispatch(MvcEvent $e)
    // {
    // }
}
