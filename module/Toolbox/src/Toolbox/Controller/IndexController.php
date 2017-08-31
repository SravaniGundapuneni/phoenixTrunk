<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Toolbox\Controller;

use Toolbox\Mvc\Controller\AbstractToolboxController;

use Zend\View\Model\ViewModel;

class IndexController extends AbstractToolboxController
{
    public function indexAction()
    {
        $viewModel = new ViewModel();
        $mergedConfig = $this->getServiceLocator()->get('MergedConfig');

        $routeMatch = $this->params()->fromRoute();

        $request = $this->getRequest();

        //Disable the auto layout, unless specifically told not to in Config.
        if (!$mergedConfig->get(array('auto-layout'), false)) {
            $viewModel->setTerminal(true);
        }

        $templateResolver = $this->serviceLocator->get('Zend\View\Resolver\TemplatePathStack');
        $template = $mergedConfig->get('template', 'default.phtml');

        if ($templateResolver->resolve($template)) {
            $viewModel->setTemplate($template);
        }

        $viewManager = $this->getServiceLocator()->get('view-manager');
        $viewModel->setVariables($viewManager->getViewModel()->getVariables());
        return $viewModel;
    }
}