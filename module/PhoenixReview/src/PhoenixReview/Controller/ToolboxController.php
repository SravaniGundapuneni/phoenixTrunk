<?php

namespace PhoenixReview\Controller;


use Zend\View\Model\ViewModel;
use PhoenixReview\Form\PhoenixReviewForm;

use Zend\Mail\Message;
use Zend\ViewRenderer\RendererInterface as ViewRenderer;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

use Zend\Mvc\MvcEvent;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;


//use ListModule\Controller\ToolboxController; //as ListModuleToolbox;

/**
 * PhoenixReview ToolboxController
 *
 * This is the primary controller to use for Toolbox actions for the UserReview module.
 * There are two other controllers that are used by this module in Toolbox, the GroupsController and the PermissionsController.
 * Anything that involves users themselves (including adding users to or removing them from groups) can be found here.
 *
 * @category    Toolbox
 * @package    	PhoenixReview
 * @subpackage  Controller
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13//.4
 * @author 		H.Naik <hnaik@travelclick.com>
 */




class ToolboxController extends \ListModule\Controller\ToolboxController 
{
	 
	public function __construct() {
        $this->modsing = 'PhoenixReview';
        parent::__construct();
    }
	/*public function additemAction() {
        $this->newItem = true;
		$viewModel = parent::additemAction();
		
		$ureview = $this->getServiceLocator()->get('phoenix-review');
				
        $request = $this->getRequest();
	   
				
        if ($request->isPost()) 
		{
			
            $postdata=$this->params()->fromPost();
			
			if($postdata['emailEnabled']==true)
			{
                 $remail=$postdata['emailId'];
			     $subject = $postdata['title'];
				 $name=$postdata['name'];
			     $data=$postdata['content'];
				 $result=$ureview->sendEmail($remail,$subject,$name,$data);
			} 
                       
		}
		    
        return $viewModel;

	}*/
	
	public function edititemAction()
	{
	    $editviewModel=parent::edititemAction();
		
		$urreview=$this->getServiceLocator()->get('phoenix-review');
		
		$request=$this->getRequest();
		if($request->isPost())
		{
			$postdata=$this->params()->fromPost();
			
			if($postdata['emailEnabled']==true)
			{
                  
				 $remail=$postdata['emailId'];
			     $subject = $postdata['title'];
				 $name=$postdata['name'];
			     $dataComment=$postdata['content'];
				 $result=$urreview->sendEmail($remail,$subject,$name,$dataComment);
				 	 
			} 
		}
		
		return $editviewModel;
	}
	
	
        
	
	
}

