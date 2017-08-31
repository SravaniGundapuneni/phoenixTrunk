<?php

/**
 * The file for the GetPropertyWithRateCode Helper
 *
 * @category    Toolbox
 * @package     GetPropertyWithRateCode
 * @subpackage  Helper
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace Integration\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * The file for the GetPropertyWithRateCode Helper
 *
 * @category    Toolbox
 * @package     GetPropertyWithRateCode
 * @subpackage  Helper
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 */

class GetPropertyWithRateCode extends AbstractHelper
{
	protected $phoenixProperties;
    
    /**
     *  __invoke function
     * 
     * @access public
     * @return mixed $result
     *
    */

	public function __invoke($rateCode)
	{
                                $result = $this->integrationManager->getHotelsByRateCode( $rateCode );
            return $result;
	}

    /**
     *  __construct function
     * 
     * @access public
     * @param  mixed $phoenixProperties
     *
    */

	public function __construct($phoenixProperties,$integrationManager)
	{
        $this->phoenixProperties = $phoenixProperties;
        $this->integrationManager = $integrationManager;
	}
}