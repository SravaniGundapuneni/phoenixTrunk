<?php

/**
 * ListUpdateStatusScript Helper file
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

/**
 * @deprecated since 2013-12-18
 */

use Zend\View\Helper\AbstractHelper; 

class ListUpdateStatusScript extends AbstractHelper
{
    public function __invoke()
    {
        return "function phoenixUpdateStatus(phoenixForm, phoenixStatus)
                {
                    phoenixForm.action.value = phoenixStatus;
                    phoenixForm.submit();
                }

                function showWait()
                {
                }
                ";
    }
}
