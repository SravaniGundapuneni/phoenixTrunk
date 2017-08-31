<?php

namespace ContentFilter\Controller;
use Zend\View\Model\ViewModel;
use ContentFilter\Form\ContentFilter as contentFilter;
use ContentFilter\Form\Form as Form;
use Zend\ViewRenderer\RendererInterface as ViewRenderer;
use Zend\Mvc\MvcEvent;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;


//use ListModule\Controller\ToolboxController; //as ListModuleToolbox;

/**
 * ContentFilter ToolboxController
 *
 * 
 *
 * @category    Toolbox
 * @package    	ContentFilter
 * @subpackage  Controller
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13//.4
 * author       Saurabh S <sshirgaonkar@travelclick.com>
 * 
 */

 
class ToolboxController extends \ListModule\Controller\ToolboxController 
{
    protected $tasksMenu = array('filter' => 'Add Filters','editList' => 'All Items');
	
	/**
    * Module name and signature
    * @var string
    */
    protected $module = 'ContentFilter';
    protected $modsing;
    protected $newItem = false;
    protected $havePropertyList = true;
    protected $editListOptions = array(
        'Toggle Re-Order' => 'toggleReorder',
        'Save Item Order' => 'orderListItems',        
    );
	
    protected $editItemOptions = array(
        'Save' => 'publish',
        'Archive' => 'archive',
        'Trash' => 'trash',
        'Cancel' => 'cancel',
    );
	
	 /**
     * The proper title to use for the different templates
     * @var string
     */
    protected $editItemTitle = 'Edit Item';
    protected $editListTitle = 'Items';

    /**
     * The proper template to use for the editItem screen
     * @var string
     */
    protected $editItemTemplate = "edit-item";
    //protected $editListTemplate = "phoenix-contentfilter/toolbox/edit-list";
	protected $editListTemplate = "edit-list";
	
    /**
     * The base admin level for actions. Each action (or task within an action)
     * can require a higher adminLevel, but this is the lowest allowed.
     *
     * @var string
     */
		
    protected $baseAdminLevel = \Users\Service\Acl::PERMISSIONS_RESOURCE_ADMIN;

     /**
     * The route to use in setting up the the module task menu (the left hand menu)
     * @var string
     */
	 
    protected $toolboxRoute = 'home/toolbox-root';
	
    protected $socketsRoute = 'contentFilter-sockets';

	protected function getTemplateName($moduleName)
    {
        return $this->editListTemplate;
    }
    
	
	public function __construct()
    {
        preg_match("/(.+?)\\\/", get_class($this), $moduleNamespace);
        $this->module = $moduleNamespace = array_pop($moduleNamespace);
        //define("DEFAULT_NOITEM_ROUTE", $this->toolboxRoute = lcfirst("$moduleNamespace-toolbox"));
        $this->defaultNoItemRoute = $this->toolboxRoute = lcfirst("$moduleNamespace-toolbox");
        $this->editListTitle = $this->editListHeader = "List $this->modsing";
        //$this->newItemOptions = array_reverse($this->newItemOptions);
        $this->editItemOptions = array_reverse($this->editItemOptions);
        $this->editItemTitle = "Edit $this->modsing";
    }
		
	
	public function editListAction()
	{				
		$service = $this->getServiceLocator()->get("phoenix-$module");	
		$viewModel = parent::editlistAction();		
		if ($viewModel instanceof ViewModel) {
            $viewModel->module = $actualModule;
        }
        $children = $viewModel->getChildren();		
        $taskViewModel = $children[0];	
        $taskViewModel->setTemplate('phoenix-contentfilter/toolbox/edit-list');		
        $viewModel->addChild($taskViewModel, 'taskContent');
		return $viewModel;	
	}
    
	
    public function filterAction()
	{	
	    $service = $this->getServiceLocator()->get('phoenix-contentfilter');      
        $service->action = 'filter';                 
        $viewModel = parent::editlistAction();
        $children = $viewModel->getChildren();		
		$taskViewModel = $children[0];		
		$form = new contentFilter();
		$hotels=$service->getHotelOptions();
		$form->get('hotel')->setValueOptions($hotels );
		$rooms=$service->getRoomOptions();
		$form->get('roomName')->setValueOptions($rooms );
		$roomCodes=$service->getRoomCodeOptions();
		$form->get('roomCode')->setValueOptions($roomCodes );
		$bedTypes=$service->getBedTypeOptions();
		$form->get('bedType')->setValueOptions($bedTypes);	
		$roomCategory=$service->getRoomCategoryOptions();
		$form->get('roomCategories')->setValueOptions($roomCategory);
        $taskViewModel->form = $form;		
		$taskViewModel->setTemplate('phoenix-contentfilter/toolbox/filter');
        return $viewModel;				
	}
	
	
	public function filterControlAction()
    {
      $service = $this->getServiceLocator()->get('phoenix-contentfilter'); 		  
	  $request=$this->getRequest();
	  if($request->isPost()) {	 
	   $postdata=$this->params()->fromPost();	   
	   $hotelCheckbox=$postdata['hotelCheckbox'];
	   $hotel=$postdata['hotel'];	  
	   $roomNameCheckbox=$postdata['roomNameCheckbox'];
	   $roomName=$postdata['roomName'];	      
	   $bedTypeCheckbox=$postdata['bedTypeCheckbox'];
	   $bedType=$postdata['bedType'];   
	   $roomCategoriesCheckbox=$postdata['roomCategoriesCheckbox'];
	   $roomCategories=$postdata['roomCategories'];	   	   
	   $maxOccupancyCheckbox=$postdata['maxOccupancyCheckbox'];
	   $maxOccupancy=$postdata['maxOccupancy'];	   
	   $roomCodeCheckbox=$postdata['roomCodeCheckbox'];
	   $roomCode=$postdata['roomCode'];		   
	   $roomId=$postdata['roomId'];
	   if($roomId!=000){   
			$roomIdCheckbox=1;
	   }		  		   
	   $roomDescription=$postdata['roomDescription'];
	   if( $roomDescription!="none"){   
			 $roomDescriptionCheckbox=1;
	   }	  	  	   
	  }
	   $service = $this->getServiceLocator()->get('phoenix-contentfilter');
       $service->_action = 'filterControl';
       $result=$service->getFilteredContents(
		                                      $hotelCheckbox, $hotel,
		                                      $roomNameCheckbox, $roomName,
										      $bedTypeCheckbox, $bedType,
											  $roomCategoriesCheckbox, $roomCategories,
											  $maxOccupancyCheckbox, $maxOccupancy,
											  $roomCodeCheckbox, $roomCode,
											  $roomIdCheckbox, $roomId,
											  $roomDescriptionCheckbox, $roomDescription
											  );        $viewModel = parent::editlistAction();  
        $children = $viewModel->getChildren();		
        $taskViewModel = $children[0];        
        $taskViewModel->setVariable('res',$result);
        $taskViewModel->setTemplate('phoenix-contentfilter/toolbox/filter-control');
        return $viewModel;
	}	
}


