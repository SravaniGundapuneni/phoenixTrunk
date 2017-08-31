<?php
/**
 * PropertyForm Class
 *
 * This extends Zend\Form and creates a base form class for the Phoenix Property Model.
 *
 * @category    Toolbox
 * @package     PhoenixProperties
 * @subpackage  Form
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace PhoenixProperties\Form;

use Zend\InputFilter;
use Zend\Form\Element;
use Zend\Form\Form;

/**
 * PropertyForm Class
 *
 * This extends Zend\Form and creates a base form class for the Phoenix Property Model.
 *
 * @category    Toolbox
 * @package     PhoenixProperties
 * @subpackage  Form
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 */
class PropertyForm extends \ListModule\Form\Form
{
    /**
     * __construct
     *
     * The form class constructor
     *
     * This sets up all of the fields, which can then be modified to the needs of the code the form is being used in.
     * @param string $name
     */
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('phoenixProperty');
        $this->setAttribute('method', 'post');
    }

    /**
     * We need to find a proper way to get this list
     */
    public function getCountryOptions()
    {
        return array(
            'CA' => 'Canada',
            'US' => 'United States of America',
            'MX' => 'Mexico',
        );
    }
}