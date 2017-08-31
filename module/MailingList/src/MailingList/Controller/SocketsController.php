<?php

namespace MailingList\Controller;

use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

use Zend\ViewRenderer\RendererInterface as ViewRenderer;
use ListModule\Controller\SocketsController as ListModuleSocket;

/**
 * The SocketsController controller file
 *
 * @category    Toolbox
 * @package     Config
 * @subpackage  Controller
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */
class SocketsController extends ListModuleSocket
{
    public function signUpFormAction()
    {
        
        $mailinglistService = $this->serviceLocator->get('phoenix-mailinglist');
        
        $salutation            = $this->params()->fromPost('salutation');
        $name                  = $this->params()->fromPost('name');
        $lastName              = $this->params()->fromPost('lastName');
        $email                 = $this->params()->fromPost('email');
        $country               = $this->params()->fromPost('country');
        
        $data = array(
            'title' => $salutation,
            'email'    => $email,
            'firstName' => $name,
            'lastName' => $lastName,            
            'countryCode'  => $country,
            'subscribe'  => 1,
        );
        
        $model = $mailinglistService->createMailingListModel();
 
        $mailinglistService->save($model, $data);       
        

     return new JsonModel(array('message' => 'Subscribe successfully!'));
    }

}
