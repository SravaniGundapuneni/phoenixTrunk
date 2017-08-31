<?

/**
 * @package     PhoenixToolbar
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      Saurabh Shirgaonkar <sshirgaonkar@travelclick.com>
 * @filename    ToolboxController.php
 */

namespace PhoenixToolbar\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use PhoenixToolbar\Form\ToolbarForm;
use PhoenixToolbar\Service;


    class ToolboxController extends AbstractActionController
    {
	
	
        public function indexAction()
        {               			
		    //$form = new ToolbarForm;
			//$viewModel = new ViewModel(array('form' => $form));
			//return $viewModel;	
  // REDIRECTION ALTERNATIVE METHOD 1 STARTS		 
		    return $this->redirect()->toRoute('phoenixToolbar-toolbox', array('action' => 'manage'));
		    // REDIRECTION ALTERNATIVE METHOD 1 ENDS			
        }
		
		
		public function viewAction()
        {   
		
		    // OBTAINING THE VALUES FROM THE MANAGE INDEX PAGE STARTS  
		    $id=$_GET["id"];
			$filename=$_GET["fname"];
			$hotelname=$_GET["hname"];
			// OBTAINING THE VALUES FROM THE MANAGE INDEX PAGE ENDS 
			
			// CHECKING THE VALUES OBTAINED STARTS 			
			//echo "<br>";
			//echo $id;
			//echo "<br>";
			//echo $filename;
			//echo "<br>";
			//echo $hotelname;
			//echo "<br>";
			// CHECKING THE VALUES OBTAINED ENDS 
			
            // DISPLAY THE 
			//return new ViewModel();			
		    $form = new ToolbarForm;
			$viewModel = new ViewModel(array('form' => $form));
			return $viewModel;	
            //             
 			
        }
		
		
		 public function editAction()
        { 
		    
			
			// OBTAIN INFORMATION OF THE RECORD TO BE EDITED STARTS 					
			$id=$_GET["id"];
			$filename=$_GET["fname"];
			$hotelname=$_GET["hname"];
			// OBTAIN INFORMATION OF THE RECORD TO BE EDITED ENDS
			
			// CHECKING VALUES STARTS 
            /*			
			echo "<br>";
			echo $id;
			echo "<br>";
			echo $filename;
			echo "<br>";
			echo $hotelname;
			echo "<br>";
			*/
			// CHECKING VALUES ENDS 
            
			// GET THE VALUES FOR THE SELECTED RECORD STARTS 
			$serviceLocator=$this->getServiceLocator();
		    $config=$serviceLocator->get('config');
		    $editsiteinfoService = $this->getServiceLocator()->get('toolbar12');		
		    $siteParamaters=$editsiteinfoService->editsiteInformation($id,$filename,$hotelname);		
			// GET THE VALUES FOR THE SELECTED RECORD ENDS  
			
			// echo $siteParamaters['hotelName'];
			
			    //echo $siteParamaters['custom1Url'];
				//echo $siteParamaters['custom1Flag'];
				
				//echo $siteParamaters['amazonFlag'];
				//echo $siteParamaters['amazonUrl'];
			
			    //echo $siteParamaters['tripadvisorFlag'];
				//echo $siteParamaters['tripadvisorUrl'];
			
			   // die();
			   // var_dump($siteParamaters);
				
			
			$viewModel = new ViewModel(array(
		            'hotelName'=>$siteParamaters['hotelName'],
					'toolbarLayout'=>$siteParamaters['toolbarLayout'],
					'toolbarEnabled'=>$siteParamaters['toolbarEnabled'],
					'toolbarMinimize'=>$siteParamaters['toolbarMinimize'],
					'toolbarColor'=>$siteParamaters['toolbarColor'],
					'facebookFlag'=>$siteParamaters['facebookFlag'],
					'facebookUrl'=>$siteParamaters['facebookUrl'],
					'facebookCompanyPageName'=>$siteParamaters['facebookCompanyPageName'],
					'twitterFlag'=>$siteParamaters['twitterFlag'],
					'twitterUrl'=>$siteParamaters['twitterUrl'],
                    'twitterUsername'=>$siteParamaters['twitterUsername'],
                    'twitterWidgetId'=>$siteParamaters['twitterWidgetId'],					
					'googleplusFlag'=>$siteParamaters['googleplusFlag'],
					'googleplusUrl'=>$siteParamaters['googleplusUrl'],
					'youtubeFlag'=>$siteParamaters['youtubeFlag'],
					'youtubeUrl'=>$siteParamaters['youtubeUrl'],
					'tripadvisorFlag'=>$siteParamaters['tripadvisorFlag'],
					'tripadvisorUrl'=>$siteParamaters['tripadvisorUrl'],
					'amazonFlag'=>$siteParamaters['amazonFlag'],
					'amazonUrl'=>$siteParamaters['amazonUrl'],
					'instagramFlag'=>$siteParamaters['instagramFlag'],
					'instagramUrl'=>$siteParamaters['instagramUrl'],
					'instagramUsername'=>$siteParamaters['instagramUsername'],
					'dropboxFlag'=>$siteParamaters['dropboxFlag'],
					'dropboxUrl'=>$siteParamaters['dropboxUrl'],
					'gmailFlag'=>$siteParamaters['gmailFlag'],
					'gmailUrl'=>$siteParamaters['gmailUrl'],
					'googleFlag'=>$siteParamaters['googleFlag'],
					'googleUrl'=>$siteParamaters['googleUrl'],
					'makemytripFlag'=>$siteParamaters['makemytripFlag'],
					'makemytripUrl'=>$siteParamaters['makemytripUrl'],
					'expediaFlag'=>$siteParamaters['expediaFlag'],
					'expediaUrl'=>$siteParamaters['expediaUrl'],					
					'orkutFlag'=>$siteParamaters['orkutFlag'],
					'orkutUrl'=>$siteParamaters['orkutUrl'],
					'wordpressFlag'=>$siteParamaters['wordpressFlag'],
					'wordpressUrl'=>$siteParamaters['wordpressUrl'],					
					'yahooFlag'=>$siteParamaters['yahooFlag'],
					'yahooUrl'=>$siteParamaters['yahooUrl'],
					'bbcFlag'=>$siteParamaters['bbcFlag'],
					'bbcUrl'=>$siteParamaters['bbcUrl'],
					'aolFlag'=>$siteParamaters['aolFlag'],
					'aolUrl'=>$siteParamaters['aolUrl'],
					'microsoftFlag'=>$siteParamaters['microsoftFlag'],
					'microsoftUrl'=>$siteParamaters['microsoftUrl'],
					'boaFlag'=>$siteParamaters['boaFlag'],
					'boaUrl'=>$siteParamaters['boaUrl'],
					'airfranceFlag'=>$siteParamaters['airfranceFlag'],
					'airfranceUrl'=>$siteParamaters['airfranceUrl'],
					'enterpriseFlag'=>$siteParamaters['enterpriseFlag'],
					'enterpriseUrl'=>$siteParamaters['enterpriseUrl'],
					'hertzFlag'=>$siteParamaters['hertzFlag'],
					'hertzUrl'=>$siteParamaters['hertzUrl'],
					'airemiratesFlag'=>$siteParamaters['airemiratesFlag'],
					'airemiratesUrl'=>$siteParamaters['airemiratesUrl'],					
					'custom1Flag'=>$siteParamaters['custom1Flag'],
					'custom1Url'=>$siteParamaters['custom1Url'],
					'custom2Flag'=>$siteParamaters['custom2Flag'],
					'custom2Url'=>$siteParamaters['custom2Url'],
					'custom3Flag'=>$siteParamaters['custom3Flag'],
					'custom3Url'=>$siteParamaters['custom3Url'],
					'custom4Flag'=>$siteParamaters['custom4Flag'],
					'custom4Url'=>$siteParamaters['custom4Url'],
					'custom5Flag'=>$siteParamaters['custom5Flag'],
					'custom5Url'=>$siteParamaters['custom5Url'],
					'codeArea'=>$siteParamaters['codeArea']					
					));
						
			        return $viewModel; 
					
        }
		
		 public function deleteAction()
        {   
		    // OBTAIN THE VALUES OF THE RECORD TO BE DELETED STARTS 
			$id=$_GET["id"];
			$filename=$_GET["fname"];
			$hotelname=$_GET["hname"];
			// OBTAIN THE VALUES OF THE RECORD TO BE DELETED ENDS 
			
			// CHECK FOR THE VARIABLE VALUES STARTS 
            /*  			
			echo "<br>";
			echo $id;
			echo "<br>";
			echo $filename;
			echo "<br>";
			echo $hotelname;
			echo "<br>";
			*/
			// CHECK FOR THE VARIABLE VALUES ENDS  
			
			// CALL TO THE SERVICE TO PERFORM DELETE OPERATIONS STARTS  
		    $serviceLocator=$this->getServiceLocator();
		    $config=$serviceLocator->get('config');
		    $deleteinfoService = $this->getServiceLocator()->get('toolbar12');		
		    $result=$deleteinfoService->deletesiteInformation($id,$filename,$hotelname);		 
            // CALL TO THE SERVICE TO PERFORM DELETE OPERATION ENDS  
	    
            // REDIRECTION ALTERNATIVE METHOD 1 STARTS		 
		    return $this->redirect()->toRoute('phoenixToolbar-toolbox', array('action' => 'manage'));
		    // REDIRECTION ALTERNATIVE METHOD 1 ENDS 	

		    // REDIRECTION ALTERNATIVE METHOD 2 STARTS 		
            //return $this->redirect()->toRoute('manage');
		    // REDIRECTION ALTERNATIVE METHOD 2 ENDS 			
        }
		
        /*
        public function configurationAction()
        {
		   $serviceLocator=$this->getServiceLocator();
		   $config=$serviceLocator->get('config');		   
		   $assetService = $this->getServiceLocator()->get('toolbar12');
		   $a=10;
		   $b=20;
		   $result=$assetService->displayMessage($a,$b);


           //echo "resukt".$result;  
		   $viewModel=new ViewModel();
		   $children = $viewModel->getChildren();
  
            //$viewModel->setVariable('stat',$re);
			//$taskViewModel = $children[0];
			$viewModel->setVariable('stat',$result);
			$viewModel->setTemplate('phoenix-toolbar/toolbox/configuration');
		
			
			
			//$viewmodel->setTemplate();
			//$viewmodel->setVariable('listkey',$result);
			return $viewModel;	

        }
		*/
		
		   
        public function addsiteAction()
        {
            $form = new ToolbarForm;
			$viewModel = new ViewModel(array('form' => $form));
			return $viewModel;  
        }
		
		/*		
		public function settingsAction()
        {
            $form = new ToolbarForm;
			$viewModel = new ViewModel(array('form' => $form));
			return $viewModel;  
        }
		*/
		
        /*
        public function displayAction()
        {
            return new ViewModel();   
        }
		*/
		
		public function manageAction()
		{
		    //return new ViewModel(); 
		    $serviceLocator=$this->getServiceLocator();
		    $config=$serviceLocator->get('config');
		    $toolboxService = $this->getServiceLocator()->get('toolbar12');
			$a=10;
			$b=20;
			$result=$toolboxService->displayManageItems($a,$b);		
			return $result;          			
		}
		
		public function confirmationAction()
		{
		    return new ViewModel();
           
		}
		
		
		
		
		
		
		
		
		// FUNCTION CONFIRM ACTION STARTS  
		
		public function confirmAction()
		{
   
        // new code added on 9 oct 2014 starts 
		$facebookFlag=$this->getRequest()->getPost('facebookFlag');
		//echo "Facebook Flag : ";
		//echo $facebookFlag;
		//die();
		
		// new code added on 9 oct 2014 ends 
     
        //echo "I am in the confirm action"; 
		//die();
        
        // property details starts  		
        
		$propertyName=$this->getRequest()->getPost('propertyName');
        //echo "<br> Property Name : ".$propertyName;		
        
		// property details ends 
    
        // toolbox customizations options starts   
		
		$toolbarEnable=$this->getRequest()->getPost('toolbarEnabled');
		//echo "<br> Toolbar Enabled : ".$toolbarEnable;
		
		$toolbarMinimize=$this->getRequest()->getPost('toolbarMinimize');
		//echo "<br> Toolbar Minimized : ".$toolbarMinimize;
		
		$toolbarLayout=$this->getRequest()->getPost('toolbarLayout');
		//echo "<br> Toolbar Layout : ".$toolbarLayout;
		
		$toolbarColor=$this->getRequest()->getPost('toolbarColor');
		//echo "<br> Toolbar Color : ".$toolbarColor;
				
		// toolbox customization option ends 
		
		// toolbox links/account setup starts   
		
		// facebook starts 
		$facebookFlag=$this->getRequest()->getPost('facebookFlag');
		//echo "<br> Facebook Flag : ".$facebookFlag;		
        $facebookUrl=$this->getRequest()->getPost('facebookUrl');
		$facebookCompanyPageName=$this->getRequest()->getPost('facebookCompanyPageName');
		//echo "<br> Facebook Url : ".$facebookUrl;
		// facebook ends 
				
		// twitter starts	
	    $twitterFlag=$this->getRequest()->getPost('twitterFlag');
		//echo "<br> Twitter Flag : ".$twitterFlag;		
		$twitterUrl=$this->getRequest()->getPost('twitterUrl');
		$twitterUsername=$this->getRequest()->getPost('twitterUsername');
		$twitterWidgetId=$this->getRequest()->getPost('twitterWidgetId');
		//echo "<br> Twitter Url : ".$twitterUrl;
        // twitter ends 

		// Google+ starts	
	    $googleplusFlag=$this->getRequest()->getPost('googleplusFlag');
		//echo "<br> Googleplus Flag : ".$googleplusFlag;		
		$googleplusUrl=$this->getRequest()->getPost('googleplusUrl');
		//echo "<br> Google plus Url : ".$googleplusUrl;
        // Google+ ends
		
		// youtube starts 
		$youtubeFlag=$this->getRequest()->getPost('youtubeFlag');
		//echo "<br> Youtube Flag : ".$youtubeFlag;
		$youtubeUrl=$this->getRequest()->getPost('youtubeUrl');
		//echo "<br> Youtube Url : ".$youtubeUrl;	
		// youtube ends 
		
		// tripadvisor starts
	    $tripadvisorFlag=$this->getRequest()->getPost('tripadvisorFlag');
		//echo "<br> Tripadvisor Flag : ".$tripadvisorFlag;
		$tripadvisorUrl=$this->getRequest()->getPost('tripadvisorUrl');
		//echo "<br> Tripadvisor Url : ".$tripadvisorUrl;		
		// tripadvisor ends 
				
		// amazon starts 
		$amazonFlag=$this->getRequest()->getPost('amazonFlag');
		//echo "<br> Amazon Flag : ".$amazonFlag;
		$amazonUrl=$this->getRequest()->getPost('amazonUrl');
		//echo "<br> Amazon Url : ".$amazonUrl;	
		// amazon ends		
				
		// instagram starts 
		$instagramFlag=$this->getRequest()->getPost('instagramFlag');
		//echo "<br> Instagram Flag : ".$instagramFlag;
		$instagramUrl=$this->getRequest()->getPost('instagramUrl');
	    $instagramUsername=$this->getRequest()->getPost('instagramUsername');
		//echo "<br> Instagram Url : ".$instagramUrl;	
		// instagram ends 
		
		// dropbox starts 
		$dropboxFlag=$this->getRequest()->getPost('dropboxFlag');
		//echo "<br> Dropbox Flag : ".$dropboxFlag;
		$dropboxUrl=$this->getRequest()->getPost('dropboxUrl');
		//echo "<br> Dropbox Url : ".$dropboxUrl;	
		// dropbox ends
				
        // Gmail starts 
		$gmailFlag=$this->getRequest()->getPost('gmailFlag');
		//echo "<br> Gmail Flag : ".$gmailFlag;
		$gmailUrl=$this->getRequest()->getPost('gmailUrl');
		//echo "<br> Gmail Url : ".$gmailUrl;	
		// Gmail ends
		
		// Google starts 
		$googleFlag=$this->getRequest()->getPost('googleFlag');
		//echo "<br> Google Flag : ".$googleFlag;
		$googleUrl=$this->getRequest()->getPost('googleUrl');
		//echo "<br> Google Url : ".$googleUrl;	
		// Google ends
		
		// Makemytrip starts 
		$makemytripFlag=$this->getRequest()->getPost('makemytripFlag');
		//echo "<br> Makemytrip Flag : ".$makemytripFlag;
		$makemytripUrl=$this->getRequest()->getPost('makemytripUrl');
		//echo "<br> Makemytrip Url : ".$makemytripUrl;	
		// Makemytrip ends
		
		// Expedia starts 
		$expediaFlag=$this->getRequest()->getPost('expediaFlag');
		//echo "<br> Expedia Flag : ".$expediaFlag;
		$expediaUrl=$this->getRequest()->getPost('expediaUrl');
		//echo "<br> Expedia Url : ".$expediaUrl;	
		// Expedia ends
		
		// orkut starts 
		$orkutFlag=$this->getRequest()->getPost('orkutFlag');
		//echo "<br> Orkut Flag : ".$orkutFlag;
		$orkutUrl=$this->getRequest()->getPost('orkutUrl');
		//echo "<br> Orkut Url : ".$orkutUrl;	
		// orkut ends
		
		// wordpress starts 
		$wordpressFlag=$this->getRequest()->getPost('wordpressFlag');
		//echo "<br> Wordpress Flag : ".$wordpressFlag;
		$wordpressUrl=$this->getRequest()->getPost('wordpressUrl');
		//echo "<br> wordpress Url : ".$wordpressUrl;	
		// wordpress ends
		
		// yahoo starts 
		$yahooFlag=$this->getRequest()->getPost('yahooFlag');
		//echo "<br> Yahoo Flag : ".$yahooFlag;
		$yahooUrl=$this->getRequest()->getPost('yahooUrl');
		//echo "<br> Yahoo Url : ".$yahooUrl;	
		// yahoo ends
		
		// bbc starts 
		$bbcFlag=$this->getRequest()->getPost('bbcFlag');
		//echo "<br> BBC Flag : ".$bbcFlag;
		$bbcUrl=$this->getRequest()->getPost('bbcUrl');
		//echo "<br> BBC Url : ".$bbcUrl;	
		// bbc ends
		
	    // aol starts 
		$aolFlag=$this->getRequest()->getPost('aolFlag');
		//echo "<br> AOL Flag : ".$aolFlag;
		$aolUrl=$this->getRequest()->getPost('aolUrl');
		//echo "<br> AOL Url : ".$aolUrl;	
		// aol ends
	
	    // microsoft starts 
		$microsoftFlag=$this->getRequest()->getPost('microsoftFlag');
		//echo "<br> Microsoft Flag : ".$microsoftFlag;
		$microsoftUrl=$this->getRequest()->getPost('microsoftUrl');
		//echo "<br> Microsoft Url : ".$microsoftUrl;	
		// microsoft ends
				
		// boa starts 
		$boaFlag=$this->getRequest()->getPost('boaFlag');
		//echo "<br> BOA Flag : ".$boaFlag;
		$boaUrl=$this->getRequest()->getPost('boaUrl');
		//echo "<br> BOA Url : ".$boaUrl;	
		// boa ends
		
		// airfrance starts 
		$airfranceFlag=$this->getRequest()->getPost('airfranceFlag');
		//echo "<br> Airfrance Flag : ".$airfranceFlag;
		$airfranceUrl=$this->getRequest()->getPost('airfranceUrl');
		//echo "<br> Air France Url : ".$airfranceUrl;	
		// airfrance ends
		
		// enterprise starts 
		$enterpriseFlag=$this->getRequest()->getPost('enterpriseFlag');
		//echo "<br> Enterprise Flag : ".$enterpriseFlag;
		$enterpriseUrl=$this->getRequest()->getPost('enterpriseUrl');
		//echo "<br> Enterprise Url : ".$enterpriseUrl;	
		// enterprise ends
		
		// hertz starts 
		$hertzFlag=$this->getRequest()->getPost('hertzFlag');
		//echo "<br> Hertz Flag : ".$hertzFlag;
		$hertzUrl=$this->getRequest()->getPost('hertzUrl');
		//echo "<br> Hertz Url : ".$hertzUrl;	
		// hertz ends
		
		// airemirates starts 
		$airemiratesFlag=$this->getRequest()->getPost('airemiratesFlag');
		//echo "<br> Emirates Flag : ".$airemiratesFlag;
		$airemiratesUrl=$this->getRequest()->getPost('airemiratesUrl');
		//echo "<br> Air Emirates Url : ".$airemiratesUrl;	
		// airemirates ends
				
		// toolbox link/account setup ends 
				
		// custom components starts 
		
		// custom 1 starts 
		$custom1Flag=$this->getRequest()->getPost('custom1Flag');
		//echo "<br> Custom 1 Flag : ".$custom1Flag;		
		$custom1Url=$this->getRequest()->getPost('custom1Url');
		//echo "<br> Custom 1 Url : ".$custom1Url;
		// custom 1 ends 
			
		// custom 2 starts 	
		$custom2Flag=$this->getRequest()->getPost('custom2Flag');
		//echo "<br> Custom 2 Flag : ".$custom2Flag;		
		$custom2Url=$this->getRequest()->getPost('custom2Url');
		//echo "<br> Custom 2 Url : ".$custom2Url;
		// custom 2 ends 
		
		// custom 3 starts 	
		$custom3Flag=$this->getRequest()->getPost('custom3Flag');
		//echo "<br> Custom 3 Flag : ".$custom3Flag;		
		$custom3Url=$this->getRequest()->getPost('custom3Url');
		//echo "<br> Custom 3 Url : ".$custom3Url;
		// custom 3 ends
		
		// custom 4 starts 	
		$custom4Flag=$this->getRequest()->getPost('custom4Flag');
		//echo "<br> Custom 4 Flag : ".$custom4Flag;		
		$custom4Url=$this->getRequest()->getPost('custom4Url');
		//echo "<br> Custom 4 Url : ".$custom4Url;
		// custom 4 ends
				
		// custom 5 starts 
		$custom5Flag=$this->getRequest()->getPost('custom5Flag');
		//echo "<br> Custom 5 Flag : ".$custom5Flag;		
		$custom5Url=$this->getRequest()->getPost('custom5Url');
		//echo "<br> Custom 5 Url : ".$custom5Url;
		// custom 5 ends

        // Code Area Starts
        $codeArea=$this->getRequest()->getPost('codeArea');		
        // Code Area Ends  		
		
		// custom components ends 
		
		
		
       	//$toolbarColourscheme=$this->getRequest()->getPost('toolbarcolourscscheme');
        //echo $toolbarColourScheme;
		
		
		 $serviceLocator=$this->getServiceLocator();
		 $config=$serviceLocator->get('config');
		 $assetService = $this->getServiceLocator()->get('toolbar12');
		 $a=10;
		 $b=20;
		 //die();
		 //$result=$assetService->displayMessage($a,$b);
		 //$result=$assetService->addsiteInformation($propertyName,$toolbarEnable,$toolbarMinimize,$toolbarLayout,$toolbarColor,$facebookFlag,$facebookUrl,$youtubeFlag,$youtubeUrl);
		 //die();
		 
		 $result=$assetService->addsiteInformation(
		                                           $propertyName,
		                                           $toolbarEnable,
												   $toolbarMinimize,
												   $toolbarLayout,
												   $toolbarColor,
												   $facebookFlag,$facebookUrl,$facebookCompanyPageName,
												   $twitterFlag,$twitterUrl,$twitterUsername,$twitterWidgetId,
                                                   $googleplusFlag,$googleplusUrl,												   
												   $youtubeFlag,$youtubeUrl,
												   $tripadvisorFlag,$tripadvisorUrl,
												   $amazonFlag,$amazonUrl,
												   $instagramFlag,$instagramUrl,$instagramUsername,
												   $dropboxFlag,$dropboxUrl,												  
												   $gmailFlag,$gmailUrl,
												   $googleFlag,$googleUrl,
												   $makemytripFlag,$makemytripUrl,
												   $expediaFlag,$expediaUrl,
												   $orkutFlag,$orkutUrl,
												   $wordpressFlag,$wordpressUrl,
												   $yahooFlag,$yahooUrl,
												   $bbcFlag,$bbcUrl,
												   $aolFlag, $aolUrl,
												   $microsoftFlag, $microsoftUrl,
												   $boaFlag, $boaUrl,
												   $airfranceFlag, $airfranceUrl,
												   $enterpriseFlag, $enterpriseUrl,
												   $hertzFlag, $hertzUrl,
												   $airemiratesFlag, $airemiratesUrl,
												   $custom1Flag,$custom1Url,
                                                   $custom2Flag,$custom2Url,
                                                   $custom3Flag,$custom3Url,
                                                   $custom4Flag,$custom4Url,												   
												   $custom5Flag,$custom5Url,
												   $codeArea
												   );
												 
		 
		 echo $result;	
         	 
		  return $this->redirect()->toRoute('phoenixToolbar-toolbox', array('action' => 'manage')); 			
		  //return new ViewModel();  
           
		   // $viewModel= new ViewModel();
	 
		   // return $viewModel;
		
		//die();
		
		}
		
		// FUNCTION CONFIRM ACTION ENDS
		
		
		
		
		
		// FUNCTION CONFIRM ACTION STARTS  
		
		public function editconfirmAction()
		{
   
        // new code added on 9 oct 2014 starts 
		$facebookFlag=$this->getRequest()->getPost('facebookFlag');
		//echo "Facebook Flag : ";
		//echo $facebookFlag;
		//die();
		
		// new code added on 9 oct 2014 ends 
     
        //echo "I am in the confirm action"; 
		//die();
        
        // property details starts  		
        
		$propertyName=$this->getRequest()->getPost('propertyName');
        //echo "<br> Property Name : ".$propertyName;		
        
		// property details ends 
    
        // toolbox customizations options starts   
		
		$toolbarEnable=$this->getRequest()->getPost('toolbarEnabled');
		//echo "<br> Toolbar Enabled : ".$toolbarEnable;
		
		$toolbarMinimize=$this->getRequest()->getPost('toolbarMinimize');
		//echo "<br> Toolbar Minimized : ".$toolbarMinimize;
		
		$toolbarLayout=$this->getRequest()->getPost('toolbarLayout');
		//echo "<br> Toolbar Layout : ".$toolbarLayout;
		
		$toolbarColor=$this->getRequest()->getPost('toolbarColor');
		//echo "<br> Toolbar Color : ".$toolbarColor;
				
		// toolbox customization option ends 
		
		// toolbox links/account setup starts   
		
		// facebook starts 
		$facebookFlag=$this->getRequest()->getPost('facebookFlag');
		//echo "<br> Facebook Flag : ".$facebookFlag;		
        $facebookUrl=$this->getRequest()->getPost('facebookUrl');
		$facebookCompanyPageName=$this->getRequest()->getPost('facebookCompanyPageName');
		//echo "<br> Facebook Url : ".$facebookUrl;
		// facebook ends 
				
		// twitter starts	
	    $twitterFlag=$this->getRequest()->getPost('twitterFlag');
		//echo "<br> Twitter Flag : ".$twitterFlag;		
		$twitterUrl=$this->getRequest()->getPost('twitterUrl');
		$twitterUsername=$this->getRequest()->getPost('twitterUsername');
		$twitterWidgetId=$this->getRequest()->getPost('twitterWidgetId');
		//echo "<br> Twitter Url : ".$twitterUrl;
        // twitter ends 

		// Google+ starts	
	    $googleplusFlag=$this->getRequest()->getPost('googleplusFlag');
		//echo "<br> Googleplus Flag : ".$googleplusFlag;		
		$googleplusUrl=$this->getRequest()->getPost('googleplusUrl');
		//echo "<br> Google plus Url : ".$googleplusUrl;
        // Google+ ends
		
		// youtube starts 
		$youtubeFlag=$this->getRequest()->getPost('youtubeFlag');
		//echo "<br> Youtube Flag : ".$youtubeFlag;
		$youtubeUrl=$this->getRequest()->getPost('youtubeUrl');
		//echo "<br> Youtube Url : ".$youtubeUrl;	
		// youtube ends 
		
		// tripadvisor starts
	    $tripadvisorFlag=$this->getRequest()->getPost('tripadvisorFlag');
		//echo "<br> Tripadvisor Flag : ".$tripadvisorFlag;
		$tripadvisorUrl=$this->getRequest()->getPost('tripadvisorUrl');
		//echo "<br> Tripadvisor Url : ".$tripadvisorUrl;		
		// tripadvisor ends 
				
		// amazon starts 
		$amazonFlag=$this->getRequest()->getPost('amazonFlag');
		//echo "<br> Amazon Flag : ".$amazonFlag;
		$amazonUrl=$this->getRequest()->getPost('amazonUrl');
		//echo "<br> Amazon Url : ".$amazonUrl;	
		// amazon ends		
				
		// instagram starts 
		$instagramFlag=$this->getRequest()->getPost('instagramFlag');
		//echo "<br> Instagram Flag : ".$instagramFlag;
		$instagramUrl=$this->getRequest()->getPost('instagramUrl');
	    $instagramUsername=$this->getRequest()->getPost('instagramUsername');
		//echo "<br> Instagram Username : ".$instagramUsername;	
		//die();
		
		// instagram ends 
		
		// dropbox starts 
		$dropboxFlag=$this->getRequest()->getPost('dropboxFlag');
		//echo "<br> Dropbox Flag : ".$dropboxFlag;
		$dropboxUrl=$this->getRequest()->getPost('dropboxUrl');
		//echo "<br> Dropbox Url : ".$dropboxUrl;	
		// dropbox ends
				
        // Gmail starts 
		$gmailFlag=$this->getRequest()->getPost('gmailFlag');
		//echo "<br> Gmail Flag : ".$gmailFlag;
		$gmailUrl=$this->getRequest()->getPost('gmailUrl');
		//echo "<br> Gmail Url : ".$gmailUrl;	
		// Gmail ends
		
		// Google starts 
		$googleFlag=$this->getRequest()->getPost('googleFlag');
		//echo "<br> Google Flag : ".$googleFlag;
		$googleUrl=$this->getRequest()->getPost('googleUrl');
		//echo "<br> Google Url : ".$googleUrl;	
		// Google ends
		
		// Makemytrip starts 
		$makemytripFlag=$this->getRequest()->getPost('makemytripFlag');
		//echo "<br> Makemytrip Flag : ".$makemytripFlag;
		$makemytripUrl=$this->getRequest()->getPost('makemytripUrl');
		//echo "<br> Makemytrip Url : ".$makemytripUrl;	
		// Makemytrip ends
		
		// Expedia starts 
		$expediaFlag=$this->getRequest()->getPost('expediaFlag');
		//echo "<br> Expedia Flag : ".$expediaFlag;
		$expediaUrl=$this->getRequest()->getPost('expediaUrl');
		//echo "<br> Expedia Url : ".$expediaUrl;	
		// Expedia ends
		
		// orkut starts 
		$orkutFlag=$this->getRequest()->getPost('orkutFlag');
		//echo "<br> Orkut Flag : ".$orkutFlag;
		$orkutUrl=$this->getRequest()->getPost('orkutUrl');
		//echo "<br> Orkut Url : ".$orkutUrl;	
		// orkut ends
		
		// wordpress starts 
		$wordpressFlag=$this->getRequest()->getPost('wordpressFlag');
		//echo "<br> Wordpress Flag : ".$wordpressFlag;
		$wordpressUrl=$this->getRequest()->getPost('wordpressUrl');
		//echo "<br> wordpress Url : ".$wordpressUrl;	
		// wordpress ends
		
		// yahoo starts 
		$yahooFlag=$this->getRequest()->getPost('yahooFlag');
		//echo "<br> Yahoo Flag : ".$yahooFlag;
		$yahooUrl=$this->getRequest()->getPost('yahooUrl');
		//echo "<br> Yahoo Url : ".$yahooUrl;	
		// yahoo ends
		
		// bbc starts 
		$bbcFlag=$this->getRequest()->getPost('bbcFlag');
		//echo "<br> BBC Flag : ".$bbcFlag;
		$bbcUrl=$this->getRequest()->getPost('bbcUrl');
		//echo "<br> BBC Url : ".$bbcUrl;	
		// bbc ends
		
	    // aol starts 
		$aolFlag=$this->getRequest()->getPost('aolFlag');
		//echo "<br> AOL Flag : ".$aolFlag;
		$aolUrl=$this->getRequest()->getPost('aolUrl');
		//echo "<br> AOL Url : ".$aolUrl;	
		// aol ends
	
	    // microsoft starts 
		$microsoftFlag=$this->getRequest()->getPost('microsoftFlag');
		//echo "<br> Microsoft Flag : ".$microsoftFlag;
		$microsoftUrl=$this->getRequest()->getPost('microsoftUrl');
		//echo "<br> Microsoft Url : ".$microsoftUrl;	
		// microsoft ends
				
		// boa starts 
		$boaFlag=$this->getRequest()->getPost('boaFlag');
		//echo "<br> BOA Flag : ".$boaFlag;
		$boaUrl=$this->getRequest()->getPost('boaUrl');
		//echo "<br> BOA Url : ".$boaUrl;	
		// boa ends
		
		// airfrance starts 
		$airfranceFlag=$this->getRequest()->getPost('airfranceFlag');
		//echo "<br> Airfrance Flag : ".$airfranceFlag;
		$airfranceUrl=$this->getRequest()->getPost('airfranceUrl');
		//echo "<br> Air France Url : ".$airfranceUrl;	
		// airfrance ends
		
		// enterprise starts 
		$enterpriseFlag=$this->getRequest()->getPost('enterpriseFlag');
		//echo "<br> Enterprise Flag : ".$enterpriseFlag;
		$enterpriseUrl=$this->getRequest()->getPost('enterpriseUrl');
		//echo "<br> Enterprise Url : ".$enterpriseUrl;	
		// enterprise ends
		
		// hertz starts 
		$hertzFlag=$this->getRequest()->getPost('hertzFlag');
		//echo "<br> Hertz Flag : ".$hertzFlag;
		$hertzUrl=$this->getRequest()->getPost('hertzUrl');
		//echo "<br> Hertz Url : ".$hertzUrl;	
		// hertz ends
		
		// airemirates starts 
		$airemiratesFlag=$this->getRequest()->getPost('airemiratesFlag');
		//echo "<br> Emirates Flag : ".$airemiratesFlag;
		$airemiratesUrl=$this->getRequest()->getPost('airemiratesUrl');
		//echo "<br> Air Emirates Url : ".$airemiratesUrl;	
		// airemirates ends
				
		// toolbox link/account setup ends 
				
		// custom components starts 
		
		// custom 1 starts 
		$custom1Flag=$this->getRequest()->getPost('custom1Flag');
		//echo "<br> Custom 1 Flag : ".$custom1Flag;		
		$custom1Url=$this->getRequest()->getPost('custom1Url');
		//echo "<br> Custom 1 Url : ".$custom1Url;
		// custom 1 ends 
			
		// custom 2 starts 	
		$custom2Flag=$this->getRequest()->getPost('custom2Flag');
		//echo "<br> Custom 2 Flag : ".$custom2Flag;		
		$custom2Url=$this->getRequest()->getPost('custom2Url');
		//echo "<br> Custom 2 Url : ".$custom2Url;
		// custom 2 ends 
		
		// custom 3 starts 	
		$custom3Flag=$this->getRequest()->getPost('custom3Flag');
		//echo "<br> Custom 3 Flag : ".$custom3Flag;		
		$custom3Url=$this->getRequest()->getPost('custom3Url');
		//echo "<br> Custom 3 Url : ".$custom3Url;
		// custom 3 ends
		
		// custom 4 starts 	
		$custom4Flag=$this->getRequest()->getPost('custom4Flag');
		//echo "<br> Custom 4 Flag : ".$custom4Flag;		
		$custom4Url=$this->getRequest()->getPost('custom4Url');
		//echo "<br> Custom 4 Url : ".$custom4Url;
		// custom 4 ends
				
		// custom 5 starts 
		$custom5Flag=$this->getRequest()->getPost('custom5Flag');
		//echo "<br> Custom 5 Flag : ".$custom5Flag;		
		$custom5Url=$this->getRequest()->getPost('custom5Url');
		//echo "<br> Custom 5 Url : ".$custom5Url;
		// custom 5 ends

        // Code Area Starts
        $codeArea=$this->getRequest()->getPost('codeArea');
		$codeArea = str_replace(' ','',$codeArea);
        // Code Area Ends  		
		
		// custom components ends 
		
		
		
       	//$toolbarColourscheme=$this->getRequest()->getPost('toolbarcolourscscheme');
        //echo $toolbarColourScheme;
		
		
		 $serviceLocator=$this->getServiceLocator();
		 $config=$serviceLocator->get('config');
		 $assetService = $this->getServiceLocator()->get('toolbar12');
		 $a=10;
		 $b=20;
		 //die();
		 //$result=$assetService->displayMessage($a,$b);
		 //$result=$assetService->addsiteInformation($propertyName,$toolbarEnable,$toolbarMinimize,$toolbarLayout,$toolbarColor,$facebookFlag,$facebookUrl,$youtubeFlag,$youtubeUrl);
		 //die();
		 
		 $result=$assetService->editsavesiteInformation(
		                                           $propertyName,
		                                           $toolbarEnable,
												   $toolbarMinimize,
												   $toolbarLayout,
												   $toolbarColor,
												   $facebookFlag,$facebookUrl,$facebookCompanyPageName,
												   $twitterFlag,$twitterUrl,$twitterUsername,$twitterWidgetId,
                                                   $googleplusFlag,$googleplusUrl,												   
												   $youtubeFlag,$youtubeUrl,
												   $tripadvisorFlag,$tripadvisorUrl,
												   $amazonFlag,$amazonUrl,
												   $instagramFlag,$instagramUrl,$instagramUsername,
												   $dropboxFlag,$dropboxUrl,												  
												   $gmailFlag,$gmailUrl,
												   $googleFlag,$googleUrl,
												   $makemytripFlag,$makemytripUrl,
												   $expediaFlag,$expediaUrl,
												   $orkutFlag,$orkutUrl,
												   $wordpressFlag,$wordpressUrl,
												   $yahooFlag,$yahooUrl,
												   $bbcFlag,$bbcUrl,
												   $aolFlag, $aolUrl,
												   $microsoftFlag, $microsoftUrl,
												   $boaFlag, $boaUrl,
												   $airfranceFlag, $airfranceUrl,
												   $enterpriseFlag, $enterpriseUrl,
												   $hertzFlag, $hertzUrl,
												   $airemiratesFlag, $airemiratesUrl,
												   $custom1Flag,$custom1Url,
                                                   $custom2Flag,$custom2Url,
                                                   $custom3Flag,$custom3Url,
                                                   $custom4Flag,$custom4Url,												   
												   $custom5Flag,$custom5Url,
												   $codeArea
												   );
									 
		  echo $result;	
         	 
		  return $this->redirect()->toRoute('phoenixToolbar-toolbox', array('action' => 'manage')); 			
		
		
		}
		
		// FUNCTION EDIT CONFIRM ACTION ENDS
		
		
		
		
		
				
		
   
    }