<?php 
namespace IHotelier\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class SocketsController extends AbstractActionController
{
    private $rateInfoDefault = array(
        'amountAfterTax'   => '0',
        'basicBookingLink' => '',
        'bookingLink'      => '',
        'currency'         => '$',
    );

    public function indexAction()
    {

    }

    public function dailyRateAction()
    {
        try {
            $rateInfo = $this->getServiceLocator()->get('phoenix-ihotelier-dailyrate')->getRateInfo();
        } catch (\Exception $e) {
            $rateInfo = $this->rateInfoDefault;
        }

        return new JsonModel(array('rateInfo' => $rateInfo));
    }

    public function settingsAction()
    {
        $serviceLocator = $this->getServiceLocator();
        $ihsService     = $serviceLocator->get('phoenix-ihotelier-settings');

        $options = array(
            'bookingChannelType' => $this->params()->fromPost('bookingChannelType'),
            'companyNameCode'    => $this->params()->fromPost('companyNameCode'),
            'dataSource'         => $this->params()->fromPost('dataSource'),
            'hotelId'            => $this->params()->fromPost('hotelId'),
            'requestorIDID'      => $this->params()->fromPost('requestorIDID'),
            'requestorIDType'    => $this->params()->fromPost('requestorIDType'),
            'fallbackRate'       => $this->params()->fromPost('fallbackRate'),
            'lookaheadDays'      => $this->params()->fromPost('lookaheadDays'),
            'messageID'          => $this->params()->fromPost('messageID'),
            'headerUsername'     => $this->params()->fromPost('headerUsername'),
            'headerPassword'     => $this->params()->fromPost('headerPassword'),
        );

        try {
            $settingsResponse = $ihsService->saveSettings($options);
        } catch(\Exception $e) {
            $settingsResponse = 'Server Error 500';
        }

        return new JsonModel(array('settingsResponse' => $settingsResponse));
    }
}
