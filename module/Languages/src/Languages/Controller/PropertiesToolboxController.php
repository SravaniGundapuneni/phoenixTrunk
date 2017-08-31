<?php
/**
 * Languages Toolbox Controller
 *
 * The ToolboxController for the Languages Modlue
 *
 * If it is a toolbox action for the Languages module.
 *
 * @category         Toolbox
 * @package          Languages
 * @subpackage       Controllers
 * @copyright        Copyright (c) 2013 TravelClick, Inc (http://travelclick.com)
 * @license          All Rights Reserved
 * @version          Release 13.5.5
 * @since            File available since release 13.5.5
 * @author           Jose A. Duarte <jduarte@travelclick.com>
 * @filesource
 */

namespace Languages\Controller;

class PropertiesToolboxController extends \ListModule\Controller\ToolboxController
{
    /**
     * The base admin level for actions. Each action (or task within an action)
     * can require a higher adminLevel, but this is the lowest allowed.
     * 
     * @var string
     */    
    protected $baseAdminLevel = \Users\Service\Acl::PERMISSIONS_RESOURCE_SUPER_ADMIN;

    protected $tasksMenu = array(
        //'addItem'=>'Add Languages',
        'index' => 'View Languages',
        'propertyLanguages'=>'Manage Hotel Languages', 
        'export' => 'Export Translations', 
        'import' => 'Import Translations');

    public function __construct()
    {
        $this->modsing = "PropertyLanguages";
        parent::__construct();
    }

    public function getModuleName($moduleName)
    {
        return 'languages-property';
    }
}
