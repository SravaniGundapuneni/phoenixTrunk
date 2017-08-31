<?php
/**
 * This extends Zend\Form and creates a base form class for the PhoenixReview.
 *
 * @category    Toolbox
 * @package     PhoenixReview
 * @subpackage  Form
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace Calendar\Form;

use Zend\InputFilter;
use Zend\Form\Element;

/**
 * This extends Zend\Form and creates a base form class for the Calendar.
 *
 * @category    Toolbox
 * @package     Calendar
 * @subpackage  Form
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      
 */
class CategoryForm extends \ListModule\Form\Form
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
        parent::__construct('EventCategory');
        $this->setAttribute('method', 'post');
    }
}