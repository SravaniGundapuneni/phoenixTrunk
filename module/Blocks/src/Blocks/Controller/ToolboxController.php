<?php

/**
 * Blocks ToolboxController
 *
 * The ToolboxController for the GooglePlaces Module
 *
 * If it is a toolbox action for the GooglePlaces module, it goes here.
 *
 * @category    Toolbox
 * @package     GooglePlaces
 * @subpackage  Controllers
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.5
 * @since       File available since release 13.4
 * @author      Igor Sorokin <isorokin@travelclick.com>
 * @filesource
 */

namespace Blocks\Controller;

/**
 * ToolbooxController Class
 *
 * @extends \ListModule\Controller\ToolboxController
 *
 */
class ToolboxController extends \ListModule\Controller\ToolboxController
{

    const DEFAULT_NOITEM_ROUTE = 'blocks-toolbox';

    protected $editItemTemplate = "edit-item";
    protected $editListTemplate = "edit-list";
    protected $module = 'Blocks';
    protected $toolboxRoute = self::DEFAULT_NOITEM_ROUTE;
    protected $defaultNoItemRoute = 'blocks-toolbox';
    
    public function __construct()
    {
        $this->modsing = $this->module;
        define('DEFAULT_NOITEM_ROUTE', static::DEFAULT_NOITEM_ROUTE);
    }

}
