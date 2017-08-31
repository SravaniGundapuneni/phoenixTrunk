<?php
/**
 * PhoenixRooms ToolboxController
 * 
 * The ToolboxController for the PhoneixRooms Module
 *
 * If it is a toolbox action for the phoneixRooms module.
 * 
 * @category       Toolbox
 * @package        PhoenixRooms
 * @subpackage     Controllers     
 * @copyright      Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license        All Rights Resverved
 * @version        Release: 13.4
 * @since          File available since release 13.4
 * @author         Kevin Davis <kedavis@travelclick.com>
 * @filesource    
 */

namespace PhoenixRooms\Controller;

/**
 * PhoenixRooms ToolboxController
 * 
 * The ToolboxController for the PhoneixRooms Module
 *
 * If it is a toolbox action for the phoneixRooms module.
 * 
 * @category       Toolbox
 * @package        PhoenixRooms
 * @subpackage     Controllers     
 * @copyright      Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license        All Rights Resverved
 * @version        Release: 13.4
 * @since          Class available since release 13.4
 * @author         Kevin Davis <kedavis@travelclick.com>
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
        'editList' => 'Manage Items',
        'addItem' => 'Add New Room',
        'editcategorylist' => 'Edit Categories'        
    );

    public function __construct() {
        $this->modsing = 'Room';
        parent::__construct();
    }

    public function editcategorylistAction()
    {
        $modulesService = $this->getServiceLocator()->get('phoenix-modules');

        $module = $modulesService->getItemBy(array('name' => 'PhoenixRooms'));

        $this->redirect()->toRoute('categories-toolbox',array('moduleId' => $module->getId()));        
    }    
}