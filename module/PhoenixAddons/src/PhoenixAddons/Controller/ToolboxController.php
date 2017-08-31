<?php
/**
 * PhoenixAddons ToolboxController
 *
 * The ToolboxController for the PhoneixAddons Module
 *
 * If it is a toolbox action for the phoneixAddons module.
 *
 * @category       Toolbox
 * @package        PhoenixAddons
 * @subpackage     Controllers
 * @copyright      Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license        All Rights Resverved
 * @version        Release: 13.4
 * @since          File available since release 13.4
 * @author         Kevin Davis <kedavis@travelclick.com>
 * @filesource
 */

namespace PhoenixAddons\Controller;

/**
 * PhoenixAddons ToolboxController
 *
 * The ToolboxController for the PhoneixAddons Module
 *
 * If it is a toolbox action for the phoneixAddons module.
 *
 * @category       Toolbox
 * @package        PhoenixAddons
 * @subpackage     Controllers
 * @copyright      Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license        All Rights Resverved
 * @version        Release: 13.4
 * @since          Class available since release 13.4
 * @author         Bradley Davidson <bdavidson@travelclick.com>
 */
class ToolboxController extends \ListModule\Controller\ToolboxController
{
    /**
     * This is to override the default listModule's tasksMenu
     */
    protected $tasksMenu = array(
        'editList' => 'Manage Items'
    );

    /**
     * The base admin level for actions. Each action (or task within an action)
     * can require a higher adminLevel, but this is the lowest allowed.
     * 
     * @var string
     */    
    protected $baseAdminLevel = \Users\Service\Acl::PERMISSIONS_RESOURCE_ADMIN;

    public function __construct() {
        $this->modsing = 'Addon';
        parent::__construct();
    }
}