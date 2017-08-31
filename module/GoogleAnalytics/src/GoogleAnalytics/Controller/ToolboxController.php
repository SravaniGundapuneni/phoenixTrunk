<?php
/**
 * GoogleAnalytics ToolboxController
 *
 * The ToolboxController for the GoogleAnalytics Module
 *
 * If it is a toolbox action for the phoenixAttributes module, it goes here.
 *
 * @category    Toolbox
 * @package     GoogleAnalytics
 * @subpackage  Controllers
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 2.14
 * @since       File available since release 13.4
 * @author      Grou <jrubio@travelclick.com>
 * @filesource
 */

namespace GoogleAnalytics\Controller;

class ToolboxController extends \ListModule\Controller\ToolboxController
{
    /**
     * The base admin level for actions. Each action (or task within an action)
     * can require a higher adminLevel, but this is the lowest allowed.
     * 
     * @var string
     */    
    protected $baseAdminLevel = \Users\Service\Acl::PERMISSIONS_RESOURCE_ADMIN;
    
    public function __construct() {
        $this->modsing = 'GoogleAnalytic';
        parent::__construct();
    }
}
