<?php
/**
 * PhoenixEvents ToolboxController
 *
 * The ToolboxController for the Event Module
 *
 * If it is a toolbox action for the phoenixProperties module, it goes here.
 *
 * @category    Toolbox
 * @package     PhoenixEvents
 * @subpackage  Controllers
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.5.5
 * @since       File available since release 13.5.5
 * @author      Kevin Davis <kedavis@travelclick.com>
 * @filesource
 */

namespace PhoenixEvents\Controller;

/**
 * PhoenixEvents ToolboxController
 *
 * The ToolboxController for the Event Module
 *
 * If it is a toolbox action for the phoenixProperties module, it goes here.
 *
 * @category    Toolbox
 * @package     PhoenixEvents
 * @subpackage  Controllers
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.5.5
 * @since       File available since release 13.5.5
 * @author      Kevin Davis <kedavis@travelclick.com>
 */

class ToolboxController extends \ListModule\Controller\ToolboxController
{
    public function __construct() 
    {
        $this->modsing = 'Events';
        parent::__construct();
    }
}
