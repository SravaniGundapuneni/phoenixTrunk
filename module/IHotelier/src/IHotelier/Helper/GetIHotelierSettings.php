<?php

/**
 * The file for the IHotelier Helper
 *
 * @category    Toolbox
 * @package     IHotelier
 * @subpackage  Helper
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      Scott Stadt <sstadt@travelclick.com>
 * @filesource
 */

namespace IHotelier\Helper;

use Zend\View\Helper\AbstractHelper;

class GetIHotelierSettings extends \ListModule\Helper\ItemInformation {

    protected $iHotelierSettings;

    /**
     *  __construct function
     * 
     * @access public
     * @param  mixed $iHotelierSettings
     *
     */
    public function __construct($iHotelierSettings) {
        $this->iHotelierSettings = $iHotelierSettings;
    }


    /**
     * Get the checked property for a given radio button
     * @param  string $source The data source type to check against current options
     * @return string         Checked if the provided data source is active, enpty string if not
     */
    public function getDataSourceCheckedProp($source) {
        return ($source === $this->$iHotelierSettings['dataSource']) ? 'checked="checked"' : '';
    }

}
