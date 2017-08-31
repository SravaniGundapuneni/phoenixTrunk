<?php
/**
 * The file for the GetProperties Helper
 *
 * @category    Toolbox
 * @package     GetProperties
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
 * The file for the GetProperties Helper
 *
 * @category    Toolbox
 * @package     GetProperties
 * @subpackage  Helper
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

class GetProperties extends AbstractHelper
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

        $properties = $this->phoenixProperties->getItems(true, true);

        foreach ($properties as $key => $item)
        {
            $result[] = $this->view->getPropertyInformation($item->getId());
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