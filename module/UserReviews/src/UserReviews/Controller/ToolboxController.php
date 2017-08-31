<?php

namespace UserReviews\Controller;


use Zend\View\Model\ViewModel;
use UserReviews\Form\UserReviewsForm;

use Zend\Mvc\MvcEvent;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;


//use ListModule\Controller\ToolboxController; //as ListModuleToolbox;

/**
 * UserReviews ToolboxController
 *
 * This is the primary controller to use for Toolbox actions for the UserReview module.
 * There are two other controllers that are used by this module in Toolbox, the GroupsController and the PermissionsController.
 * Anything that involves users themselves (including adding users to or removing them from groups) can be found here.
 *
 * @category    Toolbox
 * @package    	UserReviews
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
        $this->modsing = 'UserReviews';
        parent::__construct();
    }
	
	
	
}

