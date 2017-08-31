<?

/**
 * @package     PhoenixToolbar
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      Saurabh Shirgaonkar <sshirgaonkar@travelclick.com>
 * @filename    SocketsController.php
 */
 
namespace PhoenixToolbar\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

    class SocketsController extends AbstractActionController
    {
    
	   public function indexAction()
       {
           return new ViewModel();
       }
    
	}   
   