<?php
/**
 * PhoenixProperties ToolboxController
 *
 * The ToolboxController for the PhoenixProperties Module
 *
 * If it is a toolbox action for the phoenixProperties module, it goes here.
 *
 * @category    Toolbox
 * @package     PhoenixProperties
 * @subpackage  Controllers
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace PhoenixProperties\Controller;

/**
 * PhoenixProperties ToolboxController
 *
 * The ToolboxController for the PhoenixProperties Module
 *
 * If it is a toolbox action for the phoenixProperties module, it goes here.
 *
 * This will need to have a way of deciding whether to show all properties, or just the property for the current site
 * depending upon the user.
 *
 * @category    Toolbox
 * @package     PhoenixProperties
 * @subpackage  Controllers
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       Class available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 */
class ToolboxController extends \ListModule\Controller\ToolboxController
{
    /**
     * The base admin level for actions. Each action (or task within an action)
     * can require a higher adminLevel, but this is the lowest allowed.
     * 
     * @var string
     */    
    protected $baseAdminLevel = \Users\Service\Acl::PERMISSIONS_RESOURCE_ADMIN;
  
    /**
     * This is to override the default listModule's tasksMenu
     */
    protected $tasksMenu = array(
        'editList' => 'Manage Items'
    );

    public function __construct() 
    {
        $this->modsing = 'Property';
        parent::__construct();
    }

    public function editlistAction()     
    {         
        $viewModel = parent::editlistAction();                  
        if ($viewModel instanceof \Zend\View\Model\ViewModel)  
         {  $viewModel->moduleName = 'Hotels';   }          
        return $viewModel;     
    }      

   public function edititemAction()     
   {         
       
       $title = 'Edit Hotel';
       $viewModel = parent::edititemAction();  
             
       if ($viewModel instanceof \Zend\View\Model\ViewModel) { 
            $viewModel->setVariable('title', $title);  
            $viewModel->moduleName = 'Hotels';     
            foreach($viewModel->getChildren() as $valChild) {
                $valChild->title = $title;
            }         
        }             
 
       return $viewModel;
   }
}
