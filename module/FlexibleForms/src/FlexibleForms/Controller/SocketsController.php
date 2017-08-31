<?php

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

namespace FlexibleForms\Controller;

use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Mail;
use Zend\ViewRenderer\RendererInterface as ViewRenderer;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Message as MimeMessage;
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
        
    protected $module = 'FlexibleForms';

    /**
     *  __construct function
     * 
     * @access public
     *
     */
    public function __construct()
    {
        
    }
    

    public function submitFormAction()
    {
        $postDetails = $this->params()->fromPost();
        $formName    = $postDetails['formName'];
        
        $flexibleFormsManager = $this->getServiceLocator()->get('phoenix-formmanager');
        $flexibleFormsService = $this->getServiceLocator()->get('phoenix-flexibleforms');
        $item = $flexibleFormsService->getItemBy(array('name' => $formName));

        if (empty($item)) {
            return new JsonModel(array('success' => false, 'message' => 'The form could not be submitted.'));
        }
      
        $fields = $flexibleFormsService->getItemsByName($formName);
        $flexibleFormsManager->setFlexibleForm($item);
        $submitModel = $flexibleFormsManager->createModel();

        $flexibleFormsManager->save($submitModel, $postDetails);

        $this->renderer = $this->getServiceLocator()->get('ViewRenderer');
        $content = $this->renderer->render('flexible-forms/view-toolbox/email-template', array('postDetails' => $postDetails, 'nameFields' => $fields));
        
        
//        
//// make a header as html  
//        $toEmail = $flexibleFormsManager->departEmails($postDetails['department'], $postDetails['currentProperty']);
//        $html = new MimePart($content);
//        $html->type = "text/html";
//        $body = new MimeMessage();
//        $body->setParts(array($html,));
//
//// instance mail   
//        $mail = new Mail\Message();
//        $mail->setBody($body); // will generate our code html from email-template.phtml  
//        $mail->setFrom('info@loewshotelsintl.com', 'Contact Information');
//        $mail->setTo($toEmail);
//        $mail->setSubject($formName);
//
//        $transport = new Mail\Transport\Smtp($options);
//        $transport->send($mail);
//         
        return new JsonModel(array('message' => 'The form is submitted.'));
    }

}
