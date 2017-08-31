<?php

/**
 * ListUpdateStatusButtons Helper file
 *
 * @category        Toolbox
 * @package         ListModule
 * @subpackage      Helper
 * @copyright       Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license         All Rights Reserved
 * @version         Release: 13.5.5
 * @since           File available since release 13.5.5
 * @author          A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace ListModule\Helper;

use Zend\View\Helper\AbstractHelper; 

/**
 * ListUpdateStatusButtons Helper class
 *
 * @category        Toolbox
 * @package         ListModule
 * @subpackage      Helper
 * @copyright       Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license         All Rights Reserved
 * @version         Release: 13.5.5
 * @since           Class available since release 13.5.5
 * @author          A. Tate <atate@travelclick.com>
 */
class ListUpdateStatusButtons extends AbstractHelper
{

    /**
     * __invoke() function
     *
     * @access public
     * @return mixed $buttons
     */

    public function __invoke()
    {
        $viewVars = $this->getView()->vars();
        $arguments = func_get_args();
        $formName = $arguments[0];
        $exceptions = isset($arguments[1]) ? explode(',', strtolower($arguments[1])) : array();
        $buttons = "
            <div id='editListOptionsList' style='float:right'>
                <div style='display:inline-block'";
        if (!in_array('publish', $exceptions)) {
            $buttons .= " onclick='phoenixUpdateStatus(document.$formName, 'publish'); showWait(); return false;' title='Save'";
        } else {
            $buttons .= " style='cursor:default'";
        }
        $buttons .= 
            "><img src='{$viewVars->toolboxIncludeUrl}public/toolbox/images/editListOption.publish.png' height='26' width='26' alt='Save' />
                </div>
                <div style='display:inline-block'";
        if (!in_array('archive', $exceptions)) {
            $buttons .= " onclick='phoenixUpdateStatus(document.$formName, 'archive'); showWait(); return false;' title='Archive'";
        } else {
            $buttons .= " style='cursor:default'";
        }
        $buttons .= 
            "><img src='{$viewVars->toolboxIncludeUrl}public/toolbox/images/editListOption.archive.png' height='26' width='26' alt='archive' />
            </div>
            <div style='display:inline-block'";
        if (!in_array('trash', $exceptions)) {
            $buttons .= " onclick='phoenixUpdateStatus(document.$formName, 'trash'); showWait(); return false;' title='Trash'";
        } else {
            $buttons .= " style='cursor:default'";
        }
        $buttons .=
            "><img src='{$viewVars->toolboxIncludeUrl}public/toolbox/images/editListOption.delete.png' height='26' width='26' alt='trash' />
            </div>
        </div>";
        return $buttons;
    }
}
