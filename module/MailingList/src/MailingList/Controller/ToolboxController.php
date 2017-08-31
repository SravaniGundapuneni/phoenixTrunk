<?php

namespace MailingList\Controller;


use Zend\View\Model\ViewModel;
use MailingList\Form\MailingListForm;


use Zend\ViewRenderer\RendererInterface as ViewRenderer;


use Zend\Mvc\MvcEvent;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;


//use ListModule\Controller\ToolboxController; //as ListModuleToolbox;

/**
 * MailingList ToolboxController
 *
 * This is the primary controller to use for Toolbox actions for the UserReview module.
 * There are two other controllers that are used by this module in Toolbox, the GroupsController and the PermissionsController.
 * Anything that involves users themselves (including adding users to or removing them from groups) can be found here.
 *
 * @category    Toolbox
 * @package    	MailingList
 * @subpackage  Controller
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13//.4
 * @author 		H.Naik <hnaik@travelclick.com>
 */




class ToolboxController extends \ListModule\Controller\ToolboxController 
{
	
	 protected $tasksMenu = array('addItem'=>'New Item','editList'=>'Manage Item','exportList'=>'Export Text File','exportExcel'=>'Export Excel File','statistics'=>'Statistics');
    /**
     * Module name and signature
     * @var string
     */
    protected $module = 'MailingList';
    protected $modsing;

    protected $newItem = false;
    protected $havePropertyList = true;

    protected $editListOptions = array(
        'Toggle Re-Order'    => 'toggleReorder',
        'Save Item Order'    => 'orderListItems',
        'Save'    => 'publish',
        'Archive' => 'archive',
        'Trash'   => 'trash',
    );

    protected $editItemOptions = array(
        'Save'    => 'publish',
        'Archive' => 'archive',
        'Trash'   => 'trash',
        'Cancel'  => 'cancel',
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

    /**
     * __construct
     *
     * @access public
     *
     */
	 public function __construct()
    {
        preg_match("/(.+?)\\\/",get_class($this),$moduleNamespace);
        $this->module = $moduleNamespace = array_pop($moduleNamespace);
        //define("DEFAULT_NOITEM_ROUTE", $this->toolboxRoute = lcfirst("$moduleNamespace-toolbox"));
        $this->defaultNoItemRoute = $this->toolboxRoute = lcfirst("$moduleNamespace-toolbox");
        $this->editListTitle = $this->editListHeader = "Select $this->modsing to edit";
        //$this->newItemOptions = array_reverse($this->newItemOptions);
        $this->editItemOptions = array_reverse($this->editItemOptions);
        $this->editItemTitle = "Edit $this->modsing";
    }
			
	public function edititemAction()
	{
	    $editviewModel=parent::edititemAction();
		
		
		
		return $editviewModel;
	}
	
	public function exportListAction()
	{
	    $service = $this->getServiceLocator()->get('phoenix-mailinglist');
        //pass action for filtering file type
        $service->_action = 'exportList';
                 
        $r=$service->exportContacts();
		
		$viewModel = parent::editlistAction();
		
        $children = $viewModel->getChildren();
		
        $taskViewModel = $children[0];

       $taskViewModel->setTemplate('phoenix-mailinglist/toolbox/export-list')
					  ->setVariable('etext',$r)
					  ->setTerminal(true);
		
		$filename="data.txt";
		$output = $this->getServiceLocator()
                   ->get('viewrenderer')
                   ->render($taskViewModel);
			
			$response = $this->getResponse();

			$headers = $response->getHeaders();
			$headers->addHeaderLine('Content-Type', 'text/plain')
					->addHeaderLine('Content-Disposition', 
						sprintf("attachment; filename=\"%s\"", $filename)
					)
					->addHeaderLine('Accept-Ranges', 'bytes')
					->addHeaderLine('Content-Length', strlen($output));

			$response->setContent($output);

		return $response;
	
	
	}
	public function exportExcelAction()
	{
	    $service = $this->getServiceLocator()->get('phoenix-mailinglist');
        //pass action for filtering file type
        $service->_action = 'exportExcel';
                 
        $re=$service->exportDataAsCSVFormat();
		
		$viewModel = parent::editlistAction();
		
        $children = $viewModel->getChildren();
		
        $taskViewModel = $children[0];

        $taskViewModel->setTemplate('phoenix-mailinglist/toolbox/export-excel')
					->setVariable('elist',$re)
					->setTerminal(true);
		
		$filename="data.csv";
		$output = $this->getServiceLocator()
                   ->get('viewrenderer')
                   ->render($taskViewModel);
			
			$response = $this->getResponse();

			$headers = $response->getHeaders();
			$headers->addHeaderLine('Content-Type', 'text/csv')
					->addHeaderLine(
						'Content-Disposition', 
						sprintf("attachment; filename=\"%s\"", $filename)
					)
					->addHeaderLine('Accept-Ranges', 'bytes')
					->addHeaderLine('Content-Length', strlen($output));

			$response->setContent($output);

		return $response;
	
	}
    public function statisticsAction()
	{
	    
		$service = $this->getServiceLocator()->get('phoenix-mailinglist');
        //pass action for filtering file type
        $service->_action = 'statistics';
        $varry=$service->getStatistics();
		
        $viewModel = parent::editlistAction();
		
        $children = $viewModel->getChildren();
		
		//$viewModel->setVariable('stat',$re);
        $taskViewModel = $children[0];
		$taskViewModel->setVariable('stat',$varry);
        $taskViewModel->setTemplate('phoenix-mailinglist/toolbox/statistics');
		
        return $viewModel;
	
	
	}
	
	
}

