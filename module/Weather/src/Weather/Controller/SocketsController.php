<?php 
namespace Weather\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class SocketsController extends AbstractActionController
{
    public function indexAction()
    {
        $weatherService = $this->getServiceLocator()->get('phoenix-weather');

        try {
            $responseData = $weatherService->callApi($this->getApiOptions());
            $status       = '200';
        } catch(\Exception $e) {
            $responseData = array();
            $status       = '500';
        }

        return new JsonModel(array('status' => $status, 'data' => $responseData));
    }

    private function getApiOptions()
    {
        return array(
            'weatherLocation' => $this->params()->fromQuery('weatherLocation')
        );
    }
}
