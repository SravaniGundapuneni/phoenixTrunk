<?php
/**
 * MapMarkers ToolboxController
 *
 * The ToolboxController for the MapMarkers Module
 *
 * If it is a toolbox action for the phoenixAttributes module, it goes here.
 *
 * @category    Toolbox
 * @package     MapMarkers
 * @subpackage  Controllers
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      Grou <jrubio@travelclick.com>
 * @filesource
 */

namespace MapMarkers\Controller;


/**
 * MapMarkers ToolboxController
 *
 * The ToolboxController for the MapMarkers Module
 *
 * If it is a toolbox action for the phoenixAttributes module, it goes here.
 *
 * This will need to have a way of deciding whether to show all attributes, or just the attribute for the current site
 * depending upon the user.
 *
 * @category    Toolbox
 * @package     MapMarkers
 * @subpackage  Controllers
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       Class available since release 13.4
 * @author      Grou <jrubio@travelclick.com>
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
    protected $tasksMenu = array('additem'=>'New Marker','editcategorylist' => 'Edit Categories','editlist'=>'Manage Markers','sampletest'=>'Test Marker');
    
    public function __construct() {
        $this->modsing = 'MapMarker';
        parent::__construct();
    }   
    
    public function editItemAction() {
        $viewModel = parent::editItemAction();
        return $viewModel;
    }
    
    public function editcategorylistAction()
    {
        $modulesService = $this->getServiceLocator()->get('phoenix-modules');
        $module = $modulesService->getItemBy(array('name' => 'MapMarkers'));

        $this->redirect()->toRoute('categories-toolbox',array('moduleId' => $module->getId()));   
    }
    
    public function sampletestAction(){
        $viewModel = parent::editlistAction();
        $children = $viewModel->getChildren();
        $sampleModel = $children[0];
        
        
        $modulesService = $this->getServiceLocator()->get('phoenix-mapmarkers');
         $sampleModel->items = $modulesService->sampleOutput();
         $sampleModel->setTemplate('phoenix-mapmarkers/toolbox/sample');
        return $viewModel;
         
         
        
    }
    
}
