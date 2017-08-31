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
class SocketsController extends ListModuleSocket {

    protected $module = 'UserReviews';

    /**
     *  __construct function
     * 
     * @access public
     *
     */
    public function __construct() {
        
    }

    //=============================================================================================================

    public function submitFormAction() {

        $postDetails = $this->params()->fromPost();
        $subject = $postDetails['formName'];
        print_r($postDetails); 
		die;
        $flexibleFormsService = $this->getServiceLocator()->get('phoenix-userreviews');
        //$flexibleFormsManager = $this->getServiceLocator()->get('phoenix-formmanager');

        $item = $flexibleFormsService->getItemBy(array('name' => $postDetails['formName']));

        if (empty($item)) {
            return new JsonModel(array('success' => false, 'message' => 'The form could not be submitted.'));
        }

        $fields = $flexibleFormsService->getItemsByName($subject);
        //print_r($fields);
        // $items = $flexibleFormsService->getNameById();
        //print_r($items);
        $flexibleFormsManager->setFlexibleForm($item);
        $submitModel = $flexibleFormsManager->createModel();

        $flexibleFormsManager->save($submitModel, $postDetails);
	
// Added by sgundapuneni@travelclick.com
        $subject = $postDetails['formName'];
        // render template
        $this->renderer = $this->getServiceLocator()->get('ViewRenderer');
        $content = $this->renderer->render('user-reviews/view-toolbox/email-template', array('postDetails' => $postDetails, 'nameFields' => $fields));

// make a header as html  
        $html = new MimePart($content);
        $html->type = "text/html";
        $body = new MimeMessage();
        $body->setParts(array($html,));

// instance mail   
        $mail = new Mail\Message();
        $mail->setBody($body); // will generate our code html from email-template.phtml  
        $mail->setFrom('contactt@travelclick.com', 'Contact');
        $mail->setTo('sgundapuneni@travelclick.com', 'sravani');
        $mail->addCc('bsubramanian@travelclick.com', 'bhuva');
		//$mail->addCc('afatima@travelclick.com ', 'Amina');
       // $mail->addCc('lboswell@travelclick.com', 'Linnae');
        //$mail->addCc('trozman@travelclick.com', 'Theresa');
        //$mail->addCc('mbaig@travelclick.com', 'Madhiha');

        $mail->setSubject($subject);

        $transport = new Mail\Transport\Smtp($options);
        $transport->send($mail);

        return new JsonModel(array('message' => 'The form is submitted.'));
    }

}
