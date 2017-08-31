<?php
/**
 * PhpSample ToolboxController
 *
 * The ToolboxController for the MapMarkers Module
 *
 * If it is a toolbox action for the phoenixAttributes module, it goes here.
 *
 * @category    Toolbox
 * @package     PhpSample
 * @subpackage  Controllers
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      Grou <jrubio@travelclick.com>
 * @filesource
 */

namespace PhpSample\Controller;

class ToolboxController extends \ListModule\Controller\ToolboxController
{

    /**
     * The base admin level for actions. Each action (or task within an action)
     * can require a higher adminLevel, but this is the lowest allowed.
     * 
     * @var string
     */    
    protected $baseAdminLevel = \Users\Service\Acl::PERMISSIONS_RESOURCE_ADMIN;    
    protected $tasksMenu = array('sampletest'=>'Test Marker');
    
    public function __construct() {
        $this->modsing = 'PhpSample';
        parent::__construct();
    }   
    
    
    
    public function sampletestAction(){
        $viewModel = parent::editlistAction();
        $children = $viewModel->getChildren();
        $sampleModel = $children[0];
        
        
        $modulesService = $this->getServiceLocator()->get('phoenix-phpsample');
         $sampleModel->items = $modulesService->sampleOutput();
         $sampleModel->setTemplate('phoenix-phpsample/toolbox/sample');
        return $viewModel;
         
         
        
    }
    
}
