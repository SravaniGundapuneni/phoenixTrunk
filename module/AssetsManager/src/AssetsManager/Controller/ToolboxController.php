<?

/**
 * @package     AssetsManager
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      Saurabh Shirgaonkar <sshirgaonkar@travelclick.com>
 * @filename    ToolboxController.phtml
 */

namespace AssetsManager\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;



	class ToolboxController extends AbstractActionController
	{
	
		public function indexAction()
		{	
						
		}
		
		public function asseticAction()
		{		
			$serviceLocator=$this->getServiceLocator();
			$config=$serviceLocator->get('config');	
			
			// for testing
			$page = "about";
			
			error_log("\nPage Key: ", 3, "/www/jason.log");
            $assetService = $this->getServiceLocator()->get('assets');   
            $assetService->minifyCssAndJs($page); 		
			return $result2;  		   
		}
   
	}