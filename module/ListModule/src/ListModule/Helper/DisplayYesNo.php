<?php

/**
 * DisplayYesNo Helper file
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

// 9/5/2013 BJD added helper DisplayYesNo

/**
 * Displays the text yesno for a given flag value.
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
class DisplayYesNo extends AbstractHelper
{
    const YES_LABEL = 'Yes';
    const NO_LABEL = 'No';

    /**
     * __invoke() function
     *
     * @access public
     * @return mixed $yesno
     */
    public function __invoke()
    {
        $parameters = func_get_args();

        $yesno = (int) $parameters[0];

        switch ($yesno) {
            case 1:
                return static::YES_LABEL;
            case 0:
                return static::NO_LABEL;
            default:
                return 'N/A';
        }
    }
}