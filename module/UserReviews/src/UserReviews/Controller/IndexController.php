<?php

namespace UserReviews\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


class IndexController extends AbstractActionController
{
	/**
     * The index action. This is the default action for the controller.
     * Right now, it's just being used for debug output.
     * @return \Zend\View\Model\ViewModel $viewModel;
     */
 	public function IndexAction()
 	{
 		echo "this is the indexController's index action";
 	}

 	public function ViewDetailsAction()
 	{
 		echo "this is the view test action";
 	}

}


