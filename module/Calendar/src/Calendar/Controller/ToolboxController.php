<?php

namespace Calendar\Controller;


use Zend\View\Model\ViewModel;

use Calendar\Form\CategoryForm as CategoryForm;
use Calendar\Form\Form as Form;


use Zend\ViewRenderer\RendererInterface as ViewRenderer;
use Zend\Mvc\MvcEvent;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;


//use ListModule\Controller\ToolboxController; //as ListModuleToolbox;

/**
 * EventCalendar ToolboxController
 *
 * This is the primary controller to use for Toolbox actions for the UserReview module.
 * There are two other controllers that are used by this module in Toolbox, the GroupsController and the PermissionsController.
 * Anything that involves users themselves (including adding users to or removing them from groups) can be found here.
 *
 * @category    Toolbox
 * @package    	EventCalendar
 * @subpackage  Controller
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13//.4
 * @author 		H.Naik <hnaik@travelclick.com>
 */




class ToolboxController extends \ListModule\Controller\ToolboxController 
{
    protected $tasksMenu = array('addEvent' => 'New Event', 'editList' => 'Manage Event', 'addEventCategory' => 'New Event Category', 'categoryList' => 'Manage Category', 'addCalendar' => 'Calendar');

    /**
     * Module name and signature
     * @var string
     */
    protected $module = 'Calendar';
    protected $modsing;
    protected $newItem = false;
    protected $havePropertyList = true;
    protected $editListOptions = array(
        'Toggle Re-Order' => 'toggleReorder',
        'Save Item Order' => 'orderListItems',
        'Save' => 'publish',
        'Archive' => 'archive',
        'Trash' => 'trash',
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
    protected $editListTitle = 'Select Item to edit';

    /**
     * The proper template to use for the editItem screen
     * @var string
     */
    protected $editItemTemplate = "edit-item";
    protected $editListTemplate = "phoenix-calendar/toolbox/edit-list";
	//protected $editListTemplate = 'dynamic-list-module/toolbox/edit-list';
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
	
    protected $socketsRoute = 'calendar-sockets';

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
        $this->editListTitle = $this->editListHeader = "Select $this->modsing to edit";
        //$this->newItemOptions = array_reverse($this->newItemOptions);
        $this->editItemOptions = array_reverse($this->editItemOptions);
        $this->editItemTitle = "Edit $this->modsing";
    }
	
   
	public function addEventAction(){
	
		$actualModule=$this->module;
        
        $this->module='calendarevent';
		
        $viewModel = parent::additemAction();

        $subsite = $this->params()->fromRoute('subsite', '');

        if ($subsite) {
            $subsite = substr($subsite, 1);
        } else {
            $subsite = null;
        }


        if (!$viewModel instanceof ViewModel) {
            $redirect = $this->buildRoute($this->defaultNoItemRoute, array(
                'action' => 'editList',
                'itemId' => null,
                'subsite' => $subsite,
            ));
           
           return $redirect;
        }

        $taskViewModel = current($viewModel->getChildren());
 
		
		$form = $taskViewModel->form;
		
		
        $form->setAttribute('action', $this->url()->fromRoute($this->defaultNoItemRoute, array_merge(array('action' => 'addEvent', 'subsite' => $subsite, 'itemId' => null), $this->additionalRouteParams)));

        $viewModel->module = $actualModule;
        return $viewModel;    
	
	}
	public function editEventAction(){
	
		$actualModule=$this->module;
        $this->module = 'calendarevent';
        $module=$this->module;

        $viewModel = parent::edititemAction();

        $itemId = $this->params()->fromPost('id', $this->params()->fromRoute('itemId', null));
        $request = $this->getRequest();
        $subsite = $this->params()->fromRoute('subsite', '');

        if ($subsite) {
            $subsite = substr($subsite, 1);
        } else {
            $subsite = null;
        }

        if (!$viewModel instanceof ViewModel) {



            $redirect = $this->buildRoute($this->defaultNoItemRoute, array(
                'action' => 'editList',
                'itemId' => (!empty($itemId)) ? $itemId : null,
                'subsite' => $subsite,
            ));        
           
           return $redirect;            
        }

        $taskViewModel = current($viewModel->getChildren());

        $form = $taskViewModel->form;

        $form->setAttribute('action', $this->url()->fromRoute($this->defaultNoItemRoute, array_merge(array('action' => 'editEvent', 'subsite' => $subsite, 'itemId' => null), $this->additionalRouteParams)));


        return $viewModel;

	
	}
	public function editListAction(){
			
		
		$actualModule=$this->module;
		$this->module = 'calendarevent';
        $module=$this->module;
		//$moduleName='eventcategory';
		$service = $this->getServiceLocator()->get("phoenix-$module");
		         
		
		$viewModel = parent::editlistAction();
		
		if ($viewModel instanceof ViewModel) {
            $viewModel->module = $actualModule;
        }
        $children = $viewModel->getChildren();
		
        $taskViewModel = $children[0];
		
		
        $taskViewModel->setTemplate('phoenix-calendar/toolbox/edit-list');
		
        $viewModel->addChild($taskViewModel, 'taskContent');
		return $viewModel;
	
	}
    public function addEventCategoryAction()
    {
        
		$actualModule=$this->module;
        
        $this->module='eventcategory';
		//$moduleName='eventcategory';
		//echo "after assigning event category to module:--".$this->module;	
        $viewModel = parent::additemAction();

        $subsite = $this->params()->fromRoute('subsite', '');

        if ($subsite) {
            $subsite = substr($subsite, 1);
        } else {
            $subsite = null;
        }


        if (!$viewModel instanceof ViewModel) {
            $redirect = $this->buildRoute($this->defaultNoItemRoute, array(
                'action' => 'categoryList',
                'itemId' => null,
                'subsite' => $subsite,
            ));        
           
           return $redirect;
        }

        $taskViewModel = current($viewModel->getChildren());
 
		
		$form = $taskViewModel->form;
		
		
        $form->setAttribute('action', $this->url()->fromRoute($this->defaultNoItemRoute, array_merge(array('action' => 'addEventCategory', 'subsite' => $subsite, 'itemId' => null), $this->additionalRouteParams)));

        $viewModel->module = $actualModule;
        return $viewModel;    
    }

    public function editEventCategoryAction(){

        $actualModule=$this->module;
        $this->module = 'eventcategory';
        $module=$this->module;

        $viewModel = parent::edititemAction();

        $itemId = $this->params()->fromPost('id', $this->params()->fromRoute('itemId', null));
        $request = $this->getRequest();
        $subsite = $this->params()->fromRoute('subsite', '');

        if ($subsite) {
            $subsite = substr($subsite, 1);
        } else {
            $subsite = null;
        }

        if (!$viewModel instanceof ViewModel) {

            $redirect = $this->buildRoute($this->defaultNoItemRoute, array(
                'action' => 'categoryList',
                'itemId' => (!empty($itemId)) ? $itemId : null,
                'subsite' => $subsite,
            ));        
           
           return $redirect;            
        }

        $taskViewModel = current($viewModel->getChildren());

        $form = $taskViewModel->form;

        $form->setAttribute('action', $this->url()->fromRoute($this->defaultNoItemRoute, array_merge(array('action' => 'editEventCategory', 'subsite' => $subsite, 'itemId' => null), $this->additionalRouteParams)));


        return $viewModel;
    }

    public function categoryListAction()
    {
		
		//echo "actual module".$actualModule=$this->module;
		$actualModule=$this->module;
		$this->module = 'eventcategory';
        $module=$this->module;
		//$moduleName='eventcategory';
		$service = $this->getServiceLocator()->get("phoenix-$module");
		         
		
		$viewModel = parent::editlistAction();
		
		if ($viewModel instanceof ViewModel) {
            $viewModel->module = $actualModule;
        }
        $children = $viewModel->getChildren();
		
        $taskViewModel = $children[0];
		
		
        $taskViewModel->setTemplate('phoenix-calendar/toolbox/category-list');
		
        //$viewModel->addChild($taskViewModel, 'taskContent');
		return $viewModel;
		
    }

	
    public function addCalendarAction()
    {
	    
	//$service = $this->getServiceLocator()->get('phoenix-calendar');
	//$d=$service->DeleteEventCalendar(1,'2014-12-29 00:00:00');
	//exit;
	
	
	$service = $this->getServiceLocator()->get('phoenix-calendarevent');
	
	
	
        //pass action for filtering file type
        $service->_action = 'addCalendar';
        $varry=$service->getAllEvents();
		
        $viewModel = parent::editlistAction();
		
        $children = $viewModel->getChildren();
		
	   
        $taskViewModel = $children[0];
		$taskViewModel->setVariable('items',$varry);
        $taskViewModel->setTemplate('phoenix-calendar/toolbox/add-calendar');
		
        return $viewModel;
	
	
	}
	

    protected function getForm($service)
    {
        if ($this->module == 'eventcategory') {
            return $service->getForm("\\Calendar\\Form\\CategoryForm", $this->getServiceLocator());   
        }
        elseif($this->module == 'calendarevent'){
           return $service->getForm("\\Calendar\\Form\\Form", $this->getServiceLocator());    
        }
        return parent::getForm($service);
    }
	
}

