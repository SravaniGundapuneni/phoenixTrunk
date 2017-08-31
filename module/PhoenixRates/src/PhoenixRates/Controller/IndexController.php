<?php

namespace PhoenixRates\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{

    public function indexAction()
    {
        
    }

    public function viewItemAction($moduleService)
    {
        $ratesService = $this->getServiceLocator()->get('phoenix-rates');
        $itemId = $this->params('itemId');
        $rate = $ratesService->getRate($itemId);
        
        $config = $this->getServiceLocator()->get('config');
        $viewModel = new ViewModel();
        $viewModel->setVariables(array('config' => $config, 'rate' => $rate));
        return $viewModel;
    }

}
