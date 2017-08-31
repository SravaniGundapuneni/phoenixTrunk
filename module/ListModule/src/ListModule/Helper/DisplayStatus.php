<?php

/**
 * DisplayStatus Helper file
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
 * Displays the text status for a given status value.
 * @category        Toolbox
 * @package         ListModule
 * @subpackage      Helper
 * @copyright       Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license         All Rights Reserved
 * @version         Release: 13.5.5
 * @since           Class available since release 13.5.5
 * @author          A. Tate <atate@travelclick.com>
 */
class DisplayStatus extends AbstractHelper
{
    const NEW_LABEL         = 'New';
    const DRAFT_LABEL       = 'Draft';
    const PUBLISHED_LABEL       = 'Saved';
    const ARCHIVED_LABEL    = 'Archived';
    const TRASHED_LABEL     = 'Trashed';

    /**
     * __invoke() function
     *
     * @access public
     * @return mixed $status
     */

    public function __invoke()
    {
        $parameters = func_get_args();

        $status = (int) $parameters[0];

        switch ($status) {
            case 2:
                return static::NEW_LABEL;
            case 3:
                return static::DRAFT_LABEL;
            case 1:
                return static::PUBLISHED_LABEL;
            case 5:
                return static::ARCHIVED_LABEL;
            case 9:
                return static::TRASHED_LABEL;
            default:
                return 'N/A';
        }
    }
}