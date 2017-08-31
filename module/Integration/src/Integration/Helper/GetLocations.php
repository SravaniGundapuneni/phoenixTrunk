<?php

/**
 * The file for the GetLocations Helper
 *
 * @category    Toolbox
 * @package     GetLocations
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
 * The file for the GetLocations Helper
 *
 * @category    Toolbox
 * @package     GetLocations
 * @subpackage  Helper
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 */

class GetLocations extends AbstractHelper
{
	protected $phoenixProperties;
    
    /**
     *  __invoke function
     * 
     * @access public
     * @return mixed $result
     *
    */

	public function __invoke()
	{
		$result = array();

	    $properties = $this->view->getProperties();

	    foreach ($properties as $key => $property)
	    {
	        if (! isset($result[$property['information']['state']]) )
	        {
	            $result[$property['information']['state']] = array(
	                'name' => $property['information']['state'],
	                'cities' => array(
	                )
	            );
	        }

	        if (! isset($result[$property['information']['state']]['cities'][$property['information']['city']]) )
	        {
	            $result[$property['information']['state']]['cities'][$property['information']['city']] = array(
	                'name' => $property['information']['city'],
	                'properties' => array(
	                )
	            );
	        }

	        if (! isset($result[$property['information']['state']]['cities'][$property['information']['city']]['properties'][$property['id']]) )
	        {
	            $result[$property['information']['state']]['cities'][$property['information']['city']]['properties'][$property['id']] = $property['information'];
	        }
	    }

	    return $result;
	}

    /**
     *  __construct function
     * 
     * @access public
     * @param  mixed $phoenixProperties
     *
    */

	public function __construct($phoenixProperties)
	{
		$this->phoenixProperties = $phoenixProperties;
	}
}