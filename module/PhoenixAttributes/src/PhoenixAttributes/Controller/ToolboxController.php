<?php
/**
 * PhoenixAttributes ToolboxController
 *
 * The ToolboxController for the PhoenixAttributes Module
 *
 * If it is a toolbox action for the phoenixAttributes module, it goes here.
 *
 * @category    Toolbox
 * @package     PhoenixAttributes
 * @subpackage  Controllers
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      Grou <jrubio@travelclick.com>
 * @filesource
 */

namespace PhoenixAttributes\Controller;

/**
 * PhoenixAttributes ToolboxController
 *
 * The ToolboxController for the PhoenixAttributes Module
 *
 * If it is a toolbox action for the phoenixAttributes module, it goes here.
 *
 * This will need to have a way of deciding whether to show all attributes, or just the attribute for the current site
 * depending upon the user.
 *
 * @category    Toolbox
 * @package     PhoenixAttributes
 * @subpackage  Controllers
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       Class available since release 13.4
 * @author      Grou <jrubio@travelclick.com>
 */
class ToolboxController extends \ListModule\Controller\ToolboxController
{
    public function __construct() {
        $this->modsing = 'Attribute';
        parent::__construct();
    }
}
